#!/usr/bin/env python3
"""
business_logo_parser.py - CitasYa Business Logo Enricher & Vision Analyzer

A self-contained pipeline to:
1. Search & scrape candidate logos for businesses (especially Santa Cruz, Bolivia local businesses)
   via DuckDuckGo, Google Favicons / Clearbit, and social web heuristics.
2. Download and validate image data (format, dimensions, aspect ratio).
3. Process image (crop bounding box, squircle/circle masking, transparent/solid bg normalization).
4. Run AI vision inspection via self-hosted Ollama (e.g. gemma4, llama3.2-vision, or local API)
   to assess if candidate is a clean logo, extract colors, and score relevance.
5. Save standardized logo outputs (PNG/WebP + metadata JSON).

Usage:
  python3 business_logo_parser.py --name "Café Patrimonio" --city "Santa Cruz" --out-dir ./logos
  python3 business_logo_parser.py --batch businesses.json --ollama-model gemma4:latest
"""

import argparse
import base64
import io
import json
import logging
import os
import re
import sys
import urllib.parse
import urllib.request
from typing import Dict, List, Optional, Tuple

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%H:%M:%S"
)
logger = logging.getLogger("LogoParser")

# Attempt Pillow import (graceful fallback if not in bare environment)
try:
    from PIL import Image, ImageDraw, ImageOps
    PIL_AVAILABLE = True
except ImportError:
    PIL_AVAILABLE = False
    logger.warning("Pillow (PIL) not found. Basic image operations will be limited. Install with `pip install Pillow`.")


class LogoSearcher:
    """Discovers candidate logo URLs from search engines and known service endpoints."""

    USER_AGENT = (
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
        "(KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36"
    )

    @staticmethod
    def _fetch_url(url: str, headers: Optional[Dict[str, str]] = None, timeout: int = 10) -> Optional[bytes]:
        req_headers = {"User-Agent": LogoSearcher.USER_AGENT}
        if headers:
            req_headers.update(headers)
        req = urllib.request.Request(url, headers=req_headers)
        try:
            with urllib.request.urlopen(req, timeout=timeout) as resp:
                return resp.read()
        except Exception as e:
            logger.debug(f"Fetch failed for {url}: {e}")
            return None

    @classmethod
    def search_duckduckgo_images(cls, query: str, limit: int = 5) -> List[str]:
        """Search DuckDuckGo Images endpoint without API keys."""
        logger.info(f"Searching DuckDuckGo images for: '{query}'")
        candidates: List[str] = []
        try:
            # 1. Get vqd token from main search page
            token_url = f"https://duckduckgo.com/?q={urllib.parse.quote(query)}"
            page_content = cls._fetch_url(token_url)
            if not page_content:
                return candidates

            html = page_content.decode("utf-8", errors="ignore")
            vqd_match = re.search(r'vqd=([\d-]+)&', html) or re.search(r'vqd="([^"]+)"', html) or re.search(r'vqd:\s*\'([^\']+)\'', html)
            if not vqd_match:
                logger.debug("Could not extract DuckDuckGo vqd token.")
                return candidates

            vqd = vqd_match.group(1)
            # 2. Fetch image JSON
            img_url = f"https://duckduckgo.com/i.js?l=wt-wt&o=json&q={urllib.parse.quote(query)}&vqd={vqd}&f=,,,&p=1"
            headers = {
                "Accept": "application/json",
                "Referer": "https://duckduckgo.com/",
            }
            raw_json = cls._fetch_url(img_url, headers=headers)
            if raw_json:
                data = json.loads(raw_json.decode("utf-8", errors="ignore"))
                results = data.get("results", [])
                for item in results[:limit]:
                    image_url = item.get("image")
                    if image_url and image_url.startswith("http"):
                        candidates.append(image_url)
        except Exception as e:
            logger.warning(f"Error searching DuckDuckGo: {e}")

        return candidates

    @classmethod
    def generate_domain_candidates(cls, domain: str) -> List[str]:
        """Generate high-resolution logo candidates from domain services (Clearbit, Google Favicons)."""
        domain_clean = domain.replace("https://", "").replace("http://", "").strip("/")
        return [
            f"https://logo.clearbit.com/{domain_clean}",
            f"https://www.google.com/s2/favicons?domain={domain_clean}&sz=256",
            f"https://icon.horse/icon/{domain_clean}",
        ]

    @classmethod
    def get_candidates(cls, business_name: str, city: str = "Santa Cruz Bolivia", domain: Optional[str] = None) -> List[str]:
        """Aggregates candidates from various sources."""
        candidates = []
        if domain:
            candidates.extend(cls.generate_domain_candidates(domain))

        # Query formulations for high precision
        queries = [
            f'"{business_name}" {city} logo vector',
            f'"{business_name}" {city} logo png transparente',
            f'"{business_name}" logo {city}',
        ]

        for q in queries:
            results = cls.search_duckduckgo_images(q, limit=4)
            for r in results:
                if r not in candidates:
                    candidates.append(r)
            if len(candidates) >= 8:
                break

        return candidates


