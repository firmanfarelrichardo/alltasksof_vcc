\
# Frontend Context

## Purpose

Dokumen ini menjelaskan konteks frontend untuk sistem konsultasi teknologi versi `v2.0`. AI agent harus membaca dokumen ini sebelum membuat atau mengubah tampilan frontend.

Frontend harus mendukung pengalaman pengguna yang modern, profesional, konsisten, dan tetap sederhana. Desain mengambil inspirasi utama dari referensi gambar pertama untuk halaman publik dan landing page, serta referensi gambar kedua untuk dashboard admin dan superadmin.

---

# Frontend Scope

## Public Area

Area publik adalah bagian website yang dapat diakses tanpa login.

Halaman publik meliputi:

1. Landing page.
2. Daftar layanan.
3. Detail layanan.
4. Detail sublayanan.
5. Daftar harga konsultasi.
6. Profil kompetensi konsultan.
7. Login.
8. Registrasi pengguna.

## User Area

Area pengguna hanya dapat diakses setelah pengguna login dan akun sudah disetujui oleh superadmin.

Halaman pengguna meliputi:

1. Dashboard pengguna.
2. Riwayat konsultasi pengguna.
3. Detail konsultasi.
4. Halaman pembayaran.
5. Halaman chat konsultasi.

## Admin Area

Area admin digunakan oleh admin layanan untuk mengelola konsultasi sesuai layanan yang menjadi tanggung jawabnya.

Halaman admin meliputi:

1. Dashboard admin.
2. Daftar konsultasi masuk.
3. Detail konsultasi.
4. Chat konsultasi.
5. Manajemen sublayanan.
6. Edit harga konsultasi.
7. Riwayat konsultasi.
8. Data keuangan layanan.

## Superadmin Area

Area superadmin digunakan oleh superadmin untuk mengatur sistem secara keseluruhan.

Halaman superadmin meliputi:

1. Dashboard superadmin.
2. Approval akun pengguna.
3. Manajemen admin.
4. Penetapan role admin.
5. Penetapan admin ke layanan.
6. Manajemen layanan.
7. Manajemen sublayanan.
8. Seluruh riwayat konsultasi.
9. Seluruh data transaksi dan keuangan.

---

# Design Direction

## Main Design Reference

Desain utama untuk landing page dan halaman publik mengikuti karakter referensi gambar pertama:

1. Dark futuristic.
2. Premium technology look.
3. Visual besar pada hero section.
4. Penggunaan glassmorphism.
5. Kontras tinggi antara background gelap dan teks terang.
6. Elemen cahaya, gradien, dan glow yang halus.
7. Tampilan modern, eksklusif, dan meyakinkan.

## Secondary Design Reference

Desain dashboard admin dan superadmin mengikuti karakter referensi gambar kedua:

1. Clean dashboard.
2. Layout sidebar kiri.
3. Area konten luas.
4. Card-based interface.
5. Data disusun dalam tabel, kartu, dan panel statistik.
6. Warna lebih netral dan terang.
7. Cocok untuk aktivitas manajemen data.

## Design Combination

Frontend harus menggabungkan dua pendekatan:

1. Halaman publik menggunakan gaya dark premium futuristic.
2. Dashboard admin dan superadmin menggunakan gaya clean productivity dashboard.
3. User dashboard dapat memakai kombinasi keduanya, yaitu tetap modern namun lebih sederhana dan mudah digunakan.
4. Sistem menggunakan mode gelap saja.

---

# Theme Mode

Frontend menggunakan satu mode tampilan:

1. Dark mode.

## Dark Mode

Dark mode menjadi mode utama untuk landing page, halaman publik, auth, user dashboard, admin dashboard, dan superadmin dashboard.

Karakter dark mode:

1. Background gelap dengan aksen biru, ungu, dan cyan.
2. Teks putih atau abu terang.
3. Card semi-transparan.
4. Border tipis dengan warna transparan.
5. Efek glow lembut pada tombol utama dan elemen penting.
6. Cocok untuk kesan teknologi dan konsultasi profesional.

## Light Mode

Light mode tidak digunakan.

## Theme Toggle

Sistem tidak menggunakan tombol pengganti tema karena seluruh UI memakai tema gelap.

---

# Typography

## Font Direction

Gunakan tipografi yang modern, bersih, dan mudah dibaca.

Rekomendasi font:

1. `Inter`
2. `Plus Jakarta Sans`
3. `Poppins`
4. `System UI`

