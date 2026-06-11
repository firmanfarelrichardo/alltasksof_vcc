# cPanel Deployment Guide - Zeta v2.0

Panduan operasional utama tersedia di:

```text
v2.0/docs/cpanel-deployment-guide.md
```

Ringkasan aturan deploy sesuai struktur cPanel saat ini:

1. Document root domain adalah `/home/zeta/public_html`.
2. Isi `v2.0/public/` diupload ke `/home/zeta/public_html`.
3. Folder internal `app/`, `config/`, `database/`, `docs/`, `routes/`, `storage/`, `vendor/`, `composer.json`, `composer.lock`, dan `.env` diupload ke `/home/zeta`.
4. Jangan upload folder internal aplikasi ke `/home/zeta/public_html`.
5. Gunakan PHP 8.2 atau minimal PHP 8.1 dengan extension `pdo_mysql`, `curl`, `openssl`, `json`, `mbstring`, dan `fileinfo`.
6. Jalankan `composer install --no-dev --optimize-autoloader` di `/home/zeta` atau upload folder `vendor/` dari lokal.
7. Pilih database:
   - MySQL cPanel lokal hosting; atau
   - MySQL Ubuntu Server via IP Tailscale jika cPanel/VPS bisa mengakses Tailnet.
8. Jika memakai Tailscale, gunakan `DB_HOST=100.110.81.10` atau `DB_HOST=db.zeta.co.id`, dedicated user `consultation_app`, dan firewall Ubuntu yang hanya membuka `3306` dari IP Tailscale cPanel.
9. Import `schema.sql` lalu `seed.sql`, atau import backup production.
10. Gunakan `.env` production di `/home/zeta/.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://zeta.informatika.site
```

11. Midtrans callback harus memakai URL HTTPS publik:

```text
https://zeta.informatika.site/payments/midtrans/notification
```

12. Jangan expose `MIDTRANS_SERVER_KEY`, `.env`, atau folder internal aplikasi.
13. Setelah deploy, test login, service catalog, consultation, payment Sandbox, webhook, dan chat.