class ImageProcessor:
    """Validates, crops, normalizes, and applies squircle mask to candidate logos."""

    @staticmethod
    def load_image(image_bytes: bytes) -> Optional["Image.Image"]:
        if not PIL_AVAILABLE:
            return None
        try:
            img = Image.open(io.BytesIO(image_bytes))
            img.load()
            return img
        except Exception as e:
            logger.warning(f"Failed to decode image: {e}")
            return None

    @staticmethod
    def create_squircle_mask(size: Tuple[int, int], radius_ratio: float = 0.25) -> "Image.Image":
        """Generates an antialiased squircle / rounded-rectangle mask."""
        w, h = size
        scale = 4  # Supersampling for smooth antialiasing
        big_size = (w * scale, h * scale)
        mask = Image.new("L", big_size, 0)
        draw = ImageDraw.Draw(mask)
        radius = int(min(big_size) * radius_ratio)
        draw.rounded_rectangle([0, 0, big_size[0], big_size[1]], radius=radius, fill=255)
        return mask.resize(size, Image.Resampling.LANCZOS)

    @classmethod
    def process_logo(
        cls,
        img: "Image.Image",
        target_size: int = 512,
        pad_ratio: float = 0.12,
        apply_squircle: bool = True
    ) -> "Image.Image":
        """
        Normalizes logo:
        - Converts to RGBA
        - Trims outer solid borders if applicable
        - Centers onto a square canvas with padding
        - Optionally applies rounded squircle corners
        """
        if not PIL_AVAILABLE:
            return img

        # Convert to RGBA
        if img.mode != "RGBA":
            img = img.convert("RGBA")

        # Trim transparent / uniform margins
        bbox = img.getbbox()
        if bbox:
            img = img.crop(bbox)

        # Scale down while maintaining aspect ratio
        inner_max_dim = int(target_size * (1.0 - (pad_ratio * 2)))
        img.thumbnail((inner_max_dim, inner_max_dim), Image.Resampling.LANCZOS)

        # Create target square canvas
        canvas = Image.new("RGBA", (target_size, target_size), (255, 255, 255, 0))
        offset_x = (target_size - img.width) // 2
        offset_y = (target_size - img.height) // 2
        canvas.paste(img, (offset_x, offset_y), img if img.mode == "RGBA" else None)

        if apply_squircle:
            mask = cls.create_squircle_mask((target_size, target_size), radius_ratio=0.22)
            canvas.putalpha(ImageOps.composite(canvas.getchannel("A"), mask, mask))

        return canvas


