import subprocess
import os

base = "/root/ClubeMkt/CitasYa"

def run_ffmpeg(args):
    subprocess.check_call(['ffmpeg', '-y'] + args)

def render_master_svg(svg_path, dest_png):
    subprocess.check_call(['rsvg-convert', '-w', '1024', '-h', '1024', svg_path, '-o', dest_png])
    print(f"Rendered clean master PNG from SVG: {dest_png}")

def generate_adaptive_foreground(src_png, dest_png, size=432):
    # Android adaptive foreground needs content in the center 66-72%
    icon_sz = int(size * 0.72)
    pad = (size - icon_sz) // 2
    pad_filter = f"scale={icon_sz}:{icon_sz}:flags=lanczos,pad={size}:{size}:{pad}:{pad}:color=0x00000000"
    run_ffmpeg(['-i', src_png, '-vf', pad_filter, dest_png])

def generate_assets_for_app(master_png, app_dir, is_owner=False):
    # 1. assets/icon/
    icon_dir = f"{app_dir}/assets/icon"
    img_dir = f"{app_dir}/assets/img"
    os.makedirs(icon_dir, exist_ok=True)
    os.makedirs(img_dir, exist_ok=True)

    run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{icon_dir}/icon.png"])
    run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{icon_dir}/splash.png"])
    run_ffmpeg(['-i', master_png, '-vf', 'scale=96:96:flags=lanczos', f"{icon_dir}/notification.png"])

    # 2. assets/img/
    run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{img_dir}/appicon.png"])
    run_ffmpeg(['-i', master_png, '-vf', 'scale=192:192:flags=lanczos', f"{img_dir}/favicon.png"])
    run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{img_dir}/icon_2d.png"])
    run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{img_dir}/icon_3d.png"])
    if is_owner:
        run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{img_dir}/icon_owner.png"])

    # 3. Android Mipmaps & Drawables
    densities = [
        ('mdpi', 48, 24, 108),
        ('hdpi', 72, 36, 162),
        ('xhdpi', 96, 48, 216),
        ('xxhdpi', 144, 72, 324),
        ('xxxhdpi', 192, 96, 432),
    ]
    for name, icon_s, notif_s, fg_s in densities:
        mipmap_dir = f"{app_dir}/android/app/src/main/res/mipmap-{name}"
        drawable_dir = f"{app_dir}/android/app/src/main/res/drawable-{name}"
        os.makedirs(mipmap_dir, exist_ok=True)
        os.makedirs(drawable_dir, exist_ok=True)

        run_ffmpeg(['-i', master_png, '-vf', f'scale={icon_s}:{icon_s}:flags=lanczos', f"{mipmap_dir}/ic_launcher.png"])
        run_ffmpeg(['-i', master_png, '-vf', f'scale={notif_s}:{notif_s}:flags=lanczos', f"{mipmap_dir}/ic_notification.png"])
        generate_adaptive_foreground(master_png, f"{drawable_dir}/ic_launcher_foreground.png", size=fg_s)

    # 4. Web Icons
    web_dir = f"{app_dir}/web"
    if os.path.exists(web_dir):
        os.makedirs(f"{web_dir}/icons", exist_ok=True)
        run_ffmpeg(['-i', master_png, '-vf', 'scale=192:192:flags=lanczos', f"{web_dir}/favicon.png"])
        run_ffmpeg(['-i', master_png, '-vf', 'scale=192:192:flags=lanczos', f"{web_dir}/icons/Icon-192.png"])
        run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{web_dir}/icons/Icon-512.png"])
        run_ffmpeg(['-i', master_png, '-vf', 'scale=192:192:flags=lanczos', f"{web_dir}/icons/Icon-maskable-192.png"])
        run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{web_dir}/icons/Icon-maskable-512.png"])
        run_ffmpeg(['-i', master_png, '-vf', 'scale=512:512:flags=lanczos', f"{web_dir}/loading_logo.png"])

def main():
    svg_favicon = f"{base}/backend/public/favicon.svg"
    cust_png = f"{base}/docs/appicon.png"
    owner_png = f"{base}/docs/appowner.png"

    # Render clean masters directly from favicon SVG
    render_master_svg(svg_favicon, cust_png)
    render_master_svg(svg_favicon, owner_png)

    # Generate customer app assets
    generate_assets_for_app(cust_png, f"{base}/app-customer", is_owner=False)

    # Generate owner app assets
    generate_assets_for_app(owner_png, f"{base}/app-owner", is_owner=True)

    # Backend Brand Assets
    brand_dir = f"{base}/backend/public/images/brand"
    os.makedirs(brand_dir, exist_ok=True)
    run_ffmpeg(['-i', cust_png, '-vf', 'scale=512:512:flags=lanczos', f"{brand_dir}/icon_3d.png"])
    run_ffmpeg(['-i', cust_png, '-vf', 'scale=512:512:flags=lanczos', f"{brand_dir}/icon_2d.png"])
    run_ffmpeg(['-i', owner_png, '-vf', 'scale=512:512:flags=lanczos', f"{brand_dir}/icon_owner.png"])
    run_ffmpeg(['-i', cust_png, '-vf', 'scale=512:512:flags=lanczos', f"{brand_dir}/appicon.png"])
    run_ffmpeg(['-i', owner_png, '-vf', 'scale=512:512:flags=lanczos', f"{brand_dir}/appowner.png"])

    print("All icons successfully generated from favicon!")

if __name__ == "__main__":
    main()