Jika tidak menggunakan CDN, gunakan fallback:

```css
font-family: Inter, "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
```

## Typography Rules

1. Gunakan satu font utama untuk seluruh sistem.
2. Jangan mencampur terlalu banyak jenis font.
3. Heading harus tegas dan memiliki jarak antarbaris yang nyaman.
4. Body text harus mudah dibaca.
5. Dashboard harus menggunakan ukuran font yang lebih fungsional dan tidak terlalu besar.
6. Landing page boleh menggunakan heading besar dan dramatis.

## Suggested Font Scale

```css
--font-xs: 0.75rem;
--font-sm: 0.875rem;
--font-md: 1rem;
--font-lg: 1.125rem;
--font-xl: 1.5rem;
--font-2xl: 2rem;
--font-3xl: 3rem;
--font-4xl: 4rem;
```

---

# Layout Rules

## Public Layout

Public layout terdiri dari:

1. Navbar.
2. Hero section.
3. Services preview.
4. Consultant competence section.
5. Pricing section.
6. Consultation flow section.
7. Call to action.
8. Footer.

## Landing Page Layout

Landing page harus dibuat dengan visual yang kuat.

Struktur landing page:

```text
Navbar
  |
Hero Section
  |
Service Categories
  |
Consultant Competence
  |
Pricing Preview
  |
How Consultation Works
  |
CTA Section
  |
Footer
```

## Dashboard Layout

Dashboard admin dan superadmin menggunakan layout seperti referensi gambar kedua.

Struktur dashboard:

```text
Sidebar
  |
  |--- Topbar
  |--- Content Area
        |--- Summary Cards
        |--- Table / Board / List
        |--- Detail Panel
```

## Sidebar Rules

Sidebar dashboard harus memiliki:

1. Logo atau nama sistem.
2. Menu utama.
3. Menu sesuai role.
4. Informasi akun.
5. Logout button.

## Content Rules

Area konten dashboard harus:

1. Bersih.
2. Memiliki ruang kosong yang cukup.
3. Menggunakan card untuk pengelompokan data.
4. Memiliki tabel yang rapi.
5. Tidak terlalu penuh dengan dekorasi.

---

# Page-Specific Context

## Landing Page

Landing page harus menampilkan:

1. Headline utama tentang konsultasi teknologi.
2. Deskripsi singkat sistem.
3. CTA untuk melihat layanan atau mulai konsultasi.
4. Visual teknologi yang dominan.
5. Tiga kategori layanan utama.
6. Kompetensi tiga konsultan.
7. Harga atau rentang harga konsultasi.
8. Alur konsultasi: pilih layanan, bayar, chat.
9. Tombol login dan registrasi.

Gaya landing page mengikuti gambar pertama:

1. Dark background.
2. Hero besar.
3. Elemen visual abstrak teknologi.
4. Card transparan.
5. Tombol utama dengan warna aksen terang.
6. Animasi halus pada elemen hero.

## Services Page

Halaman layanan menampilkan tiga kategori utama:

1. Network Architecture.
2. Database Architecture.
3. Web Server & Virtualization.

Setiap kategori memiliki card dengan:

1. Nama layanan.
2. Deskripsi singkat.
3. Daftar sublayanan.
4. Harga mulai dari.
5. Tombol lihat detail.

## Sub Service Detail Page

Halaman detail sublayanan menampilkan:

1. Nama sublayanan.
2. Kategori layanan.
3. Deskripsi.
4. Harga konsultasi.
5. Admin atau konsultan terkait.
6. Tombol pilih konsultasi.
7. Informasi bahwa chat aktif setelah pembayaran valid.

## Login Page

Login page harus sederhana.

Elemen:

1. Email.
2. Password.
3. Tombol login.
4. Link registrasi.
5. Informasi bahwa akun pengguna harus disetujui superadmin.

## Register Page

Register page harus menampilkan:

1. Nama.
2. Email.
3. Password.
4. Konfirmasi password.
5. Tombol daftar.
6. Pesan bahwa akun menunggu persetujuan superadmin.

## User Dashboard

Dashboard pengguna menampilkan:

1. Status akun.
2. Konsultasi aktif.
3. Riwayat konsultasi.
4. Rekomendasi layanan.
5. Status pembayaran.

## Chat Consultation Page

Halaman chat harus sederhana dan fokus.

Elemen:

