# Zeta

Fondasi awal sistem konsultasi teknologi berbasis PHP native MVC.

## Local Setup

1. Salin `.env.example` menjadi `.env`.
2. Sesuaikan konfigurasi MySQL lokal.
3. Buat database `db_consultation_v2`.
4. Import `database/schema.sql`.
5. Import `database/seed.sql`.
6. Arahkan web server ke folder `public/`.

## Development Routes

- `GET /` menampilkan halaman verifikasi aplikasi.
- `GET /_dev/db-check` mengecek koneksi database saat `APP_ENV=development`.
