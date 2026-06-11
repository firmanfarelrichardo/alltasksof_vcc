-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_consultation_v2
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_service_assignments`
--

DROP TABLE IF EXISTS `admin_service_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_service_assignments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int unsigned NOT NULL,
  `service_category_id` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_service_assignment` (`admin_id`,`service_category_id`),
  KEY `idx_admin_assignments_admin` (`admin_id`),
  KEY `idx_admin_assignments_service` (`service_category_id`),
  CONSTRAINT `fk_admin_assignments_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_admin_assignments_service` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_service_assignments`
--

/*!40000 ALTER TABLE `admin_service_assignments` DISABLE KEYS */;
INSERT INTO `admin_service_assignments` VALUES (1,2,1,'2026-06-11 06:14:59'),(2,3,2,'2026-06-11 06:14:59'),(3,4,3,'2026-06-11 06:14:59');
/*!40000 ALTER TABLE `admin_service_assignments` ENABLE KEYS */;

--
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `sub_service_id` int unsigned NOT NULL,
  `status` enum('waiting_payment','active','closed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_payment',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_consultations_user` (`user_id`),
  KEY `idx_consultations_sub_service` (`sub_service_id`),
  KEY `idx_consultations_status` (`status`),
  KEY `idx_consultations_updated` (`updated_at`),
  CONSTRAINT `fk_consultations_sub_service` FOREIGN KEY (`sub_service_id`) REFERENCES `sub_services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_consultations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultations` ENABLE KEYS */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` int unsigned NOT NULL,
  `sender_id` int unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_consultation` (`consultation_id`),
  KEY `idx_messages_sender` (`sender_id`),
  KEY `idx_messages_consultation_created` (`consultation_id`,`created_at`),
  CONSTRAINT `fk_messages_consultation` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `consultation_id` int unsigned NOT NULL,
  `sub_service_id` int unsigned NOT NULL,
  `order_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `provider` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fraud_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_status` enum('pending','paid','cancelled','failed','expired','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `idx_payments_user` (`user_id`),
  KEY `idx_payments_consultation` (`consultation_id`),
  KEY `idx_payments_sub_service` (`sub_service_id`),
  KEY `idx_payments_internal_status` (`internal_status`),
  KEY `idx_payments_transaction_status` (`transaction_status`),
  KEY `idx_payments_transaction_id` (`transaction_id`),
  KEY `idx_payments_updated` (`updated_at`),
  CONSTRAINT `fk_payments_consultation` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_sub_service` FOREIGN KEY (`sub_service_id`) REFERENCES `sub_services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_service_categories_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES (1,'Network Architecture','network-architecture','Konsultasi desain topologi, segmentasi, keamanan, dan skalabilitas jaringan.',1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(2,'Database Architecture','database-architecture','Konsultasi struktur database, optimasi query, backup, dan replikasi.',1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(3,'Web Server & Virtualization','web-server-virtualization','Konsultasi deployment web server, virtualisasi, dan hardening infrastruktur.',1,'2026-06-11 06:14:59','2026-06-11 06:14:59');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;

--
-- Table structure for table `sub_services`
--

DROP TABLE IF EXISTS `sub_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `service_category_id` int unsigned NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(170) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_sub_services_category` (`service_category_id`),
  KEY `idx_sub_services_active` (`is_active`),
  CONSTRAINT `fk_sub_services_category` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_services`
--

/*!40000 ALTER TABLE `sub_services` DISABLE KEYS */;
INSERT INTO `sub_services` VALUES (1,1,'Network Topology Review','network-topology-review','Review topologi jaringan kantor atau organisasi.',250000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(2,1,'VLAN and Segmentation Plan','vlan-and-segmentation-plan','Perencanaan segmentasi jaringan menggunakan VLAN.',300000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(3,2,'Database Schema Review','database-schema-review','Review struktur tabel, relasi, index, dan normalisasi.',275000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(4,2,'Query Performance Consultation','query-performance-consultation','Analisis awal query lambat dan strategi optimasi.',325000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(5,3,'Web Server Deployment Review','web-server-deployment-review','Review konfigurasi web server dan deployment aplikasi.',275000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59'),(6,3,'Virtualization Planning','virtualization-planning','Perencanaan virtualisasi server untuk kebutuhan awal.',350000.00,1,'2026-06-11 06:14:59','2026-06-11 06:14:59');
/*!40000 ALTER TABLE `sub_services` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin','superadmin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('pending','approved','rejected','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_role_status` (`role`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Superadmin Development','superadmin@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','superadmin','approved','2026-06-11 06:14:59','2026-06-11 06:14:59'),(2,'Admin Network Development','admin.network@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','admin','approved','2026-06-11 06:14:59','2026-06-11 06:14:59'),(3,'Admin Database Development','admin.database@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','admin','approved','2026-06-11 06:14:59','2026-06-11 06:14:59'),(4,'Admin Server Development','admin.server@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','admin','approved','2026-06-11 06:14:59','2026-06-11 06:14:59'),(5,'User Approved Development','user.approved@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','user','approved','2026-06-11 06:14:59','2026-06-11 06:14:59'),(6,'User Pending Development','user.pending@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','user','pending','2026-06-11 06:14:59','2026-06-11 06:14:59'),(7,'User Rejected Development','user.rejected@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','user','rejected','2026-06-11 06:14:59','2026-06-11 06:14:59'),(8,'User Inactive Development','user.inactive@example.local','$2y$10$u74jR1P8MEttkrDtq5lD/uiny2p3U8KmLdQqdGgyuy7Kazr2Ak6se','user','inactive','2026-06-11 06:14:59','2026-06-11 06:14:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-11  8:12:11
