# Technology Stack

## Prinsip Utama

Sistem menggunakan teknologi native dan sederhana agar mudah dipahami, dikembangkan, dan dijalankan.

## Backend

- Bahasa pemrograman: PHP native
- Arsitektur: MVC
- Database: MySQL
- Format response internal: disesuaikan dengan kebutuhan halaman PHP
- Autentikasi: session-based authentication sederhana
- Hak akses: role-based access control

## Frontend

- HTML native
- CSS native
- JavaScript native
- Tidak menggunakan framework frontend seperti React, Vue, Angular, atau Next.js pada versi awal

## Database

- DBMS: MySQL
- Koneksi: PDO atau MySQLi
- Rekomendasi: PDO karena lebih aman dan fleksibel
- Query harus menggunakan prepared statement untuk mencegah SQL Injection

## Payment Gateway

Sistem memiliki fitur pembayaran biaya konsultasi. Pada dokumentasi awal, payment gateway dicatat sebagai bagian dari alur sistem. Implementasi teknis payment gateway dapat ditentukan kemudian sesuai kebutuhan.

## Server

- Web server: Apache atau Nginx
- Runtime: PHP
- Database server: MySQL

## Catatan

Jangan menambahkan dependency eksternal yang tidak diperlukan. Sistem harus tetap ringan dan mudah dijalankan pada server standar PHP dan MySQL.
