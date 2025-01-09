-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 08:07 PM
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
-- Database: `pos_system_konter_hp`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `CART_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`CART_ID`, `USER_ID`, `QUANTITY`) VALUES
(39, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `CART_ITEM_ID` int(11) NOT NULL,
  `CART_ID` int(11) DEFAULT NULL,
  `SOLD_PRICE` decimal(10,2) DEFAULT NULL,
  `IMEI` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CATEGORY_ID` int(11) NOT NULL,
  `CATEGORY_NAME` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CATEGORY_ID`, `CATEGORY_NAME`) VALUES
(1, 'NEW'),
(2, 'SEKEN'),
(4, 'BNIB'),
(11, 'sa');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `PRODUCT_ID` int(11) NOT NULL,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `COLOR` varchar(20) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRODUCT_NAME` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`PRODUCT_ID`, `CATEGORY_ID`, `COLOR`, `QUANTITY`, `PRODUCT_NAME`) VALUES
(1, 1, 'Midnight Grey', 4, 'Realme X2 Pro 12/256'),
(2, 2, 'Tropical Green', 1, 'Redmi Note 9 Pro 6/64'),
(3, 2, 'black', 3, 'iphone 11'),
(4, 4, 'Ash grey', 5, 'Iphone 16 Pro Max'),
(9, 11, 'ads', 2, 'ads'),
(10, 2, 'ads', 2, 'rweq');

-- --------------------------------------------------------

--
-- Table structure for table `product_unit`
--

