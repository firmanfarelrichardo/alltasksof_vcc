\
# Clean Code Addendum — Admin Consultation Pipeline

## Purpose

Dokumen ini menambahkan aturan clean code khusus untuk fitur pipeline admin.

---

# Do Not Duplicate Business Data

Jangan membuat tabel baru seperti:

```text
admin_pipeline
registered_user_list
successful_payment_list
cancelled_payment_list
```

Pipeline harus dibentuk dari query tabel utama.

---

# Separate Status Layers

Pisahkan:

```text
account status
payment raw status
payment internal status
consultation status
UI label
```

Jangan menggunakan satu field untuk semua kebutuhan.

---

# Controller Rule

Gunakan controller tipis:

```text
AdminPipelineController
```

Controller tidak boleh berisi query SQL.

---

# Service Rule

Gunakan:

```text
AdminPipelineService
```

Service bertanggung jawab untuk:

1. Mapping status UI.
2. Menerapkan filter.
3. Menerapkan service scope admin.
4. Mengambil summary counts.
5. Menentukan action button.

---

# Query Rule

Gunakan:

```text
AdminPipelineRepository
```

atau model khusus query read-only.

Gunakan:

1. Prepared statement.
2. Kolom eksplisit.
3. Pagination.
4. Index.
5. Filter layanan admin.

Hindari:

```sql
SELECT *
```

---

# Privacy Rule

Registered user list hanya menampilkan:

```text
name
email
account status
registered_at
```

Jangan mengirim data sensitif ke view.

---

# Performance Rule

1. Gunakan pagination.
2. Batasi `limit`.
3. Gunakan index.
4. Hindari N+1 query.
5. Gunakan JOIN yang jelas.
6. Pisahkan query count dan query rows jika lebih sederhana.
7. Jangan memuat seluruh message chat dalam tabel pipeline.

---

# Final Rule

Fitur pipeline harus tetap sederhana:

```text
Query
Filter
Pagination
Badge
Detail
Chat action jika paid
```
