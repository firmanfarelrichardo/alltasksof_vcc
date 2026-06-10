\
# Admin Consultation Pipeline

## Purpose

Dokumen ini menjelaskan tampilan daftar konsultasi pada sisi admin.

Daftar konsultasi admin tidak hanya menampilkan percakapan konsultasi yang sudah aktif. Admin juga perlu melihat perjalanan pengguna sejak registrasi hingga transaksi berhasil.

Konsep ini disebut:

```text
Admin Consultation Pipeline
```

---

# Main Requirement

Pada sisi admin, tampilkan data berikut:

1. Pengguna yang sudah mendaftar.
2. Pengguna yang memiliki transaksi pembayaran berstatus `pending`.
3. Pengguna yang memiliki transaksi pembayaran berstatus `cancel`.
4. Pengguna yang memiliki transaksi pembayaran berstatus `success`.
5. Pengguna yang sudah memiliki ruang konsultasi aktif.
6. Riwayat konsultasi yang sudah selesai.

---

# Important Data Separation

Jangan mencampur tiga jenis status berikut:

## 1. Account Status

Status akun pengguna:

```text
pending
approved
rejected
inactive
```

Status ini menjelaskan apakah pengguna boleh login.

## 2. Payment Status

Status pembayaran:

```text
pending
cancel
success
failed
expired
refunded
```

Status ini menjelaskan kondisi transaksi pembayaran.

## 3. Consultation Status

Status konsultasi:

```text
waiting_payment
active
closed
cancelled
```

Status ini menjelaskan kondisi sesi konsultasi.

---

# Admin Pipeline Stages

Gunakan tahap berikut pada dashboard admin.

| Pipeline Stage | Meaning | Source |
|---|---|---|
| `registered` | Pengguna sudah registrasi tetapi belum memiliki transaksi | `users` |
| `payment_pending` | Pengguna sudah membuat transaksi tetapi pembayaran belum selesai | `payments` |
| `payment_cancelled` | Transaksi dibatalkan | `payments` |
| `payment_success` | Pembayaran berhasil | `payments` |
| `consultation_active` | Chat konsultasi sudah dapat digunakan | `consultations` |
| `consultation_closed` | Konsultasi sudah selesai | `consultations` |

---

# Recommended Dashboard Tabs

Pada dashboard admin, sediakan tab:

```text
Semua
Terdaftar
Menunggu Pembayaran
Pembayaran Dibatalkan
Pembayaran Berhasil
Konsultasi Aktif
Riwayat Selesai
```

Gunakan badge jumlah data pada setiap tab jika memungkinkan.

Contoh:

```text
Terdaftar (14)
Menunggu Pembayaran (4)
Pembayaran Dibatalkan (2)
Pembayaran Berhasil (8)
Konsultasi Aktif (5)
Riwayat Selesai (3)
```

---

# Registered User Visibility

## Rule

Pengguna yang baru registrasi dan belum memilih sub layanan tetap ditampilkan pada sisi admin sebagai:

```text
Registered User
```

Karena pengguna tersebut belum terhubung dengan layanan tertentu, data ini ditampilkan kepada admin sebagai informasi umum read-only.

## Visible Fields

Tampilkan hanya data minimum:

1. Nama.
2. Email.
3. Status akun.
4. Tanggal registrasi.
5. Status pipeline: `registered`.

## Restrictions

Admin tidak boleh:

1. Approve akun.
2. Reject akun.
3. Mengedit akun pengguna.
4. Menghapus akun pengguna.
5. Melihat password.
6. Melihat data sensitif yang tidak diperlukan.

Approval tetap menjadi kewenangan superadmin.

---

# Service-Scoped Visibility

Untuk transaksi dan konsultasi, admin hanya boleh melihat data yang terkait dengan layanan yang ditugaskan kepadanya.

Gunakan relasi:

```text
admin_service_assignments
```

Flow:

