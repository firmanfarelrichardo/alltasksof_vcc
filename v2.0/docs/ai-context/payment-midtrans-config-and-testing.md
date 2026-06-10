\
# Midtrans Configuration and Testing Strategy

## Purpose

Dokumen ini menjelaskan konfigurasi Sandbox, Production, local development, webhook testing, dan aturan environment untuk Midtrans.

---

# Development Strategy

Gunakan dua tahap:

```text
Tahap 1: Sandbox Midtrans + aplikasi lokal
Tahap 2: Production Midtrans + server publik
```

Jangan langsung menggunakan Production sebelum semua flow pembayaran diuji di Sandbox.

---

# Environment Variables

Gunakan `.env`.

## `.env.example`

```env
PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox

MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=

MIDTRANS_NOTIFICATION_URL=
MIDTRANS_FINISH_REDIRECT_URL=
MIDTRANS_UNFINISH_REDIRECT_URL=
MIDTRANS_ERROR_REDIRECT_URL=
```

## Local Sandbox Example

```env
PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox

MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SERVER_KEY=SB-Mid-server-REPLACE_ME
MIDTRANS_CLIENT_KEY=SB-Mid-client-REPLACE_ME

MIDTRANS_NOTIFICATION_URL=https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=http://localhost/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=http://localhost/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=http://localhost/payments/midtrans/error
```

## Production Example

```env
PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=production

MIDTRANS_IS_PRODUCTION=true
MIDTRANS_SERVER_KEY=Mid-server-REPLACE_ME
MIDTRANS_CLIENT_KEY=Mid-client-REPLACE_ME

MIDTRANS_NOTIFICATION_URL=https://YOUR-PUBLIC-DOMAIN/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=https://YOUR-PUBLIC-DOMAIN/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=https://YOUR-PUBLIC-DOMAIN/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=https://YOUR-PUBLIC-DOMAIN/payments/midtrans/error
```

---

# Config File

File:

```text
config/payment.php
```

Contoh:

```php
<?php

return [
    'provider' => getenv('PAYMENT_PROVIDER') ?: 'midtrans',
    'mode' => getenv('PAYMENT_MODE') ?: 'sandbox',

    'midtrans' => [
        'is_production' => getenv('MIDTRANS_IS_PRODUCTION') === 'true',
        'server_key' => getenv('MIDTRANS_SERVER_KEY') ?: '',
        'client_key' => getenv('MIDTRANS_CLIENT_KEY') ?: '',

        'notification_url' => getenv('MIDTRANS_NOTIFICATION_URL') ?: '',
        'finish_redirect_url' => getenv('MIDTRANS_FINISH_REDIRECT_URL') ?: '',
        'unfinish_redirect_url' => getenv('MIDTRANS_UNFINISH_REDIRECT_URL') ?: '',
        'error_redirect_url' => getenv('MIDTRANS_ERROR_REDIRECT_URL') ?: '',
    ],
];
```

---

# Access Keys

Ambil Client Key dan Server Key pada Dashboard Midtrans:

```text
SETTINGS > ACCESS KEYS
```

Sandbox key dan Production key berbeda.

## Security Rule

### Server Key

Server Key:

1. Rahasia.
2. Hanya untuk backend.
3. Tidak boleh tampil pada HTML.
4. Tidak boleh dikirim ke JavaScript.
5. Tidak boleh masuk repository.
6. Tidak boleh masuk screenshot dokumentasi.

### Client Key

Client Key:

1. Digunakan oleh Snap JS.
2. Dapat dipasang di frontend.
3. Tetap simpan di `.env` agar konfigurasi terpusat.

---

# Sandbox Testing

Midtrans menyediakan Sandbox untuk simulasi pembayaran.

Gunakan Sandbox untuk menguji:

1. Snap token berhasil dibuat.
2. Popup Snap muncul.
3. User dapat memilih metode pembayaran.
4. Status payment berubah.
5. Webhook diterima.
6. Signature webhook valid.
7. Payment lokal berubah menjadi `paid`.
8. Consultation berubah menjadi `active`.
9. Chat dapat dibuka setelah pembayaran valid.
10. Chat tetap terkunci jika pembayaran belum valid.

---

# Important Webhook Limitation

Webhook Midtrans harus dapat diakses melalui public internet.

Webhook tidak dapat dikirim ke:

```text
localhost
127.0.0.1
private Tailscale IP
VPN-only domain
URL dengan basic auth
URL yang membutuhkan session login
```

## Local Testing Options

### Option A — Public HTTPS Tunnel

Gunakan tunnel sementara menuju aplikasi lokal.

Contoh konsep:

```text
Public HTTPS Tunnel
        |
        v
localhost
        |
        v
/payments/midtrans/notification
```

Jangan commit URL tunnel sementara.

### Option B — Get Status API

Jika webhook belum diuji:

1. Buat transaksi Sandbox.
2. Selesaikan simulasi pembayaran.
3. Klik refresh status.
4. Backend memanggil Get Status API.
5. Backend memperbarui database.
6. Konsultasi aktif jika status valid.

## Important Rule

Option B membantu development, tetapi production tetap harus memiliki webhook publik.

---

# Tailscale Note

Database boleh berada pada MySQL Ubuntu Server melalui Tailscale.

Namun:

```text
Database remote Tailscale != Webhook public endpoint
```

Webhook Midtrans tetap harus diarahkan ke backend website yang dapat diakses publik.

Arsitektur akhir:

```text
Midtrans
   |
   | Public HTTPS Webhook
   v
Public Web Server / Backend PHP
   |
   | Private Tailscale Connection
   v
MySQL Ubuntu Server
```

---

# Sandbox Checklist

- [ ] Akun Sandbox Midtrans tersedia.
- [ ] Sandbox Server Key tersedia.
- [ ] Sandbox Client Key tersedia.
- [ ] Composer package terpasang.
- [ ] Snap token berhasil dibuat.
- [ ] Popup Snap tampil.
- [ ] Payment lokal tersimpan.
- [ ] `order_id` unik.
- [ ] Nominal menggunakan harga database.
- [ ] Webhook tunnel dapat menerima POST.
- [ ] Signature verification berhasil.
- [ ] Status `pending` berhasil diproses.
- [ ] Status `settlement` berhasil diproses.
- [ ] Status gagal tidak mengaktifkan konsultasi.
- [ ] Refresh status bekerja.
- [ ] Chat terkunci sebelum paid.
- [ ] Chat terbuka setelah paid.

---

# Production Migration Checklist

- [ ] Merchant Production Midtrans sudah siap.
- [ ] Production Server Key tersedia.
- [ ] Production Client Key tersedia.
- [ ] `MIDTRANS_IS_PRODUCTION=true`.
- [ ] Snap script menggunakan domain production.
- [ ] Backend memakai HTTPS.
- [ ] Notification URL publik dapat diakses.
- [ ] Redirect URL production diatur.
- [ ] `.env` production tidak di-commit.
- [ ] Debug mode dimatikan.
- [ ] Webhook signature diverifikasi.
- [ ] Log tidak menyimpan key.
- [ ] Database backup tersedia.
- [ ] Payment dengan nominal kecil diuji.
- [ ] Consultation hanya aktif setelah status valid.
