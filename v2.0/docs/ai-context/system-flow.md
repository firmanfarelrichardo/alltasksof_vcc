# System Flow

## Flow Pengguna Baru

1. Pengguna membuka landing page.
2. Pengguna melihat informasi sistem.
3. Pengguna melihat daftar layanan dan sublayanan.
4. Pengguna melakukan registrasi akun.
5. Data registrasi masuk ke dashboard superadmin.
6. Superadmin meninjau akun pengguna.
7. Superadmin menyetujui atau menolak akun pengguna.
8. Jika disetujui, pengguna dapat login.
9. Jika ditolak, pengguna tidak dapat login.

## Flow Konsultasi Pengguna

1. Pengguna login ke sistem.
2. Pengguna membuka daftar layanan.
3. Pengguna melihat detail layanan.
4. Pengguna melihat detail sublayanan.
5. Pengguna memilih sublayanan.
6. Sistem menampilkan harga konsultasi.
7. Pengguna melakukan pembayaran biaya konsultasi.
8. Sistem mencatat transaksi.
9. Jika pembayaran berhasil atau valid, sistem membuat sesi konsultasi.
10. Pengguna masuk ke halaman percakapan konsultasi.
11. Pengguna mengirim pesan konsultasi.
12. Admin yang bertanggung jawab membalas pesan.
13. Percakapan tersimpan sebagai riwayat konsultasi.

## Flow Admin Membalas Konsultasi

1. Admin login ke dashboard admin.
2. Admin melihat daftar konsultasi sesuai layanan yang dikelolanya.
3. Admin membuka detail konsultasi.
4. Admin membaca pesan pengguna.
5. Admin mengirim balasan.
6. Sistem menyimpan pesan balasan.
7. Pengguna dapat melihat balasan di ruang chat konsultasi.

## Flow Manajemen Layanan oleh Admin

1. Admin login ke dashboard.
2. Admin membuka menu sublayanan.
3. Admin dapat menambah sublayanan baru.
4. Admin dapat mengedit sublayanan.
5. Admin dapat menghapus atau menonaktifkan sublayanan.
6. Admin dapat mengubah harga konsultasi sesuai hak akses.

## Flow Superadmin Approval Pengguna

1. Superadmin login ke dashboard.
2. Superadmin membuka daftar registrasi pengguna.
3. Superadmin melihat data pengguna yang menunggu persetujuan.
4. Superadmin memilih approve atau reject.
5. Jika approve, status akun berubah menjadi aktif.
6. Jika reject, akun tidak dapat digunakan untuk login.

## Flow Superadmin Mengatur Admin

1. Superadmin login ke dashboard.
2. Superadmin membuka menu manajemen admin.
3. Superadmin membuat akun admin baru atau mengedit admin lama.
4. Superadmin menetapkan role admin.
5. Superadmin menetapkan layanan yang dapat dikelola admin.
6. Sistem menyimpan hak akses admin.

## Flow Keuangan

1. Pengguna membayar biaya konsultasi.
2. Sistem mencatat transaksi.
3. Transaksi terhubung dengan sublayanan yang dipilih.
4. Transaksi terhubung dengan kategori layanan.
5. Admin dapat melihat transaksi pada layanan yang dikelolanya.
6. Superadmin dapat melihat seluruh transaksi sistem.
