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


-- Dumping database structure for sh_portal
CREATE DATABASE IF NOT EXISTS `sh_portal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sh_portal`;

-- Dumping structure for table sh_portal.sh_properties
CREATE TABLE IF NOT EXISTS `sh_properties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sh_portal.sh_properties: ~9 rows (approximately)
INSERT INTO `sh_properties` (`id`, `url`, `updated_at`, `date`) VALUES
	(3, 'https://www.xyzug.co', '2024-03-29 20:42:39 pm', '2024-03-29 17:13:48 pm'),
	(5, 'https://www.cyxipyhot.mobi', '2024-03-29 17:52:48 pm', '2024-03-29 17:13:57 pm'),
	(6, 'https://www.pysekug.biz', '2024-03-30 15:02:05 pm', '2024-03-29 17:57:51 pm'),
	(7, 'https://www.ginuteb.cm', '2024-03-29 17:59:48 pm', '2024-03-29 17:57:57 pm'),
	(8, 'https://www.ryxirogotuw.co', NULL, '2024-03-29 17:58:02 pm'),
	(9, 'https://www.gyragewiwyn.cc', '2024-03-29 17:58:11 pm', '2024-03-29 17:58:06 pm'),
	(10, 'https://www.rygadotuduluca.co.uk', '2024-03-29 19:14:35 pm', '2024-03-29 19:08:22 pm'),
	(11, 'https://www.fuvymuves.org.uk', NULL, '2024-03-29 20:02:43 pm'),
	(12, 'https://www.voqyfi.in', '2024-04-18 13:53:54 pm', '2024-03-30 16:35:39 pm'),
	(13, 'https://shlus.ae/', '2024-04-03 15:57:00 pm', '2024-04-03 15:48:36 pm'),
	(15, 'https://www.widuqyhykicyzu.org.au', '2024-04-18 13:53:59 pm', '2024-04-18 13:53:49 pm');

-- Dumping structure for table sh_portal.sh_users
CREATE TABLE IF NOT EXISTS `sh_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url_address` varchar(500) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `email` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rank` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `properties` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `pass_token` varchar(500) DEFAULT NULL,
  `token_date` varchar(50) DEFAULT NULL,
  `expire_token` int DEFAULT '0',
  `updated_at` varchar(50) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `url_address` (`url_address`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sh_portal.sh_users: ~5 rows (approximately)
INSERT INTO `sh_users` (`id`, `url_address`, `avatar`, `name`, `email`, `rank`, `properties`, `password`, `pass_token`, `token_date`, `expire_token`, `updated_at`, `date`) VALUES
	(2, 'denphUEJt26d07kVCU6PI2bDd8WetEc59kJ', 'shahid-ifraheem.png', 'Shahid Ifraheem', 'developer.shlus@gmail.com', 'admin', 'https://siblogs.info/,https://shlus.ae/,https://www.rajyge.org', '7f4c324fc73fc9d4e7c98eb8472ad511df05de37', 'NQsDTSMaAnHyuXEvg18ltrAJ4eGvHL', '2024-04-05 19:26:17 pm', 1, '2024-04-04 15:43:03 pm', '2024-03-25 17:17:45'),
	(19, 'YR6ftYm7p4HKJneTuuxfKcICc2somyaGeT4t2BdZhBHBILa8LcOp9oj', 'hamish-griffith.png', 'Hamish Griffith', 'vogeduw@mailinator.com', 'customer', 'https://www.gyragewiwyn.cc,https://www.ryxirogotuw.co,https://www.ginuteb.cm,https://www.cyxipyhot.mobi,https://www.xyzug.co', '7268d7558532e93867d4a83d722c87f3ec252bce', NULL, NULL, NULL, NULL, '2024-03-30 16:40:25 pm'),
	(20, '3AvP5y59hX', 'violet-higgins.png', 'Violet Higgins', 'zoxa@mailinator.com', 'customer', 'https://www.fuvymuves.org.uk,https://www.rygadotuduluca.co.uk,https://www.xyzug.co', '261b0f2dc388a48a53097fbe213c42de50cf2ae3', NULL, NULL, NULL, '2024-04-03 15:27:34 pm', '2024-04-01 12:57:21 pm'),
	(21, 'n93OFhVJm29Oaxbl', 'melanie-sears.png', 'Melanie Sears', 'sesox@mailinator.com', 'customer', 'https://shlus.ae/,https://www.ryxirogotuw.co', '7f4c324fc73fc9d4e7c98eb8472ad511df05de37', 'rScxfIhGrCCZCjJXVqxroUEXQdAEk', '2024-04-06 15:32:30 pm', 0, '2024-04-03 17:22:18 pm', '2024-04-03 16:45:08'),
	(22, 'zp6n1m505fH8tdDxfp7s2gzfrTAYcNuaX', NULL, 'Backup Me', 'backup.me0256@gmail.com', 'admin', '', '8e671f22f841b01b615880c5dde7a7a24caadd5b', NULL, NULL, NULL, NULL, '2024-04-04 15:45:47 pm'),
	(23, '7Eum9Jgailti', NULL, 'Courtney Robles', 'lynex@mailinator.com', 'admin', 'https://www.widuqyhykicyzu.org.au,https://siblogs.info/,https://www.voqyfi.in,https://www.cyxipyhot.mobi', '2f39f2cc3d75697a6dbc2510049c036068135d66', NULL, NULL, 0, NULL, '2024-04-18 13:54:51 pm');

-- Dumping structure for table sh_portal.sh_visitors
CREATE TABLE IF NOT EXISTS `sh_visitors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(100) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sh_portal.sh_visitors: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
