@echo off
setlocal
title PrintFlow POS - Setup Awal

echo ========================================================
echo   PRINTFLOW POS - ONE CLICK SETUP
echo ========================================================
echo.
echo Sedang memeriksa sistem...

:: 1. CEK PHP
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [!] PHP tidak terdeteksi di System PATH.
    echo     Mencari instalasi XAMPP...
    
    if exist "C:\xampp\php\php.exe" (
        echo [OK] XAMPP ditemukan di C:\xampp
        set PATH=%PATH%;C:\xampp\php
    ) else (
        echo [X] PHP / XAMPP tidak ditemukan!
        echo.
        echo Kami akan mencoba menginstall PHP otomatis via Winget...
        echo Tekan sembarang tombol untuk lanjut install PHP...
        pause
        winget install XP8K0HKJFRXGCK
        echo.
        echo Silakan RESTART komputer Anda setelah install selesai, lalu jalankan file ini lagi.
        pause
        exit
    )
) else (
    echo [OK] PHP terdeteksi.
)

:: 2. SETUP DATABASE & FRAMEWORK
echo.
echo [1/2] Menyiapkan Database (Migrating)...
call php spark migrate
if %errorlevel% neq 0 (
    echo [!] Gagal migrasi database. Pastikan folder writable bisa diakses.
    pause
    exit
)

echo.
echo [2/2] Membersihkan Cache...
call php spark cache:clear

echo.
echo ========================================================
echo   SETUP SELESAI! SIAP DIGUNAKAN.
echo ========================================================
echo Silakan jalankan file "2_JALANKAN_SERVER.bat" untuk mulai.
pause
