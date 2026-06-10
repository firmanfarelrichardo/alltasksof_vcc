\
# Payment Gateway — Midtrans Snap Integration

## Purpose

Dokumen ini menjelaskan integrasi payment gateway Midtrans untuk sistem konsultasi teknologi versi `v2.0`.

AI agent wajib membaca dokumen ini sebelum membuat atau mengubah fitur pembayaran.

---

# Integration Decision

Gunakan:

```text
Midtrans Snap
```

Alasan:

1. Cocok untuk sistem PHP native.
2. UI pembayaran sudah disediakan Midtrans.
3. Pengguna dapat memilih metode pembayaran dari halaman Snap.
4. Backend hanya perlu membuat transaksi, menyimpan token, menerima webhook, dan memperbarui status.
5. Implementasi lebih sederhana dibanding membuat UI pembayaran khusus dengan Core API.

---

# Main Business Flow

```text
User memilih sub layanan
        |
        v
Sistem membuat consultation dengan status waiting_payment
        |
        v
Backend membuat payment lokal
        |
        v
Backend meminta Snap token ke Midtrans
        |
        v
Snap token disimpan pada tabel payments
        |
        v
Frontend membuka Snap popup / redirect
        |
        v
User menyelesaikan pembayaran
        |
        v
Midtrans mengirim webhook ke backend
        |
        v
Backend memverifikasi signature_key
        |
        v
Backend memperbarui status payment
        |
        v
Jika pembayaran valid:
consultation.status = active
        |
        v
User dapat mengakses chat konsultasi
```

---

# Backend Architecture

Gunakan struktur berikut:

```text
backend/
├── app/
│   ├── Controllers/
│   │   ├── PaymentController.php
│   │   └── MidtransWebhookController.php
│   │
│   ├── Services/
│   │   ├── PaymentService.php
│   │   └── MidtransService.php
│   │
│   ├── Models/
│   │   ├── Payment.php
│   │   └── Consultation.php
│   │
│   └── Core/
│       └── Database.php
│
├── config/
│   └── payment.php
│
└── routes/
    └── payment.php
```

## Responsibility

### `PaymentController`

Menangani request dari user:

1. Menampilkan halaman pembayaran.
2. Membuat transaksi pembayaran.
3. Menampilkan status pembayaran.
4. Mengarahkan user ke halaman konsultasi setelah pembayaran valid.

### `MidtransWebhookController`

Menangani notifikasi HTTP dari Midtrans:

1. Membaca JSON payload.
2. Memanggil `MidtransService`.
3. Memverifikasi `signature_key`.
4. Menyimpan status transaksi.
5. Mengaktifkan konsultasi jika pembayaran valid.
6. Mengembalikan HTTP response sederhana.

### `PaymentService`

Menangani logika bisnis internal:

1. Membuat `order_id`.
2. Membuat record payment lokal.
3. Memastikan nominal pembayaran sesuai harga sub layanan.
4. Menyimpan snapshot harga.
5. Mengaktifkan consultation setelah payment valid.
6. Menjaga proses menggunakan database transaction.

### `MidtransService`

Menangani komunikasi dengan Midtrans:

1. Mengatur Server Key.
2. Mengatur Sandbox atau Production mode.
3. Membuat Snap token.
4. Mengambil status transaksi jika diperlukan.
5. Memverifikasi signature webhook.
6. Memetakan status Midtrans ke status internal.

---

# Required Midtrans PHP Library

Gunakan library PHP resmi:

```bash
composer require midtrans/midtrans-php
```

Jika proyek belum memakai Composer, integrasi manual tetap memungkinkan, tetapi Composer direkomendasikan agar dependency lebih mudah dikelola.

---

# Snap Configuration

Contoh konfigurasi pada service:

```php
<?php

use Midtrans\Config;

Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') === 'true';
Config::$isSanitized = true;
Config::$is3ds = true;
```

---

# Snap Token Request

Token Snap harus dibuat dari backend.

Jangan membuat token Snap langsung dari frontend.

Contoh:

```php
<?php

$params = [
    'transaction_details' => [
        'order_id' => $payment['order_id'],
        'gross_amount' => (int) $payment['amount'],
    ],
    'customer_details' => [
        'first_name' => $user['name'],
        'email' => $user['email'],
    ],
    'item_details' => [
        [
            'id' => 'SUBSERVICE-' . $subService['id'],
            'price' => (int) $payment['amount'],
            'quantity' => 1,
            'name' => $subService['name'],
        ],
    ],
];

$snapToken = \Midtrans\Snap::getSnapToken($params);
```

## Important Rule

1. `order_id` harus unik.
2. `gross_amount` harus berupa integer.
3. Nilai `gross_amount` berasal dari backend.
4. Jangan percaya nominal yang dikirim frontend.
5. Simpan nominal transaksi sebagai snapshot.

---

# Suggested Order ID Format

Gunakan format sederhana:

```text
CONSULT-{paymentId}-{timestamp}
```

Contoh:

```text
CONSULT-125-1718000000
```

Aturan:

1. Unik.
2. Mudah ditelusuri.
3. Tidak mengandung data sensitif.
4. Maksimal 50 karakter.
5. Gunakan karakter aman: huruf, angka, dash, underscore, tilde, atau dot.

---

# Frontend Snap Popup

Gunakan Snap popup untuk versi awal.

## Sandbox Script

```html
<script
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="<?= htmlspecialchars(getenv('MIDTRANS_CLIENT_KEY'), ENT_QUOTES, 'UTF-8') ?>">
</script>
```

