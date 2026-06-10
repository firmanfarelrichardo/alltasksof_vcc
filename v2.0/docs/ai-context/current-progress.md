# Current Progress

## Update 2026-06-11 - Phase 0 sampai Phase 10

Phase yang sudah dikerjakan:

1. Phase 0 - Lock Foundation.
2. Phase 1 - Create Project Skeleton.
3. Phase 2 - Create Local Database.
4. Phase 3 - Authentication Vertical Slice.
5. Phase 4 - Frontend Base Shell.
6. Phase 5 - Service Management Vertical Slice.
7. Phase 6 - Consultation Pipeline Vertical Slice.
8. Phase 7 - Midtrans Sandbox Integration.
9. Phase 8 - Chat Polling Vertical Slice.
10. Phase 9 - UI Completion.
11. Phase 10 - End-to-End Local Testing.

## Foundation

Hasil fondasi:

1. Struktur dasar PHP native MVC dibuat di `v2.0/`.
2. Web root berada di `v2.0/public/`.
3. Core minimum tersedia:
   - `App`
   - `Router`
   - `Controller`
   - `Database`
   - `Session`
   - `Validator`
   - `Response`
   - `Env`
4. Route minimum tersedia:
   - `GET /`
   - `GET /_dev/db-check`
5. File konfigurasi awal tersedia:
   - `config/app.php`
   - `config/database.php`
6. `.env.example` tersedia untuk konfigurasi lokal.
7. `.gitignore` mengecualikan `.env`, `vendor/`, log, backup database, dan file session runtime.

## Database Lokal

Hasil database lokal:

1. `database/schema.sql` dibuat berdasarkan `final-database-schema.md`.
2. `database/seed.sql` dibuat untuk data awal development.
3. Database target:
   - `db_consultation_v2`
4. Seed awal mencakup:
   - satu akun superadmin development;
   - tiga akun admin development;
   - beberapa akun user development untuk status approved, pending, rejected, dan inactive;
   - tiga kategori layanan utama;
   - enam sublayanan contoh;
   - assignment admin ke kategori layanan.

Hasil verifikasi database:

1. Import `schema.sql` berhasil pada MySQL lokal `127.0.0.1:3306`.
2. Import `seed.sql` berhasil.
3. Superadmin development tersedia.
4. Tiga kategori layanan tersedia:
   - Network Architecture
   - Database Architecture
   - Web Server & Virtualization
5. Sublayanan awal tersedia.
6. Assignment admin ke layanan tersedia.
7. Foreign key dan index terverifikasi melalui `information_schema`.
8. Route `GET /_dev/db-check` membaca kategori layanan aktif dari database.

## Authentication

Hasil autentikasi:

1. Register user tersedia.
2. User baru selalu dibuat dengan:
   - `role = user`
   - `status = pending`
3. Password register disimpan menggunakan `password_hash()`.
4. Login menggunakan `password_verify()`.
5. Login hanya diizinkan untuk akun `status = approved`.
6. Login akun `pending`, `rejected`, dan `inactive` ditolak.
7. Session login menyimpan:
   - `user_id`
   - `user_name`
   - `role`
   - `status`
8. Session ID diregenerasi setelah login.
9. Logout menghapus session.
10. Redirect login berdasarkan role:
   - user ke `/user/dashboard`
   - admin ke `/admin/dashboard`
   - superadmin ke `/superadmin/dashboard`
11. Middleware dasar tersedia:
   - `GuestMiddleware`
   - `AuthMiddleware`
   - `ApprovedUserMiddleware`
   - `AdminMiddleware`
   - `SuperadminMiddleware`
   - `CsrfMiddleware`
12. Superadmin dapat melihat user pending.
13. Superadmin dapat approve user pending.
14. Superadmin dapat reject user pending.
15. User tidak dapat membuka dashboard admin.

Route Phase 3 yang tersedia:

