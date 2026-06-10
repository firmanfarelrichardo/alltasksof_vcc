# Tailscale Database Duplication Guide

## Tujuan

Panduan ini menjelaskan cara membuat database duplikasi di MySQL Ubuntu Server melalui Tailscale untuk sistem Zeta `v2.0`.

Panduan ini tidak menghapus database lokal.

Database lokal tetap:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
```

Database Tailscale dibuat sebagai salinan remote. Perpindahan aplikasi dari lokal ke remote cukup dilakukan melalui `.env`, tanpa mengubah controller, model, route, atau view.

## Prinsip Wajib

1. Jangan menghapus database lokal.
2. Jangan gunakan user MySQL `root` untuk aplikasi.
3. Jangan commit credential database remote.
4. Jangan membuka port `3306` ke internet publik.
5. Gunakan Tailscale IP atau hostname internal.
6. Matikan debug saat memakai konfigurasi production.

## Target Remote

Berdasarkan `database-environment-strategy.md`, server database Tailscale dapat memakai salah satu host berikut:

```text
100.110.81.10
```

atau jika DNS internal sudah stabil:

```text
db.zeta.co.id
```

Gunakan IP Tailscale dulu untuk testing awal, lalu pindah ke hostname internal jika DNS sudah pasti resolve dari mesin aplikasi.

## Nama Database Remote

Opsi yang disarankan:

```sql
db_consultation_v2
```

Nama ini boleh sama dengan lokal karena berada di server berbeda.

Jika server remote sudah memiliki database production dengan nama tersebut, gunakan database staging duplikasi:

```sql
db_consultation_v2_migration
```

Untuk migrasi final, rekomendasi tetap gunakan:

```sql
db_consultation_v2
```

## Step 1 - Pastikan Tailscale Terhubung

Di mesin lokal atau web server yang akan menjalankan aplikasi:

```powershell
tailscale status
ping 100.110.81.10
```

Jika memakai DNS internal:

```powershell
nslookup db.zeta.co.id
ping db.zeta.co.id
```

Lanjutkan hanya jika server database bisa dijangkau melalui Tailnet.

## Step 2 - Backup Database Lokal

Jalankan dari root project:

```powershell
cd C:\laragon\www\alltasksof_vcc
```

Buat folder backup jika belum ada:

```powershell
New-Item -ItemType Directory -Force v2.0\database\backups
```

Backup lokal:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_tailscale_$stamp.sql"
```

Jika root MySQL lokal memakai password:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root --password db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_tailscale_$stamp.sql"
```

Verifikasi file backup:

```powershell
Get-ChildItem v2.0\database\backups\db_consultation_v2_tailscale_*.sql | Sort-Object LastWriteTime -Descending | Select-Object -First 1 Name,Length,LastWriteTime
```

Ukuran file tidak boleh `0`.

## Step 3 - Siapkan MySQL Ubuntu Server

SSH ke Ubuntu Server:

```bash
ssh USERNAME@100.110.81.10
```

Cek MySQL:

```bash
sudo systemctl status mysql
```

Jika belum aktif:

```bash
sudo systemctl enable mysql
sudo systemctl start mysql
```

## Step 4 - Konfigurasi MySQL Agar Bisa Diakses dari Tailnet

Edit konfigurasi MySQL:

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Cari:

```text
bind-address = 127.0.0.1
```

Untuk akses via Tailscale, opsi praktis:

```text
bind-address = 0.0.0.0
```

Restart MySQL:

```bash
sudo systemctl restart mysql
```

Catatan keamanan: `bind-address = 0.0.0.0` hanya aman jika firewall membatasi port `3306` ke IP Tailscale yang diizinkan.

## Step 5 - Buat Database Remote

Masuk MySQL di Ubuntu Server:

```bash
sudo mysql
```

Buat database remote:

```sql
CREATE DATABASE IF NOT EXISTS db_consultation_v2
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Jika memakai database staging duplikasi:

