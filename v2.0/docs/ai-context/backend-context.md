\
# Backend Context

## Purpose

Dokumen ini menjelaskan konteks backend untuk sistem konsultasi teknologi versi `v2.0`.

Backend menggunakan:

- PHP native.
- Arsitektur MVC.
- MySQL.
- Session-based authentication.
- Role-based access control.
- Struktur route terpusat.
- Kode ringkas, mudah dirawat, dan mudah dikembangkan.

AI agent wajib membaca dokumen ini sebelum membuat atau mengubah backend.

---

# Main Backend Responsibilities

Backend bertanggung jawab untuk:

1. Menangani registrasi pengguna.
2. Menangani login dan logout.
3. Memastikan pengguna hanya dapat login setelah disetujui superadmin.
4. Mengelola role `user`, `admin`, dan `superadmin`.
5. Mengelola kategori layanan.
6. Mengelola sublayanan.
7. Menyimpan harga konsultasi.
8. Membuat transaksi pembayaran.
9. Membuat sesi konsultasi setelah pembayaran valid.
10. Menyimpan pesan chat konsultasi.
11. Menampilkan riwayat konsultasi.
12. Menampilkan data keuangan sesuai hak akses.
13. Menetapkan admin pada layanan tertentu.
14. Menjaga keamanan akses data antarrole.

---

# Architecture Pattern

Backend menggunakan pola MVC.

```text
Request
  |
  v
Router
  |
  v
Middleware
  |
  v
Controller
  |
  v
Service (jika diperlukan)
  |
  v
Model
  |
  v
MySQL Database
  |
  v
Response / View
```

## MVC Responsibilities

### Model

Model bertanggung jawab untuk:

1. Query database.
2. Insert, update, delete, dan select data.
3. Menjaga akses database tetap terpusat.
4. Menggunakan prepared statement.
5. Mengembalikan data sederhana ke controller atau service.

### View

View bertanggung jawab untuk:

1. Menampilkan halaman HTML.
2. Menampilkan data yang sudah disiapkan controller.
3. Tidak melakukan query database langsung.
4. Tidak menyimpan logika bisnis kompleks.
5. Menghindari pemrosesan data berat.

### Controller

Controller bertanggung jawab untuk:

1. Menerima request.
2. Memvalidasi input dasar.
3. Memanggil model atau service.
4. Menentukan response.
5. Menentukan view.
6. Menjaga controller tetap tipis.

### Service

Service digunakan jika proses memiliki logika bisnis yang lebih dari satu langkah.

Contoh:

1. Membuat transaksi pembayaran.
2. Mengaktifkan konsultasi setelah pembayaran valid.
3. Menentukan hak akses admin terhadap layanan.
4. Mengubah status pengguna hasil approval.

Jangan membuat service jika logikanya sangat sederhana.

---

# Backend Folder Structure

Struktur backend yang direkomendasikan:

```text
backend/
|
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PublicServiceController.php
│   │   ├── UserDashboardController.php
│   │   ├── ConsultationController.php
│   │   ├── ChatController.php
│   │   ├── PaymentController.php
│   │   ├── AdminDashboardController.php
│   │   ├── AdminSubServiceController.php
│   │   ├── AdminFinanceController.php
│   │   ├── SuperadminDashboardController.php
│   │   ├── SuperadminUserApprovalController.php
│   │   ├── SuperadminAdminManagementController.php
│   │   ├── SuperadminServiceController.php
│   │   └── SuperadminFinanceController.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── ServiceCategory.php
│   │   ├── SubService.php
│   │   ├── AdminServiceAssignment.php
│   │   ├── Consultation.php
│   │   ├── Payment.php
│   │   └── Message.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── ConsultationService.php
│   │   ├── PaymentService.php
│   │   ├── ApprovalService.php
│   │   └── AdminAssignmentService.php
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── ApprovedUserMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   └── SuperadminMiddleware.php
│   │
│   ├── Core/
│   │   ├── App.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Session.php
│   │   ├── Validator.php
│   │   └── Response.php
│   │
│   ├── Helpers/
│   │   ├── auth_helper.php
│   │   ├── url_helper.php
│   │   └── view_helper.php
│   │
│   └── Views/
│       ├── public/
│       ├── auth/
│       ├── user/
│       ├── admin/
│       ├── superadmin/
│       └── layouts/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── payment.php
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── user.php
│   ├── admin.php
│   ├── superadmin.php
│   └── payment.php
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│
├── storage/
│   └── logs/
│
├── .env.example
└── README.md
```

---

# Route Management Rule

Route wajib dipisahkan berdasarkan area akses:

```text
routes/
├── web.php
├── auth.php
├── user.php
├── admin.php
├── superadmin.php
└── payment.php
```

Tujuannya:

1. Route mudah dicari.
2. Hak akses mudah diperiksa.
3. File route tidak terlalu panjang.
4. Perubahan fitur tidak mengganggu modul lain.
5. AI agent dapat membaca route secara bertahap.

---

# Access Roles

Sistem menggunakan tiga role utama:

```text
user
admin
superadmin
```

## Access Rule

### Public

Tidak memerlukan login.

### Guest

Hanya untuk pengguna yang belum login.

### User

Memerlukan login dan status akun `approved`.

### Admin

Memerlukan login dengan role `admin`.

### Superadmin

Memerlukan login dengan role `superadmin`.

---

# Authentication Rule

## Registration

1. Pengguna mengisi formulir registrasi.
2. Password disimpan menggunakan `password_hash()`.
3. Role awal pengguna adalah `user`.
4. Status awal pengguna adalah `pending`.
5. Pengguna belum boleh login.

## Approval

1. Superadmin membuka daftar pengguna pending.
2. Superadmin memilih approve atau reject.
3. Status berubah menjadi `approved` atau `rejected`.
4. Hanya pengguna berstatus `approved` yang dapat login.

## Login

1. Cari akun berdasarkan email.
2. Periksa status akun.
3. Periksa password menggunakan `password_verify()`.
4. Simpan informasi minimal ke session.
5. Redirect sesuai role.

## Session Data

Simpan data minimal:

```php
$_SESSION['user_id'];
$_SESSION['user_name'];
$_SESSION['role'];
$_SESSION['status'];
```

Jangan menyimpan password atau data sensitif lain di session.

---

# Consultation Rule

## Flow

```text
Lihat layanan
  |
Pilih sublayanan
  |
Buat transaksi pembayaran
  |
Pembayaran valid
  |
Buat / aktifkan sesi konsultasi
  |
Masuk chat
```

## Important Rule

1. Konsultasi tidak aktif sebelum pembayaran valid.
2. User hanya boleh melihat konsultasi miliknya.
3. Admin hanya boleh melihat konsultasi berdasarkan layanan yang ditugaskan.
4. Superadmin boleh melihat seluruh konsultasi.
5. Semua pesan disimpan ke database.

---

# Payment Rule

Payment gateway akan dipilih belakangan.

Untuk tahap pengembangan lokal:

1. Buat struktur transaksi.
2. Simpan status pembayaran.
3. Gunakan simulasi status pembayaran jika diperlukan.
4. Jangan mengunci implementasi ke satu gateway sebelum diputuskan.
5. Pisahkan konfigurasi gateway pada `config/payment.php`.

Status pembayaran:

```text
pending
paid
failed
expired
```

---

# Database Rule

Database awal menggunakan MySQL lokal.

Setelah sistem selesai dibuat dan diuji:

1. Ubah konfigurasi database.
2. Gunakan MySQL Ubuntu Server melalui jaringan Tailscale.
3. Jangan mengubah logika aplikasi.
4. Jangan menulis host, username, dan password langsung di controller atau model.
5. Semua konfigurasi database wajib terpusat.

Baca dokumen:

```text
database-environment-strategy.md
```

---

# Error Handling

Gunakan pesan error yang aman.

## Development

Boleh menampilkan error detail untuk debugging lokal.

## Production / Tailscale Server

Jangan tampilkan detail koneksi database atau stack trace kepada pengguna.

Gunakan format:

```text
Terjadi kesalahan pada sistem. Silakan coba kembali.
```

Detail error dicatat pada log server.

---

# Security Minimum

1. Gunakan prepared statement.
2. Gunakan `password_hash()` dan `password_verify()`.
3. Gunakan session regeneration setelah login.
4. Validasi role untuk setiap halaman dashboard.
5. Escape output HTML menggunakan `htmlspecialchars()`.
6. Gunakan CSRF token untuk form penting.
7. Jangan commit file `.env`.
8. Jangan menaruh password database pada repository.
9. Jangan mempercayai input dari frontend.
10. Batasi akses database remote pada host yang diperlukan.

---

# Simplicity Rule

Backend versi awal tidak perlu:

1. Microservices.
2. Event bus.
3. Queue.
4. Websocket kompleks.
5. Redis.
6. Container orchestration.
7. Repository pattern berlapis jika belum dibutuhkan.
8. Abstraksi berlebihan.
9. Dependency eksternal yang tidak diperlukan.

Fokus utama:

```text
Registrasi
Approval
Login
Layanan
Pembayaran
Konsultasi
Chat
Riwayat
Keuangan
Role admin
```