1. `GET /register`
2. `POST /register`
3. `GET /login`
4. `POST /login`
5. `POST /logout`
6. `GET /user/dashboard`
7. `GET /admin/dashboard`
8. `GET /superadmin/dashboard`
9. `GET /superadmin/users/pending`
10. `PATCH /superadmin/users/{id}/approve`
11. `PATCH /superadmin/users/{id}/reject`

Hasil verifikasi Phase 3:

1. User baru berstatus pending.
2. User pending tidak dapat login.
3. Superadmin dapat login.
4. Superadmin dapat approve user.
5. User approved dapat login.
6. Admin seed dapat login dan masuk dashboard admin.
7. Superadmin seed dapat masuk dashboard superadmin.
8. User rejected tidak dapat login.
9. User inactive tidak dapat login.
10. Logout mengembalikan user ke halaman login.
11. User biasa mendapat `403` saat membuka dashboard admin.
12. Syntax PHP lolos `php -l`.

## Frontend Base Shell

Hasil Phase 4:

1. Public layout tersedia.
2. Public header tersedia.
3. Public footer tersedia.
4. Dashboard sidebar tersedia.
5. Dashboard topbar tersedia.
6. Theme variables tersedia di `public/assets/css/theme.css`.
7. Tema gelap menjadi satu-satunya tema untuk public, auth, user dashboard, admin dashboard, dan superadmin dashboard.
8. Light mode dihapus dari sistem.
9. Theme toggle dan penyimpanan preferensi tema di `localStorage` dihapus.
10. Form styling tersedia untuk login dan register.
11. Responsive layout dasar tersedia:
   - public hero menjadi satu kolom di mobile;
   - dashboard sidebar menjadi drawer di mobile;
   - table memakai wrapper horizontal scroll.

File frontend shell:

1. `app/Views/layouts/public-header.php`
2. `app/Views/layouts/public-footer.php`
3. `app/Views/layouts/dashboard-sidebar.php`
4. `app/Views/layouts/dashboard-topbar.php`
5. `app/Views/layouts/dashboard-layout.php`
6. `public/assets/css/theme.css`
7. `public/assets/css/main.css`
8. `public/assets/css/landing.css`
9. `public/assets/css/dashboard.css`
10. `public/assets/css/forms.css`
11. `public/assets/js/sidebar.js`

Hasil verifikasi Phase 4:

1. Landing page tampil dengan public header, hero, service cards, footer, dan tema gelap.
2. Login/register tampil dengan form styling.
3. Dashboard admin dan superadmin tampil dengan sidebar dan topbar.
4. Asset CSS/JS dapat diakses HTTP status `200`.
5. Login admin dan superadmin tetap redirect ke dashboard masing-masing.
6. Syntax PHP lolos `php -l`.

## Service Management

Hasil Phase 5:

1. Public service list tersedia di `GET /services`.
2. Service detail tersedia di `GET /services/{id}`.
3. Sub service detail tersedia di `GET /sub-services/{id}`.
4. Pricing tersedia di `GET /pricing`.
5. Superadmin dapat CRUD kategori layanan:
   - create;
   - update;
   - disable.
6. Superadmin dapat CRUD sub layanan:
   - create;
   - update;
   - disable.
7. Superadmin dapat assignment admin ke kategori layanan.
8. Superadmin dapat menghapus assignment admin.
9. Admin dapat melihat sub layanan sesuai assignment.
10. Admin dapat mengubah harga sub layanan sesuai assignment.
11. Admin tidak dapat membuka atau mengubah sub layanan di luar assignment.

Route Phase 5 yang tersedia:

1. `GET /services`
2. `GET /services/{id}`
3. `GET /sub-services/{id}`
4. `GET /pricing`
5. `GET /superadmin/services`
6. `GET /superadmin/services/create`
7. `POST /superadmin/services`
8. `GET /superadmin/services/{id}/edit`
9. `PATCH /superadmin/services/{id}`
10. `DELETE /superadmin/services/{id}`
11. `GET /superadmin/sub-services`
12. `GET /superadmin/sub-services/create`
13. `POST /superadmin/sub-services`
14. `GET /superadmin/sub-services/{id}/edit`
15. `PATCH /superadmin/sub-services/{id}`
16. `DELETE /superadmin/sub-services/{id}`
17. `GET /superadmin/admins`
18. `GET /superadmin/admins/{id}/assignments`
19. `POST /superadmin/admins/{id}/assignments`
20. `DELETE /superadmin/admins/{id}/assignments/{assignmentId}`
21. `GET /admin/sub-services`
22. `GET /admin/sub-services/{id}/edit`
23. `PATCH /admin/sub-services/{id}`

