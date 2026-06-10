\
# Backend Clean Code Guidelines

## Purpose

Dokumen ini berisi aturan penulisan kode backend agar:

1. Clean.
2. Maintainable.
3. Scalable.
4. Efektif.
5. Ringkas.
6. Mudah dipahami manusia dan AI agent.

---

# Core Principle

Gunakan prinsip:

```text
Readable first.
Simple before clever.
One responsibility per unit.
Avoid unnecessary abstraction.
```

---

# 1. File Responsibility

Setiap file harus memiliki tanggung jawab utama yang jelas.

## Good

```text
AuthController.php
PaymentService.php
Consultation.php
AdminMiddleware.php
```

## Avoid

```text
functions.php
all_process.php
admin_everything.php
helper_final_fix.php
```

Jangan membuat file umum yang berisi terlalu banyak fungsi tidak terkait.

---

# 2. Thin Controller Rule

Controller harus tipis.

Controller hanya:

1. Membaca request.
2. Memvalidasi input dasar.
3. Memanggil service atau model.
4. Mengatur redirect atau response.
5. Menentukan view.

## Good Example

```php
public function approve(int $userId): void
{
    $this->approvalService->approveUser($userId);

    redirect('/superadmin/users/pending');
}
```

## Avoid

Jangan menulis query panjang, transaksi database kompleks, atau perulangan besar langsung di controller.

---

# 3. Model Rule

Model bertanggung jawab untuk akses data.

## Good

```php
$user = $this->userModel->findByEmail($email);
```

## Avoid

```php
$pdo->query("SELECT * FROM users WHERE email = '$email'");
```

Gunakan prepared statement.

---

# 4. Service Rule

Service digunakan untuk logika bisnis multi-langkah.

## Example

```php
final class ConsultationService
{
    public function activateAfterPaidPayment(int $paymentId): void
    {
        // Validate payment.
        // Update payment.
        // Activate consultation.
    }
}
```

Gunakan service jika proses:

1. Melibatkan lebih dari satu model.
2. Memiliki transaksi database.
3. Memiliki aturan bisnis penting.
4. Digunakan ulang.

Jangan membuat service untuk satu query sederhana.

---

# 5. Naming Convention

## Class

Gunakan PascalCase.

```text
AuthController
PaymentService
AdminMiddleware
```

## Method

Gunakan camelCase.

```text
findByEmail()
approveUser()
createConsultation()
```

## Variable

Gunakan camelCase.

```text
$userId
$subServiceId
$paymentStatus
```

## Database

Gunakan snake_case.

```text
service_categories
sub_services
admin_service_assignments
created_at
```

## Constant

Gunakan UPPER_SNAKE_CASE.

```text
ROLE_ADMIN
STATUS_APPROVED
PAYMENT_PAID
```

---

# 6. Function Length

Fungsi harus singkat.

Target:

```text
10–30 baris per fungsi
```

Jika fungsi terlalu panjang:

1. Pecah menjadi private method.
2. Pindahkan logika bisnis ke service.
3. Pindahkan query ke model.
4. Hapus duplikasi.

---

# 7. Avoid Deep Nesting

Hindari nested condition berlebihan.

## Avoid

```php
if ($user) {
    if ($user['status'] === 'approved') {
        if (password_verify($password, $user['password'])) {
            // Login
        }
    }
}
```

## Prefer

```php
if (!$user) {
    return $this->loginFailed();
}

if ($user['status'] !== 'approved') {
    return $this->accountNotApproved();
}

if (!password_verify($password, $user['password'])) {
    return $this->loginFailed();
}

return $this->loginSuccess($user);
```

Gunakan early return.

---

# 8. Validation Rule

Validasi input sebelum memproses data.

## Required Validation

1. Required field.
2. Email format.
3. Numeric ID.
4. Price format.
5. Status whitelist.
6. Role whitelist.
7. Ownership check.
8. Admin assignment check.

## Do Not Trust Frontend

Frontend hanya membantu UX.

Backend tetap harus memvalidasi ulang semua input.

---

# 9. Security Rule

## Password

Gunakan:

```php
password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $hashedPassword);
```

## HTML Output

