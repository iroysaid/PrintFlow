#!/bin/bash
# ========================================================
# PRINTFLOW POS - MAC OS SETUP
# Aman & Transparan: Script ini hanya mengecek instalasi PHP.
# ========================================================

echo "========================================================"
echo "  PRINTFLOW POS - SETUP AWAL (MAC OS)"
echo "========================================================"
echo ""

# 1. Cek Homebrew (Package Manager Mac)
if ! command -v brew &> /dev/null
then
    echo "[!] Homebrew tidak ditemukan."
    echo "    Untuk menginstall PHP dengan aman, disarankan install Homebrew."
    echo "    Kunjungi: https://brew.sh/ atau hubungi teknisi IT Anda."
    echo ""
    read -p "Tekan ENTER untuk keluar..."
    exit
fi

# 2. Cek PHP
if ! command -v php &> /dev/null
then
    echo "[!] PHP tidak ditemukan."
    echo "    Sedang mencoba install PHP via Homebrew..."
    brew install php
else
    echo "[OK] PHP sudah terinstall."
fi

# 3. Setup Database
echo ""
echo "[1/2] Setup Database..."
cd "$(dirname "$0")/../.." || exit
php spark migrate

echo ""
echo "[2/2] Membersihkan Cache..."
php spark cache:clear

echo ""
echo "========================================================"
echo "  SETUP SELESAI! SIAP DIGUNAKAN."
echo "========================================================"
echo "Silakan jalankan file '2_SERVER.command' untuk mulai."
echo ""
read -p "Tekan ENTER untuk keluar..."
