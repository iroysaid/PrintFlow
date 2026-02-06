@echo off
setlocal
title PrintFlow POS - SERVER

echo ========================================================
echo   PRINTFLOW POS - SERVER RUNNER
echo ========================================================

:: 1. DETEKSI PHP (Termasuk XAMPP jika ada)
php -v >nul 2>&1
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set PATH=%PATH%;C:\xampp\php
    ) else (
        echo [ERROR] PHP tidak ditemukan. Jalankan 1_SETUP_AWAL.bat dulu!
        pause
        exit
    )
)

:: 2. AMBIL IP ADDRESS LOKAL (Cara Cerdas)
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /C:"IPv4 Address"') do (
    set IP=%%a
)
:: Hapus spasi kosong
set IP=%IP: =%

echo IP Komputer ini: %IP%
echo Aplikasi berjalan di: http://%IP%:8080
echo.
echo [PENTING] JANGAN TUTUP JENDELA HITAM INI SELAMA KASIR BUKA!
echo.

:: 3. BUKA BROWSER OTOMATIS (SERVER VIEW)
start chrome --app=http://%IP%:8080

:: 4. JALANKAN SERVER
php spark serve --host %IP% --port 8080
