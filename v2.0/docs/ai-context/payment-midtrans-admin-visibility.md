\
# Midtrans Payment Visibility on Admin Dashboard

## Purpose

Dokumen ini menjelaskan bagaimana transaksi Midtrans ditampilkan pada dashboard admin.

---

# Main Requirement

Admin perlu melihat pengguna yang:

1. Sudah mendaftar.
2. Sudah membuat transaksi pembayaran.
3. Masih berstatus pending.
4. Membatalkan pembayaran.
5. Berhasil membayar.
6. Sudah masuk ke konsultasi aktif.
7. Sudah menyelesaikan konsultasi.

---

# Midtrans Raw Status and UI Labels

Webhook Midtrans menyimpan status asli pada:

```text
payments.transaction_status
```

Status internal aplikasi disimpan pada:

```text
payments.internal_status
```

Label UI admin ditentukan terpisah.

| Midtrans Raw Status | Internal Status | Admin UI Label |
|---|---|---|
| `pending` | `pending` | Menunggu Pembayaran |
| `settlement` | `paid` | Pembayaran Berhasil |
| `capture` dengan fraud accepted | `paid` | Pembayaran Berhasil |
| `cancel` | `failed` atau `cancelled` | Pembayaran Dibatalkan |
| `deny` | `failed` | Pembayaran Gagal |
| `expire` | `expired` | Pembayaran Kedaluwarsa |
| `refund` | `refunded` | Refund |
| `partial_refund` | `refunded` | Refund Sebagian |

---

# Important Rule

Walaupun tampilan pengguna meminta status:

```text
pending
cancel
success
```

database tetap harus menyimpan:

1. Raw status Midtrans.
2. Status internal aplikasi.
3. Label UI ditentukan melalui mapping.

Jangan mengganti status database menjadi label UI secara langsung.

---

# Admin View Rule

Pada daftar admin:

## Payment Pending

Tampilkan:

1. Nama pengguna.
2. Sublayanan.
3. Nominal.
4. Order ID.
5. Waktu transaksi.
6. Status: `Menunggu Pembayaran`.

## Payment Cancelled

Tampilkan:

1. Nama pengguna.
2. Sublayanan.
3. Nominal.
4. Order ID.
5. Waktu dibatalkan.
6. Status: `Pembayaran Dibatalkan`.

## Payment Success

Tampilkan:

1. Nama pengguna.
2. Sublayanan.
3. Nominal.
4. Order ID.
5. Waktu pembayaran.
6. Status: `Pembayaran Berhasil`.
7. Tombol menuju ruang konsultasi jika consultation aktif.

---

# Webhook Rule

Webhook tetap menjadi sumber kebenaran utama status transaksi.

Midtrans mengirim notifikasi HTTP saat status transaksi berubah.

Backend harus:

1. Menerima webhook.
2. Memverifikasi signature.
3. Memperbarui raw status.
4. Memperbarui internal status.
5. Mengaktifkan konsultasi jika payment valid.
6. Membuat data terlihat pada tab admin yang sesuai.

---

# Cancel Rule

Jika Midtrans mengirim:

```text
transaction_status = cancel
```

maka:

1. Simpan raw status `cancel`.
2. Ubah internal status menjadi `failed` atau `cancelled` sesuai keputusan schema.
3. Tampilkan label UI `Pembayaran Dibatalkan`.
4. Jangan aktifkan consultation.
5. Jangan buka akses chat.

---

# Success Rule

Jika status valid:

```text
settlement
```

atau:

```text
capture + fraud_status accept
```

maka:

1. Simpan raw status.
2. Ubah internal status menjadi `paid`.
3. Tampilkan label UI `Pembayaran Berhasil`.
4. Ubah consultation menjadi `active`.
5. Buka akses chat.

---

# Pending Rule

Jika status:

```text
pending
```

maka:

1. Simpan raw status.
2. Pertahankan internal status `pending`.
3. Tampilkan `Menunggu Pembayaran`.
4. Consultation tetap `waiting_payment`.
5. Chat tetap terkunci.

---

# Admin Scope Rule

Admin hanya melihat payment berdasarkan layanan yang ditugaskan.

Gunakan:

```text
admin_service_assignments
```

Superadmin dapat melihat seluruh payment.
