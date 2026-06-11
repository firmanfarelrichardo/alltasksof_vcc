# Export SQL to Tailscale Ubuntu Server Guide

## Tujuan

Panduan ini menjelaskan cara export database MySQL lokal Zeta `v2.0` menjadi file `.sql`, mengirim file tersebut ke Ubuntu Server milik teman melalui Tailscale, lalu mengimportnya ke MySQL remote.

Panduan ini tidak menghapus database lokal.

## Data Target

Database lokal:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=root
DB_PASSWORD=
```

Ubuntu Server Tailscale:

```text
100.110.81.10
```

Hostname internal jika tersedia:

```text
db.zeta.co.id
```

Database remote yang disarankan:

```text
db_consultation_v2
```

User aplikasi remote yang disarankan:

```text
consultation_app
```

## Prasyarat

Di komputer lokal:

1. MySQL lokal berjalan.
2. Command `mysqldump` tersedia.
3. Tailscale aktif.
4. Bisa `ping 100.110.81.10`.
5. Punya akses SSH ke Ubuntu Server teman.

Di Ubuntu Server:

1. Tailscale aktif.
2. MySQL aktif.
3. Database target sudah dibuat atau boleh dibuat saat proses.
4. User MySQL untuk import tersedia.
5. Firewall mengizinkan akses yang diperlukan melalui Tailnet.

## Step 1 - Cek Koneksi Tailscale

Dari komputer lokal:

```powershell
tailscale status
ping 100.110.81.10
```

Jika memakai hostname:

```powershell
nslookup db.zeta.co.id
ping db.zeta.co.id
```

Jika ping gagal, jangan lanjut export/import dulu. Perbaiki koneksi Tailscale.

## Step 2 - Cek Database Lokal

Dari root project:

```powershell
cd C:\laragon\www\alltasksof_vcc
```

Cek data lokal:

```powershell
mysql --host=127.0.0.1 --port=3306 --user=root --database=db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users; SELECT COUNT(*) AS categories FROM service_categories; SELECT COUNT(*) AS sub_services FROM sub_services;"
```

Jika root MySQL lokal memakai password:

```powershell
mysql --host=127.0.0.1 --port=3306 --user=root --password --database=db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

## Step 3 - Buat Folder Backup Lokal

```powershell
New-Item -ItemType Directory -Force v2.0\database\backups
```

Folder ini sudah di-ignore oleh `.gitignore`, jadi file backup tidak ikut commit.

## Step 4 - Export SQL dari MySQL Lokal

Export database lokal:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root --single-transaction --routines --triggers db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_export_$stamp.sql"
```

Jika root MySQL lokal memakai password:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root --password --single-transaction --routines --triggers db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_export_$stamp.sql"
```

Verifikasi file:

```powershell
Get-ChildItem v2.0\database\backups\db_consultation_v2_export_*.sql | Sort-Object LastWriteTime -Descending | Select-Object -First 1 Name,Length,LastWriteTime
```

Ukuran file harus lebih dari `0`.

## Step 5 - Optional: Export Tanpa CREATE DATABASE

Jika remote database sudah dibuat dan Anda hanya ingin import isi tabel, command `mysqldump db_consultation_v2` di atas sudah cukup.

Jika ingin dump membawa perintah `CREATE DATABASE`, gunakan:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root --databases db_consultation_v2 --single-transaction --routines --triggers > "v2.0\database\backups\db_consultation_v2_with_database_$stamp.sql"
```

Untuk migrasi ke server yang databasenya sudah disiapkan, rekomendasi gunakan export tanpa `--databases`.

## Step 6 - Kirim File SQL ke Ubuntu Server via SCP

Ganti `USERNAME` dengan user SSH Ubuntu Server teman.

```powershell
scp v2.0\database\backups\db_consultation_v2_export_YYYYMMDD-HHMMSS.sql USERNAME@100.110.81.10:/tmp/db_consultation_v2_export.sql
```

Jika memakai hostname:

```powershell
scp v2.0\database\backups\db_consultation_v2_export_YYYYMMDD-HHMMSS.sql USERNAME@db.zeta.co.id:/tmp/db_consultation_v2_export.sql
```

Pastikan file ada di remote:

```powershell
ssh USERNAME@100.110.81.10 "ls -lh /tmp/db_consultation_v2_export.sql"
```

## Step 7 - Login ke Ubuntu Server

```powershell
ssh USERNAME@100.110.81.10
```

Di Ubuntu, cek MySQL:

```bash
sudo systemctl status mysql
```

Jika belum aktif:

```bash
sudo systemctl start mysql
```

## Step 8 - Buat Database Remote Jika Belum Ada

Masuk MySQL sebagai admin:

```bash
sudo mysql
```

Buat database:

```sql
CREATE DATABASE IF NOT EXISTS db_consultation_v2
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Keluar:

```sql
EXIT;
```

## Step 9 - Buat User MySQL Remote Jika Belum Ada

Masuk MySQL:

```bash
sudo mysql
```

Jika aplikasi/cPanel punya IP Tailscale khusus, gunakan IP tersebut sebagai host. Contoh:

