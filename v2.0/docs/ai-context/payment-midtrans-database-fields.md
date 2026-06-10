\
# Midtrans Payment Database Fields

## Purpose

Dokumen ini menjelaskan perubahan tabel database yang diperlukan untuk integrasi Midtrans Snap.

Dokumen ini melengkapi:

```text
database-schema.md
```

---

# Table: payments

Gunakan struktur:

| Column | Type | Null | Description |
|---|---|---|---|
| `id` | INT AUTO_INCREMENT | No | Primary key |
| `user_id` | INT | No | FK ke `users.id` |
| `consultation_id` | INT | No | FK ke `consultations.id` |
| `sub_service_id` | INT | No | FK ke `sub_services.id` |
| `order_id` | VARCHAR(50) | No | Unique merchant order ID |
| `amount` | DECIMAL(12,2) | No | Snapshot nominal transaksi |
| `provider` | VARCHAR(30) | No | Isi awal: `midtrans` |
| `snap_token` | VARCHAR(255) | Yes | Token Snap dari Midtrans |
| `payment_type` | VARCHAR(50) | Yes | Metode pembayaran Midtrans |
| `transaction_id` | VARCHAR(100) | Yes | ID transaksi dari Midtrans |
| `transaction_status` | VARCHAR(50) | Yes | Status asli Midtrans |
| `internal_status` | ENUM('pending','paid','failed','expired','refunded') | No | Status internal aplikasi |
| `fraud_status` | VARCHAR(50) | Yes | Fraud status jika tersedia |
| `paid_at` | DATETIME | Yes | Waktu pembayaran valid |
| `created_at` | DATETIME | No | Waktu dibuat |
| `updated_at` | DATETIME | No | Waktu diperbarui |

---

# Suggested SQL

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
    internal_status ENUM(
        'pending',
        'paid',
        'failed',
        'expired',
        'refunded'
    ) NOT NULL DEFAULT 'pending',

    fraud_status VARCHAR(50) NULL,
    paid_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_payments_user_id (user_id),
    INDEX idx_payments_consultation_id (consultation_id),
    INDEX idx_payments_sub_service_id (sub_service_id),
    INDEX idx_payments_internal_status (internal_status),
    INDEX idx_payments_transaction_id (transaction_id),

    CONSTRAINT fk_payments_user
        FOREIGN KEY (user_id) REFERENCES users(id),

    CONSTRAINT fk_payments_consultation
        FOREIGN KEY (consultation_id) REFERENCES consultations(id),

    CONSTRAINT fk_payments_sub_service
        FOREIGN KEY (sub_service_id) REFERENCES sub_services(id)
);
```

---

# Consultation Update

Pastikan tabel `consultations` memiliki:

```text
payment_id
status
```

Status konsultasi:

```text
waiting_payment
active
closed
cancelled
```

## Rule

Chat hanya dapat dibuka jika:

```text
consultations.status = active
```

dan:

```text
payments.internal_status = paid
```

---

# Index Rule

Wajib memiliki index:

1. `payments.order_id`
2. `payments.user_id`
3. `payments.consultation_id`
4. `payments.internal_status`
5. `payments.transaction_id`

Tujuan:

1. Mempercepat webhook lookup.
2. Mempercepat dashboard user.
3. Mempercepat dashboard admin.
4. Mempercepat pencarian status pembayaran.
