# PrintFlow - Sistem Manajemen Percetakan Digital 🖨️

PrintFlow adalah aplikasi Point of Sale (POS) dan Manajemen Produksi khusus untuk bisnis Percetakan Digital (Digital Printing). Dibuat dengan **CodeIgniter 4**, **Bootstrap 5**, dan **Alpine.js**.

## Fitur Unggulan
*   **Perhitungan Otomatis**: Menghitung harga barang Satuan (Unit) dan Meteran (Panjang x Lebar).
*   **Manajemen Produksi**: Fitur Kanban BOARD untuk memantau status pesanan (Queue -> Design -> Print -> Finishing).
*   **Landing Page Publik**: Halaman depan untuk menampilkan layanan dan promo ke pelanggan.
*   **Multi-Role**: Login khusus untuk Admin, Kasir, dan Bagian Produksi.

---

## 📚 Panduan Installasi & Penggunaan

Kami telah menyediakan panduan lengkap agar aplikasi ini bisa berjalan lancar di kantor Anda:

### 1. 🖥️ [Panduan Install di Windows (Server Utama)](PANDUAN_WINDOWS.md)
*Bacalah ini jika Anda ingin menginstall di komputer kasir/admin utama.*
*   Instalasi XAMPP & Git.
*   Cara agar database **TIDAK RESET** saat komputer dimatikan.
*   Script Start Otomatis.

### 2. 🔗 [Panduan Koneksi LAN (Satu Kantor)](PANDUAN_LAN.md)
*Bacalah ini agar komputer lain bisa ikut membuka aplikasi.*
*   Cara install dari GitHub.
*   Cara cek IP Address Server.
*   Cara akses dari HP atau Laptop lain.

### 3. 💾 [Strategi Backup Aman](STRATEGI_BACKUP.md)
*PENTING: Jangan sampai data hilang!*
*   Mengapa upload database ke GitHub itu berbahaya.
*   Cara otomatis backup ke Google Drive / OneDrive menggunakan script (GRATIS).

---

## Akun Default (Login)
*   **Admin**: `admin` / `admin123`
*   **Kasir**: `cashier` / `cashier123`
*   **Produksi**: `production` / `print123`

---

## Instalasi Cepat (Developer)
```bash
git clone https://github.com/iroysaid/PrintFlow.git
cd PrintFlow
composer install
cp env .env
# Set CI_ENVIRONMENT = development di .env
php spark serve
```
