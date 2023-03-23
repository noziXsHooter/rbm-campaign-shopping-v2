-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para shopping_campaign
DROP DATABASE IF EXISTS `shopping_campaign`;
CREATE DATABASE IF NOT EXISTS `shopping_campaign` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `shopping_campaign`;

-- Copiando estrutura para tabela shopping_campaign.coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `code` int NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `valor` double NOT NULL,
  `store` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_coupon` (`user_id`) USING BTREE,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela shopping_campaign.coupons: ~9 rows (aproximadamente)
INSERT INTO `coupons` (`id`, `user_id`, `code`, `cpf`, `valor`, `store`, `date_time`, `status`) VALUES
	(114, 2, 10, '22222222222', 300, 'Loja 1', '2023-03-01 10:15:00', 0),
	(115, 2, 15, '22222222222', 70, 'Loja2', '2023-02-09 10:16:00', 1),
	(116, 7, 30, '33333333333', 700, 'Loja 3', '2023-03-13 10:17:00', 0),
	(117, 7, 35, '33333333333', 100, 'Loja 2', '2023-03-10 10:17:00', 1),
	(118, 7, 38, '33333333333', 50, 'Loja 4', '2023-03-17 10:18:00', 1),
	(119, 15, 40, '44444444444', 1500, 'Loja 5', '2023-03-14 10:18:00', 0),
	(120, 1, 50, '11111111111', 200, 'Loja 6', '2023-03-24 20:00:00', 0),
	(121, 1, 55, '11111111111', 200, 'Loja23', '2023-03-15 20:50:00', 0),
	(122, 1, 60, '11111111111', 50, 'Loja23', '2023-03-15 20:50:00', 1);

-- Copiando estrutura para tabela shopping_campaign.luck_numbers
CREATE TABLE IF NOT EXISTS `luck_numbers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_num` (`user_id`),
  CONSTRAINT `fk_user_num` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=508 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela shopping_campaign.luck_numbers: ~9 rows (aproximadamente)
INSERT INTO `luck_numbers` (`id`, `user_id`, `hash`, `created_at`) VALUES
	(499, 2, 'f953f5b8-58f3-48e2-8af3-229b6bbd17ff', '2023-03-16 10:15:43'),
	(500, 7, '33af2828-7244-4587-bb00-6914f4ed5ffc', '2023-03-16 10:17:24'),
	(501, 7, 'eb482d8c-fda9-4f76-9256-db027d3aefc7', '2023-03-16 10:17:24'),
	(502, 15, '8cc62683-888d-477c-830e-2ea6ad7c8f94', '2023-03-16 10:18:49'),
	(503, 15, '98486456-60c8-405b-be7b-843f2bdfcd7c', '2023-03-16 10:18:49'),
	(504, 15, 'b252c35a-c5c0-4e1a-bd9a-5be26e8badae', '2023-03-16 10:18:49'),
	(505, 15, '5c787d4f-3ffe-47d0-bd6f-ea9a26b0e44a', '2023-03-16 10:18:49'),
	(506, 15, 'a7064fae-47a1-4870-b2e1-7ecf9b3cf4e5', '2023-03-16 10:18:49'),
	(507, 1, 'fcb43583-1617-4dc1-8ab8-a04dd9944fc3', '2023-03-16 20:50:22');

-- Copiando estrutura para tabela shopping_campaign.sweepstakes
CREATE TABLE IF NOT EXISTS `sweepstakes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `hash` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_sweepstake` (`user_id`) USING BTREE,
  CONSTRAINT `fk_user_sweepstake` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela shopping_campaign.sweepstakes: ~2 rows (aproximadamente)
INSERT INTO `sweepstakes` (`id`, `user_id`, `hash`, `name`, `created_at`) VALUES
	(10, 1, '758b8a0d-d3e2-4cf1-b8c7-331361d63fcd', 'John Doe', '2023-03-14 15:44:36'),
	(11, 1, 'e044b0ad-a0e6-4b9e-8a91-4a2dad46e2ae', 'John Doe', '2023-03-16 10:13:25');

-- Copiando estrutura para tabela shopping_campaign.sweepstake_status
CREATE TABLE IF NOT EXISTS `sweepstake_status` (
  `status` tinyint(1) NOT NULL,
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela shopping_campaign.sweepstake_status: ~1 rows (aproximadamente)
INSERT INTO `sweepstake_status` (`status`) VALUES
	(0);

-- Copiando estrutura para tabela shopping_campaign.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile` varchar(10) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `born_in` date DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela shopping_campaign.users: ~6 rows (aproximadamente)
INSERT INTO `users` (`id`, `profile`, `cpf`, `name`, `sex`, `born_in`, `password`, `created_at`) VALUES
	(1, 'admin', '11111111111', 'John Doe', 'Masculino', '2023-06-30', '$2y$10$N8CKLqFyEc1edZNyQtzawOBdHA7P9iyajZdC2exmU7VYznQJmOhJu', '2023-03-15 11:24:35'),
	(2, 'agent', '22222222222', 'Jane Doe', 'Feminino', '2023-05-30', '$2y$10$N8CKLqFyEc1edZNyQtzawOBdHA7P9iyajZdC2exmU7VYznQJmOhJu', '2023-03-15 11:24:35'),
	(7, 'agent', '33333333333', 'Jao Maria', 'Masculino', '2023-02-07', '$2y$10$N8CKLqFyEc1edZNyQtzawOBdHA7P9iyajZdC2exmU7VYznQJmOhJu', '2023-03-15 11:24:35'),
	(15, 'agent', '44444444444', 'Mark Duck', 'Masculino', '2023-03-15', '$2y$10$Gig0GuTouTDpdY.QzzeknOvfYJ39xsG7SAjhRWv406AidL8n8zxL2', '2023-03-15 17:02:17'),
	(16, 'agent', '55555555555', 'Ana Froid', 'Feminino', '2023-04-07', '$2y$10$Gig0GuTouTDpdY.QzzeknOvfYJ39xsG7SAjhRWv406AidL8n8zxL2', '2023-03-15 23:49:40'),
	(17, 'agent', '66666666666', 'Matt Damn', 'Masculino', '2023-03-15', '$2y$10$1iPsTWh1i4A4YroRXJTz6.zhQNyKCqKmGKRwifhj92XDjW3bCC9XO', '2023-03-16 00:18:08');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
