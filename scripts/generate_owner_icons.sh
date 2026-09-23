#!/bin/bash
set -e

SRC="/root/ClubeMkt/CitasYa/docs/appowner.webp"
BASE="/root/ClubeMkt/CitasYa"

if [ ! -f "$SRC" ]; then
    echo "Source $SRC does not exist!"
    exit 1
fi

echo "Converting $SRC to PNG assets..."

# Master PNG
ffmpeg -y -i "$SRC" "$BASE/docs/appowner.png"

# App Owner Assets
ffmpeg -y -i "$SRC" -vf scale=512:512 "$BASE/app-owner/assets/icon/icon.png"
ffmpeg -y -i "$SRC" -vf scale=512:512 "$BASE/app-owner/assets/icon/splash.png"
ffmpeg -y -i "$SRC" -vf scale=96:96 "$BASE/app-owner/assets/icon/notification.png"

# Android Mipmaps - Launcher
ffmpeg -y -i "$SRC" -vf scale=48:48 "$BASE/app-owner/android/app/src/main/res/mipmap-mdpi/ic_launcher.png"
ffmpeg -y -i "$SRC" -vf scale=72:72 "$BASE/app-owner/android/app/src/main/res/mipmap-hdpi/ic_launcher.png"
ffmpeg -y -i "$SRC" -vf scale=96:96 "$BASE/app-owner/android/app/src/main/res/mipmap-xhdpi/ic_launcher.png"
ffmpeg -y -i "$SRC" -vf scale=144:144 "$BASE/app-owner/android/app/src/main/res/mipmap-xxhdpi/ic_launcher.png"
ffmpeg -y -i "$SRC" -vf scale=192:192 "$BASE/app-owner/android/app/src/main/res/mipmap-xxxhdpi/ic_launcher.png"

# Android Mipmaps - Notification
ffmpeg -y -i "$SRC" -vf scale=24:24 "$BASE/app-owner/android/app/src/main/res/mipmap-mdpi/ic_notification.png"
ffmpeg -y -i "$SRC" -vf scale=36:36 "$BASE/app-owner/android/app/src/main/res/mipmap-hdpi/ic_notification.png"
ffmpeg -y -i "$SRC" -vf scale=48:48 "$BASE/app-owner/android/app/src/main/res/mipmap-xhdpi/ic_notification.png"
ffmpeg -y -i "$SRC" -vf scale=72:72 "$BASE/app-owner/android/app/src/main/res/mipmap-xxhdpi/ic_notification.png"
ffmpeg -y -i "$SRC" -vf scale=96:96 "$BASE/app-owner/android/app/src/main/res/mipmap-xxxhdpi/ic_notification.png"

# Web Icons
ffmpeg -y -i "$SRC" -vf scale=192:192 "$BASE/app-owner/web/favicon.png"
ffmpeg -y -i "$SRC" -vf scale=192:192 "$BASE/app-owner/web/icons/Icon-192.png"
ffmpeg -y -i "$SRC" -vf scale=512:512 "$BASE/app-owner/web/icons/Icon-512.png"
ffmpeg -y -i "$SRC" -vf scale=192:192 "$BASE/app-owner/web/icons/Icon-maskable-192.png"
ffmpeg -y -i "$SRC" -vf scale=512:512 "$BASE/app-owner/web/icons/Icon-maskable-512.png"

echo "All Owner icons successfully generated!"
