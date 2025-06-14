-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 12:41 AM
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
-- Database: `cosmetics_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `address`, `phone`, `created_at`) VALUES
(16, 'Kevin NDAYIZEYE', 'ndayizeyekevin6@gmail.com', 'KK 17 Ave, Kigali', '0786591604', '2025-02-05 19:52:32'),
(17, 'BIZIMANA King Sharon', 'ksharon@gmail.com', 'KG 13 Rd, Kigali', '0791099968', '2025-02-05 20:16:43'),
(18, 'MUGABO Derrick', 'mugabo@gmail.com', 'KG 15 Rd, Kigali', '0788487151', '2025-02-06 01:21:10'),
(19, 'NTWALI Joshua', 'josh.ntwali@gmail.com', 'KK 17 Ave, Kigali', '0784025490', '2025-02-06 15:21:03'),
(20, 'Kevin NDAYIZEYE', 'mayikissyou2twice@gmail.com', 'KK 17 Ave', '57645', '2025-02-06 21:03:09'),
(21, 'MUKIZA Bertrand', 'mukiza@yahoo.com', 'Nyamirambo', '654', '2025-02-06 21:05:12'),
(22, 'MUGISHA Dan', 'mugisha@icloud.com', 'Nyagatare', '123', '2025-02-06 21:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `o_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_items` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`o_id`, `customer_id`, `total_items`, `total_price`, `order_date`) VALUES
(21, 16, 5, 10400.00, '2025-02-05 20:57:58'),
(22, 18, 2, 23900.00, '2025-02-06 01:21:10'),
(23, 19, 3, 25700.00, '2025-02-06 15:21:03'),
(24, 20, 9, 25600.00, '2025-02-06 21:03:09'),
(25, 21, 1, 1800.00, '2025-02-06 21:05:12'),
(26, 22, 1, 1800.00, '2025-02-06 21:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `pricee` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `pricee`, `quantity`, `total_price`) VALUES
(13, 21, 6, 1800.00, 3, 5400.00),
(14, 21, 5, 2500.00, 2, 5000.00),
(15, 22, 7, 2400.00, 1, 2400.00),
(16, 22, 8, 21500.00, 1, 21500.00),
(17, 23, 7, 2400.00, 1, 2400.00),
(18, 23, 8, 21500.00, 1, 21500.00),
(19, 23, 6, 1800.00, 1, 1800.00),
(20, 24, 9, 7800.00, 1, 7800.00),
(21, 24, 6, 1800.00, 3, 5400.00),
(22, 24, 7, 2400.00, 1, 2400.00),
(23, 24, 5, 2500.00, 4, 10000.00),
(24, 25, 6, 1800.00, 1, 1800.00),
(25, 26, 6, 1800.00, 1, 1800.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image`) VALUES
(5, 'Nivea Soap', 'Skin care', '', 2500.00, 'uploads/image1.webp'),
(6, 'Soap 1', 'Skin care', '', 1800.00, 'uploads/image6.webp'),
(7, 'Dettol Soap', 'Skin care', 'Dettol soap is primarily designed as a disinfectant for cleaning hands and surfaces. It contains antiseptic ingredients that help kill germs and bacteria. While it is effective for maintaining general hygiene, The facial skin is more delicate and sensitive compared to the skin on other parts of your body.', 2400.00, 'uploads/image5.jpg'),
(8, 'Besque', 'Skin care', '', 21500.00, 'uploads/Besque.webp'),
(9, 'Naturium', 'Skin care', '', 7800.00, 'uploads/Naturium.webp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`o_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