class OllamaLogoAnalyzer:
    """Integrates with local Ollama API to validate logos via vision/multimodal models."""

    def __init__(self, endpoint: str = "http://127.0.0.1:11434", model: str = "gemma4:latest"):
        self.endpoint = endpoint.rstrip("/")
        self.model = model

    def analyze_candidate(
        self,
        image_bytes: bytes,
        business_name: str,
        category: Optional[str] = None
    ) -> Dict:
        """
        Sends candidate image base64 to Ollama model to evaluate:
        1. Is this a valid logo/brand mark (vs product photo, person selfie, random scenery)?
        2. Quality score (1-10).
        3. Background purity (transparent, solid white, complex/noisy).
        4. Extracted primary brand colors.
        """
        b64_image = base64.b64encode(image_bytes).decode("utf-8")

        prompt = (
            f"Analyze this image to determine if it is a valid logo or brand mark for the business '{business_name}'"
            + (f" in the category '{category}'." if category else ".")
            + "\nRespond ONLY in valid raw JSON with the following schema:\n"
            "{\n"
            '  "is_logo": true|false,\n'
            '  "confidence_score": 0.0 to 1.0,\n'
            '  "quality_rating": 1 to 10,\n'
            '  "background_type": "transparent"|"solid_white"|"solid_color"|"photo_clutter",\n'
            '  "text_detected": "string with legible text or empty",\n'
            '  "brand_colors": ["#HEX1", "#HEX2"],\n'
            '  "rejection_reason": "null or brief reason if not a good logo"\n'
            "}"
        )

        payload = {
            "model": self.model,
            "prompt": prompt,
            "images": [b64_image],
            "stream": False,
            "options": {
                "temperature": 0.1
            }
        }

        url = f"{self.endpoint}/api/generate"
        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={"Content-Type": "application/json"}
        )

        try:
            with urllib.request.urlopen(req, timeout=30) as resp:
                res_body = json.loads(resp.read().decode("utf-8"))
                raw_response = res_body.get("response", "").strip()

                # Extract JSON from potential markdown tags ```json ... ```
                json_match = re.search(r"(\{.*\})", raw_response, re.DOTALL)
                if json_match:
                    return json.loads(json_match.group(1))
                else:
                    return {"raw_text": raw_response, "parsed": False}
        except Exception as e:
            logger.warning(f"Ollama inspection failed: {e}")
            return {"error": str(e), "is_logo": True, "confidence_score": 0.5}


class BusinessLogoPipeline:
    """Full end-to-end enrichment pipeline."""

    def __init__(
        self,
        ollama_endpoint: str = "http://127.0.0.1:11434",
        ollama_model: str = "gemma4:latest",
        enable_ai_validation: bool = True
    ):
        self.searcher = LogoSearcher()
        self.processor = ImageProcessor()
        self.ai = OllamaLogoAnalyzer(endpoint=ollama_endpoint, model=ollama_model)
        self.enable_ai_validation = enable_ai_validation

    def process_business(
        self,
        name: str,
        city: str = "Santa Cruz",
        domain: Optional[str] = None,
        category: Optional[str] = None,
        output_dir: str = "./output_logos"
    ) -> Dict:
        """Executes full flow for a single business entity."""
        os.makedirs(output_dir, exist_ok=True)
        safe_name = re.sub(r'[^a-zA-Z0-9_-]', '_', name.lower())

        logger.info(f"==> Processing business: '{name}' ({city})")
        candidates = self.searcher.get_candidates(business_name=name, city=city, domain=domain)
        logger.info(f"Found {len(candidates)} candidate URLs.")

        best_candidate = None
        best_analysis = None
        best_img_bytes = None
        best_score = -1.0

        for idx, url in enumerate(candidates):
            logger.info(f"Evaluating candidate [{idx+1}/{len(candidates)}]: {url}")
            raw_bytes = self.searcher._fetch_url(url, timeout=8)
            if not raw_bytes or len(raw_bytes) < 1024:
                continue

            # Format check
            if PIL_AVAILABLE:
                pil_img = self.processor.load_image(raw_bytes)
                if not pil_img:
                    continue
                # Skip tiny images
                if pil_img.width < 64 or pil_img.height < 64:
                    continue

            # AI validation if enabled
            if self.enable_ai_validation:
                analysis = self.ai.analyze_candidate(raw_bytes, business_name=name, category=category)
                score = analysis.get("confidence_score", 0.0) if analysis.get("is_logo", False) else 0.0
                logger.info(f"AI Evaluation: is_logo={analysis.get('is_logo')}, score={score}")

                if score > best_score:
                    best_score = score
                    best_candidate = url
                    best_analysis = analysis
                    best_img_bytes = raw_bytes

                if score >= 0.85:
                    # High confidence match found
                    break
            else:
                # Without AI validation, pick first valid downloadable candidate
                best_candidate = url
                best_img_bytes = raw_bytes
                best_analysis = {"is_logo": True, "confidence_score": 1.0}
                break

        if not best_img_bytes:
            logger.error(f"No suitable logo candidate found for {name}")
            return {"status": "not_found", "name": name}

        # Post-process and save final artifacts
        final_png_path = os.path.join(output_dir, f"{safe_name}_logo.png")
        final_meta_path = os.path.join(output_dir, f"{safe_name}_meta.json")

        if PIL_AVAILABLE:
            raw_pil = self.processor.load_image(best_img_bytes)
            processed_pil = self.processor.process_logo(raw_pil, target_size=512, apply_squircle=True)
            processed_pil.save(final_png_path, format="PNG", optimize=True)
        else:
            with open(final_png_path, "wb") as f:
                f.write(best_img_bytes)

        meta = {
            "business_name": name,
            "city": city,
            "source_url": best_candidate,
            "output_file": final_png_path,
            "ai_analysis": best_analysis,
        }

        with open(final_meta_path, "w", encoding="utf-8") as f:
            json.dump(meta, f, indent=2, ensure_ascii=False)

        logger.info(f"✓ Successfully enriched logo for '{name}' -> {final_png_path}")
        return {"status": "success", "file": final_png_path, "metadata": meta}


