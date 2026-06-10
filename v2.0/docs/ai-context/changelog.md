# Changelog

## 2026-06-10

### Added

1. Membuat fondasi Phase 0 sampai Phase 2 untuk aplikasi `v2.0`.
2. Membuat struktur folder dasar PHP native MVC.
3. Menambahkan core minimum:
   - `App`
   - `Router`
   - `Controller`
   - `Database`
   - `Session`
   - `Validator`
   - `Response`
   - `Env`
4. Menambahkan route `GET /`.
5. Menambahkan route development `GET /_dev/db-check`.
6. Menambahkan konfigurasi awal aplikasi dan database.
7. Menambahkan `database/schema.sql` berdasarkan schema final.
8. Menambahkan `database/seed.sql` untuk data awal development.
9. Menambahkan dokumentasi database lokal.
10. Menambahkan `.env.example`, `.gitignore`, dan README awal.

### Notes

1. Autentikasi belum dikerjakan.
2. Midtrans belum diintegrasikan.
3. Chat belum dikerjakan.
4. Database masih lokal, belum Tailscale.
5. Import database awal sempat belum dapat diverifikasi karena MySQL lokal tidak menerima koneksi pada saat pengujian.

## 2026-06-10 - Phase 2 Verification

### Changed

1. Memperbarui route development `GET /_dev/db-check` agar ikut menampilkan kategori layanan aktif dari database.
2. Memperbarui `.gitignore` untuk mengabaikan file session runtime di `storage/sessions/`.
3. Memperbarui `current-progress.md` setelah import schema dan seed berhasil.

### Verified

1. `database/schema.sql` berhasil di-import ke MySQL lokal.
2. `database/seed.sql` berhasil dijalankan.
3. Superadmin development tersedia.
4. Tiga kategori layanan tersedia.
5. Sublayanan awal tersedia.
6. Assignment admin tersedia.
7. Foreign key dan index tersedia.
8. `GET /` berjalan.
9. `GET /_dev/db-check` berjalan dan membaca layanan dari database.

## 2026-06-10 - Phase 3 Authentication

### Added

1. Menambahkan register user dengan status awal `pending`.
2. Menambahkan login dengan blokir status `pending`, `rejected`, dan `inactive`.
3. Menambahkan logout dan session login minimum.
4. Menambahkan middleware guest, auth, approved user, admin, superadmin, dan CSRF.
5. Menambahkan approval dan reject user oleh superadmin.
6. Menambahkan dashboard minimum untuk redirect role user, admin, dan superadmin.
7. Menambahkan view minimum login, register, dan approval pending.
8. Menambahkan dukungan route parameter dan method spoofing `PATCH`.

### Verified

1. User baru berstatus pending.
2. Password register tersimpan sebagai hash.
3. User pending tidak dapat login.
4. Superadmin dapat approve user.
5. User approved dapat login.
6. Admin masuk dashboard admin.
7. Superadmin masuk dashboard superadmin.
8. User rejected dan inactive tidak dapat login.
9. User biasa tidak dapat membuka dashboard admin.
10. Logout bekerja.

## 2026-06-10 - Phase 4 Frontend Base Shell

### Added

1. Menambahkan public layout dengan header dan footer.
2. Menambahkan dashboard shell dengan sidebar dan topbar.
3. Menambahkan theme variables, dark mode, dan light mode.
4. Menambahkan theme toggle berbasis `localStorage`.
5. Menambahkan form styling untuk login dan register.
6. Menambahkan responsive layout dasar untuk public page dan dashboard.
7. Menambahkan CSS dasar:
   - `theme.css`
   - `main.css`
   - `landing.css`
   - `dashboard.css`
   - `forms.css`
8. Menambahkan JavaScript ringan:
   - `theme-toggle.js`
   - `sidebar.js`

### Changed

1. Mengubah landing page agar memakai public shell.
2. Mengubah login dan register agar memakai public shell dan form styling.
3. Mengubah dashboard user, admin, superadmin, dan approval user agar memakai dashboard shell.

### Verified

1. Landing page tampil.
2. Login/register tampil rapi dengan form styling.
3. Dashboard shell tampil untuk admin dan superadmin.
4. Theme toggle tersedia di public dan dashboard shell.
5. Asset CSS/JS dapat diakses dengan status `200`.
6. Syntax PHP lolos `php -l`.

## 2026-06-10 - Phase 5 Service Management

### Added

