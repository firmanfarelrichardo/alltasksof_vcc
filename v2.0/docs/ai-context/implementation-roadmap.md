\
# Implementation Roadmap — Safe Execution Order

## Purpose

Dokumen ini menetapkan urutan eksekusi pembangunan sistem secara aman.

Jangan langsung membangun seluruh backend atau seluruh frontend sekaligus.

Gunakan:

```text
Vertical Slice Development
```

---

# Safe Execution Flow

```text
Kunci fondasi
→ buat database lokal
→ buat backend dasar
→ buat frontend dasar
→ kerjakan fitur per alur bisnis
→ integrasikan Midtrans
→ integrasikan chat
→ uji end-to-end
→ pindahkan database ke Tailscale
```

---

# Phase 0 — Lock Foundation

## Goal

Mengunci keputusan sebelum coding.

## Tasks

1. Baca seluruh `ai-context`.
2. Gunakan `final-folder-structure.md`.
3. Gunakan `final-database-schema.md`.
4. Gunakan `authentication-and-authorization.md`.
5. Gunakan `chat-consultation-spec.md`.
6. Gunakan `page-and-route-mapping.md`.
7. Buat `.gitignore`.
8. Buat `.env.example`.
9. Tentukan base URL lokal.

## Exit Criteria

- [ ] Struktur folder final disepakati.
- [ ] Schema final disepakati.
- [ ] Role final disepakati.
- [ ] Route final disepakati.
- [ ] Polling chat final disepakati.

---

# Phase 1 — Create Project Skeleton

## Goal

Membuat fondasi aplikasi.

## Tasks

1. Buat folder sesuai struktur final.
2. Buat `public/index.php`.
3. Buat mini router.
4. Buat base controller.
5. Buat database connector.
6. Buat session helper.
7. Buat env loader.
8. Buat error handling dasar.
9. Buat halaman home sederhana.

## Exit Criteria

- [ ] `GET /` berjalan.
- [ ] Database connector dapat dipanggil.
- [ ] `.env` terbaca.
- [ ] Error tidak bocor ke user.

---

# Phase 2 — Create Local Database

## Goal

Membuat MySQL lokal sebagai database pengembangan.

## Tasks

1. Buat database `db_consultation_v2`.
2. Buat `database/schema.sql`.
3. Buat `database/seed.sql`.
4. Import schema.
5. Insert satu superadmin.
6. Insert tiga kategori layanan.
7. Insert sub layanan awal.
8. Insert tiga admin awal jika diperlukan.
9. Buat assignment admin.

## Exit Criteria

- [ ] Schema dapat di-import tanpa error.
- [ ] Seed dapat dijalankan.
- [ ] Superadmin tersedia.
- [ ] Layanan tampil dari database.

---

# Phase 3 — Authentication Vertical Slice

## Goal

Membuat registrasi, approval, login, dan logout.

## Tasks

1. Register form.
2. Register backend.
3. Hash password.
4. Simpan status `pending`.
5. Login superadmin.
6. Halaman approval.
7. Approve user.
8. Reject user.
9. Login user approved.
10. Blok user pending.
11. Logout.
12. Middleware role.

## Exit Criteria

- [ ] User baru berstatus pending.
- [ ] User pending tidak dapat login.
- [ ] Superadmin dapat approve.
- [ ] User approved dapat login.
- [ ] Admin masuk dashboard admin.
- [ ] Superadmin masuk dashboard superadmin.

---

# Phase 4 — Frontend Base Shell

## Goal

Membuat layout dasar sebelum fitur lengkap.

## Tasks

1. Public header.
2. Public footer.
3. Dashboard sidebar.
4. Dashboard topbar.
5. Theme variables dark-only.
6. Dark mode untuk public, auth, dan dashboard.
7. Form styles.
8. Basic responsive layout.

## Exit Criteria

- [ ] Landing page tampil.
- [ ] Login/register tampil rapi.
- [ ] Dashboard shell tampil.
- [ ] Tema gelap konsisten di public, auth, dan dashboard.

---

# Phase 5 — Service Management Vertical Slice

## Goal

Membangun katalog layanan lengkap.

## Tasks

1. Public service list.
2. Service detail.
3. Sub service detail.
4. Pricing.
5. Superadmin CRUD category.
6. Superadmin CRUD sub service.
7. Superadmin assign admin.
8. Admin melihat sub service sesuai assignment.
9. Admin edit harga sesuai assignment.

## Exit Criteria

