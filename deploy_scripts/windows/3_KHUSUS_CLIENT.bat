@echo off
setlocal
title PrintFlow POS - CLIENT

echo ========================================================
echo   PRINTFLOW POS - CLIENT CONNECT
echo ========================================================
echo.
echo Masukkan IP Server (lihat di layar komputer Server).
echo Contoh: 192.168.1.10
echo.
set /p SERVER_IP="Masukkan IP Server: "

if "%SERVER_IP%"=="" (
    echo IP tidak boleh kosong!
    pause
    exit
)

echo.
echo Menghubungkan ke http://%SERVER_IP%:8080 ...
start chrome --app=http://%SERVER_IP%:8080

exit