1. Menambahkan public service list.
2. Menambahkan service detail.
3. Menambahkan sub service detail.
4. Menambahkan pricing page.
5. Menambahkan CRUD kategori layanan untuk superadmin.
6. Menambahkan CRUD sub layanan untuk superadmin.
7. Menambahkan assignment admin ke layanan oleh superadmin.
8. Menambahkan daftar sub layanan admin sesuai assignment.
9. Menambahkan update harga sub layanan oleh admin sesuai assignment.

### Changed

1. Menambahkan route public layanan dan pricing.
2. Menambahkan route dashboard service management.
3. Menambahkan menu dashboard untuk layanan, sub layanan, dan assignment admin.
4. Menambahkan dukungan route `DELETE` pada router.

### Verified

1. Public dapat melihat layanan, detail layanan, detail sub layanan, dan pricing.
2. Superadmin dapat membuat, mengubah, dan disable kategori layanan.
3. Superadmin dapat membuat, mengubah, dan disable sub layanan.
4. Superadmin dapat assignment admin ke layanan.
5. Admin hanya melihat sub layanan sesuai assignment.
6. Admin dapat mengubah harga sub layanan sesuai assignment.
7. Admin mendapat `403` saat membuka sub layanan di luar assignment.
8. Harga tersimpan di database.
9. Database direset kembali ke seed awal setelah test.
10. Syntax PHP lolos `php -l`.

## 2026-06-10 - Phase 6 Consultation Pipeline

### Added

1. Menambahkan flow user memilih sub layanan.
2. Menambahkan pembuatan consultation `waiting_payment`.
3. Menambahkan payment dummy lokal `pending`.
4. Menambahkan halaman riwayat dan detail konsultasi user.
5. Menambahkan halaman dummy payment user.
6. Menambahkan aksi dummy paid dan dummy cancel.
7. Menambahkan admin pipeline dengan tab pending, cancelled, paid, active, dan closed.
8. Menambahkan filter, pagination, dan summary cards pada admin pipeline.
9. Menambahkan close consultation untuk admin sesuai assignment.
10. Menambahkan superadmin consultation pipeline untuk melihat seluruh data.

### Verified

1. User dapat membuat consultation `waiting_payment`.
2. Payment dummy `pending` dibuat dengan amount snapshot.
3. Admin hanya melihat pipeline sesuai assignment.
4. Admin di luar assignment tidak melihat data layanan lain.
5. Dummy paid mengubah payment menjadi `paid` dan consultation menjadi `active`.
6. Dummy cancel mengubah payment dan consultation menjadi cancelled.
7. Admin dapat close active consultation.
8. Superadmin dapat melihat seluruh pipeline.
9. Database direset kembali ke seed awal setelah test.
10. Syntax PHP lolos `php -l`.

## 2026-06-10 - Seeder Account Passwords

### Changed

1. Mengubah seluruh akun seed superadmin, admin, dan user development agar memakai password `password`.
2. Menambahkan akun user seed untuk status approved, pending, rejected, dan inactive.
3. Memperbarui dokumentasi akun seed di `database/README.md`.

## 2026-06-10 - Phase 7 Midtrans Sandbox Integration

### Added

1. Menginstal dependency resmi `midtrans/midtrans-php`.
2. Menambahkan `config/payment.php`.
3. Menambahkan `MidtransService` untuk Snap token, signature verification, status mapping, dan Get Status API.
4. Menambahkan `PaymentService` untuk create payment, webhook idempotent, refresh status, dan aktivasi consultation setelah paid.
5. Menambahkan `MidtransWebhookController`.
6. Menambahkan route payment Midtrans, webhook, dan redirect Midtrans.
7. Menambahkan UI payment user dengan Snap popup.

### Changed

1. Mengganti flow dummy payment user menjadi Midtrans Sandbox.
2. Melepas route dummy paid dan dummy cancel dari routing user.
3. Memperbarui `.env.example` dan `.env` untuk konfigurasi Midtrans.
4. Memuat Composer autoload dari `public/index.php`.
5. Menampilkan `order_id` dan raw status Midtrans pada pipeline admin/superadmin.
6. Memperbarui route mapping untuk payment Midtrans.

### Verified

1. Package Midtrans versi `2.6.2` terpasang.
2. Syntax PHP lolos `php -l`.
3. Route `GET /` berjalan.
4. Webhook service-level memproses `pending`, `cancel`, dan `settlement`.
5. Duplicate webhook `settlement` tetap idempotent.
6. Consultation hanya berubah `active` setelah payment internal `paid`.
7. Endpoint webhook HTTP mengembalikan `404` untuk order yang tidak ditemukan.
8. Endpoint webhook HTTP memproses `settlement` valid dan mengaktifkan consultation.

