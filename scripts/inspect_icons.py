import subprocess

def inspect(path):
    cmd = ['ffmpeg', '-y', '-i', path, '-vf', 'scale=1024:1024', '-f', 'rawvideo', '-pix_fmt', 'rgba', '-']
    proc = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    raw, _ = proc.communicate()
    print(f"File {path}:")
    print("Top-left (0,0) RGBA:", raw[0], raw[1], raw[2], raw[3])
    print("Top-right (1023,0) RGBA:", raw[1023*4], raw[1023*4+1], raw[1023*4+2], raw[1023*4+3])
    alphas = raw[3::4]
    print(f"Alphas min: {min(alphas)}, max: {max(alphas)}")
    # sample some corner pixels
    for y in [0, 50, 100, 200, 512]:
        x = y
        idx = (y * 1024 + x) * 4
        print(f"Pixel ({x},{y}) RGBA: {raw[idx]}, {raw[idx+1]}, {raw[idx+2]}, {raw[idx+3]}")

inspect('/root/ClubeMkt/CitasYa/docs/appicon.webp')
