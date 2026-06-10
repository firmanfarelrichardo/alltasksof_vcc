# Midtrans Sandbox Setup Guide

## Tujuan

Dokumen ini adalah panduan konfigurasi agar Phase 7 - Midtrans Sandbox Integration berjalan benar pada environment lokal `v2.0`.

Gunakan dokumen ini setelah akun Midtrans Sandbox tersedia.

---

## Prinsip Wajib

1. Gunakan Sandbox terlebih dahulu.
2. Jangan isi Production key pada environment lokal.
3. Jangan commit `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY`.
4. Jangan mempercayai callback frontend sebagai status pembayaran.
5. Status final payment hanya boleh berasal dari:
   - webhook Midtrans yang signature-nya valid;
   - refresh status backend melalui Get Status API.
6. Consultation hanya boleh aktif setelah `payments.internal_status = paid`.

---

## File yang Digunakan

Konfigurasi lokal:

```text
v2.0/.env
```

Template konfigurasi:

```text
v2.0/.env.example
```

Konfigurasi aplikasi:

```text
v2.0/config/payment.php
```

Route webhook:

```text
POST /payments/midtrans/notification
```

---

## 1. Ambil Sandbox Access Keys

Masuk ke dashboard Midtrans.

Pastikan sedang berada pada mode:

```text
Sandbox
```

Ambil key dari menu access key pada dashboard Midtrans:

```text
Server Key
Client Key
```

Format Sandbox biasanya diawali:

```text
SB-Mid-server-...
SB-Mid-client-...
```

Jika key tidak diawali `SB-Mid-`, kemungkinan itu Production key. Jangan gunakan untuk local development.

---

## 2. Isi `.env`

Edit:

```text
v2.0/.env
```

Gunakan pola berikut:

```env
PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox

MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SERVER_KEY=SB-Mid-server-ISI_DARI_DASHBOARD
MIDTRANS_CLIENT_KEY=SB-Mid-client-ISI_DARI_DASHBOARD

MIDTRANS_NOTIFICATION_URL=https://YOUR-TUNNEL-DOMAIN/alltasksof_vcc/v2.0/public/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/error
```

Untuk Laragon lokal saat ini, `APP_URL` sebaiknya tetap:

```env
APP_URL=http://localhost/alltasksof_vcc/v2.0/public
```

Jika aplikasi dijalankan dengan PHP built-in server atau server lain pada root `public/`:

```text
http://localhost:8000/
```

maka gunakan konfigurasi berikut:

```env
APP_URL=http://localhost:8000

PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox

MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SERVER_KEY=SB-Mid-server-ISI_DARI_DASHBOARD
MIDTRANS_CLIENT_KEY=SB-Mid-client-ISI_DARI_DASHBOARD

MIDTRANS_NOTIFICATION_URL=https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=http://localhost:8000/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=http://localhost:8000/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=http://localhost:8000/payments/midtrans/error
```

Catatan: `MIDTRANS_NOTIFICATION_URL` tidak boleh memakai `localhost`. Ganti `YOUR-TUNNEL-DOMAIN` dengan domain HTTPS dari tunnel yang mengarah ke `http://localhost:8000`.

## 2A. Contoh `.env` Jika Aplikasi Berjalan di `http://localhost:8000/`

Jika web root aplikasi sudah langsung mengarah ke `v2.0/public` dan aplikasi dibuka melalui:

```text
http://localhost:8000/
```

maka jangan gunakan path:

```text
/alltasksof_vcc/v2.0/public
```

Gunakan konfigurasi berikut:

```env
APP_URL=http://localhost:8000

PAYMENT_PROVIDER=midtrans
PAYMENT_MODE=sandbox

MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SERVER_KEY=ISI_SERVER_KEY_SANDBOX_DARI_DASHBOARD
MIDTRANS_CLIENT_KEY=ISI_CLIENT_KEY_SANDBOX_DARI_DASHBOARD

MIDTRANS_NOTIFICATION_URL=https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
MIDTRANS_FINISH_REDIRECT_URL=http://localhost:8000/payments/midtrans/finish
MIDTRANS_UNFINISH_REDIRECT_URL=http://localhost:8000/payments/midtrans/unfinish
MIDTRANS_ERROR_REDIRECT_URL=http://localhost:8000/payments/midtrans/error
```

Catatan:

1. Redirect URL boleh memakai `localhost:8000` saat testing di browser lokal.
2. Notification URL tidak boleh memakai `localhost:8000` karena webhook dikirim dari server Midtrans.
3. Notification URL harus memakai HTTPS tunnel yang mengarah ke `http://localhost:8000`.
4. Jika belum punya tunnel, kosongkan dulu `MIDTRANS_NOTIFICATION_URL` dan gunakan tombol `Refresh Status Backend` untuk testing awal.

---

## 3. Siapkan Public HTTPS Tunnel

Webhook Midtrans tidak bisa mengirim request ke:

```text
localhost
127.0.0.1
private IP
Tailscale-only URL
```

Untuk development lokal, gunakan tunnel HTTPS sementara.

Contoh target tunnel:

```text
http://localhost
```

Jika tunnel mengarah ke root Laragon `http://localhost`, maka notification URL harus memakai path lengkap:

```text
https://YOUR-TUNNEL-DOMAIN/alltasksof_vcc/v2.0/public/payments/midtrans/notification
```

Jika tunnel langsung diarahkan ke document root `v2.0/public`, maka path cukup:

```text
https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
```

Untuk setup `http://localhost:8000/`, gunakan pola ini karena aplikasi sudah berada pada root web:

```text
https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
```

Untuk aplikasi yang berjalan di `http://localhost:8000/`, gunakan pola ini:

```text
https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification
```

Pilih salah satu pola dan samakan dengan `MIDTRANS_NOTIFICATION_URL`.

Jangan commit URL tunnel sementara.

---

## 4. Atur URL di Dashboard Midtrans

Pada konfigurasi payment notification atau redirection di dashboard Midtrans, isi:

```text
Payment Notification URL:
https://YOUR-TUNNEL-DOMAIN/alltasksof_vcc/v2.0/public/payments/midtrans/notification

Finish Redirect URL:
http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/finish

Unfinish Redirect URL:
http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/unfinish

Error Redirect URL:
http://localhost/alltasksof_vcc/v2.0/public/payments/midtrans/error
```

Jika memakai tunnel yang langsung mengarah ke `v2.0/public`, hilangkan bagian:

```text
/alltasksof_vcc/v2.0/public
```

Jika aplikasi berjalan di `http://localhost:8000/`, isi dashboard Midtrans seperti ini:

```text
Payment Notification URL:
https://YOUR-TUNNEL-DOMAIN/payments/midtrans/notification

Finish Redirect URL:
http://localhost:8000/payments/midtrans/finish

Unfinish Redirect URL:
http://localhost:8000/payments/midtrans/unfinish

Error Redirect URL:
http://localhost:8000/payments/midtrans/error
```

---

## 5. Pastikan Dependency Terpasang

Dari folder:

```text
v2.0/
```

Jalankan:

```bash
composer install
```

Atau jika package belum ada:

```bash
composer require midtrans/midtrans-php
```

Verifikasi:

```bash
composer show midtrans/midtrans-php
```

Versi yang sudah pernah diuji pada proyek ini:

```text
2.6.2
```

---

## 6. Jalankan Flow User

Gunakan akun user seed yang sudah approved:

```text
user.approved@example.local
password
```

Langkah uji:

1. Login sebagai user approved.
2. Buka halaman layanan atau pricing.
3. Pilih salah satu sub layanan.
4. Sistem membuat consultation dengan status:

```text
waiting_payment
```

5. Buka halaman pembayaran.
6. Klik:

```text
Buat Pembayaran Midtrans
```

7. Jika konfigurasi benar, payment lokal dibuat dan Snap token tersimpan.
8. Klik:

```text
Bayar dengan Snap
```

9. Snap popup Sandbox harus muncul.

---

## 7. Simulasikan Pembayaran Sandbox

Selesaikan pembayaran menggunakan simulator Sandbox Midtrans.

Pola umum:

1. Pilih metode pembayaran di Snap popup.
2. Ambil instruksi pembayaran atau virtual account dari Snap.
3. Gunakan simulator Sandbox Midtrans sesuai metode tersebut.
4. Setelah simulasi berhasil, Midtrans akan mengirim webhook ke notification URL.

Jangan mengubah status payment secara manual dari frontend.

---

## 8. Verifikasi Database

Setelah transaksi dibuat, cek payment:

```sql
SELECT
    id,
    consultation_id,
    order_id,
    amount,
    provider,
    snap_token,
    transaction_status,
    fraud_status,
    internal_status,
    paid_at
FROM payments
ORDER BY id DESC
LIMIT 5;
```

Saat Snap token berhasil dibuat:

```text
provider = midtrans
snap_token tidak null
internal_status = pending
```

