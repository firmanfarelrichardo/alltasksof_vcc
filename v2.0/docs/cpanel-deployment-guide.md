# cPanel Deployment Guide - Zeta v2.0

## Tujuan

Panduan ini menjelaskan langkah deploy aplikasi Zeta `v2.0` ke hosting cPanel dengan benar.

Aplikasi ini menggunakan:

1. PHP native MVC.
2. MySQL.
3. Composer untuk `midtrans/midtrans-php`.
4. Web root di folder `public/`.

## Prinsip Penting

1. Jangan expose folder aplikasi penuh ke publik.
2. Document root domain harus mengarah ke:

```text
v2.0/public
```

3. File `.env` harus berada di luar web root.
4. Jangan upload `.env` berisi credential production ke repository.
5. Jangan mengubah controller, model, route, atau view hanya untuk deploy.
6. Untuk production:

```env
APP_ENV=production
APP_DEBUG=false
```

## Struktur Deploy yang Direkomendasikan

Jika cPanel user adalah `USERNAME`, struktur aman:

```text
/home/USERNAME/
├── zeta/
│   └── v2.0/
│       ├── app/
│       ├── config/
│       ├── database/
│       ├── docs/
│       ├── public/
│       │   ├── index.php
│       │   ├── .htaccess
│       │   └── assets/
│       ├── routes/
│       ├── storage/
│       ├── vendor/
│       ├── .env
│       └── composer.json
└── public_html/
```

Lalu atur document root domain/subdomain ke:

```text
/home/USERNAME/zeta/v2.0/public
```

Ini paling aman karena browser hanya bisa mengakses isi `public/`.

## Jika cPanel Tidak Mengizinkan Document Root ke Folder Custom

Sebagian shared hosting hanya mengarah ke `public_html`.

Urutan rekomendasi:

1. Buat subdomain atau addon domain dari menu cPanel `Domains`.
2. Saat membuat domain/subdomain, set document root ke:

```text
/home/USERNAME/zeta/v2.0/public
```

3. Jika cPanel tetap memaksa `public_html`, minta provider mengubah document root.

Jangan asal upload seluruh folder `v2.0` ke `public_html` jika document root mengarah ke parent folder, karena file seperti `.env`, `app/`, `config/`, dan `database/` berisiko bisa diakses publik jika konfigurasi server salah.

## Requirement Hosting

Pastikan cPanel menyediakan:

1. PHP 8.2 atau minimal PHP 8.1.
2. Extension PHP:
   - `pdo_mysql`
   - `json`
   - `curl`
   - `openssl`
   - `mbstring`
   - `fileinfo`
3. MySQL atau MariaDB.
4. Composer, minimal bisa dijalankan via Terminal cPanel atau lokal sebelum upload.
5. Apache rewrite aktif.

Di cPanel, cek melalui:

```text
Select PHP Version
```

atau:

```text
MultiPHP Manager
```

## Step 1 - Siapkan Source Code Lokal

Dari komputer lokal:

```powershell
cd C:\laragon\www\alltasksof_vcc\v2.0
```

Pastikan dependency Composer ada:

```powershell
composer install --no-dev --optimize-autoloader
```

Jika Composer tidak tersedia di hosting, folder `vendor/` hasil command ini ikut diupload.

## Step 2 - Buat Paket Upload

Upload isi folder `v2.0` ke:

```text
/home/USERNAME/zeta/v2.0
```

Folder yang wajib ada di server:

```text
app/
config/
database/
public/
routes/
storage/
vendor/
.env
composer.json
composer.lock
```

Folder/file yang tidak wajib diupload:

```text
database/backups/*.sql
storage/sessions/*
storage/logs/*.log
docs/
```

`docs/` boleh diupload jika ingin menyimpan dokumentasi di server, tetapi tidak boleh menjadi web root.

## Step 3 - Atur Document Root Domain

Di cPanel:

1. Buka `Domains`.
2. Pilih domain atau subdomain untuk Zeta.
3. Set document root ke:

```text
/home/USERNAME/zeta/v2.0/public
```

Contoh:

```text
zeta.example.com -> /home/USERNAME/zeta/v2.0/public
```

Pastikan file ini ada:

```text
/home/USERNAME/zeta/v2.0/public/index.php
/home/USERNAME/zeta/v2.0/public/.htaccess
```

Isi `.htaccess` saat ini sudah benar untuk front controller:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

## Step 4 - Buat Database di cPanel

Jika memakai database MySQL cPanel:

1. Buka `MySQL Databases`.
2. Buat database, contoh:

```text
USERNAME_zeta_v2
```

3. Buat user database, contoh:

```text
USERNAME_zeta_app
```

4. Buat password kuat.
5. Assign user ke database.
6. Berikan privileges:
   - `SELECT`
   - `INSERT`
   - `UPDATE`
   - `DELETE`
   - `CREATE`
   - `ALTER`
   - `INDEX`
   - `DROP`

Untuk production setelah schema stabil, privilege aplikasi bisa dibatasi ke:

```text
SELECT, INSERT, UPDATE, DELETE
```

Namun saat import awal via user yang sama, privilege schema seperti `CREATE` dan `DROP` dibutuhkan.

