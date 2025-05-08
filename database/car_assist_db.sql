-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 08:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_assist_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_reports`
--

CREATE TABLE `customer_reports` (
  `id` int(11) NOT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mechanic_services`
--

CREATE TABLE `mechanic_services` (
  `id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanic_services`
--

INSERT INTO `mechanic_services` (`id`, `mechanic_id`, `service_id`) VALUES
(5, 27, 1),
(6, 27, 3),
(11, 41, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `status` enum('pending','accepted','on_the_way','completed','rejected','cancelled') DEFAULT 'pending',
  `scheduled_time` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `mechanic_id`, `service_id`, `status`, `scheduled_time`, `location`, `created_at`, `completed_at`, `rating`, `feedback`, `latitude`, `longitude`) VALUES
(10, 32, 27, 1, 'on_the_way', NULL, NULL, '2025-05-06 14:49:59', NULL, NULL, NULL, 30.0444, 31.2357);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `reported_id` int(11) DEFAULT NULL,
  `role` enum('customer','mechanic') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Customer'),
(3, 'Mechanic');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `description`) VALUES
(1, 'Oil Change', 250.00, NULL),
(2, 'Tire Change', 180.00, NULL),
(3, 'Breakdown Help', 300.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','mechanic','customer') NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `cv` varchar(255) DEFAULT NULL,
  `availability` enum('available','busy') DEFAULT 'available',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `user_type`, `role_id`, `status`, `cv`, `availability`, `latitude`, `longitude`, `is_verified`, `verification_token`, `cv_path`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'Admin User', 'admin@roadfix.com', '01000000000', '$2y$10$b3DTZFkyHYvL3um6g7ItxeD52DzyKwOHqRQGuOzIGsrZseqgZwbnG', 'admin', 1, 'pending', NULL, 'available', NULL, NULL, 0, NULL, NULL, NULL, NULL),
(27, 'sara', 'mechanic27@roadfix.com', '01050747232', '$2y$10$lzM5Tcjm14aqUsI.hLqWduEpkuNX73S8qtU13cUUKSw1GYOaYwPC2', 'mechanic', NULL, 'approved', NULL, 'available', 30.04440000, 31.23570000, 0, NULL, '1746271888_Sheet 1.pdf', 'ca69fdd6c60b7b19ba046a6ab2317166a170347436f4e25b8f00f335830c9f9b', '2025-05-06 01:59:20'),
(32, 'salma', 's1a2l3m4a5s1a2y3e4d5@gmail.com', '01050747232', '$2y$10$5DeFBDoYXmxkUROhYjtS1.paVaT0DwK1Ez8U7hlSqpguOc4PSZXj6', 'customer', NULL, 'pending', NULL, 'available', NULL, NULL, 1, NULL, NULL, '8f4680a275c5117469f0b378706089f62ac8d2cc662172ee7cc485d71d3b065a', '2025-05-06 02:05:02'),
(33, 'habiba', 'habibakhaled129@gmail.com', '01050747232', '$2y$10$PHAzwjxcOpQac0nE/9Ou8eyDvDIqjASjS/ETDvURU0Z.pzCKPbBA6', 'customer', NULL, 'pending', NULL, 'available', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(41, 'salma sayed', 'mechanic41@roadfix.com', '01050747232', '$2y$10$ufOiPeSVVqgg3BfXxPh8Z.B4zsWetPL5DT/RaR.z/4S5wn6x1xc3G', 'mechanic', NULL, 'approved', NULL, 'available', 30.04440000, 31.23570000, 0, NULL, '1746488425_CS316 Algorithms_Sheet 4.pdf', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_reports`
--
ALTER TABLE `customer_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mechanic_services`
--
ALTER TABLE `mechanic_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_id` (`reported_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_reports`
--
ALTER TABLE `customer_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mechanic_services`
--
ALTER TABLE `mechanic_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mechanic_services`
--
ALTER TABLE `mechanic_services`
  ADD CONSTRAINT `mechanic_services_ibfk_1` FOREIGN KEY (`mechanic_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mechanic_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`mechanic_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