Hasil verifikasi Phase 5:

1. Public dapat melihat layanan.
2. Public dapat melihat detail layanan dan sub layanan.
3. Public dapat melihat pricing.
4. Superadmin dapat membuat dan mengubah kategori layanan.
5. Superadmin dapat membuat dan mengubah sub layanan.
6. Superadmin dapat disable kategori dan sub layanan.
7. Superadmin dapat assignment admin ke layanan.
8. Admin melihat sub layanan assignment.
9. Admin dapat update harga assignment.
10. Admin mendapat `403` saat membuka sub layanan di luar assignment.
11. Harga tersimpan di database.
12. Database direset kembali ke seed awal setelah test.
13. Syntax PHP lolos `php -l`.

## Consultation Pipeline

Hasil Phase 6:

1. User approved dapat memilih sub layanan.
2. Sistem membuat consultation dengan status `waiting_payment`.
3. Sistem membuat payment dummy lokal dengan status `pending`.
4. Amount payment menjadi snapshot harga sub layanan saat consultation dibuat.
5. User dapat melihat riwayat konsultasi.
6. User dapat melihat detail konsultasi.
7. User dapat membuka halaman dummy payment.
8. Dummy payment dapat diubah menjadi `paid`.
9. Consultation berubah menjadi `active` setelah dummy payment `paid`.
10. Dummy payment dapat diubah menjadi `cancelled`.
11. Consultation berubah menjadi `cancelled` setelah dummy payment cancelled.
12. Admin pipeline tersedia dengan tab:
   - pending;
   - cancelled;
   - paid;
   - active;
   - closed.
13. Admin pipeline memakai backend scope berdasarkan `admin_service_assignments`.
14. Admin di luar assignment tidak melihat data konsultasi layanan lain.
15. Admin dapat close consultation aktif menjadi `closed`.
16. Superadmin dapat melihat seluruh consultation pipeline.
17. Filter dan pagination dasar tersedia.
18. Summary cards tersedia pada admin pipeline.

Route Phase 6 yang tersedia:

1. `POST /user/consultations`
2. `GET /user/consultations`
3. `GET /user/consultations/{id}`
4. `GET /user/consultations/{id}/payment`
5. `POST /user/payments/{id}/dummy-paid`
6. `POST /user/payments/{id}/dummy-cancel`
7. `GET /admin/pipeline`
8. `GET /admin/pipeline/payments/pending`
9. `GET /admin/pipeline/payments/cancelled`
10. `GET /admin/pipeline/payments/success`
11. `GET /admin/pipeline/consultations/active`
12. `GET /admin/pipeline/consultations/closed`
13. `PATCH /admin/consultations/{id}/close`
14. `GET /superadmin/consultations`

Hasil verifikasi Phase 6:

1. Consultation `waiting_payment` berhasil dibuat.
2. Payment dummy `pending` berhasil dibuat.
3. Admin assignment melihat pending payment.
4. Admin di luar assignment tidak melihat pending payment.
5. Dummy paid mengubah payment menjadi `paid`.
6. Dummy paid mengaktifkan consultation.
7. Tab paid dan active menampilkan data.
8. Admin dapat close active consultation.
9. Tab closed menampilkan data.
10. Dummy cancel mengubah payment dan consultation menjadi cancelled.
11. Tab cancelled menampilkan data.
12. Superadmin melihat data closed dari seluruh layanan.
13. Database direset kembali ke seed awal setelah test.
14. Syntax PHP lolos `php -l`.

## Midtrans Sandbox Integration

Hasil Phase 7:

1. Dependency resmi `midtrans/midtrans-php` terpasang melalui Composer.
2. Composer autoload dimuat dari `public/index.php`.
3. Konfigurasi payment tersedia di `config/payment.php`.
4. `.env.example` dan `.env` memuat variabel Midtrans Sandbox:
   - `PAYMENT_PROVIDER`
   - `PAYMENT_MODE`
   - `MIDTRANS_SERVER_KEY`
   - `MIDTRANS_CLIENT_KEY`
   - `MIDTRANS_IS_PRODUCTION`
   - redirect dan notification URL Midtrans.
5. `MidtransService` tersedia untuk:
   - konfigurasi Midtrans;
   - membuat Snap token;
   - verifikasi signature webhook;
   - mapping status Midtrans ke status internal;
   - Get Status API fallback.
6. `PaymentService` tersedia untuk:
   - membuat payment lokal Midtrans;
   - menyimpan Snap token;
   - memproses webhook secara idempotent;
   - refresh status dari backend;
   - mengaktifkan consultation hanya setelah payment `paid`.
7. `MidtransWebhookController` tersedia untuk endpoint webhook tanpa session login.
8. Callback frontend Snap hanya dipakai untuk redirect pengalaman pengguna.
9. Status pembayaran final tidak diambil dari callback frontend.
10. Dummy payment route aktif Phase 6 sudah dilepas dari routing user.
11. Halaman pembayaran user sekarang memakai Midtrans Snap Sandbox.
12. Admin dan superadmin pipeline menampilkan `order_id` dan raw status Midtrans.

Route Phase 7 yang tersedia:

1. `GET /user/consultations/{id}/payment`
2. `POST /user/consultations/{id}/payment`
3. `GET /user/payments/{id}`
4. `GET /api/user/payments/{id}/status`
5. `POST /user/payments/{id}/refresh-status`
6. `POST /payments/midtrans/notification`
7. `GET /payments/midtrans/finish`
8. `GET /payments/midtrans/unfinish`
9. `GET /payments/midtrans/error`

Hasil verifikasi Phase 7:

1. `midtrans/midtrans-php` versi `2.6.2` terpasang.
2. Syntax PHP lolos `php -l`.
3. Route `GET /` berjalan melalui PHP built-in server.
4. Webhook service-level sintetis berhasil memproses status `pending`.
5. Webhook service-level sintetis berhasil memproses status `cancel`.
6. Webhook service-level sintetis berhasil memproses status `settlement`.
7. Duplicate webhook `settlement` tidak membuat payment atau consultation baru.
8. Consultation berubah menjadi `active` hanya setelah status internal payment `paid`.
9. Endpoint webhook HTTP mengembalikan `404` untuk order valid-signature yang tidak ditemukan.
10. Endpoint webhook HTTP memproses `settlement` valid dan mengaktifkan consultation.
11. Snap token real dan Get Status API real belum diuji karena Sandbox Server Key dan Client Key belum diisi.

## Chat Polling

Hasil Phase 8:

1. User chat page tersedia.
2. Admin chat page tersedia.
3. API GET messages tersedia untuk user dan admin.
4. API POST messages tersedia untuk user dan admin.
5. Frontend polling berjalan setiap 5 detik.
6. API mendukung parameter `after_id`.
7. User hanya dapat membuka chat consultation miliknya.
8. Admin hanya dapat membuka chat consultation sesuai assignment layanan.
9. Chat locked sebelum payment `paid` dan consultation `active`.
10. Chat read-only setelah consultation `closed`.
11. Consultation `cancelled` tetap tidak dapat digunakan untuk chat.
12. Pesan disimpan sebagai plain text dan dirender aman melalui DOM text.
13. CSRF middleware mendukung token dari header `X-CSRF-TOKEN` untuk API `fetch()`.

Route Phase 8 yang tersedia:

1. `GET /user/consultations/{id}/chat`
2. `GET /api/user/consultations/{id}/messages`
3. `POST /api/user/consultations/{id}/messages`
4. `GET /admin/consultations/{id}/chat`
5. `GET /api/admin/consultations/{id}/messages`
6. `POST /api/admin/consultations/{id}/messages`

