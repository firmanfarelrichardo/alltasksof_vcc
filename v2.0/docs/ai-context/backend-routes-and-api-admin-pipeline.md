\
# Backend Routes and API — Admin Consultation Pipeline

## Purpose

Dokumen ini menambahkan route backend untuk tampilan pipeline pengguna, pembayaran, dan konsultasi pada sisi admin.

Dokumen ini melengkapi:

```text
backend-routes-and-api.md
```

---

# Route File

Gunakan:

```text
routes/admin.php
```

Middleware group:

```text
AuthMiddleware
AdminMiddleware
```

---

# Admin Pipeline Routes

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/admin/pipeline` | `AdminPipelineController` | `index()` | Daftar seluruh stage pipeline |
| GET | `/admin/pipeline/registered` | `AdminPipelineController` | `registeredUsers()` | Pengguna sudah mendaftar |
| GET | `/admin/pipeline/payments/pending` | `AdminPipelineController` | `pendingPayments()` | Transaksi pending |
| GET | `/admin/pipeline/payments/cancelled` | `AdminPipelineController` | `cancelledPayments()` | Transaksi cancel |
| GET | `/admin/pipeline/payments/success` | `AdminPipelineController` | `successfulPayments()` | Transaksi sukses |
| GET | `/admin/pipeline/consultations/active` | `AdminPipelineController` | `activeConsultations()` | Konsultasi aktif |
| GET | `/admin/pipeline/consultations/closed` | `AdminPipelineController` | `closedConsultations()` | Riwayat konsultasi selesai |
| GET | `/admin/pipeline/{pipelineType}/{recordId}` | `AdminPipelineController` | `show()` | Detail record pipeline |

---

# Optional JSON Endpoints

Gunakan hanya jika frontend membutuhkan update tanpa reload halaman.

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/api/admin/pipeline/summary` | `AdminPipelineController` | `summaryApi()` | Jumlah per stage |
| GET | `/api/admin/pipeline` | `AdminPipelineController` | `indexApi()` | Tabel pipeline dengan filter |
| GET | `/api/admin/pipeline/{pipelineType}/{recordId}` | `AdminPipelineController` | `showApi()` | Detail pipeline |

---

# Query Parameters

Route daftar dapat menerima:

| Parameter | Example | Purpose |
|---|---|---|
| `search` | `?search=andi` | Cari nama atau email |
| `stage` | `?stage=payment_pending` | Filter pipeline |
| `service_id` | `?service_id=2` | Filter kategori layanan |
| `sub_service_id` | `?sub_service_id=5` | Filter sub layanan |
| `date_from` | `?date_from=2026-06-01` | Tanggal awal |
| `date_to` | `?date_to=2026-06-30` | Tanggal akhir |
| `page` | `?page=2` | Pagination |
| `limit` | `?limit=20` | Jumlah row per halaman |

---

# Suggested Controller

Gunakan:

```text
AdminPipelineController.php
```

Tanggung jawab:

1. Menampilkan halaman pipeline.
2. Membaca filter.
3. Memanggil `AdminPipelineService`.
4. Mengembalikan view atau JSON.
5. Tidak menulis query langsung.

---

# Suggested Service

Gunakan:

```text
AdminPipelineService.php
```

Tanggung jawab:

1. Mengambil daftar registered user.
2. Mengambil payment pending.
3. Mengambil payment cancelled.
4. Mengambil payment successful.
5. Mengambil consultation active.
6. Mengambil consultation closed.
7. Memastikan filter layanan admin diterapkan.
8. Menyusun summary card.

---

# Suggested Model or Query Object

Gunakan salah satu:

```text
AdminPipelineRepository.php
```

atau:

```text
AdminPipelineModel.php
```

Tanggung jawab:

1. Menjalankan query pipeline.
2. Menggunakan prepared statement.
3. Menerapkan pagination.
4. Memilih kolom yang diperlukan.
5. Tidak menggunakan `SELECT *`.

---

# Authorization Rules

## Registered Users

Admin dapat melihat basic profile read-only:

```text
name
email
status
created_at
```

## Payments and Consultations

Admin hanya melihat data sesuai layanan yang ditugaskan.

Gunakan:

```text
admin_service_assignments.admin_id = current_admin_id
```

dan:

```text
admin_service_assignments.service_category_id
=
sub_services.service_category_id
```

## Superadmin

Superadmin tidak dibatasi oleh service assignment.

---

# Suggested Route Registration

```php
<?php

$router->group('/admin', ['auth', 'admin'], function ($router) {
    $router->get('/pipeline', [AdminPipelineController::class, 'index']);

    $router->get(
        '/pipeline/registered',
        [AdminPipelineController::class, 'registeredUsers']
    );

    $router->get(
        '/pipeline/payments/pending',
        [AdminPipelineController::class, 'pendingPayments']
    );

    $router->get(
        '/pipeline/payments/cancelled',
        [AdminPipelineController::class, 'cancelledPayments']
    );

    $router->get(
        '/pipeline/payments/success',
        [AdminPipelineController::class, 'successfulPayments']
    );

    $router->get(
        '/pipeline/consultations/active',
        [AdminPipelineController::class, 'activeConsultations']
    );

    $router->get(
        '/pipeline/consultations/closed',
        [AdminPipelineController::class, 'closedConsultations']
    );
});
```

---

# Avoid Duplicate Routes

Jangan membuat route duplikat seperti:

```text
/admin/users-list
/admin/payment-list
/admin/chat-list
/admin/consultation-user-list
```

Gunakan satu pola:

```text
/admin/pipeline
```

dengan filter atau tab.

---

# Final Rule

Halaman utama admin untuk memonitor pengguna dan konsultasi:

```text
GET /admin/pipeline
```

Gunakan tab dan query parameter untuk membedakan stage.