### Notes

1. Snap token real dan Get Status API real memerlukan Sandbox key pada `.env`.
2. Webhook real memerlukan URL publik HTTPS atau tunnel sementara.

## 2026-06-10 - Midtrans Sandbox Setup Guide

### Added

1. Menambahkan `midtrans-sandbox-setup-guide.md`.
2. Menambahkan panduan pengisian `.env` untuk Sandbox Midtrans.
3. Menambahkan panduan webhook tunnel, dashboard URL, flow testing, query verifikasi, dan troubleshooting.

### Changed

1. Menambahkan referensi dokumen setup Midtrans pada `README.md` ai-context.
2. Menambahkan contoh konfigurasi Midtrans untuk aplikasi lokal pada `http://localhost:8000/`.

## 2026-06-11 - Phase 8 Chat Polling Vertical Slice

### Added

1. Menambahkan `Message` model.
2. Menambahkan `ChatController`.
3. Menambahkan halaman chat user dan admin.
4. Menambahkan API GET messages user dan admin.
5. Menambahkan API POST messages user dan admin.
6. Menambahkan JavaScript polling chat setiap 5 detik.
7. Menambahkan dukungan `after_id` untuk mengambil pesan baru.
8. Menambahkan entry point chat dari detail konsultasi user dan pipeline admin.

### Changed

1. Memperluas CSRF middleware agar API `fetch()` dapat mengirim token melalui header `X-CSRF-TOKEN`.
2. Memperluas query consultation admin agar membawa detail layanan, user, dan status payment untuk chat.
3. Menambahkan style dasar untuk panel pesan chat.

### Verified

1. User dapat membuka halaman chat consultation aktif.
2. User dapat mengirim pesan.
3. Admin assignment dapat membaca dan membalas pesan.
4. `after_id` bekerja.
5. Admin di luar assignment ditolak HTTP `403`.
6. Consultation sebelum paid menampilkan locked state dan API ditolak HTTP `423`.
7. Consultation closed menjadi read-only dan POST ditolak HTTP `423`.
8. Pesan kosong dan pesan lebih dari 3000 karakter ditolak HTTP `422`.
9. Syntax PHP lolos `php -l`.

## 2026-06-11 - Phase 9 UI Completion

### Added

1. Menambahkan section landing page:
   - service cards;
   - consultant competence;
   - pricing preview;
   - alur konsultasi;
   - CTA section.
2. Menambahkan helper `status_badge()` untuk badge status konsisten.
3. Menambahkan empty state reusable berbasis CSS.
4. Menambahkan loading state ringan berbasis skeleton line.
5. Menambahkan card/panel dashboard untuk user, admin, dan superadmin.

### Changed

1. Memperbarui public page agar lebih dark futuristic premium.
2. Memperbarui service list, service detail, subservice detail, dan pricing agar memakai visual card yang konsisten.
3. Memperbarui user dashboard.
4. Memperbarui admin dashboard.
5. Memperbarui superadmin dashboard.
6. Memperbarui pipeline table dan consultation detail agar memakai status badge konsisten.
7. Memperbarui dashboard topbar agar lebih stabil dan bersih.
8. Memperbarui CSS dashboard dan landing untuk responsive layout serta animasi ringan.

### Verified

1. Landing page berhasil dimuat.
2. Services page berhasil dimuat.
3. Pricing page berhasil dimuat.
4. User dashboard berhasil dimuat setelah login.
5. Admin dashboard berhasil dimuat setelah login.
6. Superadmin dashboard berhasil dimuat setelah login.
7. Syntax PHP lolos `php -l`.

## 2026-06-11 - Reference-Based UI Update

### Changed

1. Memperbarui landing page agar mengikuti referensi dark browser-frame hero:
   - menu bar pill;
   - login dan get started di kanan;
   - hero typography besar;
   - visual latar/abstrak teknologi;
   - proof/testimonial band bawah.
2. Memperbarui layout dashboard user, admin, dan superadmin agar mengikuti referensi productivity dashboard:
   - app window dengan browser bar;
   - sidebar putih;
   - topbar search/actions;
   - content area clean.
3. Memperbarui theme toggle agar memakai teks ASCII `Light` dan `Dark`.

### Verified

1. Landing page berhasil dimuat.
2. Services page berhasil dimuat.
3. Dashboard user berhasil dimuat setelah login.
4. Dashboard admin berhasil dimuat setelah login.
5. Dashboard superadmin berhasil dimuat setelah login.
6. Syntax PHP view lolos `php -l`.