Gunakan:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
```

## SQL

Gunakan PDO prepared statements.

```php
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
```

## Session

Setelah login:

```php
session_regenerate_id(true);
```

## CSRF

Gunakan token untuk form penting:

1. Login.
2. Register.
3. Approval.
4. CRUD layanan.
5. Update harga.
6. Pembayaran.
7. Logout.

---

# 10. Configuration Rule

Konfigurasi wajib terpusat.

## Good

```text
config/
├── app.php
├── database.php
└── payment.php
```

## Avoid

Jangan menaruh:

1. Host database.
2. Username database.
3. Password database.
4. Gateway key.
5. Debug mode.

langsung pada controller, model, atau view.

---

# 11. Environment Rule

Gunakan file:

```text
.env
.env.example
```

Commit:

```text
.env.example
```

Jangan commit:

```text
.env
```

---

# 12. Response Rule

Gunakan response konsisten.

## HTML Form

Gunakan flash message:

```text
success
error
warning
```

## JSON API

Gunakan format:

```json
{
  "success": true,
  "message": "Pesan berhasil dikirim.",
  "data": {}
}
```

Error:

```json
{
  "success": false,
  "message": "Data tidak valid.",
  "errors": {}
}
```

---

# 13. Database Transaction Rule

Gunakan database transaction untuk proses multi-langkah.

Contoh:

1. Membuat pembayaran.
2. Mengaktifkan konsultasi.
3. Menyimpan penugasan admin jika ada lebih dari satu query.
4. Mengubah status transaksi dan konsultasi sekaligus.

```php
$pdo->beginTransaction();

try {
    // Process.
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
}
```

---

# 14. Query Rule

## Select Specific Columns

Hindari:

```sql
SELECT *
```

Gunakan:

```sql
SELECT id, name, email, role, status
FROM users
WHERE id = :id
```

## Pagination

Untuk daftar panjang, gunakan limit.

```sql
LIMIT :limit OFFSET :offset
```

## Index

Tambahkan index pada kolom yang sering dicari:

1. `users.email`
2. `users.status`
3. `consultations.user_id`
4. `consultations.sub_service_id`
5. `consultations.status`
6. `messages.consultation_id`
7. `payments.user_id`
8. `payments.payment_status`

---

# 15. View Rule

View tidak boleh:

1. Menjalankan query.
2. Mengubah database.
3. Memiliki logika bisnis.
4. Membaca environment variable langsung.
5. Memeriksa hak akses kompleks.

View hanya menampilkan data.

---

# 16. Comment Rule

Tulis komentar hanya jika dibutuhkan.

## Good

```php
// Store transaction amount as a snapshot because service prices may change later.
```

## Avoid

```php
// Set user ID.
$userId = $user['id'];
```

Komentar harus menjelaskan alasan, bukan sekadar mengulang kode.

---

# 17. Error Handling Rule

Gunakan exception untuk error sistem.

Gunakan validasi biasa untuk kesalahan input pengguna.

## Production

Jangan menampilkan:

1. Database password.
2. SQL query mentah.
3. Stack trace.
4. Path server.
5. Detail exception.

Simpan detail ke log.

---

# 18. Logging Rule

Log minimal:

1. Login gagal berulang.
2. Error database.
3. Callback pembayaran gagal.
4. Perubahan status pembayaran.
5. Approval atau reject pengguna.
6. Perubahan role admin.

Jangan menyimpan password ke log.

---

# 19. Scalability Rule

Struktur harus memungkinkan:

1. Admin baru.
2. Layanan baru.
3. Sublayanan baru.
4. Satu admin menangani beberapa layanan.
5. Perubahan database lokal menjadi remote Tailscale.
6. Penambahan gateway pembayaran tanpa mengubah controller besar-besaran.

Gunakan tabel relasi:

```text
admin_service_assignments
```

Jangan hardcode:

```php
if ($adminName === 'Firman') {
    // Network admin
}
```

---

# 20. Simplicity Rule

Jangan over-engineering.

Tidak perlu menambahkan:

1. Framework besar.
2. Pattern berlapis-lapis.
3. Library yang tidak diperlukan.
4. Class abstrak tanpa kebutuhan nyata.
5. Interface untuk setiap class kecil.
6. Dependency injection container kompleks.

Gunakan struktur sesederhana mungkin, tetapi tetap jelas.

---

# 21. Pull Request / Change Rule

Setiap perubahan backend harus menjawab:

1. File apa yang diubah?
2. Route apa yang ditambahkan atau diubah?
3. Model apa yang dipakai?
4. Middleware apa yang berlaku?
5. Apakah schema database berubah?
6. Apakah dokumentasi perlu diperbarui?
7. Apakah perubahan sudah diuji?

---

# 22. AI Agent Rule

Sebelum menulis kode:

1. Baca `backend-context.md`.
2. Baca `backend-routes-and-api.md`.
3. Baca `database-environment-strategy.md`.
4. Periksa `database-schema.md`.
5. Periksa `current-progress.md`.

Setelah mengubah kode:

1. Perbarui route documentation jika route berubah.
2. Perbarui schema documentation jika tabel berubah.
3. Perbarui current progress.
4. Tambahkan changelog.
