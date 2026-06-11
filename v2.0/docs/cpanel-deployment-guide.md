# cPanel Deployment Guide - Zeta v2.0

## Tujuan

Panduan ini menjelaskan langkah deploy aplikasi Zeta `v2.0` ke hosting cPanel dengan benar.

Aplikasi ini menggunakan:

1. PHP native MVC.
2. MySQL.
3. Composer untuk `midtrans/midtrans-php`.
4. Web root di folder `public_html/` cPanel, berisi file dari `v2.0/public/`.

## Prinsip Penting

1. Jangan expose folder aplikasi penuh ke publik.
2. Pada struktur cPanel saat ini, document root domain adalah:

```text
/home/zeta/public_html
```

3. Isi `v2.0/public/` harus berada di `/home/zeta/public_html`.
4. Folder internal aplikasi harus berada di luar web root, yaitu langsung di `/home/zeta/`.
5. File `.env` harus berada di luar web root.
6. Jangan upload `.env` berisi credential production ke repository.
7. Jangan mengubah controller, model, route, atau view hanya untuk deploy.
8. Untuk production:

```env
APP_ENV=production
APP_DEBUG=false
```

## Struktur Deploy Sesuai cPanel Saat Ini

Screenshot cPanel menunjukkan user home:

```text
/home/zeta
```

dan web root:

```text
/home/zeta/public_html
```

Gunakan struktur berikut:

```text
/home/zeta/
├── app/
├── config/
├── database/
├── docs/
├── routes/
├── storage/
│   ├── logs/
│   └── sessions/
├── vendor/
├── .env
├── composer.json
├── composer.lock
└── public_html/
    ├── index.php
    ├── .htaccess
    ├── assets/
    └── cgi-bin/
```

Mapping upload:

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

Struktur ini cocok dengan kode `public/index.php` saat ini karena file tersebut memakai:

```php
define('BASE_PATH', dirname(__DIR__));
```

Jika `index.php` berada di `/home/zeta/public_html/index.php`, maka `BASE_PATH` menjadi `/home/zeta`, sehingga aplikasi akan mencari:

```text
/home/zeta/app
/home/zeta/config
/home/zeta/routes
/home/zeta/vendor
/home/zeta/.env
```

## Catatan untuk File Lama di `public_html`

Screenshot menunjukkan file lama seperti:

```text
/home/zeta/public_html/api.php
/home/zeta/public_html/index.php
```

Sebelum mengganti `index.php`, backup file lama:

```text
index.php -> index-old-backup.php
api.php   -> api-old-backup.php
```

Untuk Zeta v2.0, file yang wajib ada di `public_html` adalah:

```text
index.php
.htaccess
assets/
```

`api.php` tidak dipakai oleh Zeta v2.0 kecuali ada sistem lama yang masih membutuhkannya.

Jangan upload folder berikut ke `public_html`:

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

Upload isi folder internal `v2.0` ke `/home/zeta`, tetapi isi `v2.0/public` ke `/home/zeta/public_html`.

```text
/home/zeta
```

Folder/file internal yang wajib ada di `/home/zeta`:

```text
app/
config/
database/
routes/
storage/
vendor/
.env
composer.json
composer.lock
```

Folder/file public yang wajib ada di `/home/zeta/public_html`:

```text
index.php
.htaccess
assets/
```

Folder/file yang tidak wajib diupload ke server:

```text
database/backups/*.sql
storage/sessions/*
storage/logs/*.log
```

`docs/` boleh diupload ke `/home/zeta/docs`, tetapi jangan upload ke `/home/zeta/public_html/docs`.

## Step 3 - Pastikan Document Root Domain

Di cPanel:

1. Buka `Domains`.
2. Pilih domain atau subdomain untuk Zeta.
3. Pastikan document root domain adalah:

```text
/home/zeta/public_html
```

Contoh:

```text
zeta.informatika.site -> /home/zeta/public_html
```

Pastikan file ini ada:

```text
/home/zeta/public_html/index.php
/home/zeta/public_html/.htaccess
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

## Opsi Database Remote via IP Tailscale Ubuntu Server

Selain database MySQL cPanel, aplikasi juga bisa memakai database MySQL pada Ubuntu Server milik teman melalui IP Tailscale.

Gunakan opsi ini jika:

1. Hosting cPanel bisa menjangkau IP Tailscale Ubuntu Server.
2. Server cPanel adalah VPS/cPanel yang bisa menjalankan Tailscale client, atau provider shared cPanel mengizinkan koneksi outbound ke jaringan Tailscale/remote private network.
3. Ubuntu Server MySQL sudah dikonfigurasi untuk menerima koneksi dari host aplikasi.

Catatan penting: shared hosting cPanel biasa umumnya tidak menjalankan Tailscale client. Jika cPanel Anda shared hosting dan tidak masuk Tailnet, `DB_HOST=100.110.81.10` kemungkinan tidak bisa diakses. Untuk kasus itu, gunakan MySQL cPanel atau deploy aplikasi pada VPS/cPanel yang ikut Tailscale.

### Target Database Tailscale

Berdasarkan strategi database project:

```env
DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=ISI_PASSWORD_REMOTE
```

Jika DNS internal Tailscale sudah stabil:

```env
DB_HOST=db.zeta.co.id
```

### Setup di Ubuntu Server Teman

SSH ke Ubuntu Server:

```bash
ssh USERNAME@100.110.81.10
```

Cek Tailscale dan MySQL:

```bash
tailscale status
sudo systemctl status mysql
```

Buat database:

```bash
sudo mysql
```

```sql
CREATE DATABASE IF NOT EXISTS db_consultation_v2
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Buat user khusus aplikasi. Ganti `CPANEL_TAILSCALE_IP` dengan IP Tailscale server cPanel/aplikasi jika cPanel Anda ikut Tailnet.

