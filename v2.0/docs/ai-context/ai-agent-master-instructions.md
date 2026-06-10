\
# AI Agent Master Instructions

## Purpose

File ini adalah instruksi utama untuk AI agent sebelum membangun, mengubah, atau memperbaiki sistem `v2.0`.

Baca file ini terlebih dahulu.

---

# Project Summary

Sistem ini adalah website konsultasi teknologi berbasis PHP native MVC.

Layanan awal:

```text
Network Architecture
Database Architecture
Web Server & Virtualization
```

Stakeholder:

```text
user
admin
superadmin
```

Payment gateway:

```text
Midtrans Snap
```

Database:

```text
MySQL lokal terlebih dahulu
MySQL Ubuntu Server Tailscale setelah sistem stabil
```

Chat:

```text
Polling HTTP setiap 5 detik
```

---

# Mandatory Read Order

Sebelum coding:

1. `project-overview.md`
2. `business-rules.md`
3. `final-folder-structure.md`
4. `final-database-schema.md`
5. `authentication-and-authorization.md`
6. `page-and-route-mapping.md`
7. `implementation-roadmap.md`
8. `backend-clean-code-guidelines.md`
9. `frontend-context.md`
10. `ui-design-guidelines.md`
11. `payment-gateway-midtrans.md`
12. `payment-midtrans-ai-agent-rules.md`
13. `chat-consultation-spec.md`
14. `testing-checklist.md`
15. `current-progress.md`

---

# Execution Order

Gunakan urutan:

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

Jangan lompat tahapan.

---

# Core Architecture Rules

1. Gunakan PHP native MVC.
2. View berada dalam `app/Views`.
3. Asset berada dalam `public/assets`.
4. Route berada dalam `routes/`.
5. Query berada dalam Model.
6. Business logic multi-step berada dalam Service.
7. Controller harus tipis.
8. Middleware menangani auth, role, approval, dan CSRF.
9. Config berada di `config/`.
10. Secret berada di `.env`.

---

# Database Rules

1. Gunakan MySQL lokal terlebih dahulu.
2. Gunakan schema final pada `final-database-schema.md`.
3. Jangan membuat tabel baru tanpa memperbarui schema docs.
4. Jangan membuat `consultations.payment_id`.
5. Gunakan `payments.consultation_id`.
6. Satu consultation dapat memiliki beberapa payment attempts.
7. Gunakan prepared statement.
8. Gunakan database transaction untuk proses multi-step.
9. Jangan hardcode DB host.
10. Migrasi Tailscale hanya dengan mengubah `.env`.

---

# Auth Rules

1. User registrasi berstatus `pending`.
2. User login hanya jika `approved`.
3. Superadmin approve dan reject user.
4. Admin tidak boleh approve user.
5. Password menggunakan `password_hash()`.
6. Login menggunakan `password_verify()`.
7. Regenerate session ID setelah login.
8. Validasi role pada backend.
9. Jangan percaya role dari frontend.
10. Jangan percaya status dari frontend.

---

# Admin Scope Rules

1. Admin hanya melihat layanan assignment.
2. Admin hanya melihat transaksi assignment.
3. Admin hanya melihat konsultasi assignment.
4. Admin hanya membalas chat assignment.
5. Superadmin dapat melihat seluruh data.
6. Jangan hardcode nama admin.
7. Gunakan `admin_service_assignments`.

---

# Midtrans Rules

1. Gunakan Midtrans Snap.
2. Buat Snap token dari backend.
3. Server Key hanya backend.
4. Client Key digunakan frontend.
5. Gunakan webhook.
6. Verifikasi signature.
7. Gunakan Get Status API sebagai fallback.
8. Jangan percaya callback frontend.
9. Jangan aktifkan chat dari JavaScript callback.
10. Aktifkan consultation hanya setelah internal status `paid`.
11. Handler webhook harus idempotent.
12. Jangan commit key.

---

# Chat Rules

1. Gunakan polling 5 detik.
2. Gunakan `after_id`.
3. User hanya akses chat miliknya.
4. Admin hanya akses chat assignment.
5. Chat aktif hanya jika payment paid dan consultation active.
6. Closed chat read-only.
7. Cancelled chat locked.
8. Escape output.
9. Maksimal 3000 karakter.
10. Jangan gunakan WebSocket pada MVP.

---

# UI Rules

1. Landing page mengikuti dark futuristic premium style.
2. Dashboard admin mengikuti clean dark productivity dashboard.
3. Sistem menggunakan tema gelap saja; light mode dan theme toggle tidak digunakan.
4. Gunakan typography konsisten.
5. Gunakan animasi ringan.
6. Jangan membuat template AI generik.
7. Gunakan sidebar dashboard.
8. Gunakan status badge.
9. Gunakan empty state.
10. Gunakan loading state.

---

# Clean Code Rules

1. Gunakan naming konsisten.
2. Satu file satu tanggung jawab utama.
3. Gunakan early return.
4. Hindari nested condition panjang.
5. Hindari fungsi terlalu panjang.
6. Gunakan `SELECT` kolom eksplisit.
7. Gunakan pagination.
8. Hindari N+1 query.
9. Jangan query di view.
10. Jangan menaruh secret di kode.
11. Jangan over-engineering.
12. Jangan membuat abstraksi tanpa kebutuhan nyata.

---

# Documentation Update Rules

Jika mengubah route:

```text
Perbarui page-and-route-mapping.md
```

Jika mengubah schema:

```text
Perbarui final-database-schema.md
```

Jika mengubah struktur folder:

```text
Perbarui final-folder-structure.md
```

Jika mengubah chat:

```text
Perbarui chat-consultation-spec.md
```

Jika mengubah Midtrans:

```text
Perbarui payment-gateway-midtrans.md
```

Setelah perubahan besar:

```text
Perbarui current-progress.md
Tambahkan changelog
```

---

# Build Rule

Jangan membangun seluruh sistem sekaligus.

Gunakan vertical slice:

```text
Database
→ Backend
→ Frontend
→ Test
```

untuk setiap fitur.

Contoh:

```text
Register DB
→ Register backend
→ Register page
→ Test

Approval DB
→ Approval backend
→ Approval page
→ Test

Payment DB
→ Payment backend
→ Payment page
→ Test
```

---

# Final Instruction

Mulai dari fase pertama pada:

```text
implementation-roadmap.md
```

Jangan lanjut ke fase berikutnya sebelum exit criteria fase aktif terpenuhi.