- [ ] Public dapat melihat layanan.
- [ ] Admin scope bekerja.
- [ ] Superadmin dapat CRUD.
- [ ] Harga tersimpan.

---

# Phase 6 — Consultation Pipeline Vertical Slice

## Goal

Membangun alur pilih sub layanan sampai payment pending.

## Tasks

1. User memilih sub layanan.
2. Buat consultation `waiting_payment`.
3. Buat payment lokal dummy `pending`.
4. Admin pipeline.
5. Filter pending.
6. Filter cancelled.
7. Filter paid.
8. Filter active consultation.
9. Pagination.
10. Summary cards.

## Exit Criteria

- [ ] Consultation waiting payment dibuat.
- [ ] Payment pending terlihat.
- [ ] Admin hanya melihat scope layanan.
- [ ] Superadmin melihat semua data.

---

# Phase 7 — Midtrans Sandbox Integration

## Goal

Mengganti dummy payment menjadi Midtrans Snap.

## Tasks

1. Install Midtrans PHP package.
2. Tambahkan `.env`.
3. Buat `MidtransService`.
4. Buat Snap token.
5. Tampilkan Snap popup.
6. Buat webhook endpoint.
7. Verifikasi signature.
8. Mapping status.
9. Update payment.
10. Aktifkan consultation jika paid.
11. Tambahkan refresh status fallback.
12. Uji pending, cancel, success.

## Exit Criteria

- [ ] Snap popup tampil.
- [ ] Pending tercatat.
- [ ] Cancel tercatat.
- [ ] Success menjadi paid.
- [ ] Consultation aktif setelah paid.
- [ ] Chat tetap locked sebelum paid.

---

# Phase 8 — Chat Polling Vertical Slice

## Goal

Membuat chat user-admin sederhana.

## Tasks

1. User chat page.
2. Admin chat page.
3. GET messages.
4. POST messages.
5. Polling 5 detik.
6. `after_id`.
7. Ownership validation.
8. Assignment validation.
9. Locked state.
10. Closed read-only state.

## Exit Criteria

- [ ] User kirim pesan.
- [ ] Admin balas.
- [ ] Polling berjalan.
- [ ] User lain tidak dapat akses.
- [ ] Admin lain tidak dapat akses.
- [ ] Closed chat read-only.

---

# Phase 9 — UI Completion

## Goal

Menyelesaikan seluruh tampilan.

## Tasks

1. Landing page premium.
2. Service cards.
3. Consultant section.
4. Pricing section.
5. Consultation flow section.
6. User dashboard.
7. Admin dashboard.
8. Superadmin dashboard.
9. Status badges.
10. Empty states.
11. Loading states.
12. Responsive.
13. Animasi ringan.

## Exit Criteria

- [ ] Public page mengikuti desain dark futuristic.
- [ ] Admin mengikuti clean dashboard.
- [ ] Responsive.
- [ ] Tema gelap konsisten.

---

# Phase 10 — End-to-End Local Testing

## Goal

Menguji seluruh flow lokal.

## Tasks

1. Register.
2. Approval.
3. Login.
4. CRUD layanan.
5. Assignment admin.
6. Pilih layanan.
7. Midtrans pending.
8. Midtrans cancel.
9. Midtrans success.
10. Consultation active.
11. Chat.
12. Closed consultation.
13. Finance.
14. Admin pipeline.
15. Security checks.

## Exit Criteria

- [ ] Seluruh checklist testing lolos.
- [ ] Bug utama diselesaikan.
- [ ] Backup database lokal tersedia.

---

# Phase 11 — Move Database to Tailscale

## Goal

Memindahkan database lokal ke MySQL Ubuntu Server melalui Tailscale.

## Tasks

1. Backup lokal.
2. Buat database remote.
3. Buat user aplikasi khusus.
4. Import schema dan data.
5. Batasi firewall.
6. Ubah `.env`.
7. Test koneksi.
8. Test flow utama.
9. Matikan debug.

## Exit Criteria

- [ ] Backend tetap berjalan tanpa perubahan kode.
- [ ] Hanya `.env` berubah.
- [ ] MySQL remote dapat diakses.
- [ ] Port 3306 tidak dibuka publik.
- [ ] Login, CRUD, payment, chat berjalan.

---

# Final Rule

Gunakan urutan ini.

Jangan lompat langsung ke Midtrans atau Tailscale sebelum database lokal, auth, service management, dan consultation pipeline stabil.
