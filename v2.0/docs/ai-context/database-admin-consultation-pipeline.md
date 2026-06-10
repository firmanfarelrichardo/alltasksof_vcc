\
# Database Notes — Admin Consultation Pipeline

## Purpose

Dokumen ini menjelaskan kebutuhan query database untuk menampilkan pengguna, transaksi pembayaran, dan konsultasi pada dashboard admin.

Dokumen ini tidak mengubah strategi database lokal dan Tailscale.

---

# Existing Tables

Pipeline menggunakan tabel:

```text
users
service_categories
sub_services
admin_service_assignments
payments
consultations
messages
```

---

# Important Rule

Tidak perlu membuat tabel pipeline baru.

Pipeline adalah hasil query dari tabel yang sudah tersedia.

Jangan menduplikasi data pengguna, pembayaran, atau konsultasi hanya untuk dashboard.

---

# Registered Users Query

Pengguna terdaftar tetapi belum memiliki payment dapat ditampilkan dengan query:

```sql
SELECT
    u.id,
    u.name,
    u.email,
    u.status AS account_status,
    u.created_at AS registered_at
FROM users u
LEFT JOIN payments p
    ON p.user_id = u.id
WHERE u.role = 'user'
  AND p.id IS NULL
ORDER BY u.created_at DESC
LIMIT :limit OFFSET :offset;
```

## Note

Data ini read-only untuk admin.

---

# Admin Scoped Payment Query

Gunakan relasi assignment admin:

```sql
SELECT
    p.id AS payment_id,
    p.order_id,
    p.amount,
    p.internal_status,
    p.transaction_status,
    p.created_at,
    p.updated_at,

    u.id AS user_id,
    u.name AS user_name,
    u.email AS user_email,
    u.status AS account_status,

    ss.id AS sub_service_id,
    ss.name AS sub_service_name,

    sc.id AS service_category_id,
    sc.name AS service_category_name

FROM payments p

INNER JOIN users u
    ON u.id = p.user_id

INNER JOIN sub_services ss
    ON ss.id = p.sub_service_id

INNER JOIN service_categories sc
    ON sc.id = ss.service_category_id

INNER JOIN admin_service_assignments asa
    ON asa.service_category_id = sc.id

WHERE asa.admin_id = :admin_id
  AND p.internal_status = :internal_status

ORDER BY p.updated_at DESC
LIMIT :limit OFFSET :offset;
```

---

# Admin Scoped Consultation Query

```sql
SELECT
    c.id AS consultation_id,
    c.status AS consultation_status,
    c.created_at,
    c.updated_at,

    u.id AS user_id,
    u.name AS user_name,
    u.email AS user_email,

    ss.id AS sub_service_id,
    ss.name AS sub_service_name,

    sc.id AS service_category_id,
    sc.name AS service_category_name,

    p.id AS payment_id,
    p.amount,
    p.internal_status AS payment_status

FROM consultations c

INNER JOIN users u
    ON u.id = c.user_id

INNER JOIN sub_services ss
    ON ss.id = c.sub_service_id

INNER JOIN service_categories sc
    ON sc.id = ss.service_category_id

INNER JOIN admin_service_assignments asa
    ON asa.service_category_id = sc.id

LEFT JOIN payments p
    ON p.id = c.payment_id

WHERE asa.admin_id = :admin_id
  AND c.status = :consultation_status

ORDER BY c.updated_at DESC
LIMIT :limit OFFSET :offset;
```

---

# Display Status Mapping

Gunakan mapping di service layer.

```php
<?php

function paymentDisplayStatus(array $payment): string
{
    if ($payment['internal_status'] === 'paid') {
        return 'success';
    }

    if ($payment['transaction_status'] === 'cancel') {
        return 'cancel';
    }

    return $payment['internal_status'];
}
```

## Rule

Simpan raw status Midtrans:

```text
transaction_status
```

Simpan status internal:

```text
internal_status
```

Tentukan label UI di service atau presenter.

Jangan mencampur ketiganya.

---

# Suggested Indexes

Tambahkan index:

```sql
CREATE INDEX idx_users_role_status
ON users(role, status);

CREATE INDEX idx_payments_user_id
ON payments(user_id);

CREATE INDEX idx_payments_internal_status
ON payments(internal_status);

CREATE INDEX idx_payments_transaction_status
ON payments(transaction_status);

CREATE INDEX idx_payments_updated_at
ON payments(updated_at);

CREATE INDEX idx_consultations_status
ON consultations(status);

CREATE INDEX idx_consultations_updated_at
ON consultations(updated_at);

CREATE INDEX idx_sub_services_category
ON sub_services(service_category_id);

CREATE INDEX idx_admin_assignments_admin_service
ON admin_service_assignments(admin_id, service_category_id);
```

---

# Pagination Rule

Daftar pipeline harus menggunakan pagination.

Default:

```text
limit = 20
```

Maximum:

```text
limit = 100
```

Jangan memuat seluruh record tanpa batas.

---

# Summary Count Query

Summary card dapat dihitung dengan query terpisah atau query agregasi sederhana.

Contoh untuk payment:

```sql
SELECT
    p.internal_status,
    COUNT(*) AS total
FROM payments p

INNER JOIN sub_services ss
    ON ss.id = p.sub_service_id

INNER JOIN admin_service_assignments asa
    ON asa.service_category_id = ss.service_category_id

WHERE asa.admin_id = :admin_id

GROUP BY p.internal_status;
```

---

# Local and Tailscale Rule

Query pipeline harus bekerja tanpa perubahan pada:

1. MySQL lokal.
2. MySQL Ubuntu Server melalui Tailscale.

Perpindahan environment hanya melalui:

```text
.env
```

Jangan hardcode IP database pada query atau model.