Saat payment sukses:

```text
transaction_status = settlement
internal_status = paid
paid_at tidak null
```

Cek consultation:

```sql
SELECT
    id,
    user_id,
    sub_service_id,
    status,
    created_at,
    updated_at
FROM consultations
ORDER BY id DESC
LIMIT 5;
```

Jika payment sukses:

```text
consultations.status = active
```

---

## 9. Jika Webhook Belum Masuk

Gunakan tombol:

```text
Refresh Status Backend
```

Tombol ini memanggil backend, lalu backend memanggil Get Status API Midtrans.

Refresh status boleh digunakan untuk development lokal ketika webhook tunnel terlambat atau belum stabil.

Tetap ingat:

```text
Frontend callback bukan sumber kebenaran.
```

---

## 10. Checklist Phase 7 Berhasil

- [ ] `.env` memakai Sandbox Server Key.
- [ ] `.env` memakai Sandbox Client Key.
- [ ] `MIDTRANS_IS_PRODUCTION=false`.
- [ ] Composer package `midtrans/midtrans-php` terpasang.
- [ ] User bisa membuat consultation `waiting_payment`.
- [ ] Backend bisa membuat payment lokal `midtrans`.
- [ ] `payments.snap_token` terisi.
- [ ] Snap popup muncul.
- [ ] Nominal Snap sesuai harga sub layanan di database.
- [ ] Webhook Midtrans masuk ke backend.
- [ ] Signature webhook valid.
- [ ] `pending` tetap `waiting_payment`.
- [ ] `cancel` tidak mengaktifkan consultation.
- [ ] `settlement` mengubah payment menjadi `paid`.
- [ ] `settlement` mengubah consultation menjadi `active`.
- [ ] Duplicate webhook tidak membuat payment atau consultation baru.
- [ ] Admin pipeline menampilkan order ID dan status payment.

---

## Troubleshooting

### Snap token gagal dibuat

Cek:

1. `MIDTRANS_SERVER_KEY` sudah diisi.
2. Key yang dipakai adalah Sandbox Server Key.
3. `MIDTRANS_IS_PRODUCTION=false`.
4. Composer dependency sudah terpasang.
5. Koneksi internet lokal tersedia.

### Snap popup tidak muncul

Cek:

1. `MIDTRANS_CLIENT_KEY` sudah diisi.
2. Key yang dipakai adalah Sandbox Client Key.
3. Browser tidak memblokir script:

```text
https://app.sandbox.midtrans.com/snap/snap.js
```

### Webhook tidak masuk

Cek:

1. Tunnel HTTPS sedang aktif.
2. Notification URL di dashboard Midtrans benar.
3. Path URL sesuai target tunnel.
4. Route berikut bisa diakses publik:

```text
POST /payments/midtrans/notification
```

5. Jangan arahkan webhook ke `localhost`.

### Webhook masuk tetapi status tidak berubah

Cek:

1. `order_id` dari payload Midtrans ada di tabel `payments`.
2. `gross_amount` sama dengan `payments.amount`.
3. Server Key di `.env` sama dengan Server Key Sandbox yang digunakan transaksi.
4. Signature verification tidak gagal.

### Payment success tetapi consultation belum active

Cek:

```sql
SELECT
    p.order_id,
    p.transaction_status,
    p.fraud_status,
    p.internal_status,
    c.status AS consultation_status
FROM payments p
JOIN consultations c ON c.id = p.consultation_id
ORDER BY p.id DESC
LIMIT 5;
```

Status yang mengaktifkan consultation:

```text
settlement
capture + fraud_status accept
```

Status yang tidak boleh mengaktifkan consultation:

```text
pending
cancel
deny
expire
refund
partial_refund
```

---

## Catatan Keamanan

1. `MIDTRANS_SERVER_KEY` hanya boleh ada di backend.
2. `MIDTRANS_CLIENT_KEY` boleh dipakai oleh Snap JS.
3. Jangan log Server Key.
4. Jangan commit `.env`.
5. Jangan screenshot dashboard access key ke dokumentasi.
6. Jangan ubah payment menjadi paid dari JavaScript.
7. Jangan membuat endpoint manual untuk menandai payment paid.

---

## Setelah Sandbox Berhasil

Berhenti di Sandbox sampai seluruh flow ini stabil:

```text
create consultation
create payment
open Snap
receive webhook
verify signature
update payment
activate consultation
show admin pipeline
```

Production Midtrans baru boleh dibahas setelah Sandbox end-to-end selesai.
