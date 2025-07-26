-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 04:12 AM
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
-- Database: `dpp_printing`
--
CREATE DATABASE IF NOT EXISTS `dpp_printing` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dpp_printing`;

-- --------------------------------------------------------

--
-- Table structure for table `completed_orders`
--

CREATE TABLE `completed_orders` (
  `completed_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `completion_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `invoice_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `title`, `content`, `image`, `date`, `created_at`) VALUES
(1, 'leon is new', 'sadasdsdsdasdcxcxcvxcvxcvxcv', 'news6.jpg', '2025-06-23', '2025-06-23 00:55:39'),
(2, 'asdasdasd', 'xcvxcfghgfhfghfgfdffvvcvb', 'news1.jpg', '2025-06-23', '2025-06-23 01:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `document_type` varchar(100) DEFAULT NULL,
  `paper_size` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `document_link` varchar(255) DEFAULT NULL,
  `color` enum('bw','color') DEFAULT NULL,
  `sides` enum('single','double') DEFAULT NULL,
  `paper_type` varchar(50) DEFAULT NULL,
  `paper_thickness` varchar(50) DEFAULT NULL,
  `binding` varchar(50) DEFAULT NULL,
  `lamination` varchar(50) DEFAULT NULL,
  `corners` enum('square','rounded') DEFAULT NULL,
  `hole_punch` tinyint(1) DEFAULT 0,
  `perforation` tinyint(1) DEFAULT 0,
  `foil_stamping` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `status` enum('pending','processing','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `document_type`, `paper_size`, `quantity`, `document_link`, `color`, `sides`, `paper_type`, `paper_thickness`, `binding`, `lamination`, `corners`, `hole_punch`, `perforation`, `foil_stamping`, `notes`, `status`) VALUES
(4, 7, '2025-06-23 01:36:41', 'contract', 'A3', 1, 'https://plus.unsplash.com/premium_DB8fHx8fA%3D%3D', 'bw', 'single', 'premium', 'medium', 'none', 'none', 'square', 0, 0, 0, 'a', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `created_at`) VALUES
(1, 'KazamaJin', 'kavinda@gmail.com', '$2y$10$sJFjKu5MweIqdu8PmNiRdenuEiW6ICFtw1vmZzeGBWV5feZitayGC', '0715080145', 'customer', '2025-06-22 23:08:38'),
(4, 'admin', 'admin@dpp.com', '$2y$10$gbbJRJYvwyts5taIWqhFgucnAzhc0Pskw8clsL1TTN6p2elhnr/ti', '0776657611', 'admin', '2025-06-22 23:58:04'),
(5, 'abc', 'overkingwwm@gmail.com', '$2y$10$tDFqgsuZIy94bEk9KHclSesPkcjXl7Wv/Rcsx7SMxUgbzOl8m3/Me', '0715070145', 'customer', '2025-06-23 00:01:03'),
(6, 'admin2', 'dpp@admin.lk', '$2y$10$u8HkzbMbG3fNY1gearPNJebZfN9k/cn7OcPOFjP2uNzzkjWsCPPpK', '0776657611', 'admin', '2025-06-23 00:02:09'),
(7, 'isuru', 'rangajith@aa.cc', '$2y$10$yTsXTotLqokiPeRNUXQ0A.bYDqH6zkbAc23LWcb//H/KM4rfwfocm', '0715070145', 'customer', '2025-06-23 01:35:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `completed_orders`
--
ALTER TABLE `completed_orders`
  ADD PRIMARY KEY (`completed_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completed_orders`
--
ALTER TABLE `completed_orders`
  MODIFY `completed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `completed_orders`
--
ALTER TABLE `completed_orders`
  ADD CONSTRAINT `completed_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