## Step 5 - Import Database

Opsi A - via phpMyAdmin:

1. Buka `phpMyAdmin`.
2. Pilih database `USERNAME_zeta_v2`.
3. Import:

```text
v2.0/database/schema.sql
```

4. Setelah selesai, import:

```text
v2.0/database/seed.sql
```

Opsi B - via Terminal cPanel:

```bash
cd /home/USERNAME/zeta/v2.0
mysql -u USERNAME_zeta_app -p USERNAME_zeta_v2 < database/schema.sql
mysql -u USERNAME_zeta_app -p USERNAME_zeta_v2 < database/seed.sql
```

Catatan: `schema.sql` berisi `CREATE DATABASE` dan `USE db_consultation_v2`. Pada cPanel, nama database biasanya diprefix username, misalnya `USERNAME_zeta_v2`. Jika import via phpMyAdmin ke database yang sudah dipilih dan provider tidak mengizinkan `CREATE DATABASE`, gunakan salah satu cara ini:

1. Hapus sementara baris berikut dari salinan file import, bukan dari source utama:

```sql
CREATE DATABASE IF NOT EXISTS db_consultation_v2
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_consultation_v2;
```

2. Atau buat dump khusus dari lokal yang tidak membawa `CREATE DATABASE`.

Jangan mengubah `database/schema.sql` utama hanya untuk kebutuhan cPanel.

## Step 6 - Buat File `.env` di Server

Buat file:

```text
/home/USERNAME/zeta/v2.0/.env
```

Contoh untuk database cPanel:

```env
APP_NAME="Zeta"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://zeta.example.com

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=USERNAME_zeta_v2
DB_USERNAME=USERNAME_zeta_app
DB_PASSWORD=ISI_PASSWORD_DATABASE_CPANEL
DB_CHARSET=utf8mb4

PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox
MIDTRANS_SERVER_KEY=ISI_SERVER_KEY_SANDBOX
MIDTRANS_CLIENT_KEY=ISI_CLIENT_KEY_SANDBOX
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_NOTIFICATION_URL=https://zeta.example.com/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=https://zeta.example.com/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=https://zeta.example.com/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=https://zeta.example.com/payments/midtrans/error
```

Jika memakai database remote Tailscale dari VPS/cPanel yang mendukung Tailscale:

```env
DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=ISI_PASSWORD_REMOTE
```

Catatan penting: shared hosting cPanel biasanya tidak menjalankan Tailscale client. Jika hosting adalah shared cPanel, gunakan database MySQL cPanel atau pastikan provider mengizinkan koneksi outbound ke IP Tailscale/remote database. Untuk Tailscale penuh, biasanya lebih cocok memakai VPS dengan cPanel/WHM.

## Step 7 - Set Permission Folder Runtime

Pastikan folder berikut writable oleh PHP:

```text
storage/
storage/sessions/
storage/logs/
database/backups/
```

Di File Manager cPanel:

```text
folder: 755
file: 644
```

Jika session tidak tersimpan, pastikan:

```text
storage/sessions/
```

dapat ditulis oleh proses PHP.

## Step 8 - Jalankan Composer di Server Jika Tersedia

Jika cPanel punya Terminal dan Composer:

```bash
cd /home/USERNAME/zeta/v2.0
composer install --no-dev --optimize-autoloader
```

Jika tidak tersedia, upload folder `vendor/` dari lokal.

Pastikan file ini ada:

```text
vendor/autoload.php
```

Tanpa file ini, Midtrans SDK tidak akan tersedia.

## Step 9 - Konfigurasi Midtrans Dashboard

Di dashboard Midtrans Sandbox, set URL callback:

```text
Payment Notification URL:
https://zeta.example.com/payments/midtrans/notification

Finish Redirect URL:
https://zeta.example.com/payments/midtrans/finish

Unfinish Redirect URL:
https://zeta.example.com/payments/midtrans/unfinish

Error Redirect URL:
https://zeta.example.com/payments/midtrans/error
```

Gunakan HTTPS domain production/staging, bukan `localhost`.

Jangan menaruh `MIDTRANS_SERVER_KEY` di frontend atau JavaScript.

## Step 10 - Test Awal Setelah Deploy

Buka:

```text
https://zeta.example.com/
https://zeta.example.com/login
https://zeta.example.com/services
https://zeta.example.com/pricing
```

Expected:

```text
HTTP 200
```

Test asset:

```text
https://zeta.example.com/assets/css/theme.css
https://zeta.example.com/assets/css/main.css
https://zeta.example.com/assets/css/dashboard.css
https://zeta.example.com/assets/js/sidebar.js
https://zeta.example.com/assets/img/zeta-icon-192.png
```

Expected:

```text
HTTP 200
```

Test file yang tidak boleh ada:

```text
https://zeta.example.com/.env
https://zeta.example.com/app/
https://zeta.example.com/config/
https://zeta.example.com/database/
```

Expected:

```text
404 atau 403
```

Jika `.env` bisa diakses dari browser, document root salah. Segera perbaiki document root ke `public/`.

## Step 11 - Test Login Seed

Setelah import seed:

