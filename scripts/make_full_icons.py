import subprocess
import os
from collections import deque

def make_full_bleed_icon(src_path, dest_path, crop_scale=1.20):
    # Get dimensions
    probe = subprocess.check_output([
        'ffprobe', '-v', 'error', '-show_entries', 'stream=width,height',
        '-of', 'csv=p=0', src_path
    ]).decode().strip().split(',')
    w, h = int(probe[0]), int(probe[1])

    crop_w = int(w / crop_scale)
    crop_h = int(h / crop_scale)
    x_offset = (w - crop_w) // 2
    y_offset = (h - crop_h) // 2

    # Export cropped RGBA
    cmd_crop = [
        'ffmpeg', '-y', '-i', src_path,
        '-vf', f'crop={crop_w}:{crop_h}:{x_offset}:{y_offset},scale=1024:1024:flags=lanczos',
        '-f', 'rawvideo', '-pix_fmt', 'rgba', '-'
    ]
    proc = subprocess.Popen(cmd_crop, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    raw, _ = proc.communicate()
    
    pixels = bytearray(raw)
    size = 1024
    
    # Flood-fill any light/white/transparent pixel connected to the 4 corners
    visited = bytearray(size * size)
    q = deque([(0,0), (size-1, 0), (0, size-1), (size-1, size-1)])
    
    for x, y in q:
        visited[y * size + x] = 1
        
    dark_r, dark_g, dark_b, dark_a = 0, 38, 19, 255 # Deep emerald green matching icon bevel
    
    while q:
        cx, cy = q.popleft()
        idx = (cy * size + cx) * 4
        
        # Color current corner pixel
        pixels[idx] = dark_r
        pixels[idx+1] = dark_g
        pixels[idx+2] = dark_b
        pixels[idx+3] = dark_a
        
        for nx, ny in ((cx+1, cy), (cx-1, cy), (cx, cy+1), (cx, cy-1)):
            if 0 <= nx < size and 0 <= ny < size:
                pos = ny * size + nx
                if not visited[pos]:
                    nidx = pos * 4
                    r, g, b, a = pixels[nidx], pixels[nidx+1], pixels[nidx+2], pixels[nidx+3]
                    is_outer_bg = (r > 160 and g > 160 and b > 160) or a < 220
                    if is_outer_bg:
                        visited[pos] = 1
                        q.append((nx, ny))

    # Save to final PNG
    p_out = subprocess.Popen([
        'ffmpeg', '-y', '-f', 'rawvideo', '-pix_fmt', 'rgba', '-s', f'{size}x{size}',
        '-i', '-', '-frames:v', '1', '-update', '1', dest_path
    ], stdin=subprocess.PIPE, stderr=subprocess.PIPE)
    p_out.communicate(input=bytes(pixels))
    print(f"Full-bleed master generated: {dest_path}")

def generate_all_assets(master_png, app_dir, is_owner=False):
    # App Icons
    subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{app_dir}/assets/icon/icon.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{app_dir}/assets/icon/splash.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=96:96', '-frames:v', '1', '-update', '1', f"{app_dir}/assets/icon/notification.png"])
    
    # Android Mipmaps
    densities = [
        ('mdpi', 48, 24),
        ('hdpi', 72, 36),
        ('xhdpi', 96, 48),
        ('xxhdpi', 144, 72),
        ('xxxhdpi', 192, 96),
    ]
    for name, icon_s, notif_s in densities:
        mipmap_dir = f"{app_dir}/android/app/src/main/res/mipmap-{name}"
        os.makedirs(mipmap_dir, exist_ok=True)
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', f'scale={icon_s}:{icon_s}', '-frames:v', '1', '-update', '1', f"{mipmap_dir}/ic_launcher.png"])
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', f'scale={notif_s}:{notif_s}', '-frames:v', '1', '-update', '1', f"{mipmap_dir}/ic_notification.png"])
    
    # Web Icons
    web_dir = f"{app_dir}/web"
    if os.path.exists(web_dir):
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=192:192', '-frames:v', '1', '-update', '1', f"{web_dir}/favicon.png"])
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=192:192', '-frames:v', '1', '-update', '1', f"{web_dir}/icons/Icon-192.png"])
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{web_dir}/icons/Icon-512.png"])
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=192:192', '-frames:v', '1', '-update', '1', f"{web_dir}/icons/Icon-maskable-192.png"])
        subprocess.check_call(['ffmpeg', '-y', '-i', master_png, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{web_dir}/icons/Icon-maskable-512.png"])

if __name__ == "__main__":
    base = "/root/ClubeMkt/CitasYa"
    
    # 1. Customer
    cust_master = f"{base}/docs/appicon.png"
    make_full_bleed_icon(f"{base}/docs/appicon.webp", cust_master, crop_scale=1.20)
    generate_all_assets(cust_master, f"{base}/app-customer", is_owner=False)
    
    # 2. Owner
    owner_master = f"{base}/docs/appowner.png"
    make_full_bleed_icon(f"{base}/docs/appowner.webp", owner_master, crop_scale=1.20)
    generate_all_assets(owner_master, f"{base}/app-owner", is_owner=True)
    
    # 3. Backend Brand & Favicons
    subprocess.check_call(['ffmpeg', '-y', '-i', cust_master, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{base}/backend/public/images/brand/icon_3d.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', owner_master, '-vf', 'scale=512:512', '-frames:v', '1', '-update', '1', f"{base}/backend/public/images/brand/icon_owner.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', cust_master, '-vf', 'scale=64:64', f"{base}/backend/public/favicon.ico"])
    subprocess.check_call(['ffmpeg', '-y', '-i', cust_master, '-vf', 'scale=192:192', '-frames:v', '1', '-update', '1', f"{base}/backend/public/favicon.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', cust_master, '-vf', 'scale=180:180', '-frames:v', '1', '-update', '1', f"{base}/backend/public/apple-touch-icon.png"])
    subprocess.check_call(['ffmpeg', '-y', '-i', cust_master, '-vf', 'scale=192:192', '-frames:v', '1', '-update', '1', f"{base}/backend/public/images/brand/favicon.png"])

    print("All icons successfully generated full-bleed without white borders!")
