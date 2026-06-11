# cPanel Setup Guide - zeta.informatika.site

## Tujuan

Panduan ini menjelaskan setup file kode dan database untuk domain:

```text
zeta.informatika.site
```

Panduan ini disesuaikan dengan struktur cPanel:

```text
/home/zeta
/home/zeta/public_html
```

File SQL siap import phpMyAdmin:

```text
v2.0/database/zeta_informatika_site_phpmyadmin_import.sql
```

## Catatan Nama Database

Anda menyebut nama database:

```text
zeta_informatika.site
```

Nama dengan titik `.` bisa bermasalah pada beberapa panel/konfigurasi MySQL karena titik sering dibaca sebagai pemisah `database.table` pada query SQL.

Jika cPanel memang sudah membuat database dengan nama persis:

```text
zeta_informatika.site
```

gunakan nama tersebut di `.env`.

Namun jika cPanel menolak titik atau otomatis memberi prefix, gunakan nama yang diberikan cPanel, misalnya:

```text
zeta_zeta_informatika_site
```

atau:

```text
zeta_informatika_site
```

Yang paling penting: nilai `DB_DATABASE` di `.env` harus sama persis dengan nama database yang tampil di cPanel.

## File SQL yang Harus Di-import

Gunakan file:

```text
v2.0/database/zeta_informatika_site_phpmyadmin_import.sql
```

File ini dibuat khusus untuk import via phpMyAdmin ke database yang sudah dipilih.

File ini tidak memakai:

```sql
CREATE DATABASE
USE database_name
```

File ini juga sudah dibuat ulang dengan encoding SQL yang aman untuk phpMyAdmin, bukan UTF-16.

Jadi cara importnya:

1. Buka phpMyAdmin.
2. Pilih database `zeta_informatika.site` atau nama database cPanel yang benar.
3. Klik tab `Import`.
4. Upload file `zeta_informatika_site_phpmyadmin_import.sql`.
5. Jalankan import.

Jangan import file ini dari halaman utama phpMyAdmin tanpa memilih database terlebih dahulu.

Jika phpMyAdmin menampilkan error seperti `Unexpected character` pada banyak posisi atau error dekat karakter `-` di baris pertama, biasanya file SQL terbaca sebagai UTF-16. Gunakan ulang file:

```text
v2.0/database/zeta_informatika_site_phpmyadmin_import.sql
```

yang sudah dibuat ulang dengan encoding bersih.

## Struktur File Kode di cPanel

Struktur yang benar:

```text
/home/zeta/
├── app/
├── config/
├── database/
├── docs/
├── routes/
├── storage/
├── vendor/
├── .env
├── composer.json
├── composer.lock
└── public_html/
    ├── index.php
    ├── .htaccess
    └── assets/
```

Mapping dari project lokal:

```text
v2.0/app/        -> /home/zeta/app/
v2.0/config/     -> /home/zeta/config/
v2.0/database/   -> /home/zeta/database/
v2.0/docs/       -> /home/zeta/docs/
v2.0/routes/     -> /home/zeta/routes/
v2.0/storage/    -> /home/zeta/storage/
v2.0/vendor/     -> /home/zeta/vendor/
v2.0/.env        -> /home/zeta/.env
v2.0/composer.*  -> /home/zeta/
v2.0/public/*    -> /home/zeta/public_html/
```

Jangan upload folder internal berikut ke `public_html`:

```text
app/
config/
database/
docs/
routes/
storage/
vendor/
.env
composer.json
composer.lock
```

## Backup File Lama di public_html

Screenshot cPanel menunjukkan ada file lama:

```text
/home/zeta/public_html/index.php
/home/zeta/public_html/api.php
```

Sebelum upload Zeta v2.0, backup:

```text
index.php -> index-old-backup.php
api.php   -> api-old-backup.php
```

Untuk Zeta v2.0, file yang dipakai di `public_html` adalah:

```text
index.php
.htaccess
assets/
```

`api.php` tidak dipakai oleh Zeta v2.0.

## Isi public_html

Upload isi folder:

```text
v2.0/public/
```

ke:

```text
/home/zeta/public_html/
```

Hasilnya:

```text
/home/zeta/public_html/index.php
/home/zeta/public_html/.htaccess
/home/zeta/public_html/assets/css/theme.css
/home/zeta/public_html/assets/css/main.css
/home/zeta/public_html/assets/css/dashboard.css
/home/zeta/public_html/assets/js/sidebar.js
/home/zeta/public_html/assets/img/zeta-icon-192.png
```

## Kenapa Folder Internal Harus di /home/zeta

File:

```text
/home/zeta/public_html/index.php
```

memakai:

```php
define('BASE_PATH', dirname(__DIR__));
```

Jika `index.php` ada di `/home/zeta/public_html/index.php`, maka:

```text
BASE_PATH = /home/zeta
```

Jadi aplikasi akan mencari:

```text
/home/zeta/app
/home/zeta/config
/home/zeta/routes
/home/zeta/vendor
/home/zeta/.env
```

Karena itu folder internal harus berada langsung di `/home/zeta`, bukan di `/home/zeta/v2.0`.

## File .env Production

Buat file:

```text
/home/zeta/.env
```

Contoh jika nama database benar-benar `zeta_informatika.site`:

```env
APP_NAME="Zeta"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://zeta.informatika.site

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=zeta_informatika.site
DB_USERNAME=ISI_USERNAME_DATABASE_CPANEL
DB_PASSWORD=ISI_PASSWORD_DATABASE_CPANEL
DB_CHARSET=utf8mb4

PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox
MIDTRANS_SERVER_KEY=ISI_SERVER_KEY_SANDBOX
MIDTRANS_CLIENT_KEY=ISI_CLIENT_KEY_SANDBOX
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_NOTIFICATION_URL=https://zeta.informatika.site/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/error
```

