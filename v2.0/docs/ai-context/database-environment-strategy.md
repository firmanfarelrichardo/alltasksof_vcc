\
# Database Environment Strategy

## Purpose

Dokumen ini menjelaskan strategi konfigurasi database sistem konsultasi teknologi versi `v2.0`.

Database menggunakan MySQL.

Pengembangan dilakukan dalam dua tahap:

1. Tahap awal menggunakan MySQL lokal.
2. Tahap akhir menggunakan MySQL Ubuntu Server melalui jaringan Tailscale.

Database remote Tailscale digunakan belakangan setelah seluruh sistem selesai dibuat dan diuji secara lokal.

---

# Environment Overview

## Environment 1 — Local Development

Digunakan selama proses:

1. Membuat fitur.
2. Menguji login.
3. Menguji approval pengguna.
4. Menguji CRUD layanan.
5. Menguji transaksi.
6. Menguji chat konsultasi.
7. Menguji role admin.
8. Menguji dashboard.

Konfigurasi umum:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi MySQL lokal.

## Environment 2 — Tailscale Database Server

Digunakan setelah aplikasi lokal stabil.

Database berjalan pada Ubuntu Server milik anggota tim dan terhubung melalui Tailscale.

Berdasarkan dokumentasi `v1.0`, DNS internal untuk database mengarah ke:

```text
db.zeta.co.id
```

dan private Tailscale IP database server tercatat sebagai:

```text
100.110.81.10
```

Pada saat migrasi, konfigurasi aplikasi dapat menggunakan salah satu:

```text
DB_HOST=db.zeta.co.id
```

atau:

```text
DB_HOST=100.110.81.10
```

Gunakan hostname internal jika DNS internal sudah stabil dan seluruh client terhubung ke Tailnet.

---

# Important Security Note

Jangan menulis credential asli database ke repository.

Gunakan:

```text
.env
```

Jangan commit:

```text
.env
```

Commit hanya:

```text
.env.example
```

---

# Suggested `.env.example`

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=root
DB_PASSWORD=

PAYMENT_MODE=simulation
PAYMENT_GATEWAY=
PAYMENT_GATEWAY_KEY=
PAYMENT_GATEWAY_SECRET=
```

---

# Database Config Rule

Buat satu file konfigurasi:

```text
config/database.php
```

Contoh:

```php
<?php

return [
    'driver' => getenv('DB_CONNECTION') ?: 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'db_consultation_v2',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
];
```

Buat koneksi database hanya pada:

```text
app/Core/Database.php
```

Jangan membuat koneksi baru di setiap model.

---

# PDO Connection Recommendation

Gunakan PDO.

Contoh:

```php
<?php

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        self::$connection = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return self::$connection;
    }
}
```

---

# Local Development Setup

## 1. Install MySQL Local

Gunakan MySQL lokal melalui:

1. XAMPP.
2. Laragon.
3. MySQL Community Server.
4. Docker MySQL jika memang dibutuhkan.

Untuk versi awal, gunakan opsi paling sederhana yang sudah tersedia pada komputer developer.

## 2. Create Database

```sql
CREATE DATABASE db_consultation_v2
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

## 3. Import Schema

Simpan file SQL pada:

```text
backend/database/schema.sql
```

Jalankan:

```bash
mysql -u root -p db_consultation_v2 < backend/database/schema.sql
```

## 4. Seed Minimal Data

Simpan seed pada:

```text
backend/database/seed.sql
```

Isi minimal:

1. Satu superadmin.
2. Tiga kategori layanan.
3. Sublayanan contoh.
4. Penugasan admin contoh jika diperlukan.

---

# Remote Tailscale Migration Plan

Gunakan langkah berikut setelah sistem lokal stabil.

## Step 1 — Backup Local Database

```bash
mysqldump -u root -p db_consultation_v2 > db_consultation_v2_backup.sql
```

## Step 2 — Prepare Ubuntu MySQL Server

Pastikan MySQL berjalan:

```bash
sudo systemctl status mysql
```

## Step 3 — Create Remote Database

```sql
CREATE DATABASE db_consultation_v2
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

## Step 4 — Create Dedicated Application User

Jangan gunakan root remote.

Contoh:

```sql
CREATE USER 'consultation_app'@'WEB_SERVER_TAILSCALE_IP'
IDENTIFIED BY 'CHANGE_THIS_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
ON db_consultation_v2.*
TO 'consultation_app'@'WEB_SERVER_TAILSCALE_IP';

