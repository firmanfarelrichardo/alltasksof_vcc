\
# UI Design Guidelines

## Design Goal

Desain UI sistem konsultasi teknologi `v2.0` harus terlihat modern, premium, profesional, dan tetap mudah digunakan. Sistem menggabungkan dua referensi visual:

1. Referensi pertama sebagai prioritas utama untuk landing page dan halaman publik.
2. Referensi kedua sebagai prioritas untuk dashboard admin dan superadmin.

Tujuan akhirnya adalah membuat sistem terlihat seperti platform konsultasi teknologi profesional, bukan sekadar website sederhana.

---

# Visual Identity

## Personality

Karakter visual sistem:

1. Modern.
2. Futuristic.
3. Premium.
4. Clean.
5. Professional.
6. Trustworthy.
7. Technology-oriented.

## Keywords

Gunakan kata kunci berikut sebagai acuan desain:

```text
dark futuristic
premium technology
glassmorphism
clean dashboard
minimal admin panel
soft glow
consistent typography
smooth animation
professional consulting platform
```

---

# Color System

## Core Colors

Gunakan sistem warna berbasis CSS variables untuk tema gelap saja.

```css
:root {
  --color-primary: #3b82f6;
  --color-primary-dark: #1d4ed8;
  --color-secondary: #8b5cf6;
  --color-accent: #22d3ee;

  --color-success: #22c55e;
  --color-warning: #f59e0b;
  --color-danger: #ef4444;
  --color-info: #38bdf8;

  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 22px;
  --radius-xl: 32px;
}
```

## Dark Theme Variables

```css
:root {
  --bg-body: #050816;
  --bg-surface: rgba(15, 23, 42, 0.76);
  --bg-surface-solid: #0f172a;
  --bg-card: rgba(255, 255, 255, 0.06);

  --text-primary: #ffffff;
  --text-secondary: #cbd5e1;
  --text-muted: #94a3b8;

  --border-soft: rgba(255, 255, 255, 0.12);
  --shadow-soft: 0 24px 80px rgba(0, 0, 0, 0.45);
  --glow-primary: 0 0 40px rgba(59, 130, 246, 0.35);
}
```

## Usage Rules

1. Landing page default menggunakan dark theme.
2. Admin dan superadmin dashboard default menggunakan dark productivity theme.
3. Theme toggle tidak tersedia.
4. Aksen utama menggunakan biru.
5. Aksen sekunder menggunakan ungu atau cyan.
6. Warna merah hanya untuk error atau aksi berbahaya.
7. Warna hijau hanya untuk status berhasil atau pembayaran sukses.

---

# Typography System

## Font Family

Gunakan satu font utama secara konsisten.

```css
body {
  font-family: Inter, "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}
```

## Heading Style

Landing page boleh menggunakan heading besar.

```css
.hero-title {
  font-size: clamp(2.8rem, 7vw, 6rem);
  line-height: 0.95;
  letter-spacing: -0.06em;
  font-weight: 600;
}
```

Dashboard menggunakan heading yang lebih fungsional.

```css
.dashboard-title {
  font-size: 1.75rem;
  line-height: 1.2;
  font-weight: 600;
}
```

## Body Text

```css
body {
  font-size: 16px;
  line-height: 1.6;
}

.text-muted {
  color: var(--text-muted);
}
```

## Typography Rules

1. Jangan menggunakan lebih dari dua weight secara berlebihan.
2. Gunakan font weight 400 untuk body.
3. Gunakan font weight 500 atau 600 untuk heading.
4. Hindari font terlalu dekoratif.
5. Gunakan line-height luas untuk paragraf.
6. Gunakan letter-spacing negatif hanya pada heading besar.

---

# Landing Page Design

## Main Style

Landing page harus mengikuti referensi pertama.

Ciri visual utama:

1. Background gelap.
2. Hero section dominan.
3. Visual abstrak teknologi.
4. Glass card.
5. Tombol CTA modern.
6. Navigasi minimal.
7. Elemen glow dan gradient.
8. Banyak ruang kosong.

## Hero Section

Hero section harus memiliki:

1. Navbar transparan atau semi-transparan.
2. Headline besar.
3. Deskripsi singkat.
4. CTA utama.
5. CTA sekunder.
6. Visual abstrak atau ilustrasi teknologi.
7. Card kecil sebagai highlight.

Contoh struktur:

```html
<section class="hero-section">
  <nav class="navbar"></nav>
  <div class="hero-content">
    <div class="hero-copy"></div>
    <div class="hero-visual"></div>
  </div>
</section>
```

## Hero Copy Direction

Tone headline:

```text
Konsultasi Infrastruktur Teknologi yang Lebih Terarah.
```

