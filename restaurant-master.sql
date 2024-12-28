-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 09:00 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant-master`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin@example.com', 'admin_hashed_password', '2024-12-16 09:49:35', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`log_id`, `admin_id`, `action`, `timestamp`) VALUES
(1, 1, 'Updated restaurant status for Pizza Planet to active', '2024-12-16 09:49:35'),
(2, 1, 'Added new delivery partner: Delivery Guy 2', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partners`
--

CREATE TABLE `delivery_partners` (
  `delivery_partner_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `vehicle_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vehicle_details`)),
  `is_available` tinyint(1) DEFAULT 1,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `current_lat` decimal(10,8) DEFAULT NULL,
  `current_lng` decimal(11,8) DEFAULT NULL,
  `current_order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_partners`
--

INSERT INTO `delivery_partners` (`delivery_partner_id`, `name`, `email`, `password`, `phone`, `vehicle_details`, `is_available`, `status`, `created_at`, `updated_at`, `current_lat`, `current_lng`, `current_order_id`) VALUES
(1, 'Delivery Guy 1', 'delivery1@example.com', 'hashed_password5', '3334445555', '{\"vehicle_type\": \"bike\", \"vehicle_number\": \"XY1234\"}', 1, '', '2024-12-16 09:49:35', '2024-12-21 11:07:04', '30.34000000', '6.37999700', 1),
(2, 'Delivery Guy 2', 'delivery2@example.com', 'hashed_password6', '6667778888', '{\"vehicle_type\": \"scooter\", \"vehicle_number\": \"AB5678\"}', 1, '', '2024-12-16 09:49:35', '2024-12-16 09:49:35', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `restaurant_id`, `item_name`, `description`, `price`, `image_url`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pepperoni Pizza', 'Classic pepperoni pizza with extra cheese', '12.99', 'http://example.com/pizza1.jpg', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(2, 1, 'Veggie Pizza', 'Loaded with fresh vegetables and cheese', '10.99', 'http://example.com/pizza2.jpg', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(3, 2, 'Cheeseburger', 'Juicy burger with cheddar cheese and pickles', '8.99', 'http://example.com/burger1.jpg', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(4, 2, 'Veggie Burger', 'Delicious plant-based burger with toppings', '9.49', 'http://example.com/burger2.jpg', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `delivery_partner_id` int(11) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Preparing','On the way','Delivered','Cancelled') DEFAULT 'Pending',
  `delivery_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `current_status` enum('Pending','Preparing','Out for Delivery','Delivered','Cancelled') DEFAULT 'Pending',
  `delivery_lat` decimal(10,8) DEFAULT NULL,
  `delivery_lng` decimal(11,8) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `restaurant_id`, `delivery_partner_id`, `items`, `total_price`, `status`, `delivery_address`, `created_at`, `updated_at`, `current_status`, `delivery_lat`, `delivery_lng`, `otp`) VALUES
