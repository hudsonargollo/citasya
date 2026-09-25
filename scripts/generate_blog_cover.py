import math
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def create_blog_cover():
    width = 1200
    height = 675
    
    # 1. Base image with dark luxury slate background
    img = Image.new("RGBA", (width, height), (15, 23, 42, 255))
    draw = ImageDraw.Draw(img)
    
    # 2. Draw modern diagonal gradient / ambient glows
    glow = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    glow_draw = ImageDraw.Draw(glow)
    
    # Top-right Lime glow
    glow_draw.ellipse([750, -100, 1300, 450], fill=(132, 204, 22, 60))
    # Bottom-left Emerald glow
    glow_draw.ellipse([-100, 300, 500, 800], fill=(5, 150, 105, 70))
    # Center ambient
    glow_draw.ellipse([300, 100, 900, 600], fill=(30, 41, 59, 120))
    
    glow = glow.filter(ImageFilter.GaussianBlur(80))
    img.paste(glow, (0, 0), glow)
    
    # Re-instantiate draw after paste
    draw = ImageDraw.Draw(img)
    
    # 3. Grid / tech overlay dots
    for x in range(40, width, 40):
        for y in range(40, height, 40):
            draw.ellipse([x, y, x+2, y+2], fill=(255, 255, 255, 20))
            
    # 4. Fonts
    font_title_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"
    font_reg_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf"
    
    font_badge = ImageFont.truetype(font_title_path, 18)
    font_category = ImageFont.truetype(font_title_path, 16)
    font_h1 = ImageFont.truetype(font_title_path, 46)
    font_sub = ImageFont.truetype(font_reg_path, 22)
    font_pill = ImageFont.truetype(font_title_path, 16)
    font_card_title = ImageFont.truetype(font_title_path, 20)
    font_card_body = ImageFont.truetype(font_reg_path, 15)
    
    # 5. Top Bar: Category Pill & Location Badge
    # Category Pill
    draw.rounded_rectangle([70, 60, 260, 96], radius=18, fill=(5, 150, 105, 220), outline=(52, 211, 153, 255), width=1)
    draw.text((88, 68), "SALONES & SPAS", font=font_category, fill=(255, 255, 255, 255))
    
    # Location Pill
    draw.rounded_rectangle([275, 60, 520, 96], radius=18, fill=(30, 41, 59, 200), outline=(100, 116, 139, 150), width=1)
    draw.ellipse([292, 74, 302, 84], fill=(132, 204, 22, 255))
    draw.text((312, 68), "SANTA CRUZ, BOLIVIA", font=font_category, fill=(203, 213, 225, 255))
    
    # 6. Main Headline (Left Column)
    title_line1 = "Sistema de Reservas Online"
    title_line2 = "para Salones y Spas"
    title_line3 = "en Santa Cruz"
    
    draw.text((70, 130), title_line1, font=font_h1, fill=(255, 255, 255, 255))
    draw.text((70, 185), title_line2, font=font_h1, fill=(163, 230, 53, 255))
    draw.text((70, 240), title_line3, font=font_h1, fill=(255, 255, 255, 255))
    
    subtitle = "Automatiza tu agenda 24/7, elimina las ausencias y cobra con QR Simple."
    draw.text((70, 315), subtitle, font=font_sub, fill=(148, 163, 184, 255))
    
    # Key Highlights Chips
    chips = [
        "✓ Agenda 24/7 sin WhatsApp manual",
        "✓ Cobros con QR Simple inmediato",
        "✓ Recordatorios automáticos"
    ]
    chip_y = 370
    for chip in chips:
        draw.rounded_rectangle([70, chip_y, 450, chip_y + 36], radius=10, fill=(30, 41, 59, 180), outline=(51, 65, 85, 180), width=1)
        draw.text((85, chip_y + 8), chip, font=font_card_body, fill=(226, 232, 240, 255))
        chip_y += 46
        
    # 7. Right Column: Stylized Mockup Card (Card UI)
    card_x = 730
    card_y = 90
    card_w = 400
    card_h = 490
    
    # Card outer shadow / glow
    card_glow = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    cg_draw = ImageDraw.Draw(card_glow)
    cg_draw.rounded_rectangle([card_x - 10, card_y - 10, card_x + card_w + 10, card_y + card_h + 10], radius=30, fill=(132, 204, 22, 50))
    card_glow = card_glow.filter(ImageFilter.GaussianBlur(25))
    img.paste(card_glow, (0, 0), card_glow)
    draw = ImageDraw.Draw(img)
    
    # Glassmorphic Card Container
    draw.rounded_rectangle([card_x, card_y, card_x + card_w, card_y + card_h], radius=24, fill=(15, 23, 42, 245), outline=(132, 204, 22, 180), width=2)
    
    # Card Header
    draw.rounded_rectangle([card_x + 20, card_y + 20, card_x + card_w - 20, card_y + 80], radius=16, fill=(30, 41, 59, 255))
    draw.ellipse([card_x + 35, card_y + 35, card_x + 65, card_y + 65], fill=(5, 150, 105, 255))
    draw.text((card_x + 43, card_y + 40), "CY", font=font_badge, fill=(255, 255, 255, 255))
    draw.text((card_x + 78, card_y + 32), "Salon & Spa Premium", font=font_card_title, fill=(255, 255, 255, 255))
    draw.text((card_x + 78, card_y + 55), "Equipetrol, Santa Cruz", font=font_card_body, fill=(148, 163, 184, 255))
    
    # Booking Calendar Item 1
    item1_y = card_y + 98
    draw.rounded_rectangle([card_x + 20, item1_y, card_x + card_w - 20, item1_y + 75], radius=14, fill=(30, 41, 59, 180), outline=(51, 65, 85, 200), width=1)
    draw.text((card_x + 35, item1_y + 14), "Balayage & Estilismo", font=font_card_title, fill=(255, 255, 255, 255))
    draw.text((card_x + 35, item1_y + 42), "Viernes 16:30 • 120 min", font=font_card_body, fill=(148, 163, 184, 255))
    draw.rounded_rectangle([card_x + card_w - 110, item1_y + 20, card_x + card_w - 35, item1_y + 55], radius=10, fill=(132, 204, 22, 220))
    draw.text((card_x + card_w - 98, item1_y + 28), "Bs 350", font=font_card_title, fill=(15, 23, 42, 255))
    
    # Booking Calendar Item 2
    item2_y = item1_y + 88
    draw.rounded_rectangle([card_x + 20, item2_y, card_x + card_w - 20, item2_y + 75], radius=14, fill=(30, 41, 59, 180), outline=(51, 65, 85, 200), width=1)
    draw.text((card_x + 35, item2_y + 14), "Manicura Rusa Spa", font=font_card_title, fill=(255, 255, 255, 255))
    draw.text((card_x + 35, item2_y + 42), "Sábado 10:00 • 60 min", font=font_card_body, fill=(148, 163, 184, 255))
    draw.rounded_rectangle([card_x + card_w - 110, item2_y + 20, card_x + card_w - 35, item2_y + 55], radius=10, fill=(132, 204, 22, 220))
    draw.text((card_x + card_w - 98, item2_y + 28), "Bs 120", font=font_card_title, fill=(15, 23, 42, 255))
    
    # QR Simple Verified Banner inside Mockup
    qr_y = item2_y + 88
    draw.rounded_rectangle([card_x + 20, qr_y, card_x + card_w - 20, qr_y + 90], radius=16, fill=(5, 150, 105, 40), outline=(5, 150, 105, 180), width=1)
    draw.text((card_x + 35, qr_y + 15), "Pago QR Simple Confirmado", font=font_card_title, fill=(163, 230, 53, 255))
    draw.text((card_x + 35, qr_y + 42), "Transferencia directa sin comisiones abusivas", font=font_card_body, fill=(203, 213, 225, 255))
    draw.text((card_x + 35, qr_y + 62), "Todos los bancos de Bolivia (BCP, BNB, Mercantil)", font=ImageFont.truetype(font_reg_path, 13), fill=(148, 163, 184, 255))
    
    # Bottom CitasYa badge in card
    draw.text((card_x + 115, card_y + card_h - 35), "⚡ Powered by CitasYa.bo", font=font_card_body, fill=(100, 116, 139, 255))
    
    # 8. Bottom Brand Bar
    try:
        brand_logo = Image.open("/root/ClubeMkt/CitasYa/backend/public/images/brand/logo_white_horizontal.png").convert("RGBA")
        brand_logo.thumbnail((160, 45), Image.Resampling.LANCZOS)
        img.paste(brand_logo, (70, height - 75), brand_logo)
    except Exception as e:
        draw.text((70, height - 70), "CitasYa", font=font_h1, fill=(255, 255, 255, 255))
        
    draw.text((250, height - 60), "|   citasya.clubemkt.online   |   Guía Editorial 2026", font=font_card_body, fill=(148, 163, 184, 255))
    
    # Convert RGBA to RGB for saving PNG/JPEG
    rgb_img = Image.new("RGB", (width, height), (15, 23, 42))
    rgb_img.paste(img, (0, 0), img)
    
    # Save to both destinations
    dest1 = "/root/ClubeMkt/CitasYa/backend/public/images/blog/sistema-reservas-online-salones-santa-cruz.png"
    dest2 = "/root/ClubeMkt/seomachine/content/images/sistema-reservas-online-salones-santa-cruz.png"
    
    rgb_img.save(dest1, "PNG", quality=95, optimize=True)
    rgb_img.save(dest2, "PNG", quality=95, optimize=True)
    print(f"Saved cover image to {dest1} and {dest2}")

if __name__ == "__main__":
    create_blog_cover()
