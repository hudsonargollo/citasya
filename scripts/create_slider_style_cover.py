import os
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def create_slider_style_blog_cover():
    # Load base 3D slide1 render
    base_path = "/root/ClubeMkt/CitasYa/backend/public/images/slides/slide1.png"
    slide = Image.open(base_path).convert("RGBA")
    
    # Original slide dimensions
    sw, sh = slide.size # 1672 x 941 (approx 16:9)
    
    # We want to place clean, bold, editorial typography on the left matching the slider style
    # First, let's create an overlay to ensure text contrast on the left side while keeping the 3D objects pristine on the right
    overlay = Image.new("RGBA", (sw, sh), (0, 0, 0, 0))
    ov_draw = ImageDraw.Draw(overlay)
    
    # Soft emerald/slate gradient shadow on the left third
    for x in range(int(sw * 0.55)):
        ratio = x / (sw * 0.55)
        # smooth cubic ease-out
        alpha = int(220 * (1 - ratio)**1.5)
        ov_draw.line([(x, 0), (x, sh)], fill=(4, 47, 31, alpha))
        
    slide = Image.alpha_composite(slide, overlay)
    draw = ImageDraw.Draw(slide)
    
    # Fonts
    font_title_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"
    font_reg_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf"
    
    font_tag = ImageFont.truetype(font_title_path, 26)
    font_h1 = ImageFont.truetype(font_title_path, 68)
    font_h1_sub = ImageFont.truetype(font_title_path, 64)
    font_loc = ImageFont.truetype(font_title_path, 36)
    font_btn = ImageFont.truetype(font_title_path, 28)
    
    x_offset = 90
    y_start = 140
    
    # 1. Top Pill Badge: "GUÍA EDITORIAL 2026"
    badge_w = 340
    badge_h = 52
    draw.rounded_rectangle([x_offset, y_start, x_offset + badge_w, y_start + badge_h], radius=26, fill=(5, 150, 105, 230), outline=(52, 211, 153, 255), width=2)
    draw.text((x_offset + 28, y_start + 12), "GUÍA EDITORIAL 2026", font=font_tag, fill=(255, 255, 255, 255))
    
    # 2. Main Bold Headline
    h1_y = y_start + 85
    draw.text((x_offset, h1_y), "Sistema de Reservas", font=font_h1, fill=(255, 255, 255, 255))
    draw.text((x_offset, h1_y + 80), "para Salones y Spas", font=font_h1_sub, fill=(163, 230, 53, 255))
    
    # 3. Location & Benefit Sub-headline
    sub_y = h1_y + 175
    draw.text((x_offset, sub_y), "Santa Cruz de la Sierra • Automatización 24/7", font=font_loc, fill=(241, 245, 249, 255))
    
    # 4. Action / CTA Pill Button matching slider: "Leer Guía Completa →"
    btn_y = sub_y + 85
    btn_w = 380
    btn_h = 72
    draw.rounded_rectangle([x_offset, btn_y, x_offset + btn_w, btn_y + btn_h], radius=36, fill=(163, 230, 53, 255), outline=(190, 242, 100, 255), width=2)
    draw.text((x_offset + 42, btn_y + 18), "Leer Guía Completa →", font=font_btn, fill=(15, 23, 42, 255))
    
    # 5. Bottom Brand Watermark
    brand_y = sh - 90
    draw.ellipse([x_offset, brand_y + 6, x_offset + 14, brand_y + 20], fill=(163, 230, 53, 255))
    draw.text((x_offset + 26, brand_y), "CitasYa Blog   |   citasya.clubemkt.online", font=ImageFont.truetype(font_reg_path, 22), fill=(203, 213, 225, 220))
    
    # Target destinations
    dest1 = "/root/ClubeMkt/CitasYa/backend/public/images/blog/sistema-reservas-online-salones-santa-cruz.png"
    dest2 = "/root/ClubeMkt/CitasYa/backend/public/images/sistema-reservas-online-salones-santa-cruz.png"
    dest3 = "/root/ClubeMkt/seomachine/content/images/sistema-reservas-online-salones-santa-cruz.png"
    
    slide.save(dest1, "PNG", quality=95, optimize=True)
    slide.save(dest2, "PNG", quality=95, optimize=True)
    slide.save(dest3, "PNG", quality=95, optimize=True)
    print("Successfully generated slider-style blog cover image!")

if __name__ == "__main__":
    create_slider_style_blog_cover()
