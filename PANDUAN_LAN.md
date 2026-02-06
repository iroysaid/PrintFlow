# Panduan Installasi PrintFlow di Windows (Server LAN)

Panduan ini untuk menginstall aplikasi **PrintFlow** dari awal (GitHub) di komputer Windows agar bisa diakses oleh komputer lain di kantor.

## 1. Persiapan Software (Wajib)
Download dan install software berikut di komputer "Server" (yang akan menjalankan aplikasi):

1.  **XAMPP** (Download yang PHP 8.1 / 8.2)
    *   Link: [apachefriends.org](https://www.apachefriends.org/)
    *   *Fungsi: Menjalankan PHP.*
2.  **Git**
    *   Link: [git-scm.com](https://git-scm.com/download/win)
    *   *Fungsi: Mengambil kode dari GitHub.*
3.  **Composer** (Penting!)
    *   Link: [getcomposer.org](https://getcomposer.org/download/)
    *   Saat install, pastikan centang "Developer Mode" (opsional tapi bagus). Arahkan `php.exe` ke `C:\xampp\php\php.exe`.
    *   *Fungsi: Menginstall library aplikasi.*

---

## 2. Mengambil Kode (Clone)
1.  Buka **File Explorer**, masuk ke folder `C:\xampp\htdocs`.
2.  Klik kanan di ruang kosong > **Open Git Bash Here**.
3.  Ketik perintah ini:
    ```bash
    git clone https://github.com/iroysaid/PrintFlow.git
    ```
4.  Sekarang akan muncul folder baru bernama `PrintFlow`.

---

## 3. Setup Aplikasi
1.  Masuk ke folder `PrintFlow` dengan terminal (atau buka Git Bash di dalam folder `PrintFlow`):
    ```bash
    cd PrintFlow
    ```
2.  Install dependencies (Wajib):
    ```bash
    composer install
    ```
    *Tunggu proses selesai.*
3.  Duplikasi file environment:
    *   Copy file `env` menjadi `.env` (isi titik di depan).
    *   Buka `.env` dengan Notepad.
    *   Cari baris `CI_ENVIRONMENT = production`.
    *   Ubah jadi: `CI_ENVIRONMENT = development`.

---

## 4. Menjalankan Server untuk LAN
Agar teman sekantor bisa akses, kita harus jalankan di IP Address komputer ini.

### A. Cek IP Address
1.  Buka Command Prompt (CMD).
2.  Ketik `ipconfig`.
3.  Cari **IPv4 Address**. (Biasanya `192.168.1.X` atau `10.X.X.X`).
    *   *Misal IP-nya: `192.168.1.10`*

### B. Jalankan Server
Ketik perintah ini di terminal folder PrintFlow:
```bash
php spark serve --host 0.0.0.0 --port 8080
```
*(Jangan tutup jendela terminal ini selama jam kantor)*

---

## 5. Cara Akses (Dari Komputer Lain)
Teman-teman di kantor sekarang bisa membuka browser (Chrome/Edge) dan mengetik alamat:

**http://[IP_KOMPUTER_SERVER]:8080**

Contoh:
`http://192.168.1.10:8080`

---

## Tips Tambahan
1.  **Firewall**: Jika teman tidak bisa akses, mungkin terblokir firewall.
    *   Saat pertama kali run `php spark serve`, akan muncul popup Windows Firewall. Klik **Allow Access** (Centang Private & Public).
2.  **Database**: Database sudah aman tersimpan di folder `writable/database.sqlite`. Jangan dihapus!
3.  **Shortcut**:
    Buat file `StartServer.bat` di Desktop server agar mudah dijalankan tiap pagi:
    ```batch
    @echo off
    cd C:\xampp\htdocs\PrintFlow
    php spark serve --host 0.0.0.0 --port 8080
    pause
    ```