```text
Admin
  |
admin_service_assignments
  |
service_categories
  |
sub_services
  |
payments / consultations
```

## Rule

1. Data registrasi umum bersifat read-only.
2. Data transaksi dibatasi berdasarkan layanan admin.
3. Data konsultasi dibatasi berdasarkan layanan admin.
4. Superadmin dapat melihat seluruh data.

---

# Payment Display Status

Gunakan status tampilan yang sederhana pada UI admin.

| Internal / Midtrans Source | Admin UI Label | Badge |
|---|---|---|
| Belum memiliki transaksi | `Terdaftar` | Neutral |
| Internal `pending` | `Menunggu Pembayaran` | Warning |
| Midtrans `cancel` | `Pembayaran Dibatalkan` | Danger |
| Internal `paid` | `Pembayaran Berhasil` | Success |
| Consultation `active` | `Konsultasi Aktif` | Info |
| Consultation `closed` | `Konsultasi Selesai` | Neutral |

## Important Rule

Jangan menggunakan label `success` sebagai status database utama.

Gunakan:

```text
paid
```

sebagai status internal database.

Gunakan:

```text
Pembayaran Berhasil
```

sebagai label UI.

---

# Admin Consultation List Columns

Gunakan tabel dengan kolom:

| Column | Description |
|---|---|
| User | Nama pengguna |
| Email | Email pengguna |
| Service | Kategori layanan |
| Sub Service | Sublayanan |
| Account Status | Status akun |
| Payment Status | Status pembayaran |
| Consultation Status | Status konsultasi |
| Amount | Nominal pembayaran |
| Registered At | Tanggal registrasi |
| Updated At | Perubahan terakhir |
| Action | Detail atau buka chat |

---

# Action Rules

## Registered

Action:

```text
Lihat Detail
```

Tidak ada tombol chat.

## Pending Payment

Action:

```text
Lihat Detail
```

Tidak ada tombol chat.

## Cancelled Payment

Action:

```text
Lihat Detail
```

Tidak ada tombol chat.

## Successful Payment

Action:

```text
Buka Konsultasi
```

Chat aktif setelah status konsultasi menjadi `active`.

## Closed Consultation

Action:

```text
Lihat Riwayat
```

---

# Filter Rules

Sediakan filter sederhana:

1. Search nama atau email.
2. Filter status pipeline.
3. Filter kategori layanan.
4. Filter sub layanan.
5. Filter tanggal registrasi atau transaksi.

Jangan menambahkan filter kompleks pada versi awal.

---

# Sort Rules

Urutan default:

```text
updated_at DESC
```

Artinya data terbaru atau data yang baru berubah tampil lebih dahulu.

---

# UI Rules

Dashboard admin mengikuti gaya clean productivity dashboard.

Gunakan:

1. Sidebar kiri.
2. Topbar.
3. Summary cards.
4. Tabs pipeline.
5. Tabel data.
6. Status badge.
7. Search sederhana.
8. Filter sederhana.
9. Pagination.

Jangan membuat board terlalu kompleks pada versi awal.

---

# Summary Cards

Tampilkan summary card:

1. Total pengguna terdaftar.
2. Menunggu pembayaran.
3. Pembayaran dibatalkan.
4. Pembayaran berhasil.
5. Konsultasi aktif.
6. Konsultasi selesai.

---

# Privacy Rule

Admin hanya boleh melihat informasi minimum pengguna.

Jangan tampilkan:

1. Password hash.
2. Session token.
3. Midtrans Server Key.
4. Raw webhook signature.
5. Data sensitif yang tidak diperlukan.
6. Informasi pembayaran rahasia.

---

# Final Rule

Daftar admin harus menggambarkan perjalanan pengguna:

```text
Registrasi
   |
Pilih sub layanan
   |
Pembayaran pending
   |
Pembayaran cancel atau success
   |
Konsultasi aktif
   |
Konsultasi selesai
```
