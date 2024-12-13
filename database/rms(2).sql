-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2024-12-13 17:52:41
-- 服务器版本： 8.2.0
-- PHP 版本： 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `rms`
--

-- --------------------------------------------------------

--
-- 表的结构 `buyer`
--

DROP TABLE IF EXISTS `buyer`;
CREATE TABLE IF NOT EXISTS `buyer` (
  `b_id` int NOT NULL AUTO_INCREMENT,
  `buyer_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `b_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remains` decimal(10,2) DEFAULT NULL,
  `safety_question` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `answer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `dish_id` int NOT NULL AUTO_INCREMENT,
  `dish_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ingredient` text COLLATE utf8mb4_general_ci,
  `description` text COLLATE utf8mb4_general_ci,
  `type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `amount` int NOT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `image` longblob,
  PRIMARY KEY (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `operate`
--

DROP TABLE IF EXISTS `operate`;
CREATE TABLE IF NOT EXISTS `operate` (
  `operate_id` int NOT NULL AUTO_INCREMENT,
  `s_id` int NOT NULL,
  `dish_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  PRIMARY KEY (`operate_id`),
  KEY `s_id` (`s_id`),
  KEY `dish_id` (`dish_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `b_id` int NOT NULL,
  `time` datetime NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`order_id`),
  KEY `b_id` (`b_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
CREATE TABLE IF NOT EXISTS `order_detail` (
  `detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `num_dishes` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `order_id` (`order_id`),
  KEY `dish_id` (`dish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `seller`
--

DROP TABLE IF EXISTS `seller`;
CREATE TABLE IF NOT EXISTS `seller` (
  `s_id` int NOT NULL AUTO_INCREMENT,
  `s_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `s_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 限制导出的表
--

--
-- 限制表 `operate`
--
ALTER TABLE `operate`
  ADD CONSTRAINT `operate_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `seller` (`s_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `operate_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `menu` (`dish_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `operate_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;

--
-- 限制表 `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`b_id`) REFERENCES `buyer` (`b_id`) ON DELETE CASCADE;

--
-- 限制表 `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `menu` (`dish_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
