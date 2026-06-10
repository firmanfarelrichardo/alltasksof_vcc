# Business Rules

## Aturan Umum

1. Sistem dibuat sederhana sesuai kebutuhan awal.
2. Jangan menambahkan fitur di luar scope tanpa instruksi eksplisit.
3. Sistem memiliki tiga jenis role utama: pengguna, admin, dan superadmin.
4. Backend menggunakan arsitektur MVC.
5. Database menggunakan MySQL.
6. Sistem menggunakan PHP native.

## Aturan Pengguna

1. Pengguna dapat melihat landing page tanpa login.
2. Pengguna dapat melihat layanan, sublayanan, harga, dan kompetensi konsultan tanpa login.
3. Pengguna harus registrasi sebelum dapat melakukan konsultasi.
4. Pengguna tidak dapat login sebelum disetujui oleh superadmin.
5. Pengguna hanya dapat melakukan konsultasi setelah memilih sublayanan.
6. Pengguna hanya dapat masuk ke ruang chat konsultasi setelah pembayaran tercatat valid.
7. Pengguna hanya dapat melihat riwayat konsultasi miliknya sendiri.

## Aturan Admin

1. Admin hanya dapat mengelola layanan sesuai role atau penugasan dari superadmin.
2. Admin dapat membalas konsultasi yang masuk pada layanan yang dikelolanya.
3. Admin dapat melakukan CRUD sublayanan sesuai hak akses.
4. Admin dapat mengubah harga konsultasi sesuai hak akses.
5. Admin dapat melihat data keuangan dari layanan yang dikelolanya.
6. Admin tidak dapat menyetujui pengguna jika kewenangan tersebut hanya dimiliki superadmin.
7. Admin tidak boleh melihat data konsultasi di luar layanan yang dikelolanya kecuali diberi akses khusus.

## Aturan Superadmin

1. Superadmin memiliki akses tertinggi.
2. Superadmin menyetujui atau menolak registrasi pengguna.
3. Superadmin menetapkan role admin.
4. Superadmin dapat menambah admin baru.
5. Superadmin dapat mengatur admin untuk satu atau lebih layanan.
6. Superadmin dapat melihat seluruh data konsultasi.
7. Superadmin dapat melihat seluruh data transaksi dan keuangan.
8. Superadmin dapat mengelola seluruh layanan dan sublayanan.

## Aturan Layanan

1. Layanan terdiri dari kategori layanan dan sublayanan.
2. Setiap sublayanan harus memiliki harga konsultasi.
3. Setiap sublayanan harus berada di bawah satu kategori layanan.
4. Setiap sublayanan harus memiliki status aktif atau tidak aktif.
5. Sublayanan tidak aktif tidak ditampilkan kepada pengguna.
6. Harga yang digunakan pada transaksi adalah harga saat pengguna melakukan pembayaran.

## Aturan Konsultasi

1. Satu konsultasi terhubung dengan satu pengguna.
2. Satu konsultasi terhubung dengan satu sublayanan.
3. Satu konsultasi memiliki status.
4. Chat konsultasi hanya dapat dilakukan oleh pengguna terkait dan admin yang berwenang.
5. Semua pesan konsultasi harus disimpan dalam database.

## Aturan Pembayaran

1. Pembayaran dilakukan setelah pengguna memilih sublayanan.
2. Konsultasi hanya dapat dimulai setelah pembayaran valid.
3. Setiap pembayaran harus tercatat sebagai transaksi.
4. Transaksi harus terhubung dengan pengguna, sublayanan, dan konsultasi.
5. Admin hanya dapat melihat transaksi sesuai layanan yang dikelolanya.
6. Superadmin dapat melihat seluruh transaksi.
