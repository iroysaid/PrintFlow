# PANDUAN INSTALASI & PENGGUNAAN (WINDOWS)

Folder ini berisi 3 Script Ajaib untuk menjalankan Aplikasi POS tanpa ribet.

## 1. INSTALASI PERTAMA KALI (Hanya Server)
- Jalankan file `1_SETUP_AWAL.bat`.
- Script ini otomatis mengecek apakah PHP sudah terinstall atau ada XAMPP.
- Jika belum ada, script akan mencoba install otomatis (perlu internet).
- Script juga akan menyiapkan Database secara otomatis.

## 2. MENJALANKAN APLIKASI (Server / Komputer Utama)
- Jalankan file `2_JALANKAN_SERVER.bat`.
- Jendela hitam (Terminal) akan muncul. **JANGAN DITUTUP**.
- Browser akan otomatis terbuka dan masuk ke aplikasi.
- IP Address komputer ini akan muncul di layar hitam. Catat IP ini untuk Client.

## 3. UNTUK KOMPUTER KASIR LAIN (Client)
- Copy file `3_KHUSUS_CLIENT.bat` ke komputer kasir.
- Jalankan file tersebut.
- Masukkan IP Server yang muncul di langkah no 2 (misal: 192.168.1.5).
- Aplikasi langsung terbuka!

---
**Catatan:**
- Pastikan semua komputer terhubung ke Wifi/LAN yang sama.
- Jika Windows Firewall memblokir, klik "Allow Access" saat pertama kali `php spark serve` jalan.
