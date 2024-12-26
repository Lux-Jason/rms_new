SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库：`rms`
--

DELIMITER $$

--
-- 存储过程
--
DROP PROCEDURE IF EXISTS `update_order_status`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_order_status` (
    IN `p_order_id` INT,
    IN `p_dish_id` INT,
    IN `p_new_status` VARCHAR(50)
)
BEGIN
    DECLARE current_status VARCHAR(50);

    -- 获取当前状态
    SELECT status INTO current_status
    FROM order_detail
    WHERE order_id = p_order_id AND dish_id = p_dish_id;

    -- 验证状态转换
    IF (current_status = 'inprogress' AND p_new_status IN ('ordered', 'cancel')) OR
       (current_status = 'ordered' AND p_new_status = 'cancel') THEN
        UPDATE order_detail
        SET status = p_new_status
        WHERE order_id = p_order_id AND dish_id = p_dish_id;
    ELSE
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invalid state transition';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `buyer`
--

DROP TABLE IF EXISTS `buyer`;
CREATE TABLE IF NOT EXISTS `buyer` (
    `b_id` INT NOT NULL AUTO_INCREMENT,
    `buyer_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `b_password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `remains` DECIMAL(10, 2) DEFAULT NULL,
    PRIMARY KEY (`b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `buyer`
--

INSERT INTO `buyer` (`b_id`, `buyer_name`, `b_password`, `type`, `remains`)
VALUES
    (6, 'Alice', '$2y$10$nFcjuDRmsOD5RnlsNgZcCOf4cmKC0XFkc4cQIc9ztLWTkPJ.gvmc6', 'vip', 1000.00);

-- --------------------------------------------------------

--
-- 表的结构 `cashflow`
--