## 2026-06-11 - Zeta Branding

### Added

1. Menambahkan ikon brand Zeta hasil image generation:
   - `public/assets/img/zeta-icon.png`
   - `public/assets/img/zeta-icon-192.png`

### Changed

1. Mengubah nama sistem menjadi `Zeta`.
2. Memasang ikon Zeta pada public navbar.
3. Memasang ikon Zeta pada dashboard sidebar.
4. Memasang ikon Zeta sebagai favicon.
5. Memperbarui APP name, title, footer, README, dan project overview.

## 2026-06-11 - Theme Toggle Restore

### Added

1. Mengembalikan `public/assets/js/theme-toggle.js` agar mode gelap dan terang kembali bisa dipakai.

### Verified

1. `GET /assets/js/theme-toggle.js` berhasil HTTP `200`.
2. `GET /` berhasil HTTP `200`.
3. `GET /login` berhasil HTTP `200`.

## 2026-06-11 - Dark Only Theme

### Removed

1. Menghapus fitur light mode dari CSS theme.
2. Menghapus tombol theme toggle dari public header dan dashboard topbar.
3. Menghapus pemuatan script `theme-toggle.js` dari layout.
4. Menghapus file `public/assets/js/theme-toggle.js`.

### Changed

1. Mengubah dashboard user, admin, dan superadmin menjadi tema gelap.
2. Mengubah token warna global agar `:root` langsung memakai palet gelap.
3. Memastikan public, auth, dashboard, form, badge, flash message, table, dan chat tetap memakai kontras tema gelap.

### Verified

1. `GET /` berhasil HTTP `200`.
2. `GET /login` berhasil HTTP `200`.
3. Asset CSS utama berhasil HTTP `200`.

## 2026-06-11 - Phase 10 End-to-End Local Testing

### Fixed

1. Memperkuat session cookie di `app/Core/Session.php`:
   - `HttpOnly`;
   - `SameSite=Lax`;
   - `Secure` otomatis saat request HTTPS atau forwarded HTTPS.

### Verified

1. Backup database lokal dibuat di `database/backups/e2e-local-20260611-060552.sql`.
2. Import `schema.sql` dan `seed.sql` berhasil.
3. Register, pending approval, approve superadmin, login, logout, dan role redirect berhasil.
4. Pending, rejected, inactive, dan wrong password ditolak.
5. Authorization user/admin/superadmin menghasilkan scope dan HTTP status yang sesuai.
6. Public service catalog, pricing, CRUD kategori, CRUD sub layanan, assignment admin, admin price update, dan inactive subservice berhasil diuji.
7. Consultation pipeline berhasil diuji untuk waiting payment, pending, cancelled, paid, active, closed, filter, dan pagination route.
8. Midtrans Sandbox berhasil membuat payment pending dengan Snap token.
9. Webhook Midtrans sintetis berhasil untuk cancel, settlement, duplicate settlement, dan invalid signature.
10. Chat polling API berhasil diuji untuk locked state, active send/read, `after_id`, validation, assignment/ownership, dan closed read-only.
11. Server Key Midtrans tidak tampil di HTML payment page.
12. Theme toggle tidak tersedia dan `theme-toggle.js` mengembalikan HTTP `404`.
13. Syntax PHP seluruh `app/`, `config/`, `routes/`, dan `public/` lolos `php -l`.
14. Database lokal dikembalikan ke seed awal setelah pengujian.

## 2026-06-11 - Tailscale Database Duplication Guide

### Added

1. Menambahkan `tailscale-database-duplication-guide.md`.
2. Menambahkan panduan backup database lokal, pembuatan database remote, dedicated MySQL user, firewall Tailscale, import backup, konfigurasi `.env` production, testing koneksi, rollback lokal, dan checklist Phase 11.

### Notes

1. Panduan ini belum menjalankan migrasi remote.
2. Database lokal tetap digunakan sampai `.env` diubah secara sadar.

## 2026-06-11 - cPanel Deployment Guide

### Added

1. Menambahkan `docs/cpanel-deployment-guide.md`.
2. Menambahkan ringkasan deployment cPanel di `docs/ai-context/cpanel-deployment-guide.md`.
3. Mendokumentasikan document root ke `public/`, konfigurasi `.env` production, Composer/vendor, database cPanel, import schema/seed, permission runtime, Midtrans callback HTTPS, checklist security, dan troubleshooting.

### Notes

1. Panduan ini tidak mengubah kode aplikasi.
2. Controller, model, route, dan view tetap tidak perlu diubah untuk deploy cPanel.
