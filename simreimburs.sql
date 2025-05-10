-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for simreimburs
CREATE DATABASE IF NOT EXISTS `simreimburs` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `simreimburs`;

-- Dumping structure for table simreimburs.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table simreimburs.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_05_02_014452_create_permission_tables', 2);

-- Dumping structure for table simreimburs.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table simreimburs.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.model_has_roles: ~1 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(9, 'App\\Models\\User', 5);

-- Dumping structure for table simreimburs.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.password_resets: ~0 rows (approximately)

-- Dumping structure for table simreimburs.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.permissions: ~8 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'User Management', 'web', '2025-05-02 03:52:12', '2025-05-02 03:52:13'),
	(2, 'Role Management', 'web', '2025-05-02 04:05:15', '2025-05-02 04:05:16'),
	(3, 'Kendaraan', 'web', NULL, NULL),
	(4, 'Rute Perjalanan', 'web', NULL, NULL),
	(5, 'Perjalanan', 'web', NULL, NULL),
	(6, 'Kelola Report Perjalanan', 'web', NULL, NULL),
	(7, 'Report Perjalanan', 'web', NULL, NULL),
	(8, 'Kelola Perjalanan', 'web', NULL, NULL);

-- Dumping structure for table simreimburs.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- Dumping data for table simreimburs.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table simreimburs.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'web', '2025-05-01 18:07:24', '2025-05-01 18:07:24'),
	(9, 'Driver', 'web', '2025-05-09 19:42:34', '2025-05-09 19:42:34');

-- Dumping structure for table simreimburs.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.role_has_permissions: ~3 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(5, 9),
	(7, 9);

-- Dumping structure for table simreimburs.tb_kendaraan
CREATE TABLE IF NOT EXISTS `tb_kendaraan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tipe` int NOT NULL DEFAULT '0',
  `id_merk` int DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_polisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warna` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bahan_bakar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `konsumsi` int DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_tipe` (`id_tipe`),
  KEY `id_merk` (`id_merk`),
  CONSTRAINT `FK_id_merk` FOREIGN KEY (`id_merk`) REFERENCES `tb_merkkendaraan` (`id`),
  CONSTRAINT `FK_id_tipe` FOREIGN KEY (`id_tipe`) REFERENCES `tb_tipekendaraan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_kendaraan: ~1 rows (approximately)
INSERT INTO `tb_kendaraan` (`id`, `id_tipe`, `id_merk`, `nama`, `no_polisi`, `warna`, `bahan_bakar`, `konsumsi`, `status`, `created_at`, `updated_at`) VALUES
	(3, 5, 3, 'Kendaraan A', 'DK 1234 ABC', 'Hitam', 'Pertalite', 1, 'active', '2025-05-09 01:27:10', '2025-05-09 05:55:36');

-- Dumping structure for table simreimburs.tb_merkkendaraan
CREATE TABLE IF NOT EXISTS `tb_merkkendaraan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_merkkendaraan: ~0 rows (approximately)
INSERT INTO `tb_merkkendaraan` (`id`, `nama`, `status`, `created_at`, `updated_at`) VALUES
	(3, 'Merk A', 'active', '2025-05-07 01:59:32', '2025-05-09 05:52:45');

-- Dumping structure for table simreimburs.tb_perjalanan
CREATE TABLE IF NOT EXISTS `tb_perjalanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_rute` int DEFAULT NULL,
  `id_kendaraan` int DEFAULT NULL,
  `id_user` bigint unsigned NOT NULL,
  `jarak` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kalkulasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_rute_tbperjalanan` (`id_rute`),
  KEY `FK_id_mobil_tbperjalanan` (`id_kendaraan`),
  KEY `FK_id_user_tbperjalanan` (`id_user`),
  CONSTRAINT `FK_id_mobil_tbperjalanan` FOREIGN KEY (`id_kendaraan`) REFERENCES `tb_kendaraan` (`id`),
  CONSTRAINT `FK_id_rute_tbperjalanan` FOREIGN KEY (`id_rute`) REFERENCES `tb_ruteperjalanan` (`id`),
  CONSTRAINT `FK_id_user_tbperjalanan` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_perjalanan: ~1 rows (approximately)
INSERT INTO `tb_perjalanan` (`id`, `id_rute`, `id_kendaraan`, `id_user`, `jarak`, `kalkulasi`, `status`, `created_at`, `updated_at`) VALUES
	(11, NULL, 3, 1, '7.49', '7.49', NULL, '2025-05-09 05:59:55', '2025-05-09 05:59:55'),
	(13, NULL, 3, 5, '9.56', '9.56', NULL, '2025-05-10 03:39:38', '2025-05-10 03:39:38'),
	(15, NULL, 3, 5, '7.49', '7.49', NULL, '2025-05-10 03:48:58', '2025-05-10 03:48:58');

-- Dumping structure for table simreimburs.tb_reportperjalanan
CREATE TABLE IF NOT EXISTS `tb_reportperjalanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_perjalanan` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `bukti` longblob,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_perjalanan_tbreportperjalanan` (`id_perjalanan`),
  CONSTRAINT `FK_id_perjalanan_tbreportperjalanan` FOREIGN KEY (`id_perjalanan`) REFERENCES `tb_perjalanan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_reportperjalanan: ~0 rows (approximately)
INSERT INTO `tb_reportperjalanan` (`id`, `id_user`, `id_perjalanan`, `tanggal`, `bukti`, `status`, `created_at`, `updated_at`) VALUES
	(3, 1, 11, '2025-05-10', _binary 0x75706c6f6164732f426f6947475277456b4d3932426b6b774a6a32465a525975435253567643565a617867756b7064512e6a7067, 'confirmed', '2025-05-10 02:44:46', '2025-05-10 02:52:11'),
	(6, 5, 15, '1989-01-15', _binary 0x75706c6f6164732f786944413463394c4c4652466237534850514339386f703557417a48696f504c36645356676d494d2e6a7067, 'not_confirmed', '2025-05-10 03:57:10', '2025-05-10 03:57:27');

-- Dumping structure for table simreimburs.tb_rute
CREATE TABLE IF NOT EXISTS `tb_rute` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_rute` int DEFAULT NULL,
  `id_perjalanan` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_rute_tbrute` (`id_rute`),
  KEY `FK_id_perjalanan_tbrute` (`id_perjalanan`),
  CONSTRAINT `FK_id_perjalanan_tbrute` FOREIGN KEY (`id_perjalanan`) REFERENCES `tb_perjalanan` (`id`),
  CONSTRAINT `FK_id_rute_tbrute` FOREIGN KEY (`id_rute`) REFERENCES `tb_ruteperjalanan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_rute: ~3 rows (approximately)
