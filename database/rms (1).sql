-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2024-12-15 07:32:13
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
  `buyer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `b_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remains` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `buyer`
--

INSERT INTO `buyer` (`b_id`, `buyer_name`, `b_password`, `type`, `remains`) VALUES
(6, 'Alice', '$2y$10$nFcjuDRmsOD5RnlsNgZcCOf4cmKC0XFkc4cQIc9ztLWTkPJ.gvmc6', 'vip', 1000.00);

-- --------------------------------------------------------

--
-- 表的结构 `cashflow`
--

DROP TABLE IF EXISTS `cashflow`;
CREATE TABLE IF NOT EXISTS `cashflow` (
  `c_id` int NOT NULL AUTO_INCREMENT,
  `s_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_general_ci NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`c_id`),
  KEY `s_id` (`s_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `cashflow`
--

INSERT INTO `cashflow` (`c_id`, `s_id`, `amount`, `type`, `balance`, `date`, `description`) VALUES
(1, 5, 1000.00, 'income', 1000.00, '2024-12-15 01:11:51', 'Received payment from customer'),
(2, 5, 500.00, 'expense', 500.00, '2024-12-15 01:11:51', 'Paid supplier for goods');

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
) ENGINE=InnoDB AUTO_INCREMENT=50031 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`order_id`),
  KEY `b_id` (`b_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 触发器 `order`
--
DROP TRIGGER IF EXISTS `set_order_time`;
DELIMITER $$
CREATE TRIGGER `set_order_time` BEFORE INSERT ON `order` FOR EACH ROW BEGIN
    -- 如果订单没有提供下单时间，则使用当前时间
    IF NEW.time IS NULL THEN
        SET NEW.time = NOW();
    END IF;
END
$$
DELIMITER ;

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

--
-- 触发器 `order_detail`
--
DROP TRIGGER IF EXISTS `update_cashflow_on_order_status`;
DELIMITER $$
CREATE TRIGGER `update_cashflow_on_order_status` AFTER UPDATE ON `order_detail` FOR EACH ROW BEGIN
    -- 检查订单状态是否变为 'ordered'
    IF OLD.status != 'ordered' AND NEW.status = 'ordered' THEN
        -- 获取订单的总金额
        INSERT INTO cashflow (s_id, amount, type, balance, date, description)
        SELECT 
            o.s_id,                                  -- 卖家ID
            SUM(od.price * od.num_dishes),           -- 计算总金额
            'income',                                -- 类型：收入
            b.remains + SUM(od.price * od.num_dishes), -- 卖家新的账户余额
            NOW(),                                   -- 当前时间
            CONCAT('Income from order_id ', NEW.order_id) -- 描述信息
        FROM order_detail od
        JOIN operate o ON od.order_id = o.order_id
        JOIN `order` ord ON od.order_id = ord.order_id
        JOIN buyer b ON ord.b_id = b.b_id
        WHERE od.order_id = NEW.order_id
        GROUP BY o.s_id, b.remains;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `security`
--

DROP TABLE IF EXISTS `security`;
CREATE TABLE IF NOT EXISTS `security` (
  `user_id` int NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `answer_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`security_question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `security`
--

INSERT INTO `security` (`user_id`, `security_question`, `answer_hash`) VALUES
(6, 'birth_city', '$2y$10$HBJpKLfu/lN3ccfxndyvYO9tjhbcI/2YKAy0rx9itWkJPhCNh7zem'),
(6, 'first_pet', '$2y$10$55XJMSjAD6MhHEgBEMoDq.bQ5LlXQB2TdqWDb4TaX0x1b9cwYhrp6');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `seller`
--

INSERT INTO `seller` (`s_id`, `s_name`, `s_password`) VALUES
(5, 'Jason', '$2y$10$DR5YGddZ2DqvhehJ5CZcyeqhL9qVN58n62JPNVbQ0ZVhLHj3lJ.iO');

--
-- 限制导出的表
--

--
-- 限制表 `cashflow`
--
ALTER TABLE `cashflow`
  ADD CONSTRAINT `cashflow_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `seller` (`s_id`) ON DELETE CASCADE;

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

--
-- 限制表 `security`
--
ALTER TABLE `security`
  ADD CONSTRAINT `security_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `buyer` (`b_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
