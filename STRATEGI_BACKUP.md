# Strategi Backup Database PrintFlow (Otomatis & Aman)

Menyimpan database ke GitHub setiap hari **TIDAK DISARANKAN** karena:
1.  **Privasi**: Data pelanggan dan keuangan akan tersimpan di internet (GitHub).
2.  **Ukuran**: Database SQLite adalah file biner. Setiap hari di-upload akan membuat repository membengkak (bisa bergiga-giga) hingga menjadi lambat.
3.  **Konflik**: Jika ada error upload, database bisa rusak.

## Solusi Terbaik: Backup Lokal ke Cloud (Google Drive / OneDrive)
Cara paling aman dan profesional adalah membuat duplikat database setiap hari ke folder yang terhubung dengan Google Drive / OneDrive.

### Langkah 1: Buat Folder Backup
Buat folder baru, misal di `D:\PrintFlow_Backup` atau di dalam folder Google Drive Anda.

### Langkah 2: Buat Script Backup Otomatis
1.  Buka Notepad.
2.  Copy kode di bawah ini:
    ```batch
    @echo off
    set "source=C:\xampp\htdocs\PrintFlow\writable\database.sqlite"
    set "destination=D:\PrintFlow_Backup"
    set "filename=database_%date:~-4,4%-%date:~-7,2%-%date:~-10,2%.sqlite"

    echo Membackup database...
    copy "%source%" "%destination%\%filename%"
    echo Selesai! Backup tersimpan di %destination%\%filename%
    timeout /t 5
    ```
    *(Sesuaikan path `destination` dengan lokasi folder Google Drive/OneDrive Anda)*

3.  Simpan file ini dengan nama **`BackupHarian.bat`** di Desktop.

### Cara Pakai
Setiap sore sebelum pulang:
1.  **Klik 2x `BackupHarian.bat`**.
2.  File database akan tercopy otomatis dengan nama tanggal hari ini (contoh: `database_2024-02-06.sqlite`).
3.  Biarkan Google Drive/OneDrive menguploadnya ke cloud secara otomatis.

---

## Opsi Alternatif: Jika *Sangat* Ingin ke GitHub
Jika Anda tetao ingin memaksa upload ke GitHub (resiko ditanggung sendiri seperti repo lambat dan database korup):

1.  Ubah file `.gitignore`, hapus baris `/writable/*`.
2.  Buat script `BackupToGit.bat`:
    ```batch
    @echo off
    cd C:\xampp\htdocs\PrintFlow
    git add writable/database.sqlite
    git commit -m "Backup Database Harian"
    git push origin main
    echo Backup terupload ke GitHub!
    pause
    ```
**Peringatan**: Cara GitHub ini akan membuat proses Push/Pull menjadi sangat lambat setelah berjalan beberapa minggu.