FLUSH PRIVILEGES;
```

## Step 5 — Import Backup

```bash
mysql -u consultation_app -p -h 100.110.81.10 db_consultation_v2 < db_consultation_v2_backup.sql
```

## Step 6 — Update `.env`

```env
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=mysql
DB_HOST=100.110.81.10
DB_PORT=3306
DB_DATABASE=db_consultation_v2
DB_USERNAME=consultation_app
DB_PASSWORD=CHANGE_THIS_STRONG_PASSWORD
```

Jika DNS internal sudah dipastikan bekerja:

```env
DB_HOST=db.zeta.co.id
```

## Step 7 — Test Connection

Buat script koneksi sementara atau gunakan fitur login aplikasi.

Pastikan:

1. Web server dapat mengakses database.
2. Login bekerja.
3. CRUD bekerja.
4. Chat menyimpan pesan.
5. Data transaksi tersimpan.
6. Dashboard membaca data.

## Step 8 — Remove Temporary Debug Script

Hapus file test koneksi setelah selesai.

---

# Ubuntu MySQL Remote Access Note

Dokumentasi `v1.0` menggunakan:

```text
bind-address = 0.0.0.0
```

Konfigurasi tersebut memungkinkan MySQL menerima koneksi dari luar.

Untuk versi `v2.0`, lakukan dengan hati-hati.

File konfigurasi umum:

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Konfigurasi:

```text
bind-address = 0.0.0.0
```

Restart:

```bash
sudo systemctl restart mysql
```

Namun, akses tetap harus dibatasi menggunakan:

1. User MySQL khusus aplikasi.
2. Host Web Server Tailscale tertentu.
3. Firewall.
4. Tailnet private access.
5. Password kuat.

---

# Firewall Recommendation

Izinkan akses port MySQL hanya dari Web Server Tailscale IP.

Contoh:

```bash
sudo ufw allow from WEB_SERVER_TAILSCALE_IP to any port 3306 proto tcp
sudo ufw enable
sudo ufw status
```

Jangan membuka port 3306 ke seluruh internet.

---

# Credentials Rule

Jangan menggunakan:

```sql
'root'@'%'
```

Jangan menggunakan:

```sql
'consultation_app'@'%'
```

untuk deployment akhir kecuali hanya untuk pengujian sementara dan segera diganti.

Gunakan host terbatas:

```sql
'consultation_app'@'WEB_SERVER_TAILSCALE_IP'
```

---

# Database Environment Switching Rule

Perpindahan database lokal ke remote harus cukup dilakukan dengan mengubah `.env`.

Tidak boleh mengubah:

1. Controller.
2. Model.
3. View.
4. Route.
5. Business logic.

Jika perpindahan database memerlukan perubahan banyak file, struktur backend belum benar.

---

# Database Testing Checklist

## Local Checklist

- [ ] MySQL lokal berjalan.
- [ ] Database `db_consultation_v2` dibuat.
- [ ] Schema berhasil di-import.
- [ ] Superadmin seed tersedia.
- [ ] Registrasi berhasil.
- [ ] Approval berhasil.
- [ ] Login berhasil.
- [ ] CRUD layanan berhasil.
- [ ] Transaksi tercatat.
- [ ] Chat tersimpan.
- [ ] Role access berjalan.

## Tailscale Checklist

- [ ] Ubuntu Server online.
- [ ] Tailscale online.
- [ ] IP database Tailscale dapat di-ping.
- [ ] DNS internal `db.zeta.co.id` dapat di-resolve jika digunakan.
- [ ] Port `3306` hanya dapat diakses dari host yang diizinkan.
- [ ] Dedicated MySQL user dibuat.
- [ ] Database lokal sudah dibackup.
- [ ] Backup berhasil di-import.
- [ ] `.env` sudah diperbarui.
- [ ] Login aplikasi berhasil.
- [ ] CRUD berhasil.
- [ ] Chat berhasil.
- [ ] Payment record berhasil.
- [ ] Debug mode dimatikan.

---

# Final Rule

Gunakan database lokal terlebih dahulu.

Pindahkan ke MySQL Ubuntu Server melalui Tailscale hanya setelah:

1. Seluruh fitur utama selesai dibuat.
2. Pengujian lokal selesai.
3. Struktur schema dianggap stabil.
4. Backup tersedia.
5. Credential remote sudah disiapkan secara aman.
