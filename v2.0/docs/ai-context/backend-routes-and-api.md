\
# Backend Routes and API Endpoints

## Purpose

Dokumen ini menjadi sumber kebenaran untuk route backend sistem konsultasi teknologi versi `v2.0`.

Semua route harus:

1. Terdaftar di file route yang sesuai.
2. Memiliki controller yang jelas.
3. Memiliki middleware yang sesuai.
4. Menggunakan penamaan konsisten.
5. Tidak dibuat langsung di file view.
6. Tidak diduplikasi.

---

# Route File Structure

```text
routes/
├── web.php
├── auth.php
├── user.php
├── admin.php
├── superadmin.php
└── payment.php
```

---

# Route Naming Convention

Gunakan aturan:

```text
GET     untuk menampilkan data atau halaman
POST    untuk membuat data atau memproses aksi
PUT     untuk update penuh jika memakai API JSON
PATCH   untuk update sebagian jika memakai API JSON
DELETE  untuk hapus data jika memakai API JSON
```

Untuk form HTML native, update dan delete dapat menggunakan `POST` dengan hidden field:

```html
<input type="hidden" name="_method" value="PATCH">
```

atau:

```html
<input type="hidden" name="_method" value="DELETE">
```

Router dapat membaca `_method` agar pola tetap konsisten.

---

# 1. Public Routes

File:

```text
routes/web.php
```

| Method | URI | Controller | Action | Middleware | Purpose |
|---|---|---|---|---|---|
| GET | `/` | `PublicServiceController` | `home()` | - | Landing page |
| GET | `/services` | `PublicServiceController` | `index()` | - | Daftar layanan |
| GET | `/services/{serviceId}` | `PublicServiceController` | `showCategory()` | - | Detail kategori layanan |
| GET | `/sub-services/{subServiceId}` | `PublicServiceController` | `showSubService()` | - | Detail sublayanan |
| GET | `/pricing` | `PublicServiceController` | `pricing()` | - | Daftar harga konsultasi |
| GET | `/consultants` | `PublicServiceController` | `consultants()` | - | Kompetensi konsultan |

---

# 2. Authentication Routes

File:

```text
routes/auth.php
```

| Method | URI | Controller | Action | Middleware | Purpose |
|---|---|---|---|---|---|
| GET | `/register` | `AuthController` | `showRegister()` | `GuestMiddleware` | Form registrasi |
| POST | `/register` | `AuthController` | `register()` | `GuestMiddleware` | Proses registrasi |
| GET | `/login` | `AuthController` | `showLogin()` | `GuestMiddleware` | Form login |
| POST | `/login` | `AuthController` | `login()` | `GuestMiddleware` | Proses login |
| POST | `/logout` | `AuthController` | `logout()` | `AuthMiddleware` | Logout |

## Registration Response Rule

Setelah registrasi berhasil:

```text
Akun berhasil dibuat dan menunggu persetujuan superadmin.
```

## Login Rule

Login hanya berhasil jika:

```text
status = approved
```

---

# 3. User Routes

File:

```text
routes/user.php
```

Middleware group:

```text
AuthMiddleware
ApprovedUserMiddleware
```

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/user/dashboard` | `UserDashboardController` | `index()` | Dashboard pengguna |
| GET | `/user/consultations` | `ConsultationController` | `userHistory()` | Riwayat konsultasi milik pengguna |
| GET | `/user/consultations/{consultationId}` | `ConsultationController` | `showForUser()` | Detail konsultasi |
| POST | `/user/consultations` | `ConsultationController` | `create()` | Membuat konsultasi dari sublayanan |
| GET | `/user/consultations/{consultationId}/chat` | `ChatController` | `showForUser()` | Halaman chat konsultasi |
| POST | `/user/consultations/{consultationId}/messages` | `ChatController` | `sendByUser()` | Kirim pesan pengguna |

## User Data Ownership Rule

Setiap route detail konsultasi wajib memeriksa:

```text
consultation.user_id == current_user.id
```

---

# 4. Payment Routes

File:

```text
routes/payment.php
```

Middleware group:

```text
AuthMiddleware
ApprovedUserMiddleware
```

| Method | URI | Controller | Action | Middleware | Purpose |
|---|---|---|---|---|---|
| GET | `/user/consultations/{consultationId}/payment` | `PaymentController` | `show()` | User | Halaman pembayaran |
| POST | `/user/consultations/{consultationId}/payment` | `PaymentController` | `create()` | User | Membuat transaksi pembayaran |
| GET | `/user/payments/{paymentId}` | `PaymentController` | `showStatus()` | User | Melihat status pembayaran |

## Reserved Gateway Endpoint

Endpoint berikut disiapkan untuk digunakan setelah payment gateway ditentukan:

| Method | URI | Controller | Action | Middleware | Purpose |
|---|---|---|---|---|---|
| POST | `/payments/callback` | `PaymentController` | `callback()` | Gateway signature validation | Callback payment gateway |

## Important Rule

1. Callback harus divalidasi.
2. Jangan percaya status pembayaran dari frontend.
3. Konsultasi hanya aktif setelah backend menerima status pembayaran valid.
4. Untuk tahap lokal, callback dapat disimulasikan secara terbatas melalui mode development.

---

# 5. Admin Routes

File:

```text
routes/admin.php
```

Middleware group:

```text
AuthMiddleware
AdminMiddleware
```

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/admin/dashboard` | `AdminDashboardController` | `index()` | Dashboard admin |
| GET | `/admin/consultations` | `ConsultationController` | `adminIndex()` | Konsultasi sesuai layanan admin |
| GET | `/admin/consultations/{consultationId}` | `ConsultationController` | `showForAdmin()` | Detail konsultasi |
| GET | `/admin/consultations/{consultationId}/chat` | `ChatController` | `showForAdmin()` | Chat konsultasi |
| POST | `/admin/consultations/{consultationId}/messages` | `ChatController` | `sendByAdmin()` | Membalas pesan konsultasi |
| GET | `/admin/sub-services` | `AdminSubServiceController` | `index()` | Daftar sublayanan yang dikelola |
| GET | `/admin/sub-services/create` | `AdminSubServiceController` | `create()` | Form tambah sublayanan |
| POST | `/admin/sub-services` | `AdminSubServiceController` | `store()` | Simpan sublayanan |
| GET | `/admin/sub-services/{subServiceId}/edit` | `AdminSubServiceController` | `edit()` | Form edit sublayanan |
| PATCH | `/admin/sub-services/{subServiceId}` | `AdminSubServiceController` | `update()` | Update sublayanan |
| DELETE | `/admin/sub-services/{subServiceId}` | `AdminSubServiceController` | `destroy()` | Hapus atau nonaktifkan sublayanan |
| GET | `/admin/finance` | `AdminFinanceController` | `index()` | Data keuangan layanan admin |

## Admin Authorization Rule

Admin hanya boleh mengakses data yang memenuhi:

```text
admin_id terhubung dengan service_category_id
```

Gunakan tabel:

```text
admin_service_assignments
```

---

# 6. Superadmin Routes

File:

```text
routes/superadmin.php
```

Middleware group:

```text
AuthMiddleware
SuperadminMiddleware
```

## Dashboard

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/dashboard` | `SuperadminDashboardController` | `index()` | Dashboard superadmin |

## User Approval

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/users/pending` | `SuperadminUserApprovalController` | `pending()` | Daftar akun pending |
| PATCH | `/superadmin/users/{userId}/approve` | `SuperadminUserApprovalController` | `approve()` | Approve akun |
| PATCH | `/superadmin/users/{userId}/reject` | `SuperadminUserApprovalController` | `reject()` | Reject akun |

## Admin Management

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/admins` | `SuperadminAdminManagementController` | `index()` | Daftar admin |
| GET | `/superadmin/admins/create` | `SuperadminAdminManagementController` | `create()` | Form tambah admin |
| POST | `/superadmin/admins` | `SuperadminAdminManagementController` | `store()` | Simpan admin baru |
| GET | `/superadmin/admins/{adminId}/edit` | `SuperadminAdminManagementController` | `edit()` | Form edit admin |
| PATCH | `/superadmin/admins/{adminId}` | `SuperadminAdminManagementController` | `update()` | Update admin |
| DELETE | `/superadmin/admins/{adminId}` | `SuperadminAdminManagementController` | `destroy()` | Nonaktifkan admin |
| GET | `/superadmin/admins/{adminId}/assignments` | `SuperadminAdminManagementController` | `assignments()` | Halaman penugasan layanan |
| POST | `/superadmin/admins/{adminId}/assignments` | `SuperadminAdminManagementController` | `storeAssignment()` | Tambah penugasan layanan |
| DELETE | `/superadmin/admins/{adminId}/assignments/{assignmentId}` | `SuperadminAdminManagementController` | `destroyAssignment()` | Hapus penugasan layanan |

## Service Category Management

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/services` | `SuperadminServiceController` | `index()` | Daftar kategori layanan |
| GET | `/superadmin/services/create` | `SuperadminServiceController` | `create()` | Form tambah kategori |
| POST | `/superadmin/services` | `SuperadminServiceController` | `store()` | Simpan kategori |
| GET | `/superadmin/services/{serviceId}/edit` | `SuperadminServiceController` | `edit()` | Form edit kategori |
| PATCH | `/superadmin/services/{serviceId}` | `SuperadminServiceController` | `update()` | Update kategori |
| DELETE | `/superadmin/services/{serviceId}` | `SuperadminServiceController` | `destroy()` | Nonaktifkan kategori |