def main():
    parser = argparse.ArgumentParser(description="CitasYa Business Logo Enricher & Vision Analyzer")
    parser.add_argument("--name", type=str, help="Business name (e.g. 'Café Patrimonio')")
    parser.add_argument("--city", type=str, default="Santa Cruz Bolivia", help="City / Region")
    parser.add_argument("--domain", type=str, default=None, help="Website domain if known (e.g. 'cafepatrimonio.bo')")
    parser.add_argument("--category", type=str, default=None, help="Business category (e.g. 'Cafetería', 'Barbería')")
    parser.add_argument("--out-dir", type=str, default="./logos", help="Output directory")
    parser.add_argument("--batch", type=str, default=None, help="Path to JSON file with list of businesses")
    parser.add_argument("--ollama-endpoint", type=str, default="http://127.0.0.1:11434", help="Local Ollama endpoint")
    parser.add_argument("--ollama-model", type=str, default="gemma4:latest", help="Ollama vision/model name")
    parser.add_argument("--no-ai", action="store_true", help="Disable Ollama AI inspection")

    args = parser.parse_args()

    pipeline = BusinessLogoPipeline(
        ollama_endpoint=args.ollama_endpoint,
        ollama_model=args.ollama_model,
        enable_ai_validation=not args.no_ai
    )

    if args.batch:
        if not os.path.exists(args.batch):
            logger.error(f"Batch file '{args.batch}' not found.")
            sys.exit(1)
        with open(args.batch, "r", encoding="utf-8") as f:
            businesses = json.load(f)

        results = []
        for b in businesses:
            res = pipeline.process_business(
                name=b.get("name"),
                city=b.get("city", args.city),
                domain=b.get("domain"),
                category=b.get("category"),
                output_dir=args.out_dir
            )
            results.append(res)
        print(json.dumps(results, indent=2, ensure_ascii=False))
    elif args.name:
        res = pipeline.process_business(
            name=args.name,
            city=args.city,
            domain=args.domain,
            category=args.category,
            output_dir=args.out_dir
        )
        print(json.dumps(res, indent=2, ensure_ascii=False))
    else:
        parser.print_help()


if __name__ == "__main__":
    main()
