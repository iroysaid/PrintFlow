#!/bin/bash
# ========================================================
# PRINTFLOW POS - CLIENT CONNECT (MAC OS)
# ========================================================

echo "========================================================"
echo "  PRINTFLOW POS - CLIENT CONNECT"
echo "========================================================"
echo ""
echo "Masukkan IP Address Server (Lihat di komputer utama):"
read -p "IP Server: " SERVER_IP

if [ -z "$SERVER_IP" ]; then
    echo "IP tidak boleh kosong."
    exit
fi

echo "Menghubungkan ke http://$SERVER_IP:8080 ..."

# Buka Chrome App Mode atau Default Browser
if [ -d "/Applications/Google Chrome.app" ]; then
    /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --app=http://$SERVER_IP:8080 &
else
    open "http://$SERVER_IP:8080"
fi

# killall Terminal # Uncomment jika ingin terminal otomatis tertutup (bisa memicu warning permission)
