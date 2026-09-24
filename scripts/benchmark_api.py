import urllib.request
import time

endpoints = [
    'https://citasya.clubemkt.online/api/settings',
    'https://citasya.clubemkt.online/api/modules',
    'https://citasya.clubemkt.online/api/translations/es',
    'https://citasya.clubemkt.online/api/slides',
    'https://citasya.clubemkt.online/api/categories',
    'https://citasya.clubemkt.online/api/categories?with=featuredEServices&parent=true&search=featured:1&searchFields=featured:=&orderBy=order&sortedBy=asc',
    'https://citasya.clubemkt.online/api/salons?only=id;name;has_media;media;total_reviews;rate;salonLevel;distance;closed&with=salonLevel&limit=6&myLat=-17.7833&myLon=-63.1821',
]

for ep in endpoints:
    t0 = time.time()
    req = urllib.request.Request(ep, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
    try:
        with urllib.request.urlopen(req) as resp:
            data = resp.read()
            dt = time.time() - t0
            print(f'{dt:.3f}s | {resp.status} | {len(data):6d} bytes | {ep}')
    except Exception as e:
        print(f'ERR {ep}: {e}')
