# cPanel Setup - zeta.informatika.site

Panduan utama:

```text
v2.0/docs/cpanel-setup-zeta-informatika-site.md
```

File SQL phpMyAdmin:

```text
v2.0/database/zeta_informatika_site_phpmyadmin_import.sql
```

Struktur cPanel:

```text
v2.0/public/* -> /home/zeta/public_html/
v2.0/app/     -> /home/zeta/app/
v2.0/config/  -> /home/zeta/config/
v2.0/routes/  -> /home/zeta/routes/
v2.0/vendor/  -> /home/zeta/vendor/
v2.0/.env     -> /home/zeta/.env
```

Contoh `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://zeta.informatika.site
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=zeta_informatika.site
DB_USERNAME=ISI_USERNAME_DATABASE_CPANEL
DB_PASSWORD=ISI_PASSWORD_DATABASE_CPANEL
```

Jika nama database dengan titik tidak diterima hosting, buat database tanpa titik dan sesuaikan `DB_DATABASE`.