```text
superadmin@example.local
password
```

Admin:

```text
admin.network@example.local
password
admin.database@example.local
password
admin.server@example.local
password
```

User approved:

```text
user.approved@example.local
password
```

Untuk production real, segera ganti password seed atau buat akun baru dan nonaktifkan akun development.

## Step 12 - Test Flow Utama

Minimal smoke test:

1. Login superadmin.
2. Buka dashboard superadmin.
3. Buka approval user.
4. Buka daftar layanan.
5. Buka daftar sub layanan.
6. Login admin.
7. Buka admin sub services.
8. Login user approved.
9. Buka service public.
10. Pilih sub service.
11. Buat consultation.
12. Buat payment Midtrans Sandbox.
13. Pastikan Snap popup muncul.
14. Simulasikan payment Sandbox.
15. Pastikan webhook mengubah payment menjadi `paid`.
16. Pastikan consultation menjadi `active`.
17. Buka chat user.
18. Kirim pesan user.
19. Login admin assignment.
20. Balas pesan.
21. Close consultation.
22. Pastikan chat read-only setelah closed.

## Step 13 - Debugging Umum

### 500 Internal Server Error

Cek:

1. PHP version.
2. `vendor/autoload.php` ada.
3. `.env` ada di root aplikasi.
4. Permission `storage/sessions`.
5. Error log cPanel.

Sementara untuk debugging staging, boleh:

```env
APP_ENV=development
APP_DEBUG=true
```

Setelah selesai:

```env
APP_ENV=production
APP_DEBUG=false
```

### Route Selain `/` Menjadi 404

Cek:

1. `public/.htaccess` terupload.
2. Apache rewrite aktif.
3. Document root benar ke `public/`.

### Login Selalu Kembali ke Login

Cek:

1. Folder `storage/sessions/` writable.
2. Cookie browser tidak diblokir.
3. Domain `APP_URL` sesuai URL asli.
4. HTTPS aktif jika production.

### Database Connection Error

Cek:

1. `DB_HOST`.
2. `DB_DATABASE` memakai prefix cPanel.
3. `DB_USERNAME` memakai prefix cPanel.
4. Password database benar.
5. User sudah di-assign ke database.

### Midtrans Snap Tidak Muncul

Cek:

1. `vendor/` sudah terinstall.
2. `MIDTRANS_CLIENT_KEY` benar.
3. `MIDTRANS_SERVER_KEY` benar.
4. `MIDTRANS_IS_PRODUCTION=false` untuk sandbox.
5. Domain memakai HTTPS.
6. Browser console tidak memblokir Snap JS.

### Webhook Tidak Masuk

Cek:

1. Notification URL di dashboard Midtrans:

```text
https://zeta.example.com/payments/midtrans/notification
```

2. Endpoint tidak dilindungi login.
3. Domain publik bisa diakses Midtrans.
4. SSL valid.
5. Tidak ada firewall/hotlink protection yang memblokir POST.

## Step 14 - Security Checklist Production

- [ ] Document root mengarah ke `public/`.
- [ ] `.env` tidak bisa diakses dari browser.
- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] Database user bukan `root`.
- [ ] Password database kuat.
- [ ] Akun seed development diganti atau dinonaktifkan.
- [ ] HTTPS aktif.
- [ ] Midtrans Server Key hanya di `.env`.
- [ ] Folder `storage/sessions` writable.
- [ ] `vendor/` tersedia.
- [ ] Backup database sudah dibuat.
- [ ] Error log cPanel dipantau setelah deploy.

## Step 15 - Backup Sebelum Update Kode

Sebelum upload versi baru:

1. Backup file aplikasi:

```bash
tar -czf zeta-v2-backup-YYYYMMDD.tar.gz /home/USERNAME/zeta/v2.0
```

2. Backup database:

```bash
mysqldump -u USERNAME_zeta_app -p USERNAME_zeta_v2 > zeta-v2-db-YYYYMMDD.sql
```

3. Upload kode baru.
4. Jalankan `composer install --no-dev --optimize-autoloader` jika dependency berubah.
5. Test login dan flow utama.

## Deployment Checklist Singkat

- [ ] PHP 8.2 aktif.
- [ ] Extension PHP wajib aktif.
- [ ] Code uploaded ke `/home/USERNAME/zeta/v2.0`.
- [ ] Document root domain ke `/home/USERNAME/zeta/v2.0/public`.
- [ ] `.htaccess` ada di `public/`.
- [ ] `.env` production dibuat.
- [ ] `APP_DEBUG=false`.
- [ ] Database dibuat.
- [ ] Schema dan seed/import data masuk.
- [ ] `vendor/autoload.php` ada.
- [ ] `storage/sessions` writable.
- [ ] Home/login/services/pricing HTTP 200.
- [ ] Login superadmin berhasil.
- [ ] Payment Sandbox dan webhook diuji.
- [ ] Chat diuji.

## Catatan

Panduan ini tidak mengharuskan perubahan kode aplikasi.

Jika deploy membutuhkan perubahan controller, model, route, atau view hanya untuk mengganti environment, berarti konfigurasi hosting atau document root belum benar.