INSERT INTO `tb_rute` (`id`, `id_rute`, `id_perjalanan`, `created_at`, `updated_at`) VALUES
	(32, 5, 11, NULL, NULL),
	(33, 6, 11, NULL, NULL),
	(34, 7, 11, NULL, NULL),
	(38, 5, 13, NULL, NULL),
	(39, 6, 13, NULL, NULL),
	(40, 11, 13, NULL, NULL),
	(43, 5, 15, NULL, NULL),
	(44, 6, 15, NULL, NULL),
	(45, 7, 15, NULL, NULL);

-- Dumping structure for table simreimburs.tb_ruteperjalanan
CREATE TABLE IF NOT EXISTS `tb_ruteperjalanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_ruteperjalanan: ~7 rows (approximately)
INSERT INTO `tb_ruteperjalanan` (`id`, `nama`, `alamat`, `longitude`, `latitude`, `status`, `created_at`, `updated_at`) VALUES
	(5, 'TPS MONANG-MANING', 'Jl. Merpati, Desa Tegal Kerta', '115.198833', '-8.659667', 'active', '2025-05-08 01:53:32', '2025-05-08 03:24:49'),
	(6, 'TPS GUNUNG KARANG', 'Jl. Gunung Karang', '115.203056', '-8.665833', 'active', '2025-05-08 01:54:13', '2025-05-08 01:54:13'),
	(7, 'TPS PEGOK', 'Jl. Raya Sesetan Gurita I', '115.218889', '-8.704472', 'active', '2025-05-08 01:54:53', '2025-05-08 01:54:53'),
	(8, 'TPS YANG BATU', 'Jl. Cok. Tresna Timur Warung Sakinah', '115.225278', '-8.666944', 'active', '2025-05-08 01:55:30', '2025-05-08 01:55:30'),
	(9, 'TPS CEMARA', 'Jl. Tukad Nyali No. 1', '115.251833', '-8.674694', 'active', '2025-05-08 01:56:09', '2025-05-08 01:56:09'),
	(10, 'TPS CITARUM', 'Jl. Citarum Panjer', '115.232778', '-8.688056', 'active', '2025-05-08 01:57:01', '2025-05-08 01:57:01'),
	(11, 'TPS SIDAKARYA', 'Jl. Mertasari, Sidakarya (Dekat Jembatan), Desa Sidakarya', '115.233056', '-8.708889', 'active', '2025-05-08 01:57:32', '2025-05-08 01:57:32');

-- Dumping structure for table simreimburs.tb_tipekendaraan
CREATE TABLE IF NOT EXISTS `tb_tipekendaraan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table simreimburs.tb_tipekendaraan: ~0 rows (approximately)
INSERT INTO `tb_tipekendaraan` (`id`, `nama`, `status`, `created_at`, `updated_at`) VALUES
	(5, 'Tipe A', 'active', '2025-05-07 01:59:24', '2025-05-07 01:59:24');

-- Dumping structure for table simreimburs.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simreimburs.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Faizal', 'faizal.appkey@gmail.com', NULL, '$2y$10$eIibDQorl6Fa9Ga8po3nbeLFZ35U1XyDOyZ/1.CbRUSjV9kn35kJ2', NULL, '2025-05-01 17:28:05', '2025-05-01 17:28:05'),
	(5, 'Driver Test', 'driver@gmail.com', NULL, '$2y$10$6kHKlY0IIsaqtxsyfMFBcufSi2iZpL6utMOKuCE17qkLjY9c6Ek1W', NULL, '2025-05-09 18:56:07', '2025-05-09 19:43:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
