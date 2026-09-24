# CitasYa Business Logo Enricher & Vision Analyzer

A self-contained Python logo ingestion and AI validation pipeline tailored for businesses in Santa Cruz, Bolivia (and general directory listings).

## Features
- **Candidate Discovery**: Queries DuckDuckGo image endpoints, Clearbit, and Google Favicons for brand marks and transparent PNG vectors.
- **AI Validation (Local Ollama)**: Sends candidate logos to self-hosted Ollama vision models (`gemma4`, `llama3.2-vision`, etc.) at `http://127.0.0.1:11434` to verify brand authenticity, detect text, evaluate background purity, and extract brand color palettes.
- **Image Standardization**: Cleans padding, crops empty alpha borders, resizes to a standardized square (512x512), and optionally applies squircle rounded corners for UI display.
- **Structured Output**: Produces clean optimized PNGs alongside rich metadata JSON files containing AI scores and source tracking.

## Script Location
`/root/ClubeMkt/CitasYa/scripts/business_logo_parser.py`

## Requirements
Standard Python 3.11+ library. Optional for advanced image cropping/squircle masking:
```bash
pip install Pillow
```

## CLI Usage Examples

### 1. Single Business Enrichment
```bash
python3 /root/ClubeMkt/CitasYa/scripts/business_logo_parser.py \
  --name "Café Patrimonio" \
  --city "Santa Cruz Bolivia" \
  --category "Cafetería" \
  --out-dir ./output_logos
```

### 2. Fast Mode Without AI Verification
```bash
python3 /root/ClubeMkt/CitasYa/scripts/business_logo_parser.py \
  --name "Farmacorp" \
  --domain "farmacorp.com" \
  --no-ai \
  --out-dir ./output_logos
```

### 3. Batch Processing JSON
```bash
python3 /root/ClubeMkt/CitasYa/scripts/business_logo_parser.py \
  --batch /path/to/businesses.json \
  --ollama-model gemma4:latest \
  --out-dir /var/www/citasya/public/logos
```

### Batch JSON format:
```json
[
  {
    "name": "Barba Negra Barber Shop",
    "city": "Santa Cruz",
    "category": "Barbería"
  },
  {
    "name": "Hipermaxi",
    "domain": "hipermaxi.com",
    "city": "Santa Cruz"
  }
]
```
