/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postnum` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `member` tinyint(1) DEFAULT NULL,
  `personal_num` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `discounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `images` (
  `id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `price` decimal(10,0) DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` varchar(50) DEFAULT NULL,
  `is_discounted` int DEFAULT '0',
  `discount_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_id` (`order_id`),
  KEY `fk_discount_id` (`discount_id`),
  CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `price` decimal(10,0) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `shipped_date` date DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Pending',
  `shipping_option_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shipping_option` (`shipping_option_id`),
  CONSTRAINT `fk_shipping_option` FOREIGN KEY (`shipping_option_id`) REFERENCES `shipping_options` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `sku` varchar(16) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `saleable` tinyint(1) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_description` varchar(500) DEFAULT NULL,
  `img_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `customers_id` int DEFAULT NULL,
  `recension_DSCR` varchar(500) DEFAULT NULL,
  `recension_grade` int DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `shipping_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `customers` (`id`, `firstname`, `lastname`, `email`, `phone`, `address`, `city`, `postnum`, `created`, `member`, `personal_num`) VALUES
(10, 'John', 'Doe', 'john.doe@example.com', '555-1234', '123 Main St', 'New York', NULL, '2023-11-08 00:00:00', 1, '$2y$10$KDmA/.gNkJ1N6fvCFnZ.9.9nEeuKpCQxSjHEUu93sq6fZlSLEgBve');
INSERT INTO `customers` (`id`, `firstname`, `lastname`, `email`, `phone`, `address`, `city`, `postnum`, `created`, `member`, `personal_num`) VALUES
(11, 'Jane', 'Smith', 'jane.smith@example.com', '555-5678', '456 Elm St', 'Los Angeles', NULL, '2023-11-08 00:00:00', 0, '$2y$10$68An.YciF2brGps4c2lVDuXyXjy3f.MiE8yHcxAcVfJvuGpM2ID5W');
INSERT INTO `customers` (`id`, `firstname`, `lastname`, `email`, `phone`, `address`, `city`, `postnum`, `created`, `member`, `personal_num`) VALUES
(12, 'Bob', 'Johnson', 'bob.johnson@example.com', '555-9876', '789 Oak St', 'Chicago', NULL, '2023-11-08 00:00:00', 1, '$2y$10$2zYIdq24p8pd8CkUqqnnb.j1fbZLAXPfugW17lQiAzBYBxtffq2Ca');
INSERT INTO `customers` (`id`, `firstname`, `lastname`, `email`, `phone`, `address`, `city`, `postnum`, `created`, `member`, `personal_num`) VALUES
(13, 'Alice', 'Williams', 'alice.williams@example.com', '555-6543', '234 Pine St', 'Houston', NULL, '2023-11-08 00:00:00', 0, '$2y$10$bn.ozI68cArxb7/K66nO5u4RUVAALn0jcpvWG9in0GjoHfHY1DsQC'),
(14, 'David', 'Brown', 'david.brown@example.com', '555-3210', '567 Cedar St', 'Miami', NULL, '2023-11-08 00:00:00', 1, '$2y$10$1UQSm7jW3rNgjCaUVKnZWOKOo9tTGwCFdbo4ah0xFhzISrTZrT5im'),
(15, 'Linda', 'Davis', 'linda.davis@example.com', '555-8765', '890 Birch St', 'Dallas', NULL, '2023-11-08 00:00:00', 0, '$2y$10$bjD/79jCDHxlLSURaLeHFeIzwl6ibQr2zwnu02vNEJ33aheLVeYh2'),
(16, 'Michael', 'Wilson', 'michael.wilson@example.com', '555-2345', '432 Spruce St', 'Phoenix', NULL, '2023-11-08 00:00:00', 1, '$2y$10$jZ7THX3kFIAttzenx22cB.WQwcl0LGQppgJBzoIIJI.cKVWs/jKKq'),
(17, 'Maja', 'Wilson', 'maja@gmail.com', '556-2340', '432 Spruce St', 'Phoenix', NULL, '2023-11-08 00:00:00', 1, '$2y$10$CvBdTACZ99QdSYkYPfJZB.CIqsjPmwrC1NzTE1mzpRTm49KWGBBpC'),
(18, 'Sara', 'Lee', 'sara.lee@example.com', '555-4321', '876 Maple St', 'San Francisco', NULL, '2023-11-08 00:00:00', 0, '$2y$10$Bm/T.3JeOx7a5fMmKIMuee6rbCSXocj/TziR0CRepyZSQnQ4lSIOq'),
(20, 'Kevin', 'Moore', 'kevin.moore@example.com', '555-7890', '543 Walnut St', 'Seattle', NULL, '2023-11-08 00:00:00', 1, '$2y$10$XLXOymZ3BRM0HA5.XBjVeuAgBOGJtqx2Z2yrCb/k.6b6J4t0FXS8W'),
(21, 'Emily', 'Taylor', 'emily.taylor@example.com', '555-8765', '765 Cherry St', 'Denver', NULL, '2023-11-08 00:00:00', 0, '$2y$10$yYGKqnX/oDqwbYNHo.pL7uHl6SxalJaidxO9g7EL9LLKNSNpitQTi'),
(22, 'Eleni', 'Vrabec', 'el@test.com', '123456789', 'Komarov 3', 'Skopje', NULL, '2023-11-22 00:00:00', NULL, '$2y$10$pofOR7ExdqtDw8HQKStns.GSoYzb0qdiMR0r5i2EbRtCwnGHWfJJO'),
(23, 'Dimi', 'Vrabec', 'supermario@gmail.com', '12345678', 'Ruzveltova 1', 'Skopje', NULL, '2023-11-22 00:00:00', NULL, '$2y$10$P6to8qk8l0Ij8TG687lgA..8SozzLhbOtTWWW9rdjHPYclvottiXi'),
(24, 'Nika', 'Vrabec', 'test@test.com', '123455432', 'Dalby 5', 'Skopje', NULL, '2023-11-22 00:00:00', NULL, '$2y$10$ToHGbZnjTCMEoEqPdNDq9OqQNVbkvJnbSr2xM83GvYRV9X7AMvPde'),
(25, 'Dani', 'Vrabec', 'eleni@test.com', '123455432', 'John Ericsson Väg 13', 'Skopje', NULL, '2023-11-23 00:00:00', NULL, '$2y$10$kcdO86wWH8NmLX69JPjr1uT0N/8I1txH9HJrMMb3/bV6746TTCHhy'),
(26, 'Eleni', 'Vrabec', 'joana@test.com', '123455432', 'Elisetorpvägen 9', 'Skopje', NULL, '2023-11-23 00:00:00', NULL, '$2y$10$//7PqDP8DEq6fUKSSIbgQudUJNGxRy1zHiI3ZbdxbtkarwjUclUAm'),
(27, 'Ana', 'Stone', 'eleni-eni@hotmail.com', '12346621', 'John Kenedi', 'Malmö', NULL, '2023-11-23 00:00:00', NULL, '$2y$10$ev.M.XGa4N8GgDOTAFzi0O0Ln4UmLIrKqCtRfErjyaYKKyvpi3uUO'),
(28, 'Eleni', 'Vrabec', 'eleniV@test.com', '12345678', 'Romanovska 10', 'skopje', NULL, '2023-11-23 00:00:00', NULL, '$2y$10$3FQHMZDXhE9MjJjgDDKEa.D1u3RWYwcWjcHjCA4A8A6ppTzKPREwm'),
(29, 'Nikola', 'Stone', 'nikola@test.com', '12346621', 'Park Avenija 5', 'Malmö', NULL, '2023-11-23 00:00:00', NULL, '$2y$10$KKT8ctEGv7DzKKf/D1jd/uSyKkkbyAVw5B/tys5cr3NJoONhaGJry'),
(30, 'Mario', 'Super', 'supermario1@gmail.com', '123455432', 'Sandanski 21', 'Skopje', NULL, '2023-11-23 11:19:36', NULL, '$2y$10$we5bKlbQjRroczbn239DXeMti46Zb91jlyZo388avgY5crGgkIHKe'),
(31, 'Nika', 'Super', 'test2@tes2t.com', '12345678', 'Borggatan 7', 'Skopje', NULL, '2023-11-23 12:56:11', NULL, '$2y$10$PL45pZ./R0.cAMISCDHGjOvh3iUABM5.SC9EavrY8gh49T.WMqWOu'),
(32, 'Gjorgji ', 'Kolozov', 'kolozov@test.com', '22222222', 'Heaven', 'Skopje', '1000', '2023-11-24 10:57:43', NULL, '$2y$10$vGw88uQHNTTuC6JJ.Hf3GuXCbBPGp5jO7Zc..0EP/L7rK9BCHLk2W'),
(33, 'Petar', 'Pan', 'petar@gmail.com', '01234567', 'Neverland 77', 'Skopje', '1000', '2023-11-26 11:21:27', NULL, '$2y$10$KXGCjbpCwrbYgsiVwskz7.7XJ3EwZRPq7SJIV5dFkqaEGawdfC8aK'),
(34, 'Eleni Vrabec', 'Vrabec', 'testtest@test.com', '01234567', 'HomeSweetHome', 'Skopje', '1000', '2023-11-27 19:03:16', NULL, '$2y$10$uf9kty5BN.yekgrllGwY0OzGzAVNKRmywBSMI1a95pqabJ1Rr/mYW'),
(35, 'Dimitrij', 'Vrabec', 'dimi@test.com', '01234567', 'Home', 'Skopje', '1000', '2023-11-27 19:04:48', NULL, '$2y$10$HDVVjBckaBzMvuQeibFd0uLqZPSt2z2/NfC55gqQo2VPao62g6Gnq');

INSERT INTO `discounts` (`id`, `code`, `amount`) VALUES
(1, 'HOLIDAY25', '25.00');
INSERT INTO `discounts` (`id`, `code`, `amount`) VALUES
(2, 'BLACKWEEK50', '50.00');
INSERT INTO `discounts` (`id`, `code`, `amount`) VALUES
(3, 'SUMMER24', '10.00');
INSERT INTO `discounts` (`id`, `code`, `amount`) VALUES
(4, 'HELLO10', '10.00'),
(5, 'W50', '50.00'),
(6, 'W50', '50.00'),
(7, 'Hello10', '10.00');

INSERT INTO `images` (`id`, `name`, `url_path`) VALUES
(1, 'T-shirt', 'https://images.pexels.com/photos/428338/pexels-photo-428338.jpeg?auto=compress&cs=tinysrgb&w=600');
INSERT INTO `images` (`id`, `name`, `url_path`) VALUES
(2, 'blouse', 'https://images.pexels.com/photos/19083425/pexels-photo-19083425/free-photo-of-teenage-model-posing-in-design-eyeglasses.jpeg?auto=compress&cs=tinysrgb&w=600');
INSERT INTO `images` (`id`, `name`, `url_path`) VALUES
(3, 'jeans', 'https://images.pexels.com/photos/1082529/pexels-photo-1082529.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
INSERT INTO `images` (`id`, `name`, `url_path`) VALUES
(4, 'shoes', 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?auto=compress&cs=tinysrgb&w=600'),
(5, 'jacket', 'https://images.pexels.com/photos/1460036/pexels-photo-1460036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'),
(6, 'sunglasses', 'https://images.pexels.com/photos/701877/pexels-photo-701877.jpeg?auto=compress&cs=tinysrgb&w=600'),
(7, 'backpack', 'https://images.pexels.com/photos/1294731/pexels-photo-1294731.jpeg?auto=compress&cs=tinysrgb&w=600'),
(8, 'thermos', 'https://images.pexels.com/photos/9716820/pexels-photo-9716820.jpeg?auto=compress&cs=tinysrgb&w=600');

INSERT INTO `order_items` (`id`, `price`, `order_id`, `quantity`, `created`, `product_id`, `is_discounted`, `discount_id`) VALUES
(41, '450', 35, 1, '2023-11-23 14:02:05', '2', 0, NULL);
INSERT INTO `order_items` (`id`, `price`, `order_id`, `quantity`, `created`, `product_id`, `is_discounted`, `discount_id`) VALUES
(44, '250', 38, 1, '2023-11-23 14:29:20', '1', 0, NULL);
INSERT INTO `order_items` (`id`, `price`, `order_id`, `quantity`, `created`, `product_id`, `is_discounted`, `discount_id`) VALUES
(65, '250', 59, 1, '2023-11-25 10:58:31', '1', NULL, NULL);
INSERT INTO `order_items` (`id`, `price`, `order_id`, `quantity`, `created`, `product_id`, `is_discounted`, `discount_id`) VALUES
(84, '125', 77, 1, '2023-11-26 12:21:27', '1', 1, NULL),
(85, '250', 77, 1, '2023-11-26 12:21:27', '2', 1, NULL),
(86, '200', 77, 1, '2023-11-26 12:21:27', '3', 1, NULL),
(110, '1500', 98, 3, '2023-11-26 18:33:17', '2', NULL, 0),
(111, '700', 98, 1, '2023-11-26 18:33:17', '4', NULL, 0),
(128, '750', 115, 1, '2023-11-27 11:12:51', '6', 1, 2),
(138, '1000', 123, 1, '2023-11-27 20:08:52', '5', NULL, 0),
(143, '500', 128, 1, '2023-11-28 13:40:37', '2', NULL, 0),
(144, '360', 129, 1, '2023-11-28 13:58:10', '3', 1, 4),
(145, '250', 130, 1, '2023-11-28 13:59:52', '2', 1, 2),
(146, '350', 130, 1, '2023-11-28 13:59:52', '4', 1, 2);

INSERT INTO `orders` (`id`, `price`, `customer_id`, `shipped_date`, `created`, `payment_method`, `status`, `shipping_option_id`) VALUES
(35, '450', 22, '2023-11-23', '2023-11-23 13:02:05', NULL, 'Shipped', NULL);
INSERT INTO `orders` (`id`, `price`, `customer_id`, `shipped_date`, `created`, `payment_method`, `status`, `shipping_option_id`) VALUES
(38, '250', 22, '2023-11-23', '2023-11-23 13:29:20', NULL, 'Completed', NULL);
INSERT INTO `orders` (`id`, `price`, `customer_id`, `shipped_date`, `created`, `payment_method`, `status`, `shipping_option_id`) VALUES
(59, '250', 22, '2023-11-25', '2023-11-25 09:58:31', NULL, 'Processing', 2);
INSERT INTO `orders` (`id`, `price`, `customer_id`, `shipped_date`, `created`, `payment_method`, `status`, `shipping_option_id`) VALUES
(77, '775', 33, '2023-11-26', '2023-11-26 11:21:27', NULL, 'Completed', 4),
(98, '2200', 27, '2023-11-26', '2023-11-26 17:33:17', NULL, 'Shipped', 2),
(115, '850', 27, '2023-11-27', '2023-11-27 10:12:51', NULL, 'Processing', 3),
(123, '1000', 35, '2023-11-27', '2023-11-27 19:08:52', NULL, 'Processing', 2),
(128, '500', 27, '2023-11-28', '2023-11-28 12:40:37', NULL, 'Processing', 2),
(129, '460', 35, '2023-11-28', '2023-11-28 12:58:10', NULL, 'Pending', 3),
(130, '600', 35, '2023-11-28', '2023-11-28 12:59:52', NULL, 'Pending', 2);

INSERT INTO `products` (`id`, `name`, `sku`, `price`, `stock`, `saleable`, `created`, `product_description`, `img_id`) VALUES
(1, 'T-shirt', 'T4', '250', 10, 1, '2022-10-01 00:00:00', 'White cotton t-shirt ', '1');
INSERT INTO `products` (`id`, `name`, `sku`, `price`, `stock`, `saleable`, `created`, `product_description`, `img_id`) VALUES
(2, 'Blouse', 'B3', '500', 0, 0, '2020-11-01 00:00:00', 'Black premium blouse', '2');
INSERT INTO `products` (`id`, `name`, `sku`, `price`, `stock`, `saleable`, `created`, `product_description`, `img_id`) VALUES
(3, 'Jeans', 'P5', '400', 20, 1, '2021-07-02 00:00:00', 'Black cropped jeans', '3');
INSERT INTO `products` (`id`, `name`, `sku`, `price`, `stock`, `saleable`, `created`, `product_description`, `img_id`) VALUES
(4, 'Shoes', 'P2', '700', 25, 1, '2023-01-02 00:00:00', 'Nike Air Max', '4'),
(5, 'Jacket', 'P3', '1000', 5, 1, '2020-08-20 00:00:00', 'Winter puffer jacket', '5'),
(6, 'Sunglasses', 'P6', '1500', 7, 1, '2020-01-18 00:00:00', 'Green sungasses ', '6'),
(7, 'Bagpack', 'P7', '1000', 20, 1, '2023-04-03 00:00:00', 'Tracking backpak from Fjallraven ', '7'),
(8, 'Thermos', 'P8', '200', 10, 1, '2023-01-02 00:00:00', 'Hicking accessoar Thermos', '8');

INSERT INTO `reviews` (`id`, `product_id`, `customers_id`, `recension_DSCR`, `recension_grade`) VALUES
(1, 1, 10, 'Nice and cozy t-shirt', 5);
INSERT INTO `reviews` (`id`, `product_id`, `customers_id`, `recension_DSCR`, `recension_grade`) VALUES
(2, 8, 12, 'It is keeping the dring warm for about 10 hours. Nice!', 5);
INSERT INTO `reviews` (`id`, `product_id`, `customers_id`, `recension_DSCR`, `recension_grade`) VALUES
(3, 3, 13, 'Not true in size!', 1);
INSERT INTO `reviews` (`id`, `product_id`, `customers_id`, `recension_DSCR`, `recension_grade`) VALUES
(4, 5, 15, 'Nice and warm. The color does not look the same as on the picture!', 3),
(5, 5, 12, 'I love the color!', 5),
(6, 1, 17, 'Bad quality', 1),
(7, 8, 14, 'good quality', 4);

INSERT INTO `shipping_options` (`id`, `name`, `amount`) VALUES
(2, 'Standard Shipping', '0.00');
INSERT INTO `shipping_options` (`id`, `name`, `amount`) VALUES
(3, 'Express Shipping', '100.00');
INSERT INTO `shipping_options` (`id`, `name`, `amount`) VALUES
(4, 'International Shipping', '200.00');
INSERT INTO `shipping_options` (`id`, `name`, `amount`) VALUES
(5, 'Next Day Shipping', '150.00'),
(6, 'FAST SHIP', '120.00'),
(7, 'Shipping ', '100.00'),
(8, 'Shipping ', '100.00'),
(9, 'Shipping ', '100.00');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;