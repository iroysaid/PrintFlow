#!/bin/bash
# ========================================================
# PRINTFLOW POS - SERVER RUNNER (MAC OS)
# ========================================================

# Pindah ke root directory project
cd "$(dirname "$0")/../.." || exit

# Ambil IP Address (en0 usually WiFi, en1 usually Ethernet on some macs, trying generic approach)
IP=$(ipconfig getifaddr en0)
if [ -z "$IP" ]; then
    IP=$(ipconfig getifaddr en1)
fi
if [ -z "$IP" ]; then
    IP="localhost"
fi

echo "========================================================"
echo "  PRINTFLOW POS - SERVER AKTIF"
echo "========================================================"
echo "IP Address Mac ini: $IP"
echo "Akses Aplikasi: http://$IP:8080"
echo ""
echo "[PENTING] Jangan tutup jendela Terminal ini."
echo ""

# Buka Google Chrome App Mode (jika ada)
if [ -d "/Applications/Google Chrome.app" ]; then
    /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --app=http://$IP:8080 &
else
    open "http://$IP:8080"
fi

# Jalankan Server
php spark serve --host 0.0.0.0 --port 8080