DROP TABLE IF EXISTS `cashflow`;
CREATE TABLE IF NOT EXISTS `cashflow` (
    `c_id` INT NOT NULL AUTO_INCREMENT,
    `s_id` INT NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `type` ENUM('income', 'expense') COLLATE utf8mb4_general_ci NOT NULL,
    `balance` DECIMAL(10, 2) NOT NULL,
    `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    PRIMARY KEY (`c_id`),
    KEY `s_id` (`s_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
                                      `dish_id` INT NOT NULL AUTO_INCREMENT,
                                      `dish_name` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
                                      `ingredient` TEXT COLLATE utf8mb4_general_ci,
                                      `description` TEXT COLLATE utf8mb4_general_ci,
                                      `type` VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
                                      `price` DECIMAL(10, 2) NOT NULL,
                                      `amount` INT NOT NULL,
                                      `note` TEXT COLLATE utf8mb4_general_ci,
                                      `image` LONGBLOB,
                                      PRIMARY KEY (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50047 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `menu`
--

INSERT INTO `menu` (`dish_id`, `dish_name`, `price`, `amount`)
VALUES
    (2873, 'Roasted Garlic Tofu Grill', 48.37, 158),
    (2874, 'Marinated Citrus Pork Skewer', 25.61, 213),
    (2875, 'Roasted Hoisin Beef Stew', 12.21, 79),
    (2876, 'Sautéed Herb Lamb Risotto', 8.52, 14);

-- --------------------------------------------------------

--
-- 表的结构 `operate`
--

DROP TABLE IF EXISTS `operate`;
CREATE TABLE IF NOT EXISTS `operate` (
                                         `operate_id` INT NOT NULL AUTO_INCREMENT,
                                         `s_id` INT NOT NULL,
                                         `dish_id` INT DEFAULT NULL,
                                         `order_id` INT DEFAULT NULL,
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
                                       `order_id` INT NOT NULL AUTO_INCREMENT,
                                       `b_id` INT NOT NULL,
                                       `time` DATETIME NOT NULL,
                                       `comment` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
                                       PRIMARY KEY (`order_id`),
                                       KEY `b_id` (`b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=971896552 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`order_id`, `b_id`, `time`, `comment`)
VALUES
    (211525621, 6, '2024-12-25 10:23:20', ''),
    (971896551, 6, '2024-12-26 02:30:28', '');

--
-- 触发器 `order`
--
DROP TRIGGER IF EXISTS `set_order_time`;
DELIMITER $$
CREATE TRIGGER `set_order_time` BEFORE INSERT ON `order` FOR EACH ROW
BEGIN
    -- 如果订单没有提供下单时间，则使用当前时间
    IF NEW.time IS NULL THEN
        SET NEW.time = NOW();
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
CREATE TABLE IF NOT EXISTS `order_detail` (
                                              `order_id` INT NOT NULL,
                                              `dish_id` INT NOT NULL,
                                              `num_dishes` INT NOT NULL,
                                              `price` DECIMAL(10, 2) NOT NULL,
                                              `status` ENUM('inprogress', 'ordered', 'cancel') COLLATE utf8mb4_general_ci DEFAULT 'inprogress',
                                              KEY `order_id` (`order_id`),
                                              KEY `dish_id` (`dish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `order_detail`
--

INSERT INTO `order_detail` (`order_id`, `dish_id`, `num_dishes`, `price`, `status`)
VALUES
    (211525621, 10, 1, 25.44, 'inprogress'),
    (211525621, 21, 1, 27.73, 'inprogress'),
    (211525621, 11, 1, 25.96, 'inprogress'),
    (211525621, 12, 1, 10.18, 'inprogress'),
    (211525621, 3, 1, 17.16, 'inprogress'),
    (211525621, 9, 1, 27.49, 'inprogress'),
    (971896551, 9, 1, 27.49, 'inprogress'),
    (971896551, 7, 1, 20.77, 'inprogress'),
    (971896551, 10, 1, 25.44, 'inprogress'),
    (971896551, 5, 1, 22.13, 'inprogress');

--
-- 触发器 `order_detail`
--
DROP TRIGGER IF EXISTS `insert_operate_after_status_ordered`;
DELIMITER $$
CREATE TRIGGER `insert_operate_after_status_ordered` AFTER UPDATE ON `order_detail` FOR EACH ROW
BEGIN
    -- 仅当 status 从非 'ordered' 改为 'ordered' 时才插入操作记录
    IF OLD.status != 'ordered' AND NEW.status = 'ordered' THEN
        -- 插入操作记录到 operate 表
        INSERT INTO `operate` (`s_id`, `dish_id`, `order_id`)
        VALUES (1, NEW.dish_id, NEW.order_id);
    END IF;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `update_cashflow_on_order_status`;
DELIMITER $$
CREATE TRIGGER `update_cashflow_on_order_status` AFTER UPDATE ON `order_detail` FOR EACH ROW
BEGIN
    -- 检查订单状态是否变为 'ordered'
    IF OLD.status != 'ordered' AND NEW.status = 'ordered' THEN
        -- 获取订单的总金额
        INSERT INTO cashflow (s_id, amount, type, balance, date, description)
        SELECT
            o.s_id, -- 卖家ID
            SUM(od.price * od.num_dishes), -- 计算总金额
            'income', -- 类型：收入
            b.remains + SUM(od.price * od.num_dishes), -- 卖家新的账户余额
            NOW(), -- 当前时间
            CONCAT('Income from order_id ', NEW.order_id) -- 描述信息
        FROM order_detail od
                 JOIN operate o ON od.order_id = o.order_id
                 JOIN `order` ord ON od.order_id = ord.order_id
                 JOIN buyer b ON ord.b_id = b.b_id
        WHERE od.order_id = NEW.order_id
        GROUP BY o.s_id, b.remains;
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `security`
--

DROP TABLE IF EXISTS `security`;
CREATE TABLE IF NOT EXISTS `security` (
                                          `user_id` INT NOT NULL,
                                          `security_question` VARCHAR(255) NOT NULL,
                                          `answer_hash` VARCHAR(255) NOT NULL,
                                          PRIMARY KEY (`user_id`, `security_question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `security`
--

INSERT INTO `security` (`user_id`, `security_question`, `answer_hash`)
VALUES
    (6, 'birth_city', '$2y$10$HBJpKLfu/lN3ccfxndyvYO9tjhbcI/2YKAy0rx9itWkJPhCNh7zem'),
    (6, 'first_pet', '$2y$10$55XJMSjAD6MhHEgBEMoDq.bQ5LlXQB2TdqWDb4TaX0x1b9cwYhrp6');

-- --------------------------------------------------------

--
-- 表的结构 `seller`
--

DROP TABLE IF EXISTS `seller`;
CREATE TABLE IF NOT EXISTS `seller` (
                                        `s_id` INT NOT NULL AUTO_INCREMENT,
                                        `s_name` VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
                                        `s_password` VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
                                        PRIMARY KEY (`s_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `seller`
--

INSERT INTO `seller` (`s_id`, `s_name`, `s_password`)
VALUES
    (6, 'Jason', '$2y$10$Y7elV15qI4pA3rK5dUybq.s1o7IY1ybP38rVYi4Q.9.UGgIUFFL/e');

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