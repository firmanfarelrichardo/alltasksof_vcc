\
# Final Database Schema — MySQL Local First

## Purpose

Dokumen ini mengunci schema database final untuk sistem konsultasi teknologi versi `v2.0`.

Tahap awal menggunakan MySQL lokal.

Tahap akhir menggunakan MySQL Ubuntu Server melalui Tailscale.

---

# Database Name

```sql
db_consultation_v2
```

Charset:

```sql
utf8mb4
```

Collation:

```sql
utf8mb4_unicode_ci
```

---

# Final Tables

```text
users
service_categories
sub_services
admin_service_assignments
consultations
payments
messages
```

---

# 1. Table: users

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    role ENUM('user', 'admin', 'superadmin')
        NOT NULL DEFAULT 'user',

    status ENUM('pending', 'approved', 'rejected', 'inactive')
        NOT NULL DEFAULT 'pending',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_users_role (role),
    INDEX idx_users_status (status),
    INDEX idx_users_role_status (role, status)
);
```

---

# 2. Table: service_categories

```sql
CREATE TABLE service_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_service_categories_active (is_active)
);
```

Initial data:

```text
Network Architecture
Database Architecture
Web Server & Virtualization
```

---

# 3. Table: sub_services

```sql
CREATE TABLE sub_services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_category_id INT UNSIGNED NOT NULL,

    name VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(12, 2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_sub_services_category (service_category_id),
    INDEX idx_sub_services_active (is_active),

    CONSTRAINT fk_sub_services_category
        FOREIGN KEY (service_category_id)
        REFERENCES service_categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
```

---

# 4. Table: admin_service_assignments

```sql
CREATE TABLE admin_service_assignments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    service_category_id INT UNSIGNED NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_admin_service_assignment (
        admin_id,
        service_category_id
    ),

    INDEX idx_admin_assignments_admin (admin_id),
    INDEX idx_admin_assignments_service (service_category_id),

    CONSTRAINT fk_admin_assignments_admin
        FOREIGN KEY (admin_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_admin_assignments_service
        FOREIGN KEY (service_category_id)
        REFERENCES service_categories(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
```

Rule:

```text
Satu admin dapat menangani satu atau lebih kategori layanan.
```

---

# 5. Table: consultations

```sql
CREATE TABLE consultations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    sub_service_id INT UNSIGNED NOT NULL,

    status ENUM(
        'waiting_payment',
        'active',
        'closed',
        'cancelled'
    ) NOT NULL DEFAULT 'waiting_payment',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_consultations_user (user_id),
    INDEX idx_consultations_sub_service (sub_service_id),
    INDEX idx_consultations_status (status),
    INDEX idx_consultations_updated (updated_at),

    CONSTRAINT fk_consultations_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_consultations_sub_service
        FOREIGN KEY (sub_service_id)
        REFERENCES sub_services(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
```

Important decision:

```text
consultations tidak memiliki payment_id.
```

Alasan:

```text
Satu consultation dapat memiliki lebih dari satu payment attempt.
```

---

# 6. Table: payments

```sql
CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,
    consultation_id INT UNSIGNED NOT NULL,
    sub_service_id INT UNSIGNED NOT NULL,

    order_id VARCHAR(50) NOT NULL UNIQUE,
    amount DECIMAL(12, 2) NOT NULL,

    provider VARCHAR(30) NOT NULL DEFAULT 'midtrans',
    snap_token VARCHAR(255) NULL,

    payment_type VARCHAR(50) NULL,
    transaction_id VARCHAR(100) NULL,
    transaction_status VARCHAR(50) NULL,
    fraud_status VARCHAR(50) NULL,

    internal_status ENUM(
        'pending',
        'paid',
        'cancelled',
        'failed',
        'expired',
        'refunded'
    ) NOT NULL DEFAULT 'pending',

    paid_at DATETIME NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_payments_user (user_id),
    INDEX idx_payments_consultation (consultation_id),
    INDEX idx_payments_sub_service (sub_service_id),
    INDEX idx_payments_internal_status (internal_status),
    INDEX idx_payments_transaction_status (transaction_status),
    INDEX idx_payments_transaction_id (transaction_id),
    INDEX idx_payments_updated (updated_at),

    CONSTRAINT fk_payments_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_payments_consultation
        FOREIGN KEY (consultation_id)
        REFERENCES consultations(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_payments_sub_service
        FOREIGN KEY (sub_service_id)
        REFERENCES sub_services(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
```

Important rule:

```text
amount adalah snapshot harga saat transaksi dibuat.
```

---

# 7. Table: messages

```sql
CREATE TABLE messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL,

    message TEXT NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_messages_consultation (consultation_id),
    INDEX idx_messages_sender (sender_id),
    INDEX idx_messages_consultation_created (
        consultation_id,
        created_at
    ),

    CONSTRAINT fk_messages_consultation
        FOREIGN KEY (consultation_id)
        REFERENCES consultations(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_messages_sender
        FOREIGN KEY (sender_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
```

---

# Relation Diagram

```text
users
├── consultations.user_id
├── payments.user_id
├── messages.sender_id
└── admin_service_assignments.admin_id

service_categories
├── sub_services.service_category_id
└── admin_service_assignments.service_category_id

sub_services
├── consultations.sub_service_id
└── payments.sub_service_id

consultations
├── payments.consultation_id
└── messages.consultation_id
```

---

# Payment Status Mapping

| Midtrans Raw Status | Internal Status |
|---|---|
| `pending` | `pending` |
| `settlement` | `paid` |
| `capture` + accepted fraud | `paid` |
| `cancel` | `cancelled` |
| `deny` | `failed` |
| `expire` | `expired` |
| `refund` | `refunded` |
| `partial_refund` | `refunded` |

---

# Consultation Activation Rule

Consultation aktif hanya jika:

```text
payments.internal_status = paid
```

Update:

```text
consultations.status = active
```

---

# Local First Rule

Gunakan lokal terlebih dahulu:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_consultation_v2
```

Setelah pengujian lokal selesai, pindahkan ke remote Tailscale hanya dengan mengubah `.env`.

---

# Final Rule

Schema ini menjadi sumber kebenaran utama database.

Jika schema berubah:

1. Perbarui file ini.
2. Perbarui `database/schema.sql`.
3. Perbarui `database/seed.sql` jika diperlukan.
4. Perbarui `current-progress.md`.
5. Tambahkan catatan ke changelog.