```sql
CREATE USER IF NOT EXISTS 'consultation_app'@'CPANEL_TAILSCALE_IP'
IDENTIFIED BY 'CHANGE_THIS_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
ON db_consultation_v2.*
TO 'consultation_app'@'CPANEL_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Jika masih tahap import dan butuh menjalankan `schema.sql`, gunakan user admin sementara atau tambahkan privilege schema sementara:

```sql
GRANT CREATE, ALTER, INDEX, DROP
ON db_consultation_v2.*
TO 'consultation_app'@'CPANEL_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Setelah import selesai, cabut privilege schema:

```sql
REVOKE CREATE, ALTER, INDEX, DROP
ON db_consultation_v2.*
FROM 'consultation_app'@'CPANEL_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Jangan gunakan:

```sql
'root'@'%'
'consultation_app'@'%'
```

untuk production.

### MySQL Bind Address di Ubuntu

Edit konfigurasi:

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Untuk akses via Tailnet, opsi praktis:

```text
bind-address = 0.0.0.0
```

Restart:

```bash
sudo systemctl restart mysql
```

Pastikan port `3306` tidak dibuka ke internet publik. Batasi dengan firewall.

### Firewall Ubuntu

Jika server cPanel punya IP Tailscale:

```bash
sudo ufw allow from CPANEL_TAILSCALE_IP to any port 3306 proto tcp
sudo ufw enable
sudo ufw status verbose
```

Jangan menjalankan:

```bash
sudo ufw allow 3306
```

karena itu membuka MySQL terlalu luas.

### Import Database ke Ubuntu Server

Buat backup lokal dari development machine:

```powershell
cd C:\laragon\www\alltasksof_vcc
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_cpanel_tailscale_$stamp.sql"
```

Upload backup ke Ubuntu Server:

```powershell
scp v2.0\database\backups\db_consultation_v2_cpanel_tailscale_YYYYMMDD-HHMMSS.sql USERNAME@100.110.81.10:/tmp/db_consultation_v2.sql
```

Import di Ubuntu:

```bash
sudo mysql db_consultation_v2 < /tmp/db_consultation_v2.sql
```

Verifikasi:

```bash
mysql -u consultation_app -p -h 127.0.0.1 db_consultation_v2
```

```sql
SELECT COUNT(*) AS users FROM users;
SELECT COUNT(*) AS categories FROM service_categories;
SELECT COUNT(*) AS sub_services FROM sub_services;
SELECT COUNT(*) AS assignments FROM admin_service_assignments;
```

### Test Koneksi dari cPanel

Jika cPanel menyediakan Terminal:

```bash
mysql --host=100.110.81.10 --port=3306 --user=consultation_app --password db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

Jika command ini gagal, jangan ubah `.env` production dulu. Periksa:

1. cPanel benar-benar bisa mengakses Tailnet.
2. IP Tailscale cPanel sudah benar.
3. Firewall Ubuntu mengizinkan IP Tailscale cPanel.
4. MySQL `bind-address` benar.
5. User MySQL dibuat untuk host yang benar.
6. Password user remote benar.

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
cd /home/zeta
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
/home/zeta/.env
```

Contoh untuk database cPanel:

```env
APP_NAME="Zeta"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://zeta.informatika.site

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
MIDTRANS_NOTIFICATION_URL=https://zeta.informatika.site/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=https://zeta.informatika.site/payments/midtrans/error
```

Jika memakai database remote via IP Tailscale Ubuntu Server:

```env
DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=ISI_PASSWORD_REMOTE
DB_CHARSET=utf8mb4
```

Jika memakai hostname internal:

```env
DB_HOST=db.zeta.co.id
```

Catatan penting: gunakan konfigurasi Tailscale hanya jika test koneksi dari cPanel ke MySQL Ubuntu Server sudah berhasil.

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
cd /home/zeta
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
https://zeta.informatika.site/payments/midtrans/notification

Finish Redirect URL:
https://zeta.informatika.site/payments/midtrans/finish

Unfinish Redirect URL:
https://zeta.informatika.site/payments/midtrans/unfinish

Error Redirect URL:
https://zeta.informatika.site/payments/midtrans/error
```

Gunakan HTTPS domain production/staging, bukan `localhost`.

Jangan menaruh `MIDTRANS_SERVER_KEY` di frontend atau JavaScript.

## Step 10 - Test Awal Setelah Deploy

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

Test file yang tidak boleh ada:

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

Jika `.env` bisa diakses dari browser, file internal aplikasi salah lokasi. Pastikan `.env` berada di `/home/zeta/.env`, bukan di `/home/zeta/public_html/.env`.

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

1. `/home/zeta/public_html/.htaccess` terupload.
2. Apache rewrite aktif.
3. `index.php` yang dipakai adalah salinan dari `v2.0/public/index.php`.
4. Folder internal aplikasi berada langsung di `/home/zeta`.

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
https://zeta.informatika.site/payments/midtrans/notification
```

2. Endpoint tidak dilindungi login.
3. Domain publik bisa diakses Midtrans.
4. SSL valid.
5. Tidak ada firewall/hotlink protection yang memblokir POST.

## Step 14 - Security Checklist Production

- [ ] Document root mengarah ke `/home/zeta/public_html`.
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
tar -czf zeta-v2-backup-YYYYMMDD.tar.gz /home/zeta
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
- [ ] Folder internal aplikasi uploaded ke `/home/zeta`.
- [ ] Isi `v2.0/public` uploaded ke `/home/zeta/public_html`.
- [ ] Document root domain ke `/home/zeta/public_html`.
- [ ] `.htaccess` ada di `/home/zeta/public_html`.
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
