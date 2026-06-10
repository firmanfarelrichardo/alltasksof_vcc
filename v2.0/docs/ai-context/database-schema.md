# Database Schema

## Database

DBMS yang digunakan adalah MySQL.

Nama database dapat disesuaikan, contoh:

```sql
db_consultation_v2
```

## Prinsip Desain Database

1. Database dibuat sederhana terlebih dahulu.
2. Setiap tabel memiliki primary key.
3. Gunakan relasi antar tabel untuk menjaga konsistensi data.
4. Password pengguna wajib disimpan dalam bentuk hash.
5. Status akun pengguna digunakan untuk menentukan apakah pengguna dapat login.
6. Transaksi harus terhubung dengan konsultasi dan sublayanan.

---

# Tabel: users

Menyimpan data semua akun, baik pengguna, admin, maupun superadmin.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| name | VARCHAR(100) | Nama pengguna |
| email | VARCHAR(100) | Email login |
| password | VARCHAR(255) | Password yang sudah di-hash |
| role | ENUM('user','admin','superadmin') | Role akun |
| status | ENUM('pending','approved','rejected','inactive') | Status akun |
| created_at | DATETIME | Waktu akun dibuat |
| updated_at | DATETIME | Waktu akun diperbarui |

## Catatan

1. Pengguna baru memiliki status `pending`.
2. Pengguna hanya dapat login jika status `approved`.
3. Admin dan superadmin juga disimpan dalam tabel ini.

---

# Tabel: service_categories

Menyimpan kategori layanan utama.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| name | VARCHAR(100) | Nama kategori layanan |
| description | TEXT | Deskripsi kategori layanan |
| is_active | TINYINT(1) | Status aktif |
| created_at | DATETIME | Waktu dibuat |
| updated_at | DATETIME | Waktu diperbarui |

## Data Awal

1. Network Architecture
2. Database Architecture
3. Web Server & Virtualization

---

# Tabel: sub_services

Menyimpan sublayanan konsultasi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| service_category_id | INT | Foreign key ke `service_categories.id` |
| name | VARCHAR(150) | Nama sublayanan |
| description | TEXT | Deskripsi sublayanan |
| price | DECIMAL(12,2) | Harga konsultasi |
| is_active | TINYINT(1) | Status aktif |
| created_at | DATETIME | Waktu dibuat |
| updated_at | DATETIME | Waktu diperbarui |

---

# Tabel: admin_service_assignments

Menyimpan relasi admin dengan layanan yang dikelolanya.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| admin_id | INT | Foreign key ke `users.id` |
| service_category_id | INT | Foreign key ke `service_categories.id` |
| created_at | DATETIME | Waktu dibuat |

## Catatan

Tabel ini membuat sistem fleksibel jika ada admin baru atau layanan baru. Satu admin dapat mengelola lebih dari satu layanan.

---

# Tabel: consultations

Menyimpan data konsultasi pengguna.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| user_id | INT | Foreign key ke `users.id` |
| sub_service_id | INT | Foreign key ke `sub_services.id` |
| payment_id | INT NULL | Foreign key ke `payments.id` |
| status | ENUM('waiting_payment','active','closed','cancelled') | Status konsultasi |
| created_at | DATETIME | Waktu dibuat |
| updated_at | DATETIME | Waktu diperbarui |

## Catatan

Konsultasi dibuat setelah pengguna memilih sublayanan. Konsultasi menjadi aktif setelah pembayaran valid.

---

# Tabel: payments

Menyimpan data pembayaran konsultasi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| user_id | INT | Foreign key ke `users.id` |
| sub_service_id | INT | Foreign key ke `sub_services.id` |
| amount | DECIMAL(12,2) | Nominal pembayaran |
| payment_status | ENUM('pending','paid','failed','expired') | Status pembayaran |
| payment_method | VARCHAR(50) | Metode pembayaran |
| payment_gateway_reference | VARCHAR(150) NULL | Referensi dari payment gateway |
| paid_at | DATETIME NULL | Waktu pembayaran berhasil |
| created_at | DATETIME | Waktu dibuat |
| updated_at | DATETIME | Waktu diperbarui |

## Catatan

`amount` harus menyimpan harga saat transaksi dibuat, bukan mengambil harga terbaru dari sublayanan setelah transaksi terjadi.

---

# Tabel: messages

Menyimpan pesan dalam percakapan konsultasi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT AUTO_INCREMENT | Primary key |
| consultation_id | INT | Foreign key ke `consultations.id` |
| sender_id | INT | Foreign key ke `users.id` |
| message | TEXT | Isi pesan |
| created_at | DATETIME | Waktu pesan dikirim |

## Catatan

Pesan hanya dapat dikirim oleh pengguna terkait dan admin yang berwenang atas layanan konsultasi tersebut.

---

# Relasi Utama

```text
users
  ├── consultations
  ├── payments
  └── messages

service_categories
  ├── sub_services
  └── admin_service_assignments

sub_services
  ├── consultations
  └── payments

consultations
  └── messages
```