```sql
CREATE DATABASE IF NOT EXISTS db_consultation_v2_migration
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

## Step 6 - Buat Dedicated MySQL User

Tentukan IP Tailscale mesin aplikasi.

Di mesin aplikasi:

```powershell
tailscale ip -4
```

Misalnya hasilnya:

```text
100.x.y.z
```

Di MySQL Ubuntu Server, buat user khusus aplikasi. Ganti `WEB_SERVER_TAILSCALE_IP` dengan IP tadi, dan ganti password dengan password kuat.

```sql
CREATE USER IF NOT EXISTS 'consultation_app'@'WEB_SERVER_TAILSCALE_IP'
IDENTIFIED BY 'CHANGE_THIS_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
ON db_consultation_v2.*
TO 'consultation_app'@'WEB_SERVER_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Jika memakai database staging:

```sql
GRANT SELECT, INSERT, UPDATE, DELETE
ON db_consultation_v2_migration.*
TO 'consultation_app'@'WEB_SERVER_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Jangan gunakan:

```sql
'root'@'%'
```

Jangan gunakan untuk production:

```sql
'consultation_app'@'%'
```

## Step 7 - Batasi Firewall Ubuntu

Izinkan port MySQL hanya dari IP Tailscale mesin aplikasi:

```bash
sudo ufw allow from WEB_SERVER_TAILSCALE_IP to any port 3306 proto tcp
sudo ufw enable
sudo ufw status verbose
```

Jangan menjalankan:

```bash
sudo ufw allow 3306
```

karena itu membuka MySQL terlalu luas.

## Step 8 - Kirim Backup ke Ubuntu Server

Dari mesin lokal:

```powershell
scp v2.0\database\backups\db_consultation_v2_tailscale_YYYYMMDD-HHMMSS.sql USERNAME@100.110.81.10:/tmp/db_consultation_v2_tailscale.sql
```

Ganti nama file sesuai backup yang dibuat.

## Step 9 - Import Backup ke Database Remote

Di Ubuntu Server:

```bash
mysql -u consultation_app -p -h 127.0.0.1 db_consultation_v2 < /tmp/db_consultation_v2_tailscale.sql
```

Jika import sebagai root administratif di server:

```bash
sudo mysql db_consultation_v2 < /tmp/db_consultation_v2_tailscale.sql
```

Jika memakai database staging:

```bash
sudo mysql db_consultation_v2_migration < /tmp/db_consultation_v2_tailscale.sql
```

## Step 10 - Verifikasi Data Remote

Di Ubuntu Server:

```bash
mysql -u consultation_app -p -h 127.0.0.1 db_consultation_v2
```

Jalankan:

```sql
SELECT COUNT(*) AS users FROM users;
SELECT COUNT(*) AS categories FROM service_categories;
SELECT COUNT(*) AS sub_services FROM sub_services;
SELECT COUNT(*) AS assignments FROM admin_service_assignments;
SELECT COUNT(*) AS consultations FROM consultations;
SHOW TABLES;
```

Jika database baru diambil dari seed akhir Phase 10, angka minimal yang diharapkan:

```text
users       = 8
categories  = 3
sub_services = 6
assignments = 3
consultations = 0
```

Angka `consultations` bisa lebih dari `0` jika backup diambil setelah data transaksi/konsultasi dibuat.

## Step 11 - Test Koneksi dari Mesin Aplikasi

Dari mesin aplikasi:

```powershell
mysql --host=100.110.81.10 --port=3306 --user=consultation_app --password db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

Jika memakai DNS:

