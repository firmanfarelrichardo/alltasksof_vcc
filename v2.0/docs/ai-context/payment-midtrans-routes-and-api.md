\
# Payment Routes and Midtrans API Endpoints

## Purpose

Dokumen ini menjadi sumber kebenaran route pembayaran internal dan endpoint eksternal Midtrans untuk sistem `v2.0`.

---

# Internal Application Routes

File:

```text
routes/payment.php
```

## User Payment Routes

Middleware:

```text
AuthMiddleware
ApprovedUserMiddleware
```

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/user/consultations/{consultationId}/payment` | `PaymentController` | `show()` | Menampilkan halaman pembayaran |
| POST | `/user/consultations/{consultationId}/payment` | `PaymentController` | `create()` | Membuat payment lokal dan Snap token |
| GET | `/user/payments/{paymentId}` | `PaymentController` | `showStatus()` | Menampilkan status pembayaran |
| GET | `/api/user/payments/{paymentId}/status` | `PaymentController` | `statusApi()` | Poll status pembayaran internal |
| POST | `/user/payments/{paymentId}/refresh-status` | `PaymentController` | `refreshStatus()` | Meminta backend mengecek status ke Midtrans |

## Midtrans Webhook Route

Middleware:

```text
No login session required
Signature verification required
```

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| POST | `/payments/midtrans/notification` | `MidtransWebhookController` | `notification()` | Menerima webhook Midtrans |

## Redirect Routes

| Method | URI | Controller | Action | Purpose |
|---|---|---|---|---|
| GET | `/payments/midtrans/finish` | `PaymentController` | `finish()` | Redirect setelah pembayaran selesai |
| GET | `/payments/midtrans/unfinish` | `PaymentController` | `unfinish()` | Redirect jika pembayaran belum selesai |
| GET | `/payments/midtrans/error` | `PaymentController` | `error()` | Redirect jika pembayaran gagal |

---

# Suggested Route Registration

```php
<?php

$router->group('/user', ['auth', 'approved'], function ($router) {
    $router->get(
        '/consultations/{consultationId}/payment',
        [PaymentController::class, 'show']
    );

    $router->post(
        '/consultations/{consultationId}/payment',
        [PaymentController::class, 'create']
    );

    $router->get(
        '/payments/{paymentId}',
        [PaymentController::class, 'showStatus']
    );

    $router->post(
        '/payments/{paymentId}/refresh-status',
        [PaymentController::class, 'refreshStatus']
    );
});

$router->post(
    '/payments/midtrans/notification',
    [MidtransWebhookController::class, 'notification']
);

$router->get(
    '/payments/midtrans/finish',
    [PaymentController::class, 'finish']
);

$router->get(
    '/payments/midtrans/unfinish',
    [PaymentController::class, 'unfinish']
);

$router->get(
    '/payments/midtrans/error',
    [PaymentController::class, 'error']
);
```

---

# External Midtrans Endpoints

## Snap Create Transaction Token

### Sandbox

```text
POST https://app.sandbox.midtrans.com/snap/v1/transactions
```

### Production

```text
POST https://app.midtrans.com/snap/v1/transactions
```

### Auth

```text
Authorization: Basic Base64(ServerKey + ":")
```

### Required Body

```json
{
  "transaction_details": {
    "order_id": "CONSULT-125-1718000000",
    "gross_amount": 150000
  }
}
```

---

# Snap JS Script

## Sandbox

```text
https://app.sandbox.midtrans.com/snap/snap.js
```

## Production

```text
https://app.midtrans.com/snap/snap.js
```

Gunakan Client Key pada attribute:

```html
data-client-key="YOUR_CLIENT_KEY"
```

---

# Midtrans Get Status Endpoint

## Sandbox

```text
GET https://api.sandbox.midtrans.com/v2/{order_id}/status
```

## Production

```text
GET https://api.midtrans.com/v2/{order_id}/status
```

## Auth

```text
Authorization: Basic Base64(ServerKey + ":")
```

---

# Webhook Notification Configuration

Atur pada dashboard Midtrans:

```text
SETTINGS > CONFIGURATION
```

Gunakan:

```text
Payment Notification URL:
https://YOUR_PUBLIC_DOMAIN/payments/midtrans/notification

Finish Redirect URL:
https://YOUR_PUBLIC_DOMAIN/payments/midtrans/finish

Unfinished Redirect URL:
https://YOUR_PUBLIC_DOMAIN/payments/midtrans/unfinish

Error Redirect URL:
https://YOUR_PUBLIC_DOMAIN/payments/midtrans/error
```

Gunakan HTTPS pada production.

---

# Internal Payment API Response

## Success Example

```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dibuat.",
  "data": {
    "payment_id": 125,
    "order_id": "CONSULT-125-1718000000",
    "snap_token": "SNAP_TOKEN",
    "payment_status": "pending"
  }
}
```

## Status Example

```json
{
  "success": true,
  "data": {
    "payment_id": 125,
    "order_id": "CONSULT-125-1718000000",
    "payment_status": "paid",
    "consultation_status": "active"
  }
}
```

---

# Webhook Response

Jika webhook valid dan berhasil diproses:

```text
HTTP 200
```

Body sederhana:

```json
{
  "success": true
}
```

Jika signature tidak valid:

```text
HTTP 403
```

Jika order tidak ditemukan:

```text
HTTP 404
```

Jika terjadi error server:

```text
HTTP 500
```

---

# Route Rules

1. Webhook route tidak memakai login session.
2. Webhook route wajib memakai signature verification.
3. Route user wajib memeriksa ownership payment.
4. Route refresh status wajib memeriksa ownership payment.
5. Route finish tidak boleh langsung mengubah status payment menjadi paid.
6. Redirect frontend bukan sumber kebenaran status transaksi.
7. Semua update status dilakukan melalui backend.
