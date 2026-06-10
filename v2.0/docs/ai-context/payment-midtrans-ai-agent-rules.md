\
# AI Agent Rules — Midtrans Payment Gateway

## Purpose

Dokumen ini berisi aturan wajib bagi AI agent ketika membuat atau mengubah integrasi Midtrans.

---

# Read Order

Sebelum membuat kode payment gateway, baca:

1. `project-overview.md`
2. `business-rules.md`
3. `backend-context.md`
4. `backend-routes-and-api.md`
5. `database-schema.md`
6. `database-environment-strategy.md`
7. `payment-gateway-midtrans.md`
8. `payment-midtrans-routes-and-api.md`
9. `payment-midtrans-config-and-testing.md`
10. File ini.

---

# Required Integration Style

Gunakan:

```text
Midtrans Snap
```

Jangan mengganti menjadi Core API kecuali diminta secara eksplisit.

Gunakan:

```text
composer require midtrans/midtrans-php
```

---

# Mandatory Rules

## Payment Creation

1. User memilih sub layanan.
2. Backend mengambil harga dari database.
3. Backend membuat consultation `waiting_payment`.
4. Backend membuat payment lokal.
5. Backend membuat `order_id` unik.
6. Backend membuat Snap token.
7. Backend menyimpan Snap token.
8. Frontend membuka Snap.

## Payment Confirmation

1. Jangan mempercayai callback frontend.
2. Jangan mengaktifkan chat dari JavaScript callback.
3. Gunakan webhook sebagai sumber utama.
4. Gunakan Get Status API sebagai verifikasi tambahan atau fallback development.
5. Verifikasi signature webhook.
6. Gunakan database transaction.
7. Pastikan proses idempotent.

## Security

1. Jangan expose Server Key.
2. Jangan commit `.env`.
3. Jangan hardcode key.
4. Jangan log credential.
5. Jangan percaya nominal dari frontend.
6. Jangan memperbarui paid status tanpa verifikasi backend.
7. Gunakan HTTPS untuk production.
8. Escape output HTML.
9. Gunakan prepared statement.
10. Validasi ownership payment dan consultation.

---

# Required Files

Gunakan struktur minimal:

```text
app/
├── Controllers/
│   ├── PaymentController.php
│   └── MidtransWebhookController.php
│
├── Services/
│   ├── PaymentService.php
│   └── MidtransService.php
│
└── Models/
    ├── Payment.php
    └── Consultation.php

config/
└── payment.php

routes/
└── payment.php
```

---

# Required Internal Routes

```text
GET  /user/consultations/{consultationId}/payment
POST /user/consultations/{consultationId}/payment
GET  /user/payments/{paymentId}
POST /user/payments/{paymentId}/refresh-status

POST /payments/midtrans/notification

GET /payments/midtrans/finish
GET /payments/midtrans/unfinish
GET /payments/midtrans/error
```

---

# Required Database Fields

Tabel `payments` minimal memiliki:

```text
id
user_id
consultation_id
sub_service_id
order_id
amount
provider
snap_token
payment_type
transaction_id
transaction_status
internal_status
fraud_status
paid_at
created_at
updated_at
```

## Important Rule

`amount` adalah snapshot harga saat transaksi dibuat.

Jangan membaca harga terbaru dari sub layanan untuk mengubah transaksi lama.

---

# Required Webhook Handling

Webhook handler harus:

1. Membaca body JSON.
2. Memastikan field penting tersedia.
3. Mencari payment berdasarkan `order_id`.
4. Menghitung signature.
5. Membandingkan menggunakan `hash_equals()`.
6. Memetakan status.
7. Memperbarui payment.
8. Mengaktifkan consultation jika paid.
9. Tidak membuat data duplikat.
10. Mengembalikan HTTP 200 jika sukses.

---

# Required Status Mapping

Gunakan:

| Midtrans | Internal |
|---|---|
| `pending` | `pending` |
| `capture` + `fraud_status=accept` | `paid` |
| `settlement` | `paid` |
| `deny` | `failed` |
| `cancel` | `failed` |
| `expire` | `expired` |
| `refund` | `refunded` |
| `partial_refund` | `refunded` |

---

# Do Not Build Yet

Jangan membuat fitur berikut kecuali diminta:

1. Refund otomatis.
2. Scheduler rekonsiliasi kompleks.
3. Multi-provider abstraction kompleks.
4. Promo.
5. Kupon.
6. Subscription.
7. Invoice otomatis.
8. Partial payment.
9. Websocket payment tracking.
10. Settlement reporting kompleks.

---

# Code Quality Rules

1. Controller tipis.
2. Business logic di service.
3. Query di model.
4. Config terpusat.
5. Route terpisah.
6. Gunakan naming konsisten.
7. Gunakan early return.
8. Hindari nested condition panjang.
9. Hindari fungsi terlalu panjang.
10. Tambahkan komentar hanya jika menjelaskan alasan.

---

# Documentation Update Rules

Jika mengubah integrasi Midtrans:

1. Perbarui `payment-gateway-midtrans.md`.
2. Perbarui `payment-midtrans-routes-and-api.md` jika route berubah.
3. Perbarui `payment-midtrans-config-and-testing.md` jika env berubah.
4. Perbarui `database-schema.md` jika field tabel berubah.
5. Perbarui `current-progress.md`.
6. Tambahkan catatan ke `changelog.md`.

---

# Final Rule

Integrasi versi awal harus tetap sederhana:

```text
Backend create Snap token
Frontend open Snap
Midtrans send webhook
Backend verify signature
Backend update payment
Backend activate consultation
User access chat
```