Jika cPanel memberi nama database dengan prefix, gunakan nama itu:

```env
DB_DATABASE=NAMA_DATABASE_DARI_CPANEL
DB_USERNAME=NAMA_USER_DATABASE_DARI_CPANEL
DB_PASSWORD=PASSWORD_DATABASE_CPANEL
```

## Composer / Vendor

Aplikasi membutuhkan:

```text
vendor/autoload.php
```

Jika cPanel punya Terminal:

```bash
cd /home/zeta
composer install --no-dev --optimize-autoloader
```

Jika tidak ada Composer di cPanel, upload folder `vendor/` dari lokal ke:

```text
/home/zeta/vendor/
```

## Permission Folder

Pastikan folder berikut writable:

```text
/home/zeta/storage
/home/zeta/storage/sessions
/home/zeta/storage/logs
/home/zeta/database/backups
```

Permission umum:

```text
Folder: 755
File: 644
```

Jika login selalu balik ke halaman login, kemungkinan `storage/sessions` tidak writable.

## Import Database via phpMyAdmin

1. Login cPanel.
2. Buka phpMyAdmin.
3. Pilih database:

```text
zeta_informatika.site
```

atau nama database sesuai cPanel.

4. Klik `Import`.
5. Pilih file:

```text
v2.0/database/zeta_informatika_site_phpmyadmin_import.sql
```

6. Jalankan import.
7. Setelah selesai, cek tabel berikut harus ada:

```text
users
service_categories
sub_services
admin_service_assignments
consultations
payments
messages
```

## Data Login Setelah Import

Superadmin:

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

Untuk production real, segera ganti password seed atau buat akun baru lalu nonaktifkan akun development.

## Test URL Setelah Upload

Buka:

```text
https://zeta.informatika.site/
https://zeta.informatika.site/login
https://zeta.informatika.site/services
https://zeta.informatika.site/pricing
```

Expected:

```text
HTTP 200
```

Test asset:

```text
https://zeta.informatika.site/assets/css/theme.css
https://zeta.informatika.site/assets/css/main.css
https://zeta.informatika.site/assets/css/dashboard.css
https://zeta.informatika.site/assets/js/sidebar.js
https://zeta.informatika.site/assets/img/zeta-icon-192.png
```

Expected:

```text
HTTP 200
```

Test file yang tidak boleh bisa diakses:

```text
https://zeta.informatika.site/.env
https://zeta.informatika.site/app/
https://zeta.informatika.site/config/
https://zeta.informatika.site/database/
```

Expected:

```text
404 atau 403
```

Jika `.env` bisa diakses, berarti `.env` salah tempat. Pindahkan ke:

```text
/home/zeta/.env
```

## Test Flow Utama

1. Login superadmin.
2. Buka dashboard superadmin.
3. Buka daftar layanan.
4. Buka daftar sub layanan.
5. Login admin.
6. Buka admin sub services.
7. Login user approved.
8. Buka service public.
9. Pilih sub service.
10. Buat consultation.
11. Buat payment Midtrans Sandbox.
12. Pastikan Snap popup muncul.
13. Pastikan webhook Midtrans memakai:

```text
https://zeta.informatika.site/payments/midtrans/notification
```

14. Setelah payment paid, chat harus aktif.

## Troubleshooting

### 500 Internal Server Error

Cek:

1. `.env` ada di `/home/zeta/.env`.
2. `vendor/autoload.php` ada.
3. PHP minimal 8.1.
4. Extension `pdo_mysql`, `curl`, `openssl`, `json`, `mbstring`, `fileinfo` aktif.
5. Permission `storage/sessions`.
6. Error log cPanel.

### Route Selain Home 404

Cek:

1. `/home/zeta/public_html/.htaccess` ada.
2. Apache rewrite aktif.
3. Isi `v2.0/public` benar-benar berada di `public_html`.

### Database Error

Cek:

1. `DB_DATABASE` sama persis dengan nama database di cPanel.
2. `DB_USERNAME` sama persis dengan user database cPanel.
3. User database sudah di-assign ke database.
4. Password benar.
5. Import SQL berhasil.

### Nama Database Mengandung Titik

Jika `DB_DATABASE=zeta_informatika.site` gagal, kemungkinan hosting tidak mendukung nama database dengan titik untuk koneksi aplikasi.

Solusi:

1. Buat database baru tanpa titik, misalnya:

```text
zeta_informatika_site
```

2. Import file SQL yang sama ke database baru.
3. Ubah `.env`:

```env
DB_DATABASE=zeta_informatika_site
```

atau gunakan nama prefixed dari cPanel.

## Checklist

- [ ] File lama `index.php` dan `api.php` di-backup.
- [ ] Isi `v2.0/public` masuk ke `/home/zeta/public_html`.
- [ ] Folder internal masuk ke `/home/zeta`.
- [ ] `.env` dibuat di `/home/zeta/.env`.
- [ ] `vendor/autoload.php` tersedia.
- [ ] Database dibuat di cPanel.
- [ ] File `zeta_informatika_site_phpmyadmin_import.sql` berhasil di-import.
- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL=https://zeta.informatika.site`.
- [ ] Home/login/services/pricing bisa dibuka.
- [ ] Login superadmin berhasil.
- [ ] Folder internal dan `.env` tidak bisa diakses dari browser.
