@echo off
:: ========================================================
:: PRINTFLOW POS - CLIENT LAUNCHER (WINDOWS)
:: ========================================================
:: 1. Pastikan komputer ini konek ke WIFI/LAN yang sama dengan Server.
:: 2. Ganti IP di bawah ini sesuai IP Komputer Server (Komputer Utama).
::    Cara cek IP Server: Buka CMD di server -> ketik "ipconfig"
:: ========================================================

set SERVER_IP=192.168.1.21
set PORT=8080

echo Menghubungkan ke PrintFlow POS Server di %SERVER_IP%...

:: Coba Buka Chrome dalam Mode App (Tanpa Address Bar)
start chrome --app=http://%SERVER_IP%:%PORT%

:: Jika Chrome tidak ada, script akan lanjut (error level tidak selalu reliable di start, tapi user akan lihat error sistem)
:: Opsi alternatif: Microsoft Edge
:: start msedge --app=http://%SERVER_IP%:%PORT%

exit