## Subservice Management

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/sub-services` | `SuperadminServiceController` | `subServices()` | Daftar seluruh sublayanan |
| GET | `/superadmin/sub-services/create` | `SuperadminServiceController` | `createSubService()` | Form tambah sublayanan |
| POST | `/superadmin/sub-services` | `SuperadminServiceController` | `storeSubService()` | Simpan sublayanan |
| GET | `/superadmin/sub-services/{subServiceId}/edit` | `SuperadminServiceController` | `editSubService()` | Form edit sublayanan |
| PATCH | `/superadmin/sub-services/{subServiceId}` | `SuperadminServiceController` | `updateSubService()` | Update sublayanan |
| DELETE | `/superadmin/sub-services/{subServiceId}` | `SuperadminServiceController` | `destroySubService()` | Nonaktifkan sublayanan |

## Consultation and Finance

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/superadmin/consultations` | `ConsultationController` | `superadminIndex()` | Seluruh konsultasi |
| GET | `/superadmin/consultations/{consultationId}` | `ConsultationController` | `showForSuperadmin()` | Detail konsultasi |
| GET | `/superadmin/finance` | `SuperadminFinanceController` | `index()` | Seluruh transaksi dan keuangan |

---

# 7. Internal JSON API Endpoints

Untuk kebutuhan JavaScript ringan pada frontend, sediakan endpoint JSON terpisah jika diperlukan.

Base path:

```text
/api
```

## User Chat API

| Method | URI | Controller | Action | Access | Purpose |
|---|---|---|---|---|---|
| GET | `/api/user/consultations/{consultationId}/messages` | `ChatController` | `messagesForUser()` | User | Ambil pesan chat |
| POST | `/api/user/consultations/{consultationId}/messages` | `ChatController` | `sendByUserApi()` | User | Kirim pesan |

## Admin Chat API

| Method | URI | Controller | Action | Access | Purpose |
|---|---|---|---|---|---|
| GET | `/api/admin/consultations/{consultationId}/messages` | `ChatController` | `messagesForAdmin()` | Admin | Ambil pesan chat |
| POST | `/api/admin/consultations/{consultationId}/messages` | `ChatController` | `sendByAdminApi()` | Admin | Kirim balasan |

## Payment Status API

| Method | URI | Controller | Action | Access | Purpose |
|---|---|---|---|---|---|
| GET | `/api/user/payments/{paymentId}/status` | `PaymentController` | `statusApi()` | User | Poll status pembayaran |

## Rule

Endpoint JSON hanya dibuat jika frontend membutuhkannya.

Jangan membuat API tambahan yang belum digunakan.

---

# 8. Suggested Router Registration

Contoh struktur route registry:

```php
<?php

$router->get('/', [PublicServiceController::class, 'home']);

$router->get('/login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [AuthController::class, 'login'], ['guest']);

$router->group('/user', ['auth', 'approved'], function ($router) {
    $router->get('/dashboard', [UserDashboardController::class, 'index']);
});

$router->group('/admin', ['auth', 'admin'], function ($router) {
    $router->get('/dashboard', [AdminDashboardController::class, 'index']);
});

$router->group('/superadmin', ['auth', 'superadmin'], function ($router) {
    $router->get('/dashboard', [SuperadminDashboardController::class, 'index']);
});
```

---

# 9. Route Documentation Rule

Jika menambah route baru:

1. Tambahkan route pada file route yang tepat.
2. Tambahkan dokumentasi pada file ini.
3. Tentukan middleware.
4. Tentukan controller dan action.
5. Tentukan apakah route HTML atau JSON API.
6. Hindari route duplikat.
7. Pastikan penamaan URI konsisten.

---

# 10. Endpoint Design Rule

Gunakan URI berbasis resource:

```text
/services
/sub-services
/consultations
/messages
/payments
/admins
/users
```

Hindari URI seperti:

```text
/doAddService
/processPaymentNow
/getAllChatData
```

Gunakan nama action hanya pada kasus khusus:

```text
/users/{id}/approve
/users/{id}/reject
```