## Production Script

```html
<script
  src="https://app.midtrans.com/snap/snap.js"
  data-client-key="<?= htmlspecialchars(getenv('MIDTRANS_CLIENT_KEY'), ENT_QUOTES, 'UTF-8') ?>">
</script>
```

## Pay Button

```html
<button id="pay-button" type="button">
  Bayar Sekarang
</button>

<script>
  document.getElementById('pay-button').addEventListener('click', function () {
    window.snap.pay('<?= htmlspecialchars($snapToken, ENT_QUOTES, 'UTF-8') ?>');
  });
</script>
```

## Important Rule

Frontend callback hanya dipakai untuk pengalaman pengguna.

Contoh:

1. Menampilkan pesan pembayaran selesai.
2. Redirect ke halaman status.
3. Menampilkan loading.

Frontend callback tidak boleh menjadi sumber kebenaran status pembayaran.

Status pembayaran harus ditentukan backend dari webhook atau Get Status API.

---

# Payment Status Mapping

## Midtrans Status

Status Midtrans yang perlu ditangani:

```text
pending
capture
settlement
deny
cancel
expire
refund
partial_refund
```

## Internal Payment Status

Gunakan status internal:

```text
pending
paid
failed
expired
refunded
```

## Mapping Recommendation

| Midtrans Status | Fraud Status | Internal Status | Consultation Access |
|---|---|---|---|
| `pending` | Any | `pending` | Locked |
| `capture` | `accept` | `paid` | Active |
| `settlement` | Any | `paid` | Active |
| `deny` | Any | `failed` | Locked |
| `cancel` | Any | `failed` | Locked |
| `expire` | Any | `expired` | Locked |
| `refund` | Any | `refunded` | Review manually |
| `partial_refund` | Any | `refunded` | Review manually |

## Important Rule

Jika status berubah menjadi `deny`, jangan memberikan akses konsultasi.

Gunakan backend untuk memperbarui status konsultasi.

---

# Webhook Signature Verification

Webhook Midtrans harus diverifikasi.

Formula:

```text
SHA512(order_id + status_code + gross_amount + ServerKey)
```

Contoh:

```php
<?php

$expectedSignature = hash(
    'sha512',
    $payload['order_id']
    . $payload['status_code']
    . $payload['gross_amount']
    . getenv('MIDTRANS_SERVER_KEY')
);

if (!hash_equals($expectedSignature, $payload['signature_key'])) {
    http_response_code(403);
    exit('Invalid signature');
}
```

## Important Rule

1. Jangan memperbarui payment sebelum signature valid.
2. Gunakan `hash_equals()`.
3. Jangan log Server Key.
4. Jangan mengirim Server Key ke frontend.

---

# Webhook Idempotency

Webhook dapat diterima lebih dari satu kali.

Handler harus idempotent.

Aturan:

1. Cari payment berdasarkan `order_id`.
2. Verifikasi signature.
3. Jika status baru sama dengan status tersimpan, jangan membuat konsultasi baru.
4. Jangan menduplikasi payment.
5. Jangan menduplikasi message room.
6. Gunakan database transaction.

Contoh konsep:

```text
Webhook settlement diterima
  |
Payment sudah paid?
  |
Ya -> return 200 tanpa membuat data baru
Tidak -> update payment dan aktifkan consultation
```

---

# Consultation Activation Rule

Aktifkan konsultasi hanya jika:

```text
payment.internal_status = paid
```

Update:

```text
consultations.status = active
```

Jangan aktifkan chat hanya karena user membuka Snap popup.

Jangan aktifkan chat hanya karena frontend menerima callback success.

---

# Get Status API

Gunakan Get Status API jika:

1. Perlu rekonsiliasi status.
2. Webhook terlambat.
3. Admin ingin mengecek ulang pembayaran.
4. Development lokal belum menerima webhook.

Gunakan endpoint Midtrans:

```text
GET /v2/{order_id}/status
```

Tetap simpan hasil verifikasi ke database melalui backend.

---

# Public Webhook Requirement

Notification URL Midtrans harus dapat diakses melalui public internet.

Webhook tidak dapat diarahkan ke:

```text
localhost
private Tailscale-only URL
VPN-only URL
URL dengan login
URL yang membutuhkan auth header khusus
```

Untuk development lokal:

1. Gunakan Sandbox.
2. Gunakan tunnel HTTPS sementara jika ingin menguji webhook.
3. Atau gunakan Get Status API untuk pengujian status.
4. Jangan menganggap callback frontend sebagai status final.

---

# Payment Security Rules

1. Server Key hanya untuk backend.
2. Client Key boleh digunakan pada frontend untuk Snap script.
3. Jangan commit key ke repository.
4. Gunakan `.env`.
5. Pisahkan Sandbox key dan Production key.
6. Validasi webhook signature.
7. Gunakan HTTPS untuk production.
8. Gunakan nominal dari database.
9. Gunakan `order_id` unik.
10. Gunakan database transaction.
11. Jangan mengaktifkan konsultasi dari request frontend.
12. Jangan expose exception detail ke user.

---

# Simplicity Rule

Untuk versi awal, jangan membuat:

1. Refund otomatis.
2. Multi-gateway abstraction kompleks.
3. Reconciliation scheduler kompleks.
4. Invoice PDF.
5. Cicilan internal.
6. Voucher.
7. Split payment.
8. Partial payment.
9. Subscription.
10. Websocket pembayaran.

Fokus versi awal:

```text
Create payment
Open Snap
Receive webhook
Verify signature
Update status
Activate consultation
```
