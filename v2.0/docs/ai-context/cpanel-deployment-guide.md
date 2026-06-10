# cPanel Deployment Guide - Zeta v2.0

Panduan operasional utama tersedia di:

```text
v2.0/docs/cpanel-deployment-guide.md
```

Ringkasan aturan deploy:

1. Document root domain harus mengarah ke `v2.0/public`.
2. `.env`, `app/`, `config/`, `database/`, `routes/`, dan `vendor/` harus berada di luar web root.
3. Gunakan PHP 8.2 atau minimal PHP 8.1 dengan extension `pdo_mysql`, `curl`, `openssl`, `json`, `mbstring`, dan `fileinfo`.
4. Jalankan `composer install --no-dev --optimize-autoloader` atau upload folder `vendor/` dari lokal.
5. Buat database MySQL cPanel dan user khusus aplikasi.
6. Import `schema.sql` lalu `seed.sql`, atau import backup production.
7. Gunakan `.env` production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN_ANDA
```

8. Midtrans callback harus memakai URL HTTPS publik:

```text
https://DOMAIN_ANDA/payments/midtrans/notification
```

9. Jangan expose `MIDTRANS_SERVER_KEY`, `.env`, atau folder internal aplikasi.
10. Setelah deploy, test login, service catalog, consultation, payment Sandbox, webhook, dan chat.
