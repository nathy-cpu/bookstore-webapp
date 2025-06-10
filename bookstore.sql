-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2025 at 08:19 PM
-- Server version: 8.4.5
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `isbn` varchar(13) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `price`, `stock`, `isbn`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'A story of decadence and excess.', 550.00, 47, NULL, 1, '2025-06-10 10:09:01', '2025-06-10 13:47:35'),
(2, 'Dune', 'Frank Herbert', 'A science fiction masterpiece about a desert planet.', 715.00, 27, NULL, 3, '2025-06-10 10:09:01', '2025-06-10 13:46:51'),
(3, 'The Da Vinci Code', 'Dan Brown', 'A mystery thriller about hidden symbols and secret societies.', 660.00, 39, NULL, 4, '2025-06-10 10:09:01', '2025-06-10 11:10:53'),
(4, 'hvdhfgv', 'dhyefve', 'aedoyebea', 322.00, 0, '8', NULL, '2025-06-10 13:59:18', '2025-06-10 13:59:18'),
(5, 'fasdihbfosda', 'asoibds', 'sadoigihbibdasi', 1000.00, 12, '8', NULL, '2025-06-10 14:00:10', '2025-06-10 14:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Fiction', 'Fictional literature including novels and short stories', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(2, 'Non-Fiction', 'Factual books including biographies, history, and self-help', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(3, 'Science Fiction', 'Books about futuristic technology, space exploration, and alternate realities', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(4, 'Mystery', 'Detective stories, crime fiction, and suspense novels', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(5, 'Romance', 'Love stories and romantic literature', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(6, 'Technology', 'Books about computers, programming, and technology', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(7, 'Business', 'Books about entrepreneurship, management, and finance', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(8, 'Children', 'Books for young readers', '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(9, 'Education', NULL, '2025-06-10 13:52:59', '2025-06-10 13:52:59'),
(10, 'Spotr123123123123', NULL, '2025-06-10 13:53:39', '2025-06-10 13:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1925.00, 'pending', '2025-06-10 11:10:53', '2025-06-10 11:10:53'),
(2, 3, 550.00, 'pending', '2025-06-10 12:43:01', '2025-06-10 12:43:01'),
(3, 3, 1430.00, 'pending', '2025-06-10 13:46:51', '2025-06-10 13:46:51'),
(4, 3, 550.00, 'pending', '2025-06-10 13:47:35', '2025-06-10 13:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_time` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `book_id`, `quantity`, `price_at_time`, `created_at`) VALUES
(1, 1, 1, 550.00, '2025-06-10 11:10:53'),
(1, 2, 1, 715.00, '2025-06-10 11:10:53'),
(1, 3, 1, 660.00, '2025-06-10 11:10:53'),
(2, 1, 1, 550.00, '2025-06-10 12:43:01'),
(3, 2, 2, 715.00, '2025-06-10 13:46:51'),
(4, 1, 1, 550.00, '2025-06-10 13:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `data` text,
  `last_accessed` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `first_name`, `last_name`, `phone_number`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'admin@example.com', '$2y$10$yKaSF46lUrhFhSTsAotjQ.NL/WGHRAJ020YjN3Ohf2xMVWgWNHPOS', 'Admin', 'User', NULL, 1, '2025-06-10 10:09:01', '2025-06-10 10:09:01'),
(2, 'jane@example.com', '$2y$10$k92Dq6XlBNp2ZsIYYBbl2e4pHLaQssO7r/sMLjWsJcy9IOuEnkgPa', 'jane', 'doe', '', 0, '2025-06-10 11:04:58', '2025-06-10 11:04:58'),
(3, 'naomizerfu@gmail.com', '$2y$10$Hw1dPcwAic3mwYBDpjkHAOnJsuJ2NpXT2YepAo0VWHshWwYi5CcWK', 'Yeab', 'Memar', '0991065050', 0, '2025-06-10 12:42:21', '2025-06-10 12:42:21'),
(4, 'asfno@gmial.com', '$2y$10$InXP4l.TNJ/YW03iJDx5R..OjQx9p/hLUHSLuxuX9rQh3oE0Of/Ki', 'Naomi', 'Zerfu', '637849560', 0, '2025-06-10 13:57:49', '2025-06-10 13:57:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`user_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
