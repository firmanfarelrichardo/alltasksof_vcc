CREATE DATABASE IF NOT EXISTS db_consultation_v2
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_consultation_v2;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS consultations;
DROP TABLE IF EXISTS admin_service_assignments;
DROP TABLE IF EXISTS sub_services;
DROP TABLE IF EXISTS service_categories;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'superadmin') NOT NULL DEFAULT 'user',
    status ENUM('pending', 'approved', 'rejected', 'inactive') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_status (status),
    INDEX idx_users_role_status (role, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE service_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_service_categories_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sub_services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(12, 2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sub_services_category (service_category_id),
    INDEX idx_sub_services_active (is_active),
    CONSTRAINT fk_sub_services_category
        FOREIGN KEY (service_category_id)
        REFERENCES service_categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admin_service_assignments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    service_category_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_service_assignment (admin_id, service_category_id),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE consultations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    sub_service_id INT UNSIGNED NOT NULL,
    status ENUM('waiting_payment', 'active', 'closed', 'cancelled') NOT NULL DEFAULT 'waiting_payment',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    internal_status ENUM('pending', 'paid', 'cancelled', 'failed', 'expired', 'refunded') NOT NULL DEFAULT 'pending',
    paid_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_messages_consultation (consultation_id),
    INDEX idx_messages_sender (sender_id),
    INDEX idx_messages_consultation_created (consultation_id, created_at),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
