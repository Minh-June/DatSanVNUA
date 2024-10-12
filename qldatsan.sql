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


-- Dumping database structure for qldatsan
CREATE DATABASE IF NOT EXISTS `qldatsan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `qldatsan`;

-- Dumping structure for table qldatsan.image_yard
CREATE TABLE IF NOT EXISTS `image_yard` (
  `image_id` int unsigned NOT NULL AUTO_INCREMENT,
  `san_id` int NOT NULL,
  `image` blob NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `san_id` (`san_id`),
  CONSTRAINT `FK_tbl_image_qldatsan.tbl_san` FOREIGN KEY (`san_id`) REFERENCES `yard` (`san_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table qldatsan.image_yard: ~16 rows (approximately)
INSERT INTO `image_yard` (`image_id`, `san_id`, `image`) VALUES
	(24, 2, _binary 0x79617264732f666f6f74322e6a7067),
	(25, 9, _binary 0x79617264732f666f6f74332e6a7067),
	(26, 16, _binary 0x79617264732f666f6f74342e6a7067),
	(27, 4, _binary 0x79617264732f626173312e6a7067),
	(28, 5, _binary 0x79617264732f626173322e6a7067),
	(29, 17, _binary 0x79617264732f626173332e6a7067),
	(30, 18, _binary 0x79617264732f626173342e6a7067),
	(31, 6, _binary 0x79617264732f626164312e6a7067),
	(32, 19, _binary 0x79617264732f626164322e6a7067),
	(33, 20, _binary 0x79617264732f626164332e6a7067),
	(34, 21, _binary 0x79617264732f626164342e6a7067),
	(35, 7, _binary 0x79617264732f74656e312e6a7067),
	(36, 8, _binary 0x79617264732f74656e322e6a7067),
	(37, 22, _binary 0x79617264732f74656e332e6a7067),
	(38, 23, _binary 0x79617264732f74656e342e6a7067),
	(39, 1, _binary 0x79617264732f666f6f74312e6a7067);

-- Dumping structure for table qldatsan.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table qldatsan.migrations: ~2 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2024_09_20_145344_change_image_column_in_tbl_order', 1);

-- Dumping structure for table qldatsan.order
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int NOT NULL,
  `date` date NOT NULL,
  `time` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `notes` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'choxacnhan',
  `san_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `san_id` (`san_id`),
  CONSTRAINT `fk_san_id` FOREIGN KEY (`san_id`) REFERENCES `yard` (`san_id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table qldatsan.order: ~3 rows (approximately)
INSERT INTO `order` (`order_id`, `name`, `phone`, `date`, `time`, `price`, `notes`, `status`, `san_id`, `user_id`, `image`) VALUES
	(131, 'Quang Minh', 356645445, '2024-10-12', '05:00 - 07:00', 120000, '12 người', 'xacnhan', 1, 41, '[]'),
	(132, 'Minh', 356645445, '2024-10-12', '07:00 - 08:30,09:00 - 11:00', 325000, '40 người', 'huydon', 1, 41, '["bills\\/thanhtoan.png"]'),
	(133, 'Minh', 356645445, '2024-10-12', '06:00 - 8:00,15:00 - 19:00', 600000, 'Tổ chức giải', 'choxacnhan', 2, 41, '[]');

