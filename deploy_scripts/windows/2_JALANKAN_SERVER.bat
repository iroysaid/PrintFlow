@echo off
setlocal
title PrintFlow POS - SERVER

echo ========================================================
echo   PRINTFLOW POS - SERVER RUNNER
echo ========================================================

:: 1. CEK ADMIN ACCESS (Agar bisa setting Firewall)
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [OK] Running as Administrator
) else (
    echo [INFO] Membutuhkan akses Administrator untuk membuka Firewall...
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin.vbs"
    echo UAC.ShellExecute "%~s0", "", "", "runas", 1 >> "%temp%\getadmin.vbs"
    "%temp%\getadmin.vbs"
    del "%temp%\getadmin.vbs"
    exit /B
)

:: 2. BUKA FIREWALL WINDOWS (Port 4448)
echo.
echo [FIREWALL] Memastikan Port 4448 Terbuka...
netsh advfirewall firewall show rule name="PrintFlow POS" >nul
if %errorlevel% neq 0 (
    netsh advfirewall firewall add rule name="PrintFlow POS" dir=in action=allow protocol=TCP localport=4448
    echo [OK] Rule Firewall Berhasil Ditambahkan.
) else (
    echo [OK] Rule Firewall Sudah Ada.
)
echo.

:: 3. DETEKSI PHP (Termasuk XAMPP jika ada)
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

:: 4. AMBIL IP ADDRESS LOKAL (Cara Cerdas)
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /C:"IPv4 Address"') do (
    set IP=%%a
)
:: Hapus spasi kosong
set IP=%IP: =%

echo Postign IP: %IP%
echo Aplikasi berjalan di: http://%IP%:4448
echo.
echo [PENTING] JANGAN TUTUP JENDELA HITAM INI SELAMA KASIR BUKA!
echo.

:: 5. BUKA BROWSER OTOMATIS (SERVER VIEW)
start chrome --app=http://%IP%:4448
:: Fallback jika Chrome tidak ada
if %errorlevel% neq 0 start http://%IP%:4448

:: 6. JALANKAN SERVER
php spark serve --host 0.0.0.0 --port 4448
