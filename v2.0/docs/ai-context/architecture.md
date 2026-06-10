# System Architecture

## Pola Arsitektur

Backend menggunakan arsitektur MVC dengan PHP native.

MVC terdiri dari:

1. Model
2. View
3. Controller

## Gambaran Arsitektur

```text
Browser Pengguna
      |
      v
Frontend / View PHP
      |
      v
Controller PHP
      |
      v
Model PHP
      |
      v
MySQL Database
```

## Pembagian Utama Folder

```text
v2.0/
|
├── ai-context/
├── frontend/
├── backend/
└── docs/
```

## Backend MVC

Struktur backend disarankan sebagai berikut:

```text
backend/
|
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── core/
│   └── helpers/
│
├── public/
│   ├── index.php
│   └── assets/
│
├── config/
│   └── database.php
│
└── routes/
    └── web.php
```

## Controller

Controller bertugas menerima request dari pengguna, memanggil model, menentukan view, dan mengatur alur proses.

Contoh controller:

1. `AuthController`
2. `UserController`
3. `AdminController`
4. `SuperadminController`
5. `ServiceController`
6. `ConsultationController`
7. `PaymentController`
8. `ChatController`
9. `FinanceController`

## Model

Model bertugas mengakses database dan merepresentasikan data utama sistem.

Contoh model:

1. `User`
2. `Role`
3. `ServiceCategory`
4. `SubService`
5. `Consultation`
6. `Message`
7. `Payment`
8. `AdminServiceAssignment`

## View

View bertugas menampilkan halaman website.

Contoh view:

1. Landing page.
2. Login.
3. Register.
4. Detail layanan.
5. Dashboard pengguna.
6. Chat konsultasi.
7. Dashboard admin.
8. Dashboard superadmin.

## Database

Database menggunakan MySQL. Semua operasi database harus menggunakan prepared statement.

## Hak Akses

Hak akses menggunakan role-based access control.

Role awal:

1. `user`
2. `admin`
3. `superadmin`

Role admin harus fleksibel dan dapat dikembangkan oleh superadmin sesuai kebutuhan layanan.

## Session

Autentikasi menggunakan session PHP. Setelah login berhasil, sistem menyimpan data penting di session, seperti:

1. user_id
2. role
3. status akun
4. nama pengguna

## Catatan Arsitektur

Sistem tidak perlu dibuat terlalu kompleks pada versi awal. Fokus utama adalah alur konsultasi berjalan dari registrasi, approval, login, pilih sublayanan, pembayaran, hingga chat konsultasi.
