-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2022 at 08:51 AM
-- Server version: 8.0.28
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kinetik`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int NOT NULL,
  `bill_name` varchar(255) NOT NULL,
  `bill_date` datetime NOT NULL,
  `amount` float NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `due` float NOT NULL,
  `paid` float NOT NULL,
  `created_by` int NOT NULL,
  `f_customer_no` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='due' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `bill_name`, `bill_date`, `amount`, `status`, `due`, `paid`, `created_by`, `f_customer_no`, `created_at`, `updated_at`) VALUES
(1, 'customer', '2022-04-02 00:00:00', 100, 1, 100, 0, 1, 1, '2022-04-14 17:06:16', '2022-04-14 18:56:32'),
(2, 'customer2', '2022-04-28 00:00:00', 100, 0, 100, 0, 1, 5, '2022-04-15 08:42:29', '2022-04-15 08:49:51');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'customer', 'customer@gmail.com', NULL, '$2y$10$QtJjL5y1BCCa.rXBw54Z/O1TxmeTGN8oATd1VYe7.wg44xUl/2FQ.', NULL, 0, 1, NULL, '2022-04-14 21:14:16'),
(5, 'customer2', 'customer2@gmail.com', NULL, '$2y$10$U6Ch//ZqZrQF6KsgUXLqHeernrmgSD30fWDPdRkSrWXh7f.I1m2M6', NULL, 0, 1, '2022-04-14 21:14:58', '2022-04-14 21:14:58'),
(6, 'customer3', 'customer3@gmail.com', NULL, '$2y$10$4ffy1ykvUV8BZ8fxaW5MEOLypF/bMZa8zHAhFM5ZBluvN//emyWL.', NULL, 0, 1, '2022-04-15 08:49:38', '2022-04-15 08:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '2022-04-10 14:55:12', '$2a$10$XOXToqBNMSHTBKFPUA/bOOpVbFT2hBmHqRlhTUE4JReXgKf1bb41i', '', 1, '2022-04-10 14:55:12', '2022-04-10 14:55:12'),
(2, 'Demo 1', 'syedsifat02@gmail.com', NULL, '$2y$10$WOaWa9/j9mkmrTgJWEFvo.a..8jk9jmw775FoY3kb2Cav4wnHVykW', NULL, 1, '2022-04-10 09:33:16', '2022-04-10 09:33:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customers_users` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
