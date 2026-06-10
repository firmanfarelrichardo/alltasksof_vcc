# Stakeholders and Roles

## Daftar Stakeholder

Sistem memiliki tiga jenis stakeholder utama:

1. Pengguna
2. Admin
3. Superadmin

## Pengguna

Pengguna adalah pihak yang membutuhkan layanan konsultasi teknologi.

### Hak Akses Pengguna

Pengguna dapat:

1. Melihat landing page.
2. Melihat daftar layanan.
3. Melihat detail layanan dan sublayanan.
4. Melihat daftar harga konsultasi.
5. Melihat kompetensi konsultan atau admin.
6. Melakukan registrasi akun.
7. Login setelah akun disetujui oleh superadmin.
8. Memilih sublayanan konsultasi.
9. Membayar biaya konsultasi.
10. Melakukan percakapan konsultasi melalui chat internal website.
11. Melihat riwayat konsultasi miliknya sendiri.

### Batasan Pengguna

Pengguna tidak dapat:

1. Login sebelum akunnya disetujui superadmin.
2. Mengakses dashboard admin.
3. Mengubah data layanan.
4. Mengubah harga konsultasi.
5. Melihat riwayat konsultasi pengguna lain.
6. Melihat data keuangan seluruh sistem.

## Admin

Admin adalah pihak yang bertugas sebagai konsultan sesuai bidang layanan tertentu. Pada versi awal terdapat tiga admin utama berdasarkan tiga bidang layanan, tetapi sistem harus tetap fleksibel jika nantinya ada admin baru atau layanan baru.

### Hak Akses Admin

Admin dapat:

1. Login ke dashboard admin.
2. Melihat konsultasi yang berkaitan dengan layanan yang ditugaskan kepadanya.
3. Membalas pesan konsultasi dari pengguna.
4. Melihat riwayat konsultasi pada layanan yang menjadi tanggung jawabnya.
5. Melakukan CRUD sublayanan konsultasi sesuai hak aksesnya.
6. Mengedit harga konsultasi sesuai layanan yang dikelolanya.
7. Melihat data keuangan dari layanan yang dikelolanya.

### Batasan Admin

Admin tidak dapat:

1. Mengatur role admin lain kecuali diberikan hak oleh superadmin.
2. Mengakses seluruh data keuangan jika tidak diberi akses.
3. Menyetujui akun pengguna jika kewenangan tersebut hanya diberikan kepada superadmin.
4. Mengubah data layanan di luar bidang yang ditetapkan.

## Superadmin

Superadmin adalah pihak dengan hak akses tertinggi dalam sistem.

### Hak Akses Superadmin

Superadmin dapat:

1. Login ke dashboard superadmin.
2. Menyetujui atau menolak registrasi pengguna.
3. Membuat, mengubah, dan menonaktifkan admin.
4. Menetapkan role admin.
5. Menentukan admin bertanggung jawab pada layanan tertentu.
6. Melihat seluruh data konsultasi.
7. Melihat seluruh data transaksi dan keuangan.
8. Mengelola layanan dan sublayanan.
9. Mengatur harga konsultasi.
10. Mengakses seluruh fitur admin.

## Fleksibilitas Role

Sistem tidak boleh mengunci admin hanya menjadi tiga orang secara permanen. Walaupun versi awal memiliki tiga bidang layanan, struktur role harus memungkinkan:

1. Penambahan admin baru.
2. Penambahan layanan baru.
3. Satu admin mengelola satu atau lebih layanan.
4. Satu layanan dapat dikelola oleh satu atau lebih admin jika dibutuhkan.