Alternatif:

```text
Bangun Arsitektur Network, Database, dan Server dengan Konsultan yang Tepat.
```

## Hero Visual

Hero visual tidak harus berupa gambar kompleks. Jika belum ada asset, dapat dibuat dengan CSS:

1. Gradient orb.
2. Abstract panel.
3. Floating card.
4. Network line.
5. Server block.
6. Glassmorphism container.

## Public Card Style

```css
.glass-card {
  background: var(--bg-card);
  border: 1px solid var(--border-soft);
  border-radius: var(--radius-lg);
  backdrop-filter: blur(18px);
  box-shadow: var(--shadow-soft);
}
```

---

# Dashboard Design

## Main Style

Dashboard admin dan superadmin mengikuti referensi kedua.

Ciri visual utama:

1. Light background.
2. Sidebar kiri.
3. Topbar horizontal.
4. Card statistik.
5. Tabel data bersih.
6. Spacing lega.
7. Warna minimal.
8. Tampilan seperti productivity dashboard.

## Dashboard Layout

```text
Dashboard Shell
├── Sidebar
├── Main Area
│   ├── Topbar
│   ├── Summary Cards
│   ├── Filter/Search
│   ├── Table/List
│   └── Detail Panel
```

## Sidebar

Sidebar harus:

1. Tetap di kiri pada desktop.
2. Memiliki logo.
3. Menampilkan menu sesuai role.
4. Memberi indikator pada menu aktif.
5. Memiliki logout di bagian bawah.

## Admin Menu

Menu admin:

1. Dashboard.
2. Konsultasi Masuk.
3. Riwayat Konsultasi.
4. Sublayanan.
5. Harga Konsultasi.
6. Keuangan.
7. Logout.

## Superadmin Menu

Menu superadmin:

1. Dashboard.
2. Approval Pengguna.
3. Manajemen Admin.
4. Role Admin.
5. Layanan.
6. Sublayanan.
7. Semua Konsultasi.
8. Semua Transaksi.
9. Keuangan.
10. Logout.

## Dashboard Card

```css
.dashboard-card {
  background: var(--bg-card);
  border: 1px solid var(--border-soft);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-soft);
  padding: 1.25rem;
}
```

## Table Style

Tabel harus:

1. Memiliki header jelas.
2. Menggunakan row hover.
3. Menampilkan status badge.
4. Memiliki aksi edit, detail, approve, reject, atau delete sesuai konteks.
5. Tidak menggunakan border tebal.

---

# Button System

## Primary Button

```css
.btn-primary {
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  color: #ffffff;
  border: none;
  border-radius: 999px;
  padding: 0.85rem 1.25rem;
  transition: transform 250ms ease, box-shadow 250ms ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--glow-primary);
}
```

## Secondary Button

```css
.btn-secondary {
  background: transparent;
  color: var(--text-primary);
  border: 1px solid var(--border-soft);
  border-radius: 999px;
  padding: 0.85rem 1.25rem;
}
```

## Dashboard Button

Dashboard button boleh lebih kotak dan praktis.

```css
.btn-dashboard {
  border-radius: 10px;
  padding: 0.65rem 1rem;
  font-size: 0.875rem;
}
```

---

# Form Design

## Form Rules

1. Semua input harus memiliki label.
2. Error message harus tampil di bawah input.
3. Placeholder tidak menggantikan label.
4. Fokus input memiliki border aksen biru.
5. Form dashboard harus sederhana.

## Input Style

```css
.form-control {
  width: 100%;
  border: 1px solid var(--border-soft);
  border-radius: var(--radius-sm);
  padding: 0.8rem 1rem;
  background: var(--bg-surface);
  color: var(--text-primary);
  transition: border-color 200ms ease, box-shadow 200ms ease;
}

.form-control:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}
```

---

# Chat UI

## Chat Page Style

Chat consultation page harus sederhana dan fokus pada percakapan.

## Chat Layout

```text
Chat Page
├── Consultation Header
├── Message List
└── Message Input
```

## Message Bubble

1. Pesan pengguna berada di kanan.
2. Pesan admin berada di kiri.
3. Gunakan warna berbeda secara halus.
4. Tampilkan waktu pesan.
5. Jangan membuat bubble terlalu lebar.

## Chat Rules

1. Chat aktif hanya setelah pembayaran valid.
2. Jika pembayaran belum valid, tampilkan locked state.
3. Chat tidak perlu real-time kompleks pada versi awal.
4. Gunakan desain ringan agar mudah dibuat dengan PHP native.

---

# Animation System

## Motion Principle

Animasi harus memperkuat kesan premium tanpa mengganggu fungsi.

## CSS Variables

