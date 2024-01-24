-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table medicall_erp.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addressable_id` bigint(20) unsigned NOT NULL,
  `addressable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.addresses: ~20 rows (approximately)
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` (`id`, `address`, `pincode`, `city`, `state`, `country`, `addressable_id`, `addressable_type`, `created_at`, `updated_at`) VALUES
	(1, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 1, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(2, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 2, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(3, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 3, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(4, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 4, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(5, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 5, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(6, 'Ezhil Nagar', '611114', 'Nagapattinam', 'Tamil Nadu', 'India', 6, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(7, 'Ezhil Nagar', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 7, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(8, 'Ezhil Nagar', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 8, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(9, 'kumaresapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 9, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(10, 'kumaresapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 10, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(11, 'kumaresapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 11, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(12, 'Kailsapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 12, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(13, 'Kailsapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 13, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(14, 'Kailsapuram', '620014', 'Tiruchirappalli', 'Tamil Nadu', 'India', 14, 'App\\Models\\visitor', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(15, 'Pune', '411001', 'Pune', 'Maharashtra', 'India', 1, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(16, 'Chennai', '60001', 'Chennai', 'Tamilnadu', 'India', 2, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(17, 'Trichy', '60002', 'Trichy', 'Tamilnadu', 'India', 3, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(18, 'Perambalur', '621114', 'Perambalur', 'Tamilnadu', 'India', 4, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(19, 'Chennai', '60001', 'Chennai', 'Tamilnadu', 'India', 5, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(20, 'Trichy', '60001', 'Trchy', 'Tamilnadu', 'India', 6, 'App\\Models\\Exhibitor', '2023-11-09 18:10:34', '2023-11-09 18:10:34');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.appointments
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `visitor_id` bigint(20) unsigned NOT NULL,
  `exhibitor_id` bigint(20) unsigned NOT NULL,
  `scheduled_at` timestamp NOT NULL,
  `status` enum('scheduled','canceled','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_meta` json DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_event_id_foreign` (`event_id`),
  KEY `appointments_visitor_id_foreign` (`visitor_id`),
  KEY `appointments_exhibitor_id_foreign` (`exhibitor_id`),
  KEY `appointments_updated_by_foreign` (`updated_by`),
  KEY `appointments_cancelled_by_foreign` (`cancelled_by`),
  CONSTRAINT `appointments_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `appointments_exhibitor_id_foreign` FOREIGN KEY (`exhibitor_id`) REFERENCES `exhibitors` (`id`),
  CONSTRAINT `appointments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.appointments: ~0 rows (approximately)
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.cache: ~0 rows (approximately)
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.cache_locks: ~0 rows (approximately)
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `_meta` json DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_created_by_foreign` (`created_by`),
  KEY `categories_updated_by_foreign` (`updated_by`),
  KEY `categories_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `categories_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.categories: ~56 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `type`, `name`, `description`, `is_active`, `is_default`, `parent_id`, `_meta`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'visitor_business_type', 'Doctor', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(2, 'visitor_business_type', 'Hospital Owner', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(3, 'visitor_business_type', 'Hospital Administrator', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(4, 'visitor_business_type', 'Dealer / Distributor', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(5, 'visitor_business_type', 'Manufacturer', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(6, 'visitor_business_type', 'Nurse', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(7, 'visitor_business_type', 'OT Assistants', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(8, 'visitor_business_type', 'Diagnostics & Lab Technicians', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(9, 'visitor_business_type', 'Hospital Staffs', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(10, 'visitor_business_type', 'Medical Shops', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(11, 'visitor_business_type', 'Physiotherapist', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(12, 'visitor_business_type', 'Psychiatrist', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(13, 'visitor_business_type', 'Dietitian', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(14, 'visitor_business_type', 'Purchase Manager', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(15, 'visitor_business_type', 'Biomedical Engineer', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(16, 'visitor_business_type', 'Logistics', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(17, 'visitor_business_type', 'Government Sector', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(18, 'visitor_business_type', 'Corporate', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(19, 'visitor_business_type', 'IT - Other IT Development Sectors', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(20, 'visitor_business_type', 'Hotels', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(21, 'visitor_business_type', 'Academician', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(22, 'visitor_business_type', 'Student', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(23, 'visitor_business_type', 'Other Business', NULL, 1, 1, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(24, 'exhibitor_business_type', 'Importer / Exportar', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(25, 'exhibitor_business_type', 'Others', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(26, 'exhibitor_business_type', 'Distributors', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(27, 'exhibitor_business_type', 'Esteemed Clients', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(28, 'exhibitor_business_type', 'Veterinarion', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(29, 'exhibitor_business_type', 'Process Engineers', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(30, 'exhibitor_business_type', 'Bio medical Engineer', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(31, 'exhibitor_business_type', 'Doctors', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(32, 'product_type', 'Electronic Health Records', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(33, 'product_type', 'Patient Engagement and Portal Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(34, 'product_type', 'Revenue Cycle Management (RCM) ', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(35, 'product_type', 'Health Information Exchange (HIE)', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(36, 'product_type', 'Telemedicine and Telehealth Platforms', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(37, 'product_type', 'Surgical Information System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(38, 'product_type', 'Pharmacy Management System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(39, 'product_type', 'Laboratory Information System (LIS)', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(40, 'product_type', 'Clinical Decision Support Systems', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(41, 'product_type', 'Picture Archiving and Communication System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(42, 'product_tags', 'EHR Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(43, 'product_tags', 'Health Records Management', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(44, 'product_tags', 'Medical Imaging Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(45, 'product_tags', 'Patient Information System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(46, 'product_tags', 'Radiology Information System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(47, 'product_tags', 'Digital Imaging Management', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(48, 'product_tags', 'Hospital Operations Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(49, 'product_tags', 'Healthcare Administration', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(50, 'product_tags', 'HMIS Solutions', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(51, 'product_tags', 'Clinical Decision Support', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(52, 'product_tags', 'Medical Decision Support', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(53, 'product_tags', 'Evidence-Based Medicine', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(54, 'product_tags', 'Clinical Lab Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(55, 'product_tags', 'Lab Management System', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(56, 'product_tags', 'Diagnostic Testing Software', NULL, 1, 1, NULL, NULL, 2, 2, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.contact_persons
CREATE TABLE IF NOT EXISTS `contact_persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exhibitor_id` bigint(20) unsigned NOT NULL,
  `salutation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_persons_exhibitor_id_foreign` (`exhibitor_id`),
  CONSTRAINT `contact_persons_exhibitor_id_foreign` FOREIGN KEY (`exhibitor_id`) REFERENCES `exhibitors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.contact_persons: ~0 rows (approximately)
/*!40000 ALTER TABLE `contact_persons` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_persons` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `organizer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `_meta` json DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_created_by_foreign` (`created_by`),
  KEY `events_updated_by_foreign` (`updated_by`),
  KEY `events_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `events_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `events_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.events: ~10 rows (approximately)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`, `title`, `start_date`, `end_date`, `organizer`, `contact`, `description`, `_meta`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`, `created_at`, `updated_at`) VALUES
	(1, 'Delhi Medicall 2023', '2024-01-06 00:00:00', '2024-01-06 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(2, 'Mumbai Medicall 2023', '2023-11-15 00:00:00', '2023-11-15 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(3, 'Kolkata Medicall 2023', '2023-11-21 00:00:00', '2023-11-21 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(4, 'Chennai Medicall 2024', '2023-12-23 00:00:00', '2023-12-23 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(5, 'Bangalore Medicall 2023', '2023-12-31 00:00:00', '2023-12-31 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(6, 'Hyderabad Medicall 2023', '2023-12-17 00:00:00', '2023-12-17 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(7, 'Pune Medicall 2024', '2023-11-29 00:00:00', '2023-11-29 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(8, 'Ahmedabad Medicall 2023', '2023-12-31 00:00:00', '2023-12-31 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(9, 'Jaipur Medicall 2024', '2023-12-15 00:00:00', '2023-12-15 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(10, 'Chandigarh Medicall 2023', '2023-11-13 00:00:00', '2023-11-13 00:00:00', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.event_exhibitors
CREATE TABLE IF NOT EXISTS `event_exhibitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `exhibitor_id` bigint(20) unsigned NOT NULL,
  `stall_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sponsorer` tinyint(1) NOT NULL DEFAULT '0',
  `products` json DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `_meta` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_exhibitors_event_id_foreign` (`event_id`),
  KEY `event_exhibitors_exhibitor_id_foreign` (`exhibitor_id`),
  KEY `event_exhibitors_cancelled_by_foreign` (`cancelled_by`),
  CONSTRAINT `event_exhibitors_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  CONSTRAINT `event_exhibitors_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_exhibitors_exhibitor_id_foreign` FOREIGN KEY (`exhibitor_id`) REFERENCES `exhibitors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.event_exhibitors: ~6 rows (approximately)
/*!40000 ALTER TABLE `event_exhibitors` DISABLE KEYS */;
INSERT INTO `event_exhibitors` (`id`, `event_id`, `exhibitor_id`, `stall_no`, `is_sponsorer`, `products`, `tags`, `order`, `cancelled_by`, `cancelled_at`, `cancelled_reason`, `is_active`, `created_at`, `updated_at`, `_meta`) VALUES
	(1, 1, 1, NULL, 0, '["1", "2"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL),
	(2, 1, 2, NULL, 0, '["1", "2"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL),
	(3, 1, 3, NULL, 0, '["1", "2", "3"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL),
	(4, 1, 4, NULL, 0, '["1", "2", "3", "4"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL),
	(5, 1, 5, NULL, 0, '["1", "2", "3", "4"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL),
	(6, 1, 6, NULL, 0, '["1", "2", "3", "4"]', NULL, NULL, NULL, NULL, NULL, 0, '2023-11-09 18:10:34', '2023-11-09 18:10:34', NULL);
/*!40000 ALTER TABLE `event_exhibitors` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.event_visitors
CREATE TABLE IF NOT EXISTS `event_visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `visitor_id` bigint(20) unsigned NOT NULL,
  `is_visited` tinyint(1) NOT NULL DEFAULT '0',
  `product_looking` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_visitors_visitor_id_foreign` (`visitor_id`),
  CONSTRAINT `event_visitors_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.event_visitors: ~14 rows (approximately)
/*!40000 ALTER TABLE `event_visitors` DISABLE KEYS */;
INSERT INTO `event_visitors` (`id`, `event_id`, `visitor_id`, `is_visited`, `product_looking`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(2, 1, 2, 0, '["1", "4"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(3, 1, 3, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(4, 1, 4, 0, '["1", "3"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(5, 1, 5, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(6, 1, 6, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(7, 1, 7, 0, '["1", "3"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(8, 1, 8, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(9, 1, 9, 0, '["1"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(10, 1, 10, 0, '["4"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(11, 1, 11, 0, '["3"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(12, 1, 12, 0, '["1"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(13, 1, 13, 0, '["1", "2"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(14, 1, 14, 0, '["1"]', '2023-11-09 18:10:33', '2023-11-09 18:10:33');
/*!40000 ALTER TABLE `event_visitors` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.exhibitors
CREATE TABLE IF NOT EXISTS `exhibitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `proof_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `known_source` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `registration_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `_meta` json DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exhibitors_username_unique` (`username`),
  UNIQUE KEY `exhibitors_email_unique` (`email`),
  UNIQUE KEY `exhibitors_proof_id_unique` (`proof_id`),
  KEY `exhibitors_category_id_foreign` (`category_id`),
  KEY `exhibitors_created_by_foreign` (`created_by`),
  KEY `exhibitors_updated_by_foreign` (`updated_by`),
  KEY `exhibitors_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `exhibitors_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `exhibitors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `exhibitors_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `exhibitors_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.exhibitors: ~6 rows (approximately)
/*!40000 ALTER TABLE `exhibitors` DISABLE KEYS */;
INSERT INTO `exhibitors` (`id`, `username`, `name`, `category_id`, `proof_type`, `proof_id`, `email`, `website`, `password`, `mobile_number`, `logo`, `description`, `known_source`, `newsletter`, `registration_type`, `_meta`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Bhuvana02', 'Agile', 1, NULL, NULL, 'bhuvana@gmail.com', NULL, '$2y$10$Q54Ngu8R3FnWmFVrVmzfT.bDKrqlqwuO58/VZMtV1e5VkhxYfiFXG', '9987754321', NULL, NULL, 'Google', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(2, 'Rajesh45', 'LND', 1, NULL, NULL, 'rajesh@gmail.com', NULL, '$2y$10$vzI1gz73amA4OSA3t0dEkemm2kexjTRRbbwYC18okoYcs0ZCYVGYa', '9987754323', NULL, NULL, 'Google', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(3, 'Vaibhava1', 'Agile', 2, NULL, NULL, 'vaibhava@gmail.com', NULL, '$2y$10$D./Fy.rP/HYZZuKjOc6ztecTvJ3cHdD0m8xW7/LwOzjjNYOSApfHi', '9987754324', NULL, NULL, 'Google', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(4, 'Vishal32', 'ZOHO', 2, NULL, NULL, 'vishal@gmail.com', NULL, '$2y$10$SR3MXVXJN9AQzZCz96aGheYNHMaINOemD0MaAf5iHidzUoP/nk/uK', '9987754325', NULL, NULL, 'Friend', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(5, 'Lavanya23', 'KMC', 3, NULL, NULL, 'lavanya@gmail.com', NULL, '$2y$10$kFIZsfyfkprQeDc3QdJfKOB3hL/gm/MSu5erbcuAWSrD1e6X3MZCm', '9987754326', NULL, NULL, 'Friend', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(6, 'Arjun', 'Agile', 3, NULL, NULL, 'arjun@gmail.com', NULL, '$2y$10$cwG5u99OzBK96Lq/XmoSmOUq7uK5xyjNr6rlAgsx/WqbxqOl5XTeq', '9987754327', NULL, NULL, 'Friend', 0, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34');
/*!40000 ALTER TABLE `exhibitors` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.exhibitor_contacts
CREATE TABLE IF NOT EXISTS `exhibitor_contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exhibitor_id` bigint(20) unsigned NOT NULL,
  `salutation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exhibitor_contacts_exhibitor_id_foreign` (`exhibitor_id`),
  CONSTRAINT `exhibitor_contacts_exhibitor_id_foreign` FOREIGN KEY (`exhibitor_id`) REFERENCES `exhibitors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.exhibitor_contacts: ~6 rows (approximately)
/*!40000 ALTER TABLE `exhibitor_contacts` DISABLE KEYS */;
INSERT INTO `exhibitor_contacts` (`id`, `exhibitor_id`, `salutation`, `name`, `contact_number`, `designation`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Ms', 'Bhuvana', '9987754321', 'Manager', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(2, 2, 'Mr', 'Rajesh', '9987754323', 'Doctor', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(3, 3, 'Ms', 'Vaibhava', '9987754324', 'Developer', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(4, 4, 'Mr', 'Vishal', '9987754325', 'Developer', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(5, 5, 'Ms', 'Lavanya', '9987754326', 'Manager', '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(6, 6, 'Mr', 'Arjun', '9987754377', 'Doctor', '2023-11-09 18:10:34', '2023-11-09 18:10:34');
/*!40000 ALTER TABLE `exhibitor_contacts` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.exhibitor_products
CREATE TABLE IF NOT EXISTS `exhibitor_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exhibitor_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exhibitor_products_exhibitor_id_foreign` (`exhibitor_id`),
  KEY `exhibitor_products_product_id_foreign` (`product_id`),
  CONSTRAINT `exhibitor_products_exhibitor_id_foreign` FOREIGN KEY (`exhibitor_id`) REFERENCES `exhibitors` (`id`),
  CONSTRAINT `exhibitor_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.exhibitor_products: ~19 rows (approximately)
/*!40000 ALTER TABLE `exhibitor_products` DISABLE KEYS */;
INSERT INTO `exhibitor_products` (`id`, `exhibitor_id`, `product_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(2, 1, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(3, 2, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(4, 2, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(5, 3, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(6, 3, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(7, 3, 3, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(8, 4, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(9, 4, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(10, 4, 3, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(11, 4, 4, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(12, 5, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(13, 5, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(14, 5, 3, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(15, 5, 4, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(16, 6, 1, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(17, 6, 2, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(18, 6, 3, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(19, 6, 4, '2023-11-09 18:10:34', '2023-11-09 18:10:34');
/*!40000 ALTER TABLE `exhibitor_products` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.migrations: ~23 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2023_04_25_170840_create_sessions_table', 1),
	(7, '2023_06_10_134304_create_categories_table', 1),
	(8, '2023_07_25_210037_add_type_and_location_columns_to_users_table', 1),
	(9, '2023_09_14_094651_add_new_column_to_users_table', 1),
	(10, '2023_10_16_115554_add_emp_no_to_users', 1),
	(11, '2023_10_20_154350_create_events_table', 1),
	(12, '2023_10_20_154442_create_visitors_table', 1),
	(13, '2023_10_20_154519_create_exhibitors_table', 1),
	(14, '2023_10_21_140326_create_cache_table', 1),
	(15, '2023_10_21_182250_create_contact_people_table', 1),
	(16, '2023_10_21_183806_create_event_exhibitors_table', 1),
	(17, '2023_10_24_061546_create_event_visitors_table', 1),
	(18, '2023_10_24_184100_create_products_table', 1),
	(19, '2023_10_24_185312_create_addresses_table', 1),
	(20, '2023_10_25_113706_create_exhibitors_product_table', 1),
	(21, '2023_10_25_115330_create_appointments_table', 1),
	(22, '2023_10_27_112041_create_exhibitor_contacts_table', 1),
	(23, '2023_11_06_152158_add_meta_column_in_event_exhibitors_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.password_reset_tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.personal_access_tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `_meta` json DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_created_by_foreign` (`created_by`),
  KEY `products_updated_by_foreign` (`updated_by`),
  KEY `products_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `products_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `products_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.products: ~23 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `image`, `tags`, `_meta`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Radiation Oncology', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(2, 1, 'Radiography', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(3, 1, 'Pathology', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(4, 1, 'Patient Monitoring', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(5, 1, 'Fluoroscopy', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(6, 1, 'Ultrasound', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(7, 1, 'Ventilation', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32'),
	(8, 35, 'Real List', 'Successfully Inserted', NULL, '["43", "55"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(9, 32, 'Dial 247', 'Successfully Inserted', NULL, '["56", "47"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(10, 36, 'ERP', 'Successfully Inserted', NULL, '["42", "48"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(11, 38, 'Microsoft Office Suite', 'Successfully Inserted', NULL, '["45", "48"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(12, 41, 'Adobe Creative Cloud', 'Successfully Inserted', NULL, '["56", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(13, 32, 'Google Workspace', 'Successfully Inserted', NULL, '["47", "56"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(14, 34, 'Slack', 'Successfully Inserted', NULL, '["51", "44"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(15, 33, 'Zoom', 'Successfully Inserted', NULL, '["46", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(16, 32, 'Salesforce', 'Successfully Inserted', NULL, '["43", "46"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(17, 37, 'GitHub', 'Successfully Inserted', NULL, '["44", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(18, 33, 'Dropbox', 'Successfully Inserted', NULL, '["45", "42"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(19, 34, 'Trello', 'Successfully Inserted', NULL, '["50", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(20, 39, 'Jira', 'Successfully Inserted', NULL, '["53", "51"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(21, 34, 'Atlassian Confluence', 'Successfully Inserted', NULL, '["47", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(22, 37, 'Spotify', 'Successfully Inserted', NULL, '["49", "42"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34'),
	(23, 33, 'Netflix', 'Successfully Inserted', NULL, '["48", "50"]', NULL, 1, 1, NULL, NULL, '2023-11-09 18:10:34', '2023-11-09 18:10:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `location_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `emp_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int(11) NOT NULL DEFAULT '0',
  `level` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_emp_no_unique` (`emp_no`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.users: ~14 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `type`, `location_id`, `is_active`, `created_by`, `updated_by`, `emp_id`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `mobile_number`, `department_id`, `level`, `emp_no`) VALUES
	(1, 'Admin', 'admin@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'LRteKjQ7i4', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9987754329', 0, NULL, 'EMP0001'),
	(2, 'Manager', 'manager@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'gy6Zj4pD9a', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9987754321', 0, NULL, 'EMP0002'),
	(3, 'Bhuvana', 'bhuvana@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'vcpg1BDDyD', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9087654321', 0, 'L1', 'EMP0003'),
	(4, 'Lavanya', 'lavanya@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'Q2NZyoq1Bx', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9187654321', 0, 'L2', 'EMP0004'),
	(5, 'Gayana', 'gayana@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'Ndq7MnPw2A', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9287654321', 0, 'L3', 'EMP0005'),
	(6, 'Vaishali', 'vaishali@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'R6sg2jBGWh', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9387654321', 0, 'L1', 'EMP0006'),
	(7, 'Shamili', 'shamili@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'PUfHFQ78aF', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9487654321', 0, 'L2', 'EMP0007'),
	(8, 'Aishu', 'aishu@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'tbffcOWCob', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9057654321', 0, 'L1', 'EMP0008'),
	(9, 'Sarumathi', 'sarumathi@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'MoDALjx6SA', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9087654421', 0, 'L2', 'EMP0009'),
	(10, 'Keerthi', 'keerthi@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'QkFy2pndSQ', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9087754321', 0, 'L3', 'EMP0010'),
	(11, 'Keerthika', 'keerthika@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, '0mZytxJ8Zf', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9087714321', 0, 'L1', 'EMP0011'),
	(12, 'Nanthini', 'nandhini@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'EFt4xd4GeN', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9987754321', 0, 'L2', 'EMP0012'),
	(13, 'Abinaya', 'abinaya@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'sPTSmvW1Jy', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9987754321', 0, 'L2', 'EMP0013'),
	(14, 'Akshaya', 'akshaya@gmail.com', '2023-11-09 18:10:32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'fxJKkP2oIU', NULL, NULL, '2023-11-09 18:10:32', '2023-11-09 18:10:32', '9987754321', 0, 'L2', 'EMP0014');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table medicall_erp.visitors
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salutation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `organization` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `known_source` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_visit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `proof_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `_meta` json DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `visitors_username_unique` (`username`),
  UNIQUE KEY `visitors_proof_id_unique` (`proof_id`),
  KEY `visitors_created_by_foreign` (`created_by`),
  KEY `visitors_updated_by_foreign` (`updated_by`),
  KEY `visitors_deleted_by_foreign` (`deleted_by`),
  KEY `visitors_category_id_foreign` (`category_id`),
  CONSTRAINT `visitors_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `visitors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `visitors_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `visitors_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table medicall_erp.visitors: ~14 rows (approximately)
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` (`id`, `username`, `password`, `salutation`, `name`, `mobile_number`, `email`, `category_id`, `organization`, `designation`, `known_source`, `reason_for_visit`, `newsletter`, `proof_type`, `proof_id`, `registration_type`, `_meta`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`, `created_at`, `updated_at`) VALUES
	(1, 'Abinaya123', '$2y$10$ZQq7DuyVaa0s0mV0bd/bIuy22nNxE6wcdefeIql/HVSknD7qjVDA2', 'Ms', 'Abinaya', '6398701234', 'abinaya@gmail.com', 1, 'Healthy Doctor', 'Patient Care Technician', 'Bus Panel', 'Stay updated on new trends ', 1, NULL, NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(2, 'Akshaya123', '$2y$10$RIJtVuG0j3mbf41cuyAsDeoYcED2xxhQKcBeb6GguPD5rjQsk3dWq', 'Ms', 'Abinaya', '6798701234', 'akshaya@gmail.com', 1, 'Hope hill', 'Dentist ', 'Bus Panel', 'Stay updated on new trends ', 1, NULL, NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(3, 'Arjun123', '$2y$10$g2T9EbSIk2yT/sgCJpc/MuCKMQzFAGnlA7RoCmgXzDmB9eC/vdbXK', 'Mr', 'Arjun', '6998701234', 'arjun@gmail.com', 1, 'Health House', 'Patient Care Technician', 'Bus Panel', 'Stay updated on new trends ', 1, NULL, NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(4, 'Bhavani123', '$2y$10$Ov0tU8105QTBQcgLofYiLeJ.d0u56uABaTVShDdnsXj4tWZ1Izaia', 'Ms', 'Bhavani', '9398701234', 'bhavani@gmail.com', 1, 'The Dreamers', 'Software Engineer', 'Facebook', 'get a good idea ', 1, NULL, NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(5, 'Bharathi123', '$2y$10$0XhDM.MJYVj9bhVK5czKv.ei.R.z24NGaaI7Gz2FJGW.0nUjJQIeK', 'Ms', 'Bharathi', '6398701234', 'barathi@gmail.com', 1, 'Silverline', 'Software Developer', 'Bus Panel', 'Stay updated on new trends ', 1, NULL, NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(6, 'Gowri123', '$2y$10$Ya/uPgDD.cI4DCE88T76O.36QVJs8rTT4/UPT954io0kNSBheWIRi', 'Ms', 'Gowri', '8318701234', 'gowri@gmail.com', 1, 'Healthy Doctor', 'Patient Care Technician', 'Newspaper Ad', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(7, 'Anupriy123', '$2y$10$bub2iGqOHuP18LAX5l7l5uf9U/Q.VKsCnsRCgUTZXJ4HYmDr9ZmQ2', 'Ms', 'Anupriya', '8498701234', 'anu@gmail.com', 1, 'Castle Health Care', 'Patient Care Technician', 'Bus Panel', 'Stay updated on new trends ', 1, 'Aadhar', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(8, 'Akil123', '$2y$10$tZEvep9TzdtW3Xx1nurxtOproBHe5.PLbT7O0RlAUpLw2WJnndiIu', 'Mr', 'Akil', '6898701234', 'akil@gmail.com', 1, 'Healthy Doctor', 'Patient Care Technician', 'Newspaper Ad', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(9, 'Kumar123', '$2y$10$qvDv0cQPrFunOGpNu04kues0BYWozh.COAt21eYkr6mPXxAyFvDIO', 'Mr', 'Kumar', '6998701234', 'kumar@gmail.com', 1, 'Castle Health Care', 'Patient Care Technician', 'Newspaper Ad', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(10, 'Sarath123', '$2y$10$2aDAAegdwKyuf/9XJ5dtPO.MmV9uyrx.SSzNEd9EHJLyn99BayLLm', 'Mr', 'Sarath', '6498701234', 'sarath@gmail.com', 15, 'Healthy Doctor', 'Patient Care Technician', 'Field Force', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(11, 'Saranya123', '$2y$10$KWzLIGW1MPdBQaShRhl5QOdaRMSSgOIxkc5cjyZYy/QEmz6twzOvy', 'Ms', 'Saranya', '6398700234', 'saranya@gmail.com', 16, 'Silverline', 'Software Architect', 'Bus Panel', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(12, 'Vimalda123', '$2y$10$AebzA4yR.VI.yH0QRTaHOO9qiOLqZk812vk5NoUVL2EGaYSkNcBmy', 'Ms', 'Vimalda', '8398701234', 'vimalda@gmail.com', 1, 'Meds USA', 'Patient Care Technician', 'Field Force', 'Stay updated on new trends ', 1, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(13, 'Vimal123', '$2y$10$IkG1qnTaGk4jcTbMccsype6BEa67VH5iCgAzoZQ2S1yXAL7g8hF2C', 'Mr', 'Vimal', '7398701234', 'vimal@gmail.com', 12, 'Silverline', 'Software Architect', 'Bus Panel', 'Stay updated on new trends ', 0, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33'),
	(14, 'Vinothini1235', '$2y$10$U.xL3RX6kXc9ndF3uOOPFOEUOEqhw0vM2vl7HHpOJOBqMkeCHuKAW', 'Ms', 'Vinothini', '6398731234', 'vinothini@gmail.com', 12, 'Meds USA', 'Patient Care Technician', 'Field Force', 'Stay updated on new trends ', 0, 'PAN', NULL, 'web', NULL, 1, NULL, NULL, NULL, '2023-11-09 18:10:33', '2023-11-09 18:10:33');
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
