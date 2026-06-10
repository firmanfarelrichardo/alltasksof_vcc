# Database Local Development

Database awal menggunakan MySQL lokal.

## Konfigurasi Default

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
```

## Import

PowerShell:

```powershell
Get-Content database/schema.sql | mysql -h 127.0.0.1 -P 3306 -u root
Get-Content database/seed.sql | mysql -h 127.0.0.1 -P 3306 -u root
```

Bash:

```bash
mysql -u root < database/schema.sql
mysql -u root < database/seed.sql
```

Jika user MySQL lokal memiliki password:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

## Akun Seed Development

- Superadmin: `superadmin@example.local`
- Admin Network: `admin.network@example.local`
- Admin Database: `admin.database@example.local`
- Admin Server: `admin.server@example.local`
- User Approved: `user.approved@example.local`
- User Pending: `user.pending@example.local`
- User Rejected: `user.rejected@example.local`
- User Inactive: `user.inactive@example.local`

Semua akun seed menggunakan password development:

```text
password
```

Password seed adalah placeholder development dan wajib diganti sebelum production.