```powershell
mysql --host=db.zeta.co.id --port=3306 --user=consultation_app --password db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

Jika command ini gagal, jangan ubah `.env` aplikasi dulu. Periksa:

1. Tailscale status.
2. IP Tailscale mesin aplikasi.
3. UFW rule.
4. MySQL `bind-address`.
5. User MySQL host restriction.
6. Password user remote.

## Step 12 - Konfigurasi `.env` untuk Production

Jangan langsung overwrite `.env` lokal. Buat salinan manual terlebih dahulu:

```powershell
Copy-Item v2.0\.env v2.0\.env.local.backup
```

Contoh konfigurasi production:

```env
APP_NAME="Zeta"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=CHANGE_THIS_STRONG_PASSWORD
DB_CHARSET=utf8mb4
```

Jika DNS internal digunakan:

```env
DB_HOST=db.zeta.co.id
```

Untuk staging duplikasi:

```env
DB_DATABASE=db_consultation_v2_migration
```

Midtrans tetap gunakan konfigurasi sandbox sampai production payment benar-benar disiapkan:

```env
PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox
MIDTRANS_IS_PRODUCTION=false
```

## Step 13 - Test Aplikasi Setelah `.env` Mengarah ke Remote

Jalankan server lokal:

```powershell
cd C:\laragon\www\alltasksof_vcc\v2.0
php -S localhost:8000 -t public
```

Test halaman:

```powershell
Invoke-WebRequest -UseBasicParsing http://localhost:8000/ | Select-Object -ExpandProperty StatusCode
Invoke-WebRequest -UseBasicParsing http://localhost:8000/login | Select-Object -ExpandProperty StatusCode
Invoke-WebRequest -UseBasicParsing http://localhost:8000/_dev/db-check | Select-Object -ExpandProperty Content
```

Catatan: `/_dev/db-check` hanya tersedia jika `APP_ENV=development`. Jika `APP_ENV=production`, route ini harus `404`.

Untuk production test, gunakan login aplikasi:

```text
superadmin@example.local
password
```

Lakukan smoke test:

1. Login superadmin.
2. Buka dashboard superadmin.
3. Buka service list.
4. Buka sub service list.
5. Login admin.
6. Buka admin sub services.
7. Login user approved.
8. Buka services dan pilih sub layanan.
9. Buat consultation.
10. Buat payment Midtrans sandbox.
11. Pastikan record payment masuk remote.
12. Buka chat setelah payment `paid`.

## Step 14 - Validasi Record Masuk Remote

Di mesin aplikasi atau Ubuntu Server:

```bash
mysql -h 100.110.81.10 -u consultation_app -p db_consultation_v2
```

Cek:

```sql
SELECT id, email, role, status FROM users ORDER BY id;
SELECT id, status, created_at FROM consultations ORDER BY id DESC LIMIT 5;
SELECT id, order_id, internal_status, created_at FROM payments ORDER BY id DESC LIMIT 5;
SELECT id, consultation_id, sender_id, created_at FROM messages ORDER BY id DESC LIMIT 5;
```

Jika data baru muncul di remote, aplikasi sudah memakai database Tailscale.

## Step 15 - Production Debug Rule

Saat memakai database Tailscale untuk mode production:

```env
APP_ENV=production
APP_DEBUG=false
```

Jangan menjalankan production dengan:

```env
APP_DEBUG=true
```

Alasannya:

1. Stack trace bisa membocorkan path server.
2. Error database bisa membocorkan host atau struktur query.
3. Konfigurasi sensitive lebih mudah terekspos.

## Step 16 - Rollback ke Database Lokal

Jika remote bermasalah, kembalikan `.env` ke lokal:

```env
APP_ENV=development
APP_DEBUG=true
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=root
DB_PASSWORD=
```

Restart PHP server setelah mengubah `.env`.

Database lokal tidak perlu dihapus atau dibuat ulang.

## Checklist Phase 11

Gunakan checklist ini saat benar-benar menjalankan migrasi:

- [ ] Tailscale aktif di mesin aplikasi.
- [ ] Tailscale aktif di Ubuntu Server.
- [ ] IP `100.110.81.10` bisa di-ping dari mesin aplikasi.
- [ ] MySQL Ubuntu Server aktif.
- [ ] `bind-address` MySQL menerima koneksi Tailnet.
- [ ] Firewall hanya membuka `3306` dari IP Tailscale mesin aplikasi.
- [ ] Backup lokal dibuat.
- [ ] Database remote dibuat.
- [ ] Dedicated MySQL user dibuat.
- [ ] Backup berhasil di-import ke remote.
- [ ] Query remote dari mesin aplikasi berhasil.
- [ ] `.env` production diarahkan ke remote.
- [ ] `APP_DEBUG=false`.
- [ ] Login superadmin berhasil.
- [ ] CRUD layanan berhasil.
- [ ] Consultation pipeline berhasil.
- [ ] Payment record berhasil tersimpan.
- [ ] Chat berhasil tersimpan.
- [ ] Tidak ada perubahan controller, model, route, atau view untuk mengganti database.

## Catatan Status Saat Dokumen Ini Dibuat

Dokumen ini adalah panduan eksekusi.

Belum ada perubahan `.env` yang dilakukan oleh panduan ini.

Belum ada koneksi remote yang dijalankan oleh panduan ini.

Database lokal tetap digunakan sampai operator menjalankan langkah migrasi dan mengubah `.env` secara sadar.
