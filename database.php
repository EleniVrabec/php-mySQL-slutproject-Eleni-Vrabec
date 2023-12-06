<?php 
require_once 'config.php';

function getDataBaseConnection() {
   
    $connection = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT); 
    if ($connection->connect_error != null) {
        die("Connection error: " . $connection->connect_error);

    }else{
         
        createTablesIfNeeded($connection);
        return $connection;
    }
   
}

function createTablesIfNeeded($connection) {
    // Create Orders table
    $sql = "CREATE TABLE IF NOT EXISTS `orders` (
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
    ) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Customers table
    $sql = "CREATE TABLE IF NOT EXISTS `customers` (
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
    ) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Discounts table
    $sql = "CREATE TABLE IF NOT EXISTS `discounts` (
        `id` int NOT NULL AUTO_INCREMENT,
        `code` varchar(50) NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Images table
    $sql = "CREATE TABLE IF NOT EXISTS `images` (
        `id` int DEFAULT NULL,
        `name` varchar(255) DEFAULT NULL,
        `url_path` varchar(255) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Order Items table
    $sql = "CREATE TABLE IF NOT EXISTS `order_items` (
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
    ) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Products table
    $sql = "CREATE TABLE IF NOT EXISTS `products` (
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
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Reviews table
    $sql = "CREATE TABLE IF NOT EXISTS `reviews` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `product_id` int DEFAULT NULL,
        `customers_id` int DEFAULT NULL,
        `recension_DSCR` varchar(500) DEFAULT NULL,
        `recension_grade` int DEFAULT NULL,
        UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);

    // Create Shipping Options table
    $sql = "CREATE TABLE IF NOT EXISTS `shipping_options` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    $connection->query($sql);
}