\
# Testing Checklist

## Purpose

Checklist ini digunakan untuk menguji MVP sebelum migrasi database ke Tailscale.

---

# 1. Foundation

- [ ] `GET /` berjalan.
- [ ] `.env` terbaca.
- [ ] Database lokal terhubung.
- [ ] Router menangani route.
- [ ] 404 berjalan.
- [ ] Error tidak membocorkan stack trace di mode production.

---

# 2. Database

- [ ] `schema.sql` dapat di-import.
- [ ] `seed.sql` dapat dijalankan.
- [ ] Superadmin tersedia.
- [ ] Tiga kategori layanan tersedia.
- [ ] Sub layanan tersedia.
- [ ] Admin assignment tersedia.
- [ ] Foreign key bekerja.
- [ ] Index tersedia.

---

# 3. Registration

- [ ] User dapat registrasi.
- [ ] Email wajib unik.
- [ ] Password di-hash.
- [ ] Status awal `pending`.
- [ ] Role awal `user`.
- [ ] Role tidak dapat dimanipulasi dari frontend.
- [ ] Status tidak dapat dimanipulasi dari frontend.

---

# 4. Login

- [ ] User pending tidak dapat login.
- [ ] User rejected tidak dapat login.
- [ ] User inactive tidak dapat login.
- [ ] User approved dapat login.
- [ ] Admin dapat login.
- [ ] Superadmin dapat login.
- [ ] Wrong password ditolak.
- [ ] Session diregenerasi setelah login.
- [ ] Logout menghapus session.

---

# 5. Authorization

- [ ] User tidak dapat membuka `/admin`.
- [ ] User tidak dapat membuka `/superadmin`.
- [ ] Admin tidak dapat membuka `/superadmin`.
- [ ] Admin hanya melihat assignment-nya.
- [ ] Admin tidak dapat membuka konsultasi layanan lain.
- [ ] User hanya melihat konsultasi miliknya.
- [ ] User hanya melihat payment miliknya.
- [ ] CSRF bekerja untuk form mutasi data.

---

# 6. Service Management

- [ ] Public melihat kategori layanan aktif.
- [ ] Public melihat sub layanan aktif.
- [ ] Public melihat harga.
- [ ] Superadmin dapat CRUD category.
- [ ] Superadmin dapat CRUD sub service.
- [ ] Superadmin dapat assign admin.
- [ ] Admin dapat edit sub service sesuai assignment.
- [ ] Admin tidak dapat edit sub service di luar assignment.
- [ ] Inactive sub service tidak tampil public.

---

# 7. Consultation Pipeline

- [ ] User memilih sub service.
- [ ] Consultation dibuat dengan `waiting_payment`.
- [ ] Payment dibuat `pending`.
- [ ] Admin melihat pending sesuai scope.
- [ ] Admin melihat cancelled sesuai scope.
- [ ] Admin melihat paid sesuai scope.
- [ ] Admin melihat active consultation.
- [ ] Admin melihat closed consultation.
- [ ] Superadmin melihat seluruh pipeline.
- [ ] Pagination berjalan.
- [ ] Filter berjalan.

---

# 8. Midtrans Sandbox

- [ ] Midtrans package terpasang.
- [ ] Sandbox key terbaca dari `.env`.
- [ ] Server Key tidak tampil frontend.
- [ ] Client Key digunakan Snap JS.
- [ ] Snap token berhasil dibuat.
- [ ] Popup tampil.
- [ ] `order_id` unik.
- [ ] Nominal berasal dari database.
- [ ] Pending tercatat.
- [ ] Cancel tercatat.
- [ ] Settlement menjadi paid.
- [ ] Capture accepted menjadi paid.
- [ ] Deny tidak aktifkan konsultasi.
- [ ] Expire tidak aktifkan konsultasi.
- [ ] Signature webhook diverifikasi.
- [ ] Webhook idempotent.
- [ ] Refresh status fallback bekerja.
- [ ] Consultation aktif hanya setelah paid.

---

# 9. Chat

- [ ] Chat locked sebelum paid.
- [ ] Chat aktif setelah consultation active.
- [ ] User dapat kirim pesan.
- [ ] Admin dapat balas pesan.
- [ ] Polling berjalan tiap 5 detik.
- [ ] `after_id` bekerja.
- [ ] Pesan kosong ditolak.
- [ ] Pesan hanya spasi ditolak.
- [ ] Pesan terlalu panjang ditolak.
- [ ] HTML di-escape.
- [ ] User lain tidak dapat membuka chat.
- [ ] Admin di luar assignment tidak dapat membuka chat.
- [ ] Closed consultation read-only.
- [ ] Cancelled consultation locked.

---

# 10. Frontend

- [ ] Landing page tampil.
- [ ] Dark mode default public.
- [ ] Dark mode default dashboard.
- [ ] Theme toggle tidak tersedia.
- [ ] Typography konsisten.
- [ ] Sidebar bekerja.
- [ ] Mobile sidebar bekerja.
- [ ] Table responsive.
- [ ] Badge status tampil.
- [ ] Empty state tampil.
- [ ] Loading state tampil.
- [ ] Animasi ringan dan tidak mengganggu.

---

# 11. Security

- [ ] `.env` tidak di-commit.
- [ ] Server Key tidak di-commit.
- [ ] DB password tidak di-commit.
- [ ] Prepared statement digunakan.
- [ ] Output di-escape.
- [ ] CSRF aktif.
- [ ] Session secure.
- [ ] Access forbidden memakai 403.
- [ ] Debug dimatikan production.
- [ ] Error detail masuk log.
- [ ] Password hash tidak ditampilkan.

---

# 12. Tailscale Migration

- [ ] Backup lokal dibuat.
- [ ] Ubuntu Server online.
- [ ] Tailscale online.
- [ ] MySQL remote berjalan.
- [ ] Dedicated MySQL user dibuat.
- [ ] Port 3306 dibatasi.
- [ ] Import database berhasil.
- [ ] `.env` remote diperbarui.
- [ ] Login berhasil.
- [ ] CRUD berhasil.
- [ ] Midtrans webhook berhasil.
- [ ] Chat berhasil.
- [ ] Pipeline berhasil.
- [ ] Finance berhasil.