```sql
CREATE USER IF NOT EXISTS 'consultation_app'@'CPANEL_OR_APP_TAILSCALE_IP'
IDENTIFIED BY 'CHANGE_THIS_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
ON db_consultation_v2.*
TO 'consultation_app'@'CPANEL_OR_APP_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

Untuk import langsung dari dalam Ubuntu Server, Anda bisa import memakai `sudo mysql`, sehingga user aplikasi tidak perlu privilege `DROP`/`CREATE`.

Jangan gunakan untuk production:

```sql
'root'@'%'
'consultation_app'@'%'
```

## Step 10 - Import File SQL di Ubuntu Server

Import sebagai admin lokal Ubuntu:

```bash
sudo mysql db_consultation_v2 < /tmp/db_consultation_v2_export.sql
```

Jika ingin import memakai user aplikasi dan user tersebut punya privilege cukup:

```bash
mysql -u consultation_app -p db_consultation_v2 < /tmp/db_consultation_v2_export.sql
```

Jika muncul error karena tabel sudah ada, pilih salah satu:

1. Import ke database kosong baru.
2. Drop tabel lama secara sadar.
3. Export ulang dengan opsi yang sesuai.

Untuk membuat database remote benar-benar sama dengan lokal, cara paling bersih adalah import ke database kosong.

## Step 11 - Verifikasi Hasil Import

Di Ubuntu Server:

```bash
mysql -u consultation_app -p -h 127.0.0.1 db_consultation_v2
```

Jalankan:

```sql
SHOW TABLES;
SELECT COUNT(*) AS users FROM users;
SELECT COUNT(*) AS categories FROM service_categories;
SELECT COUNT(*) AS sub_services FROM sub_services;
SELECT COUNT(*) AS assignments FROM admin_service_assignments;
SELECT COUNT(*) AS consultations FROM consultations;
SELECT COUNT(*) AS payments FROM payments;
SELECT COUNT(*) AS messages FROM messages;
```

Jika export diambil dari seed awal Phase 10, minimal expected:

```text
users = 8
categories = 3
sub_services = 6
assignments = 3
consultations = 0
```

Jika export diambil dari database production/staging yang sudah dipakai, jumlah consultations/payments/messages bisa lebih dari `0`.

## Step 12 - Test Koneksi dari Mesin Aplikasi

Dari mesin yang menjalankan aplikasi:

```powershell
mysql --host=100.110.81.10 --port=3306 --user=consultation_app --password db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

Jika memakai hostname:

```powershell
mysql --host=db.zeta.co.id --port=3306 --user=consultation_app --password db_consultation_v2 --execute="SELECT COUNT(*) AS users FROM users;"
```

Jika gagal:

1. Pastikan mesin aplikasi ikut Tailscale.
2. Pastikan firewall Ubuntu membuka `3306` hanya dari IP Tailscale mesin aplikasi.
3. Pastikan MySQL `bind-address` menerima koneksi Tailnet.
4. Pastikan user MySQL dibuat untuk host yang benar.
5. Pastikan password benar.

## Step 13 - Konfigurasi `.env` Aplikasi Jika Ingin Memakai Remote

Jangan ubah `.env` sebelum test koneksi berhasil.

Jika sudah siap:

```env
APP_ENV=production
APP_DEBUG=false

DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=CHANGE_THIS_STRONG_PASSWORD
DB_CHARSET=utf8mb4
```

Jika memakai hostname:

```env
DB_HOST=db.zeta.co.id
```

Restart proses PHP/server setelah mengubah `.env`.

## Step 14 - Test Aplikasi Setelah Switch

Minimal test:

1. Buka `/`.
2. Buka `/login`.
3. Login superadmin.
4. Login admin.
5. Login user approved.
6. Buka service catalog.
7. Buat consultation.
8. Buat payment.
9. Cek record payment masuk remote.
10. Kirim chat setelah consultation active.

Query remote untuk memastikan data baru masuk:

```sql
SELECT id, status, created_at FROM consultations ORDER BY id DESC LIMIT 5;
SELECT id, order_id, internal_status, created_at FROM payments ORDER BY id DESC LIMIT 5;
SELECT id, consultation_id, sender_id, created_at FROM messages ORDER BY id DESC LIMIT 5;
```

## Step 15 - Bersihkan File Temporary di Ubuntu

Setelah import berhasil:

```bash
rm /tmp/db_consultation_v2_export.sql
```

Jangan menyimpan dump database terlalu lama di `/tmp`.

Jika perlu arsip, simpan di folder backup yang permission-nya terbatas.

## Troubleshooting

### `scp` gagal

Cek:

1. IP Tailscale benar.
2. User SSH benar.
3. SSH server aktif di Ubuntu.
4. Tailscale aktif di kedua sisi.

### `Access denied for user`

Cek:

1. Username MySQL.
2. Password MySQL.
3. Host user MySQL, misalnya `'consultation_app'@'CPANEL_OR_APP_TAILSCALE_IP'`.
4. Privilege database.

### `Can't connect to MySQL server`

Cek:

1. MySQL aktif.
2. `bind-address`.
3. Firewall UFW.
4. Tailscale status.
5. Port `3306`.

### Import error karena `CREATE DATABASE`

Jika dump memakai `--databases`, file SQL membawa:

```sql
CREATE DATABASE
USE db_consultation_v2
```

Gunakan dump tanpa `--databases` jika database remote sudah dibuat.

### Import error karena tabel sudah ada

Gunakan database kosong atau drop tabel lama secara sadar.

Jangan drop database production jika belum ada backup.

## Checklist

- [ ] Tailscale lokal aktif.
- [ ] Tailscale Ubuntu Server aktif.
- [ ] Lokal bisa ping `100.110.81.10`.
- [ ] MySQL lokal bisa dibaca.
- [ ] File `.sql` berhasil dibuat.
- [ ] File `.sql` ukuran lebih dari `0`.
- [ ] File `.sql` berhasil dikirim via `scp`.
- [ ] Database remote dibuat.
- [ ] User MySQL remote dibuat.
- [ ] Import SQL berhasil.
- [ ] Data remote terverifikasi.
- [ ] Koneksi dari mesin aplikasi ke remote berhasil.
- [ ] `.env` hanya diubah setelah koneksi remote berhasil.
- [ ] File temporary `/tmp/db_consultation_v2_export.sql` dihapus setelah import.