Hasil verifikasi Phase 8:

1. User chat page aktif mengembalikan HTTP `200`.
2. User dapat mengirim pesan dan API mengembalikan HTTP `201`.
3. Admin assignment dapat membaca pesan user.
4. Admin assignment dapat membalas pesan.
5. `after_id` hanya mengembalikan pesan baru.
6. Admin di luar assignment mendapat HTTP `403`.
7. Closed consultation menolak POST message dengan HTTP `423`.
8. Waiting payment/pending payment menampilkan locked state dan API messages mengembalikan HTTP `423`.
9. Pesan kosong ditolak HTTP `422`.
10. Pesan lebih dari 3000 karakter ditolak HTTP `422`.
11. Syntax PHP lolos `php -l`.

## UI Completion

Hasil Phase 9:

1. Landing page diperluas dengan:
   - hero dark futuristic premium;
   - service cards;
   - consultant competence section;
   - pricing preview;
   - alur konsultasi;
   - CTA section.
2. Public service list, service detail, subservice detail, dan pricing memakai card premium yang konsisten.
3. Dashboard user diperbarui dengan hero dashboard, stat cards, alur cepat, loading state, dan CTA.
4. Dashboard admin diperbarui dengan clean productivity cards, prioritas pipeline, dan empty state.
5. Dashboard superadmin diperbarui dengan clean productivity cards dan kontrol sistem.
6. Status badge dibuat konsisten melalui helper `status_badge()`.
7. Empty state ditambahkan pada halaman data utama.
8. Loading state ringan ditambahkan menggunakan skeleton line.
9. Tabel dashboard mendapat hover state yang lebih jelas.
10. Responsive layout dashboard tetap memakai sidebar drawer pada mobile.
11. Animasi ringan ditambahkan pada card, button, sidebar link, hero visual, dan loading state.
12. Tidak ada framework frontend ditambahkan.
13. Landing page diperbarui ulang mengikuti referensi browser-frame dark premium dengan visual teknologi abstrak.
14. Dashboard user, admin, dan superadmin diperbarui ulang memakai dark productivity dashboard dengan topbar search/actions.
15. Theme toggle dihapus karena sistem memakai tema gelap saja.
16. Nama sistem ditetapkan menjadi `Zeta`.
17. Ikon brand Zeta dibuat sebagai asset lokal dan dipasang pada public navbar, dashboard sidebar, dan favicon.
18. File `theme-toggle.js` dihapus, selector `data-theme` dihapus dari layout, dan token light mode dihapus dari CSS.

Hasil verifikasi Phase 9:

1. `GET /` berhasil HTTP `200`.
2. `GET /services` berhasil HTTP `200`.
3. `GET /pricing` berhasil HTTP `200`.
4. `GET /user/dashboard` berhasil HTTP `200` setelah login user approved.
5. `GET /admin/dashboard` berhasil HTTP `200` setelah login admin.
6. `GET /superadmin/dashboard` berhasil HTTP `200` setelah login superadmin.
7. Syntax PHP lolos `php -l`.
8. Asset CSS utama berhasil HTTP `200` setelah light mode dihapus.

## End-to-End Local Testing

Hasil Phase 10:

1. Backup database lokal dibuat sebelum pengujian:
   - `database/backups/e2e-local-20260611-060552.sql`
2. `schema.sql` berhasil di-import ulang.
3. `seed.sql` berhasil di-import ulang.
4. Register user berhasil dan tetap memaksa:
   - `role = user`
   - `status = pending`
   - password hash.