CREATE TABLE `product_unit` (
  `IMEI` varchar(15) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `SUPPLIER_ID` int(11) DEFAULT NULL,
  `BUY_PRICE` decimal(10,2) DEFAULT NULL,
  `SRP` decimal(10,2) DEFAULT NULL,
  `PRODUCT_UNIT_DESCRIPTION` text DEFAULT NULL,
  `DATE_STOCK_IN` datetime DEFAULT NULL,
  `added_to_cart` tinyint(1) DEFAULT 0,
  `SOLD` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_unit`
--

INSERT INTO `product_unit` (`IMEI`, `PRODUCT_ID`, `SUPPLIER_ID`, `BUY_PRICE`, `SRP`, `PRODUCT_UNIT_DESCRIPTION`, `DATE_STOCK_IN`, `added_to_cart`, `SOLD`) VALUES
('0987654334567', 4, 3, 1000000.00, 5000000.00, 'Brand New In Box', '2024-12-06 15:21:33', 0, 1),
('10987', 2, 2, 121.00, 32321.00, 'asdads', '2025-01-09 14:55:22', 0, 1),
('111222', 10, 1, 1000000.00, 1200000.00, '2', '2025-01-09 23:53:17', 0, 1),
('121211111', 4, 1, 1000000.00, 1100000.00, 'dsa', '2025-01-09 23:54:14', 0, 1),
('12121112', 1, 3, 1000000.00, 1200000.00, 's', '2025-01-09 23:36:57', 0, 1),
('123123123', 3, 2, 90000.00, 100000.00, 'as', '2024-12-19 00:31:30', 0, 1),
('123456789012340', 4, 1, 16000000.00, 17000000.00, 'kmkm;lkl;', '2024-12-06 15:38:07', 0, 1),
('123456789012346', 1, 2, 12.00, 213.00, 'GARANSI REALME INDON', '2024-11-08 03:17:00', 0, 1),
('123456789012348', 3, 3, 1000000.00, 1100000.00, 'tes', '2024-12-05 15:23:40', 0, 1),
('123456789012349', 3, 1, 2300000.00, 2400000.00, 'harusnya nambah nih quantitynya', '2024-12-05 15:26:51', 0, 1),
('1234567896334', 4, 4, 3000000.00, 2300000.00, 'Brand New In Box', '2024-12-06 15:20:47', 0, 1),
('124', 1, 1, 1000000.00, 1100000.00, 'as', '2024-12-19 00:16:36', 0, 1),
('2131234', 1, 2, 2000000.00, 2200000.00, 'wer', '2025-01-09 23:34:04', 0, 1),
('21313123123', 4, 4, 900000.00, 1000000.00, 'asdsad', '2025-01-10 00:04:07', 0, 1),
('3423234', 2, 2, 1290000.00, 1400000.00, 'adsdas', '2025-01-10 00:01:41', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SUPPLIER_ID` int(11) NOT NULL,
  `SUPPLIER_NAME` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `PHONE` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SUPPLIER_ID`, `SUPPLIER_NAME`, `EMAIL`, `PHONE`) VALUES
(1, 'Koh Andre', 'andre@gmail.com', '087623134542'),
(2, 'Fendy Karanganyar', 'fendy@gmail.com', '087912348657'),
(3, 'Ronaldo Kwateh', 'kwateh@gmail.com', '098123712123'),
(4, 'Koh Cun', 'cun@gmail.com', '0876543452'),
(5, 'Ega Anggara', 'ega@gmail.com', '098765445678');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TRANSACTIONS_ID` int(11) NOT NULL,
  `CART_ID` int(11) DEFAULT NULL,
  `TRANSACTION_STATUS` varchar(20) DEFAULT NULL,
  `SHIPPING_ADDRESS` varchar(100) DEFAULT NULL,
  `TOTAL_UNIT` int(11) DEFAULT NULL,
  `GRAND_TOTAL` decimal(10,2) DEFAULT NULL,
  `BUYER_NAME` varchar(50) DEFAULT NULL,
  `CREATED_AT` datetime DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TRANSACTIONS_ID`, `CART_ID`, `TRANSACTION_STATUS`, `SHIPPING_ADDRESS`, `TOTAL_UNIT`, `GRAND_TOTAL`, `BUYER_NAME`, `CREATED_AT`, `USER_ID`) VALUES
(1, 1, 'DONE', 'Jl. Sidomukti Utara 2, Surakarta', 2, 2700000.00, 'JOKO', '2024-11-08 03:17:00', NULL),
(2, 2, 'ON THE WAY', 'Jl. Sidomukti Utara 4, Surakarta', 1, 126000.00, 'BUDI', '2024-11-08 03:17:00', NULL),
(3, 4, 'Completed', 'Default Address', 1, 1000000.00, 'Vicky Galih', '2024-12-19 18:43:22', NULL),
(4, 5, 'Completed', 'jl utara', 2, 3400000.00, 'dominic', '2024-12-20 22:35:07', NULL),
(5, NULL, 'Pending', '123 Example St, City', 2, 2400000.00, 'John Doe', '2024-12-21 00:01:50', 4),
(6, NULL, 'Pending', '123 Example St, City', 1, 2300000.00, 'John Doe', '2024-12-21 00:09:30', 4),
(7, 5, 'Pending', '123 Example St, City', 1, 1100000.00, 'John Doe', '2024-12-21 00:14:39', 4),
(8, 7, 'Pending', '123 Example St, City', 1, 2300000.00, 'John Doe', '2024-12-31 08:06:50', 4),
(9, NULL, 'Pending', '123 Example St, City', 2, 2400000.00, 'John Doe', '2024-12-31 08:22:01', 4),
(10, NULL, 'Pending', '123 Example St, City', 2, 2300000.00, 'John Doe', '2024-12-31 08:24:26', 4),
(11, 9, 'Pending', '123 Example St, City', 2, 3600000.00, 'John Doe', '2024-12-31 08:29:11', 4),
(12, 10, 'Pending', '123 Example St, City', 2, 18100000.00, 'John Doe', '2024-12-31 11:46:19', 4),
(13, 13, 'Pending', '123 Example St, City', 2, 4000000.00, 'John Doe', '2025-01-01 19:35:35', 4),
(14, 13, 'Pending', '123 Example St, City', 1, 2000000.00, 'John Doe', '2025-01-01 20:21:43', 4),
(15, 13, 'Pending', '123 Example St, City', 1, 2000000.00, 'John Doe', '2025-01-01 20:31:09', 4),
(16, 20, 'Pending', '123 Example St, City', 1, 2000000.00, 'John Doe', '2025-01-03 14:01:59', 4),
(17, 22, 'Pending', '123 Example St, City', 1, 2400.00, 'John Doe', '2025-01-09 16:57:35', 4),
(18, 31, 'Pending', '123 Example St, City', 1, 324.00, 'John Doe', '2025-01-09 20:22:34', 1),
(19, 31, 'Pending', '123 Example St, City', 1, 21.00, 'John Doe', '2025-01-09 20:23:35', 1),
(20, 31, 'Pending', '123 Example St, City', 1, 12.00, 'John Doe', '2025-01-09 20:28:28', 1),
(21, 32, 'Pending', '123 Example St, City', 1, 213123.00, 'John Doe', '2025-01-09 20:28:49', 4),
(22, 32, 'done', 'in store', 2, 22000000.00, 'Shin Ryujin', '2025-01-09 23:31:18', 4),
(23, 32, 'done', 'in store', 1, 4000000.00, 'Anonim', '2025-01-09 23:34:27', 4),
(24, 32, 'done', 'in store', 1, 3000000.00, 'Anonim', '2025-01-09 23:37:13', 4),
(25, 33, 'done', 'in store', 1, 1300000.00, 'Shin Ryujin', '2025-01-09 23:53:43', 4),
(26, 34, 'done', 'in store', 1, 1100000.00, 'sA', '2025-01-10 00:01:03', 4),
(27, 35, 'done', 'in store', 2, 3400000.00, 'ahyeon', '2025-01-10 00:04:38', 4);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `TRANSACTION_ITEM_ID` int(11) NOT NULL,
  `CART_ID` int(11) DEFAULT NULL,
  `SOLD_PRICE` decimal(10,2) DEFAULT NULL,
  `IMEI` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`TRANSACTION_ITEM_ID`, `CART_ID`, `SOLD_PRICE`, `IMEI`) VALUES
(1, 7, 1100000.00, '123'),
(2, 7, 1300000.00, '123456789012345'),
(3, 8, 1100000.00, '124'),
(4, 8, 1200000.00, '123456789012347'),
(5, 9, 1200000.00, '123456789012347'),
(6, 9, 2400000.00, '123456789012349'),
(7, 10, 1100000.00, '124'),
(8, 10, 17000000.00, '123456789012340'),
(9, 13, 2000000.00, '123456789012345'),
(10, 13, 2000000.00, '123456789012346'),
(11, 13, 2000000.00, '124'),
(12, 13, 2000000.00, '123456789012340'),
(13, 20, 2000000.00, '123456789012347'),
(14, 22, 2400.00, '123456789012346'),
(15, 31, 324.00, '124'),
(16, 31, 21.00, '10987'),
(17, 31, 12.00, '123123123'),
(18, 32, 213123.00, '123456789012349'),
(19, 32, 2000000.00, '123456789012348'),
(20, 32, 20000000.00, '0987654334567'),
(21, 32, 4000000.00, '2131234'),
(22, 32, 3000000.00, '12121112'),
(23, 33, 1300000.00, '111222'),
(24, 34, 1100000.00, '121211111'),
(25, 35, 1300000.00, '21313123123'),
(26, 35, 2100000.00, '3423234');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `USER_PASSWORD` varchar(255) DEFAULT NULL,
  `PHONE` varchar(15) DEFAULT NULL,
  `USER_ROLE` varchar(20) DEFAULT NULL,
  `CREATED_AT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `USER_PASSWORD`, `PHONE`, `USER_ROLE`, `CREATED_AT`) VALUES
