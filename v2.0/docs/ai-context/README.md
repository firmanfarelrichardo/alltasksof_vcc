# AI Context — V2.0

Folder ini berfungsi sebagai pusat konteks proyek untuk membantu AI agent memahami sistem sebelum membuat, mengubah, atau memperbaiki kode.

## Tujuan Folder

Folder `ai-context/` berisi dokumentasi teknis dan konseptual dari sistem konsultasi teknologi versi `v2.0`. Semua keputusan penting terkait fitur, stakeholder, arsitektur, database, flow sistem, dan aturan bisnis harus dicatat di folder ini.

## Aturan Utama

1. AI agent wajib membaca folder ini sebelum mengubah kode.
2. Folder ini menjadi sumber konteks utama proyek.
3. Setiap perubahan fitur besar wajib memperbarui dokumen terkait.
4. Sistem dibuat sederhana terlebih dahulu sesuai kebutuhan yang sudah ditentukan.
5. Jangan menambahkan fitur baru di luar ruang lingkup kecuali diminta secara eksplisit.

## Urutan Baca AI Agent

AI agent sebaiknya membaca dokumen dalam urutan berikut:

1. `project-overview.md`
2. `tech-stack.md`
3. `stakeholders-and-roles.md`
4. `services-catalog.md`
5. `feature-scope.md`
6. `system-flow.md`
7. `business-rules.md`
8. `architecture.md`
9. `database-schema.md`
10. `current-progress.md`

## Dokumen Operasional

1. `midtrans-sandbox-setup-guide.md` - panduan konfigurasi akun Midtrans Sandbox untuk menjalankan Phase 7.

## Prinsip Pengembangan

Sistem ini menggunakan pendekatan sederhana dengan PHP native, MySQL, dan arsitektur MVC pada backend. Frontend dan backend tetap dipisahkan secara folder agar struktur proyek lebih rapi dan mudah dikembangkan.