5. Login pending, rejected, inactive, dan wrong password ditolak.
6. Superadmin approval berhasil mengubah user pending menjadi approved.
7. Redirect role user, admin, dan superadmin berjalan.
8. Authorization role menghasilkan `403` sesuai scope.
9. Public service list, detail layanan, sublayanan, dan pricing berjalan.
10. Superadmin CRUD kategori dan sub layanan berjalan.
11. Superadmin assignment admin berjalan.
12. Admin hanya dapat mengubah harga sub layanan sesuai assignment.
13. Inactive sub service tidak tampil public.
14. Consultation waiting payment berhasil dibuat.
15. Payment pending Midtrans Sandbox berhasil dibuat dan menyimpan Snap token.
16. Webhook Midtrans sintetis berhasil memproses:
    - `cancel`
    - `settlement`
    - duplicate `settlement`.
17. Invalid webhook signature ditolak HTTP `403`.
18. Consultation aktif hanya setelah payment `paid`.
19. Admin pipeline pending, cancelled, paid, active, dan superadmin pipeline berjalan.
20. Chat locked sebelum paid dan pada cancelled consultation.
21. User dan admin assignment dapat berkirim pesan pada consultation active.
22. `after_id` chat berjalan.
23. Pesan kosong dan terlalu panjang ditolak.
24. User lain dan admin di luar assignment tidak dapat membuka chat.
25. Closed consultation tetap readable dan menolak pesan baru.
26. Payment status hanya dapat dibaca owner.
27. Server Key Midtrans tidak tampil di halaman payment.
28. Theme toggle tidak tersedia dan `theme-toggle.js` mengembalikan HTTP `404`.
29. Session cookie diperkuat dengan `HttpOnly`, `SameSite=Lax`, dan `Secure` otomatis saat HTTPS.
30. Database lokal direset kembali ke seed awal setelah pengujian.

Bug yang diperbaiki Phase 10:

1. Session cookie belum mengatur `HttpOnly`, `SameSite=Lax`, dan `Secure` berbasis HTTPS. Perbaikan dilakukan di `app/Core/Session.php`.

Hasil verifikasi akhir Phase 10:

1. Syntax PHP seluruh `app/`, `config/`, `routes/`, dan `public/` lolos `php -l`.
2. `GET /` berhasil HTTP `200`.
3. `GET /login` berhasil HTTP `200`.
4. `GET /_dev/db-check` berhasil membaca tiga kategori layanan.
5. Login superadmin seed berhasil HTTP `200` ke dashboard.
6. Database akhir kembali ke seed:
   - 8 user;
   - 3 kategori;
   - 6 sub layanan;
   - 3 assignment;
   - 0 consultation.

## Keputusan yang Sudah Ditetapkan

1. Sistem versi baru berada pada folder `v2.0`.
2. Sistem menggunakan PHP native.
3. Database menggunakan MySQL lokal terlebih dahulu.
4. Backend menggunakan arsitektur MVC.
5. Sistem berupa website dengan landing page.
6. Sistem menyediakan layanan konsultasi teknologi.
7. Pada versi awal terdapat tiga kategori layanan:
   - Network Architecture
   - Database Architecture
   - Web Server & Virtualization
8. Sistem memiliki stakeholder:
   - Pengguna
   - Admin
   - Superadmin
9. Pengguna harus registrasi terlebih dahulu.
10. Akun pengguna harus disetujui oleh superadmin sebelum dapat login.
11. Pengguna memilih sublayanan dan membayar sebelum chat aktif.
12. Admin bekerja sesuai assignment layanan.
13. Superadmin mengelola approval user dan role admin.
14. Sistem dibuat sederhana dan tidak menambahkan fitur di luar kebutuhan awal.

## Batasan Saat Ini

1. Belum menggunakan database Tailscale.
2. Pengujian Midtrans real masih perlu dipastikan stabil dengan Sandbox key dan URL webhook publik/tunnel HTTPS.
3. End-to-end local testing penuh sudah dikerjakan dan database lokal dikembalikan ke seed awal.

## Pembahasan Berikutnya

Topik yang perlu dibahas berikutnya:

1. Menguji Snap popup manual di browser dengan transaksi Sandbox real.
2. Menguji webhook memakai tunnel HTTPS publik secara manual dari dashboard Midtrans.
3. Phase 11 - Move Database to Tailscale setelah local E2E stabil.