(1, 'Tabita', 'Aryas', 'tabita2302@gmail.com', 'karyawan', '089731237924', 'KARYAWAN', '2024-11-08 03:17:00'),
(2, 'Arumi', 'Mutiara', 'mika2502@gmail.com', 'manajer', '089731237924', 'MANAJER', '2024-11-08 03:17:00'),
(3, 'Milka', 'Sekar', 'selka2902@gmail.com', 'owner', '089731237924', 'OWNER', '2024-11-08 03:17:00'),
(4, 'Vicky', 'Galih', 'vickygalih@gmail.com', 'admin', '089731237924', 'ADMIN', '2024-11-08 03:17:00'),
(6, 'Kim', 'Maguire', 'kmunited@gmail.com', 'karyawan', '089712437685', 'KARYAWAN', '2025-01-09 20:04:27'),
(8, 'Kim', 'Maguire2', 'kmunited2@gmail.com', 'karyawan', '089712437685', 'KARYAWAN', '2025-01-09 20:07:01'),
(9, 'Kiri', 'Kun', 'kmunited3@gmail.com', 'manajer', '087612349801', 'KARYAWAN', '2025-01-09 20:07:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`CART_ID`),
  ADD KEY `FK_CARTSUSERS` (`USER_ID`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`CART_ITEM_ID`),
  ADD KEY `FK_CARTITEMSPRODUCTUNIT` (`IMEI`),
  ADD KEY `fk_cart` (`CART_ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CATEGORY_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `FK_PRODUCTSCATEGORIES` (`CATEGORY_ID`);

--
-- Indexes for table `product_unit`
--
ALTER TABLE `product_unit`
  ADD PRIMARY KEY (`IMEI`),
  ADD KEY `FK_PRODUCTUNITPRODUCTS` (`PRODUCT_ID`),
  ADD KEY `FK_PRODUCTUNITSUPPLIER` (`SUPPLIER_ID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SUPPLIER_ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TRANSACTIONS_ID`),
  ADD KEY `FK_TRANSACTIONSCARTS` (`CART_ID`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`TRANSACTION_ITEM_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `CART_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `CART_ITEM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SUPPLIER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TRANSACTIONS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `TRANSACTION_ITEM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `FK_CARTSUSERS` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `FK_CARTITEMSCARTS` FOREIGN KEY (`CART_ID`) REFERENCES `carts` (`CART_ID`),
  ADD CONSTRAINT `FK_CARTITEMSPRODUCTUNIT` FOREIGN KEY (`IMEI`) REFERENCES `product_unit` (`IMEI`),
  ADD CONSTRAINT `fk_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`CART_ID`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_PRODUCTSCATEGORIES` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `categories` (`CATEGORY_ID`);

--
-- Constraints for table `product_unit`
--
ALTER TABLE `product_unit`
  ADD CONSTRAINT `FK_PRODUCTUNITPRODUCTS` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `products` (`PRODUCT_ID`),
  ADD CONSTRAINT `FK_PRODUCTUNITSUPPLIER` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `suppliers` (`SUPPLIER_ID`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `FK_TRANSACTIONSCARTS` FOREIGN KEY (`CART_ID`) REFERENCES `carts` (`CART_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
