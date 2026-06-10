USE db_consultation_v2;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE messages;
TRUNCATE TABLE payments;
TRUNCATE TABLE consultations;
TRUNCATE TABLE admin_service_assignments;
TRUNCATE TABLE sub_services;
TRUNCATE TABLE service_categories;
TRUNCATE TABLE users;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (id, name, email, password, role, status) VALUES
    (1, 'Superadmin Development', 'superadmin@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'superadmin', 'approved'),
    (2, 'Admin Network Development', 'admin.network@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'admin', 'approved'),
    (3, 'Admin Database Development', 'admin.database@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'admin', 'approved'),
    (4, 'Admin Server Development', 'admin.server@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'admin', 'approved'),
    (5, 'User Approved Development', 'user.approved@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'user', 'approved'),
    (6, 'User Pending Development', 'user.pending@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'user', 'pending'),
    (7, 'User Rejected Development', 'user.rejected@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'user', 'rejected'),
    (8, 'User Inactive Development', 'user.inactive@example.local', '$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se', 'user', 'inactive');

INSERT INTO service_categories (id, name, slug, description, is_active) VALUES
    (1, 'Network Architecture', 'network-architecture', 'Konsultasi desain topologi, segmentasi, keamanan, dan skalabilitas jaringan.', 1),
    (2, 'Database Architecture', 'database-architecture', 'Konsultasi struktur database, optimasi query, backup, dan replikasi.', 1),
    (3, 'Web Server & Virtualization', 'web-server-virtualization', 'Konsultasi deployment web server, virtualisasi, dan hardening infrastruktur.', 1);

INSERT INTO sub_services (id, service_category_id, name, slug, description, price, is_active) VALUES
    (1, 1, 'Network Topology Review', 'network-topology-review', 'Review topologi jaringan kantor atau organisasi.', 250000.00, 1),
    (2, 1, 'VLAN and Segmentation Plan', 'vlan-and-segmentation-plan', 'Perencanaan segmentasi jaringan menggunakan VLAN.', 300000.00, 1),
    (3, 2, 'Database Schema Review', 'database-schema-review', 'Review struktur tabel, relasi, index, dan normalisasi.', 275000.00, 1),
    (4, 2, 'Query Performance Consultation', 'query-performance-consultation', 'Analisis awal query lambat dan strategi optimasi.', 325000.00, 1),
    (5, 3, 'Web Server Deployment Review', 'web-server-deployment-review', 'Review konfigurasi web server dan deployment aplikasi.', 275000.00, 1),
    (6, 3, 'Virtualization Planning', 'virtualization-planning', 'Perencanaan virtualisasi server untuk kebutuhan awal.', 350000.00, 1);

INSERT INTO admin_service_assignments (admin_id, service_category_id) VALUES
    (2, 1),
    (3, 2),
    (4, 3);