-- Dumping structure for table qldatsan.time_slot
CREATE TABLE IF NOT EXISTS `time_slot` (
  `time_slot_id` int NOT NULL AUTO_INCREMENT,
  `san_id` int DEFAULT NULL,
  `time_slot` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  PRIMARY KEY (`time_slot_id`),
  KEY `fk_san_id_time_slots` (`san_id`) USING BTREE,
  CONSTRAINT `fk_san_id_time_slots` FOREIGN KEY (`san_id`) REFERENCES `yard` (`san_id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table qldatsan.time_slot: ~80 rows (approximately)
INSERT INTO `time_slot` (`time_slot_id`, `san_id`, `time_slot`, `price`) VALUES
	(1, 1, '05:00 - 07:00', 120000),
	(3, 1, '07:00 - 08:30', 125000),
	(5, 2, '05:00 - 07:00', 200000),
	(6, 2, '07:00 - 09:00', 200000),
	(13, 1, '09:00 - 11:00', 200000),
	(14, 9, '05:30 - 07:00', 120000),
	(15, 1, '14:00 - 17:00', 300000),
	(16, 9, '07:00 - 08:30', 120000),
	(17, 1, '17:00 - 19:00', 200000),
	(18, 1, '19:00 - 22:30', 300000),
	(20, 2, '09:00 - 11:00', 150000),
	(21, 2, '13:00 - 15:00', 150000),
	(22, 2, '15:30 - 17:30', 200000),
	(23, 2, '18:00 - 20:00', 300000),
	(24, 9, '14:30 - 16:00', 160000),
	(26, 9, '16:00 - 17:30', 160000),
	(27, 9, '17:30 - 19:00', 200000),
	(28, 16, '05:30 - 07:00', 120000),
	(29, 16, '07:00 - 08:30', 120000),
	(30, 16, '16:00 - 17:30', 160000),
	(31, 16, '17:30 - 19:00', 200000),
	(32, 16, '19:00 - 20:30', 200000),
	(34, 4, '08:00 - 11:00', 250000),
	(35, 4, '14:00 - 16:00', 200000),
	(36, 4, '16:00 - 18:00', 200000),
	(37, 4, '18:30 - 21:00', 250000),
	(40, 5, '07:00 - 08:30', 150000),
	(41, 5, '08:30 - 10:00', 120000),
	(42, 5, '14:30 - 16:00', 160000),
	(43, 5, '16:00 - 17:30', 160000),
	(44, 5, '17:30 - 19:00', 200000),
	(46, 6, '05:30 - 07:00', 120000),
	(47, 6, '07:00 - 08:30', 120000),
	(48, 6, '16:00 - 17:30', 160000),
	(49, 6, '17:30 - 19:00', 200000),
	(50, 6, '19:00 - 20:30', 200000),
	(53, 7, '05:30 - 07:00', 120000),
	(54, 7, '07:00 - 08:30', 120000),
	(55, 7, '14:30 - 16:00', 160000),
	(56, 7, '16:00 - 17:30', 160000),
	(57, 7, '17:30 - 19:00', 200000),
	(58, 8, '05:30 - 07:00', 120000),
	(59, 8, '16:00 - 17:30', 160000),
	(60, 8, '17:30 - 19:00', 200000),
	(61, 8, '19:00 - 20:30', 200000),
	(63, 9, '19:00 - 20:30', 200000),
	(64, 17, '07:00 - 08:30', 120000),
	(65, 17, '08:30 - 10:00', 120000),
	(66, 17, '16:00 - 17:30', 160000),
	(67, 17, '17:30 - 19:00', 200000),
	(68, 17, '19:00 - 20:30', 200000),
	(69, 18, '07:00 - 08:30', 120000),
	(70, 18, '08:30 - 10:00', 120000),
	(71, 18, '14:30 - 16:00', 160000),
	(72, 18, '16:00 - 17:30', 160000),
	(73, 18, '17:30 - 19:00', 200000),
	(74, 19, '05:30 - 07:00', 120000),
	(75, 19, '07:00 - 08:30', 120000),
	(76, 19, '14:30 - 16:00', 160000),
	(77, 19, '16:00 - 17:30', 160000),
	(78, 19, '17:30 - 19:00', 200000),
	(79, 20, '05:30 - 07:00', 120000),
	(80, 20, '07:00 - 08:30', 120000),
	(81, 20, '14:30 - 16:00', 160000),
	(82, 20, '16:00 - 17:30', 160000),
	(83, 20, '17:30 - 19:00', 200000),
	(84, 21, '05:30 - 07:00', 120000),
	(85, 21, '07:00 - 08:30', 120000),
	(86, 21, '16:00 - 17:30', 160000),
	(87, 21, '17:30 - 19:00', 200000),
	(88, 21, '19:00 - 20:30', 200000),
	(89, 22, '05:00 - 09:00', 350000),
	(90, 22, '09:00 - 11:00', 200000),
	(91, 22, '14:00 - 17:00', 300000),
	(92, 22, '17:30 - 19:00', 200000),
	(93, 22, '19:00 - 20:30', 200000),
	(94, 23, '05:00 - 09:00', 400000),
	(95, 23, '13:00 - 15:00', 250000),
	(96, 23, '15:30 - 18:00', 300000),
	(97, 23, '18:30 - 21:30', 350000);

-- Dumping structure for table qldatsan.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phonenb` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table qldatsan.user: ~2 rows (approximately)
INSERT INTO `user` (`user_id`, `username`, `password`, `role`, `fullname`, `gender`, `birthdate`, `phonenb`, `email`) VALUES
	(22, 'admin', '$2y$10$NQYaYwMmXgFVksHAotJkg.IHz1qO5V3cwQpml9vr/XZuLJHpppCN2', 0, 'Nguyễn Hữu Quang Minh', 'Nam', '2003-06-09', '0356645445', 'minhjune18@gmail.com'),
	(41, 'minh', '$2y$10$v.ldvP3XPdc2hB0kfW3qYONyZp0QoGOvRKl9DbF4fQ.PW7VCBwG3S', 1, 'Nguyễn Hữu Quang Minh', 'Nam', '2003-06-09', '0563490783', 'quangminhnguyenhuu962003@gmail.com');

-- Dumping structure for table qldatsan.yard
CREATE TABLE IF NOT EXISTS `yard` (
  `san_id` int NOT NULL AUTO_INCREMENT,
  `tensan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sosan` int NOT NULL,
  PRIMARY KEY (`san_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table qldatsan.yard: ~16 rows (approximately)
INSERT INTO `yard` (`san_id`, `tensan`, `sosan`) VALUES
	(1, 'Sân bóng đá', 1),
	(2, 'Sân bóng đá', 2),
	(4, 'Sân bóng rổ', 1),
	(5, 'Sân bóng rổ', 2),
	(6, 'Sân cầu lông', 1),
	(7, 'Sân Tennis', 1),
	(8, 'Sân Tennis', 2),
	(9, 'Sân bóng đá', 3),
	(16, 'Sân bóng đá', 4),
	(17, 'Sân bóng rổ', 3),
	(18, 'Sân bóng rổ', 4),
	(19, 'Sân cầu lông', 2),
	(20, 'Sân cầu lông', 3),
	(21, 'Sân cầu lông', 4),
	(22, 'Sân Tennis', 3),
	(23, 'Sân Tennis', 4);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