(1, 1, 1, 1, '[{\"item_name\": \"Pepperoni Pizza\", \"quantity\": 1}]', '12.99', 'Pending', '123 Elm Street, Springfield', '2024-12-16 09:49:35', '2024-12-16 09:49:35', 'Pending', NULL, NULL, NULL),
(2, 2, 2, 2, '[{\"item_name\": \"Cheeseburger\", \"quantity\": 2}]', '17.98', 'Preparing', '456 Oak Street, Springfield', '2024-12-16 09:49:35', '2024-12-16 09:49:35', 'Pending', NULL, NULL, NULL),
(3, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":5}]', '64.95', 'Pending', '123 Elm Street', '2024-12-24 05:25:28', '2024-12-24 05:25:28', 'Pending', NULL, NULL, NULL),
(4, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":5}]', '64.95', 'Pending', '123 Elm Street', '2024-12-24 05:31:22', '2024-12-24 05:31:22', 'Pending', NULL, NULL, NULL),
(5, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":5}]', '64.95', 'Pending', '123 Elm Street', '2024-12-24 05:31:57', '2024-12-24 05:31:57', 'Pending', NULL, NULL, NULL),
(6, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":5}]', '64.95', 'Pending', '123 Elm Street', '2024-12-24 05:47:26', '2024-12-24 05:47:26', 'Pending', NULL, NULL, NULL),
(7, 4, 1, NULL, '[{\"menu_id\":2,\"quantity\":5}]', '54.95', 'Pending', '123 Elm Street', '2024-12-25 06:20:23', '2024-12-25 06:20:23', 'Pending', NULL, NULL, NULL),
(8, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":1}]', '12.99', 'Pending', '123 Elm Street', '2024-12-25 06:46:00', '2024-12-25 06:46:00', 'Pending', NULL, NULL, NULL),
(9, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":3}]', '38.97', 'Pending', '123 Elm Street', '2024-12-25 07:23:12', '2024-12-25 07:23:12', 'Pending', NULL, NULL, NULL),
(10, 4, 1, NULL, '[{\"menu_id\":1,\"quantity\":2}]', '25.98', 'Pending', '123 Elm Street', '2024-12-28 06:06:25', '2024-12-28 06:06:25', 'Pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_trackings`
--

CREATE TABLE `order_trackings` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `tracking_info` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_trackings`
--

INSERT INTO `order_trackings` (`id`, `user_name`, `order_id`, `product_name`, `order_status`, `tracking_info`, `created_at`) VALUES
(1, 'John Doe', 'ORD12345', 'Laptop', 'Shipped', 'Your order is on the way. Expected delivery: 2 days.', '2024-12-28 05:56:56'),
(2, 'Jane Smith', 'ORD67890', 'Smartphone', 'Delivered', 'Your order has been delivered.', '2024-12-28 05:56:56'),
(3, 'Alice Johnson', 'ORD11223', 'Headphones', 'Processing', 'We are preparing your order.', '2024-12-28 05:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('Credit Card','Debit Card','UPI','Cash on Delivery') NOT NULL,
  `payment_status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `user_id`, `payment_method`, `payment_status`, `created_at`) VALUES
(1, 1, 1, 'Credit Card', 'Completed', '2024-12-16 09:49:35'),
(2, 2, 2, 'UPI', 'Pending', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `name`, `email`, `password`, `phone`, `address`, `rating`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pizza Planet', 'pizza.planet@example.com', 'hashed_password3', '1112223333', '789 Maple Avenue, Springfield', '4.50', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(2, 'Burger House', 'burger.house@example.com', 'hashed_password4', '4445556666', '101 Pine Street, Springfield', '4.00', 1, '2024-12-16 09:49:35', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `restaurant_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'The pizza was amazing!', '2024-12-16 09:49:35'),
(2, 2, 2, 4, 'The burger was delicious, but the fries were cold.', '2024-12-16 09:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'hashed_password1', '1234567890', '123 Elm Street, Springfield', '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(2, 'Jane Smith', 'jane.smith@example.com', 'hashed_password2', '0987654321', '456 Oak Street, Springfield', '2024-12-16 09:49:35', '2024-12-16 09:49:35'),
(3, 'Kavita Bisht', 'bishtkavita779@gmail.com', '12345678', '06284543382', NULL, '2024-12-20 09:02:32', '2024-12-20 13:34:34'),
(4, 'Kavita Bisht', 'Kavita@gmail.com', '$2y$10$TLYOjsMwSq4PMVegrNSJneVlCEYvsz5yNeIJDFXhk0qYlHCVc4zg.', '06284543382', NULL, '2024-12-20 09:12:30', '2024-12-20 13:42:30'),
(6, 'Kavita Bisht', 'kavita123@gmail.com', '$2y$10$ngxd8A1OnvfnGAZKwe3tneRGNTN.q.rk5pkbn5rmCfSxaUpg06rmS', '06284543382', NULL, '2024-12-20 09:24:57', '2024-12-20 13:54:57'),
(7, 'Pizza hut', 'Pizzahut@gmail.com', '$2y$10$5HfMcZqB.lC0MBVxsoRnFu/iPDNMEFEDjeKU9QfyIuY/me.0lx0fW', '1234567890', NULL, '2024-12-20 09:48:06', '2024-12-20 14:18:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `delivery_partners`
--
ALTER TABLE `delivery_partners`
  ADD PRIMARY KEY (`delivery_partner_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `foriegn key` (`current_order_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `delivery_partner_id` (`delivery_partner_id`);

--
-- Indexes for table `order_trackings`
--
ALTER TABLE `order_trackings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurant_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_partners`
--
ALTER TABLE `delivery_partners`
  MODIFY `delivery_partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_trackings`
--
ALTER TABLE `order_trackings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_partners`
--
ALTER TABLE `delivery_partners`
  ADD CONSTRAINT `foriegn key` FOREIGN KEY (`current_order_id`) REFERENCES `orders` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`delivery_partner_id`) REFERENCES `delivery_partners` (`delivery_partner_id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