1. Header konsultasi.
2. Nama sublayanan.
3. Status konsultasi.
4. Area pesan.
5. Input pesan.
6. Tombol kirim.

Chat tidak perlu real-time kompleks pada versi awal. Refresh manual atau polling sederhana dapat digunakan jika diperlukan.

## Admin Dashboard

Dashboard admin mengikuti referensi gambar kedua.

Elemen:

1. Sidebar.
2. Topbar.
3. Card statistik.
4. Daftar konsultasi masuk.
5. Riwayat konsultasi.
6. Data keuangan layanan.
7. Menu sublayanan.
8. Menu edit harga.

## Superadmin Dashboard

Dashboard superadmin juga mengikuti referensi gambar kedua.

Elemen:

1. Sidebar.
2. Topbar.
3. Card total pengguna.
4. Card pengguna pending approval.
5. Card total transaksi.
6. Card total layanan.
7. Tabel approval pengguna.
8. Tabel admin.
9. Tabel layanan.
10. Tabel transaksi.

---

# Component Guidelines

## Public Components

Komponen public:

1. Navbar.
2. HeroSection.
3. ServiceCard.
4. ConsultantCard.
5. PricingCard.
6. CTASection.
7. Footer.

## Dashboard Components

Komponen dashboard:

1. Sidebar.
2. Topbar.
3. StatCard.
4. DataTable.
5. FilterBar.
6. SearchInput.
7. StatusBadge.
8. ActionButton.
9. EmptyState.
10. Modal.
11. FormGroup.

## Chat Components

Komponen chat:

1. ChatHeader.
2. MessageBubble.
3. MessageList.
4. MessageInput.
5. ConsultationStatusBadge.

---

# Animation Guidelines

## General Animation Rules

Animasi harus halus, profesional, dan tidak mengganggu fungsi sistem.

Gunakan animasi untuk:

1. Hover pada tombol.
2. Transisi card.
3. Munculnya section landing page.
4. Sidebar aktif.
5. Modal.
6. Loading state.
7. Perubahan state hover, focus, dan loading pada tema gelap.

## Animation Style

Karakter animasi:

1. Smooth.
2. Subtle.
3. Fast enough.
4. Tidak berlebihan.
5. Tidak membuat website terasa berat.

## Suggested Durations

```css
--transition-fast: 150ms ease;
--transition-normal: 250ms ease;
--transition-slow: 400ms ease;
```

## Recommended Effects

1. Button hover: naik sedikit dan glow lembut.
2. Card hover: border lebih terang dan shadow meningkat.
3. Hero visual: floating animation pelan.
4. Dashboard card hover: shadow lembut.
5. Modal: fade dan scale kecil.
6. Hindari theme switch; gunakan transisi hover dan focus pada elemen aktif.

## Avoid

Jangan gunakan animasi yang:

1. Terlalu cepat.
2. Terlalu ramai.
3. Membuat teks sulit dibaca.
4. Mengganggu input form.
5. Menghambat dashboard admin.

---

# Accessibility Rules

1. Kontras teks harus jelas.
2. Tombol harus mudah terlihat.
3. Form harus memiliki label.
4. Error message harus jelas.
5. Jangan hanya mengandalkan warna untuk status.
6. Gunakan ukuran font yang nyaman dibaca.
7. Pastikan layout tetap rapi di mobile.

---

# Responsive Design

Frontend harus responsif.

## Desktop

Desktop menjadi prioritas utama untuk dashboard admin dan superadmin.

## Tablet

Layout harus tetap rapi dengan sidebar yang dapat diperkecil.

## Mobile

Pada mobile:

1. Navbar menjadi menu collapsible.
2. Sidebar dashboard dapat berubah menjadi drawer.
3. Card tersusun satu kolom.
4. Tabel dapat menggunakan horizontal scroll.
5. Chat tetap mudah digunakan.

---

# Forbidden UI Behavior

AI agent tidak boleh:

1. Membuat desain yang terlalu mirip template AI generik.
2. Mencampur terlalu banyak warna.
3. Menggunakan font yang tidak konsisten.
4. Membuat dashboard gelap dengan kontras rendah untuk konteks admin/superadmin.
5. Membuat public landing page terlalu polos.
6. Menambahkan kembali light mode atau theme toggle tanpa instruksi baru.
7. Menambahkan fitur UI di luar scope tanpa instruksi.
8. Membuat animasi berlebihan.