```css
:root {
  --ease-smooth: cubic-bezier(0.22, 1, 0.36, 1);
  --duration-fast: 150ms;
  --duration-normal: 250ms;
  --duration-slow: 500ms;
}
```

## Landing Animation

Gunakan animasi:

1. Fade-in pada hero title.
2. Slide-up pada hero description.
3. Floating pada hero visual.
4. Glow pulse sangat halus pada orb.
5. Hover lift pada service card.

Contoh:

```css
@keyframes floatSoft {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-14px);
  }
}

.hero-visual {
  animation: floatSoft 6s ease-in-out infinite;
}
```

## Dashboard Animation

Dashboard animasi lebih minimal:

1. Card hover shadow.
2. Sidebar active indicator transition.
3. Modal fade-in.
4. Table row hover.
5. Button hover.

## Theme Transition

```css
body,
.dashboard-card,
.glass-card,
.sidebar,
.topbar {
  transition:
    background-color var(--duration-normal) ease,
    color var(--duration-normal) ease,
    border-color var(--duration-normal) ease;
}
```

---

# Spacing System

Gunakan spacing konsisten.

```css
:root {
  --space-1: 0.25rem;
  --space-2: 0.5rem;
  --space-3: 0.75rem;
  --space-4: 1rem;
  --space-5: 1.5rem;
  --space-6: 2rem;
  --space-7: 3rem;
  --space-8: 4rem;
}
```

## Rules

1. Jangan menempelkan elemen terlalu dekat.
2. Dashboard butuh jarak yang lega tetapi tetap informatif.
3. Landing page menggunakan vertical spacing besar.
4. Form menggunakan spacing sedang agar mudah diisi.

---

# Border Radius

Gunakan radius konsisten.

```css
:root {
  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 22px;
  --radius-xl: 32px;
  --radius-full: 999px;
}
```

## Usage

1. Button public: `radius-full`.
2. Dashboard button: `radius-sm`.
3. Card dashboard: `radius-md`.
4. Hero card: `radius-lg` atau `radius-xl`.

---

# Status Badge

Gunakan badge untuk status:

1. Pending.
2. Approved.
3. Rejected.
4. Active.
5. Closed.
6. Paid.
7. Failed.

Contoh:

```css
.badge {
  display: inline-flex;
  align-items: center;
  border-radius: 999px;
  padding: 0.35rem 0.65rem;
  font-size: 0.75rem;
  font-weight: 500;
}
```

---

# Empty State

Setiap halaman data harus punya empty state.

Contoh:

```text
Belum ada konsultasi masuk.
```

```text
Belum ada transaksi pada layanan ini.
```

```text
Belum ada pengguna yang menunggu approval.
```

Empty state harus memiliki:

1. Judul singkat.
2. Deskripsi.
3. Tombol aksi jika relevan.

---

# Loading State

Gunakan loading state sederhana:

1. Spinner kecil.
2. Skeleton card.
3. Disabled button saat proses submit.

Jangan membuat loading animasi terlalu kompleks.

---

# Responsive Rules

## Landing Page

Pada mobile:

1. Hero menjadi satu kolom.
2. Visual hero turun ke bawah.
3. CTA tersusun vertikal.
4. Navbar berubah menjadi menu sederhana.

## Dashboard

Pada mobile:

1. Sidebar berubah menjadi drawer.
2. Topbar tetap terlihat.
3. Card menjadi satu kolom.
4. Tabel bisa horizontal scroll.
5. Action button tetap mudah diklik.

---

# Implementation Notes for PHP Native

Karena sistem menggunakan PHP native:

1. Gunakan file partial untuk layout berulang.
2. Pisahkan header, footer, sidebar, dan topbar.
3. Gunakan file CSS terpusat.
4. Gunakan JavaScript ringan untuk theme toggle, sidebar toggle, modal, dan animasi sederhana.
5. Jangan membuat struktur frontend terlalu kompleks.

Contoh partial:

```text
views/
├── layouts/
│   ├── public-header.php
│   ├── public-footer.php
│   ├── dashboard-sidebar.php
│   └── dashboard-topbar.php
```

Contoh assets:

```text
public/
└── assets/
    ├── css/
    │   ├── main.css
    │   ├── theme.css
    │   ├── landing.css
    │   └── dashboard.css
    └── js/
        ├── sidebar.js
        └── modal.js
```

---

# Final UI Rule

Prioritas desain:

```text
Landing page dan public page: referensi gambar 1.
Admin dan superadmin dashboard: referensi gambar 2.
```

AI agent harus mempertahankan perbedaan karakter ini agar website terasa premium untuk pengguna publik, tetapi tetap produktif dan bersih untuk admin.
