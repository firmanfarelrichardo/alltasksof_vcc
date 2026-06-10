\
# Authentication and Authorization

## Purpose

Dokumen ini mengunci aturan autentikasi, approval akun, role, middleware, ownership, dan akses data.

---

# Roles

Gunakan tiga role utama:

```text
user
admin
superadmin
```

---

# Account Status

Gunakan:

```text
pending
approved
rejected
inactive
```

## Meaning

| Status | Meaning | Login Allowed |
|---|---|---|
| `pending` | Menunggu approval superadmin | No |
| `approved` | Akun aktif | Yes |
| `rejected` | Registrasi ditolak | No |
| `inactive` | Akun dinonaktifkan | No |

---

# Registration Flow

```text
User isi form registrasi
        |
        v
Validasi input
        |
        v
Hash password
        |
        v
Insert users.role = user
        |
        v
Insert users.status = pending
        |
        v
Tampilkan pesan menunggu approval
```

## Registration Rules

1. Email wajib unik.
2. Password minimal 8 karakter.
3. Password disimpan menggunakan `password_hash()`.
4. Jangan menyimpan password plain text.
5. Role dari frontend tidak boleh dipercaya.
6. Status dari frontend tidak boleh dipercaya.
7. Role awal selalu `user`.
8. Status awal selalu `pending`.

---

# Login Flow

```text
User isi email dan password
        |
        v
Cari akun berdasarkan email
        |
        v
Verifikasi password
        |
        v
Periksa status approved
        |
        v
Regenerate session ID
        |
        v
Simpan session minimum
        |
        v
Redirect berdasarkan role
```

## Session Data

Gunakan:

```php
$_SESSION['user_id'];
$_SESSION['user_name'];
$_SESSION['role'];
$_SESSION['status'];
```

Jangan simpan:

```text
password
password_hash
server_key
database_password
```

---

# Redirect Rules

## User

```text
/user/dashboard
```

## Admin

```text
/admin/dashboard
```

## Superadmin

```text
/superadmin/dashboard
```

---

# Middleware Rules

## `GuestMiddleware`

Digunakan untuk:

```text
/login
/register
```

Jika user sudah login, redirect sesuai role.

## `AuthMiddleware`

Memastikan session login tersedia.

## `ApprovedUserMiddleware`

Memastikan:

```text
role = user
status = approved
```

## `AdminMiddleware`

Memastikan:

```text
role = admin
status = approved
```

## `SuperadminMiddleware`

Memastikan:

```text
role = superadmin
status = approved
```

## `CsrfMiddleware`

Digunakan untuk form mutasi data:

1. Register.
2. Login.
3. Logout.
4. Approval user.
5. CRUD layanan.
6. CRUD sub layanan.
7. Assignment admin.
8. Pembayaran.
9. Pengiriman chat.

---

# Superadmin Approval

## Route

```text
GET   /superadmin/users/pending
PATCH /superadmin/users/{userId}/approve
PATCH /superadmin/users/{userId}/reject
```

## Rules

1. Hanya superadmin boleh approve.
2. Hanya superadmin boleh reject.
3. Admin tidak boleh approve user.
4. User pending tidak boleh login.
5. User rejected tidak boleh login.
6. User inactive tidak boleh login.

---

# Admin Assignment

Admin tidak ditentukan berdasarkan nama.

Gunakan tabel:

```text
admin_service_assignments
```

## Rule

```text
Admin dapat menangani satu atau lebih kategori layanan.
```

Admin hanya boleh:

1. Melihat transaksi sesuai layanan assignment.
2. Melihat konsultasi sesuai layanan assignment.
3. Membalas chat sesuai layanan assignment.
4. CRUD sub layanan sesuai layanan assignment.
5. Melihat keuangan sesuai layanan assignment.

---

# Ownership Rule

## User Consultation

User hanya dapat mengakses consultation jika:

```text
consultations.user_id = current_user.id
```

## User Payment

User hanya dapat mengakses payment jika:

```text
payments.user_id = current_user.id
```

## Admin Consultation

Admin hanya dapat mengakses consultation jika:

```text
consultations.sub_service_id
→ sub_services.service_category_id
→ admin_service_assignments.service_category_id
```

dan:

```text
admin_service_assignments.admin_id = current_admin.id
```

## Superadmin

Superadmin dapat mengakses seluruh data.

---

# Registered User Visibility

## Superadmin

Dapat melihat detail user terdaftar:

```text
name
email
status
created_at
```

## Admin

Untuk keamanan, admin hanya melihat statistik jumlah user terdaftar.

Admin mulai melihat detail user setelah user memilih sub layanan yang sesuai dengan assignment admin.

---

# Password Rules

Gunakan:

```php
password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $storedHash);
```

---

# Session Security Rules

1. Gunakan `session_regenerate_id(true)` setelah login.
2. Hapus session saat logout.
3. Jangan menyimpan data sensitif.
4. Gunakan cookie aman saat production.
5. Aktifkan HTTPS saat production.
6. Set cookie `HttpOnly`.
7. Set cookie `Secure` saat HTTPS aktif.
8. Pertimbangkan `SameSite=Lax`.

---

# CSRF Rule

Form mutasi data wajib memiliki token.

Contoh konsep:

```php
<input type="hidden" name="_token" value="<?= csrf_token() ?>">
```

Backend memvalidasi token sebelum proses.

---

# Authorization Error Rule

Gunakan:

```text
403 Forbidden
```

Jangan mengungkapkan detail internal.

Pesan user:

```text
Anda tidak memiliki akses ke halaman ini.
```

---

# Final Rule

Backend tidak boleh mempercayai role, status, ownership, atau harga dari frontend.

Semua keputusan akses wajib divalidasi ulang pada backend.
