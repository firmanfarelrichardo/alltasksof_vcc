# Export SQL to Tailscale Ubuntu Server Guide

Panduan operasional utama tersedia di:

```text
v2.0/docs/export-sql-to-tailscale-ubuntu-guide.md
```

Ringkasan:

1. Export database lokal `db_consultation_v2` menggunakan `mysqldump`.
2. Simpan file `.sql` ke `v2.0/database/backups/`.
3. Kirim file ke Ubuntu Server teman via `scp` melalui IP Tailscale `100.110.81.10`.
4. Buat database remote `db_consultation_v2`.
5. Import file `.sql` ke MySQL Ubuntu Server.
6. Verifikasi jumlah data remote.
7. Test koneksi dari mesin aplikasi menggunakan `DB_HOST=100.110.81.10`.
8. Ubah `.env` hanya setelah koneksi remote berhasil.
9. Hapus file temporary SQL dari `/tmp` setelah import.

Contoh export lokal:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
mysqldump --host=127.0.0.1 --port=3306 --user=root --single-transaction --routines --triggers db_consultation_v2 > "v2.0\database\backups\db_consultation_v2_export_$stamp.sql"
```

Contoh kirim ke Ubuntu Server:

```powershell
scp v2.0\database\backups\db_consultation_v2_export_YYYYMMDD-HHMMSS.sql USERNAME@100.110.81.10:/tmp/db_consultation_v2_export.sql
```

Contoh import di Ubuntu:

```bash
sudo mysql db_consultation_v2 < /tmp/db_consultation_v2_export.sql
```
