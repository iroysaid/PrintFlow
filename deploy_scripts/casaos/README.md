# Cara Deploy Printflow ke CasaOS (Armbian)

Panduan ini menjelaskan cara deploy aplikasi POS Printflow ke CasaOS menggunakan Docker.

## Persyaratan
- CasaOS sudah terinstall di Armbian.
- Akses terminal atau SSH ke server Armbian.
- Git (opsional, untuk clone repo).

## Langkah-langkah

1.  **Upload/Clone Project**
    Upload folder project `pos-app` ke server CasaOS Anda (misalnya ke `/DATA/AppData/pos-app`).

2.  **Masuk ke Directory**
    Buka terminal di CasaOS atau via SSH, lalu masuk ke folder `deploy_scripts/casaos`:
    ```bash
    cd /path/to/pos-app/deploy_scripts/casaos
    ```

3.  **Build & Run**
    Jalankan perintah berikut untuk membangun dan menjalankan container:
    ```bash
    docker compose up -d --build
    ```

4.  **Akses Aplikasi**
    Buka browser dan akses alamat IP CasaOS Anda dengan port `8085`:
    ```
    http://<IP-ADDRESS>:8085
    ```

## Catatan Penting
- **Database**: Aplikasi menggunakan SQLite yang tersimpan di folder `writable/database.sqlite`. Folder `writable` di-mount dari host agar data tidak hilang saat container dihapus.
- **Permission**: Jika ada error permission saat upload file gambar atau log, pastikan folder `writable` di host memiliki permission yang benar (`chmod -R 775 writable`).
- **Port**: Jika port 8085 bentrok, ubah di file `docker-compose.yml`.

## Struktur Docker
- **App Service**: Container PHP-FPM 8.2 (Alpine).
- **Web Service**: Container Nginx (Alpine).
