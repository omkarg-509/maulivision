-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 03, 2025 at 08:20 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u367009900_maulivision`
--

-- --------------------------------------------------------

--
-- Table structure for table `finance_entries`
--

CREATE TABLE `finance_entries` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `type` enum('income','expense','borrow','repay') NOT NULL,
  `method` enum('cash','online') NOT NULL DEFAULT 'cash',
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `entry_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finance_entries`
--

INSERT INTO `finance_entries` (`id`, `admin_id`, `type`, `method`, `amount`, `note`, `entry_date`, `created_at`) VALUES
(38, 1, 'expense', 'cash', 70.00, 'jevan', '2025-11-01', '2025-11-02 06:16:02'),
(39, 1, 'expense', 'cash', 190.00, 'laundry cloth', '2025-11-01', '2025-11-02 06:16:45'),
(40, 1, 'borrow', 'cash', 200.00, 'vb', '2025-11-02', '2025-11-03 07:34:51'),
(41, 1, 'expense', 'cash', 100.00, 'night food', '2025-11-02', '2025-11-03 07:35:23'),
(42, 1, 'income', 'cash', 20.00, 'snaks', '2025-11-03', '2025-11-03 07:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `lms_customers`
--

CREATE TABLE `lms_customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(191) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_orders`
--

CREATE TABLE `lms_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mcms_customers`
--

CREATE TABLE `mcms_customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `vid` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `in_time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `staff` varchar(100) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mcms_customers`
--

INSERT INTO `mcms_customers` (`id`, `vid`, `name`, `mobile`, `in_time`, `amount`, `staff`, `payment_method`, `created_at`) VALUES
(11, 3, 'omkar vivek gaikwad', '7448224412', '13:11:00', 1000.00, 'tr', 'c', '2025-09-13 07:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `mcms_staff`
--

CREATE TABLE `mcms_staff` (
  `id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `full_name` varchar(229) NOT NULL,
  `number` decimal(10,0) NOT NULL,
  `address` varchar(299) NOT NULL,
  `status` varchar(99) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `business_name` varchar(229) NOT NULL,
  `business_number` decimal(10,0) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`id`, `name`, `email`, `mobile`, `business_name`, `business_number`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Omkar Vivek Gaikwad', 'maulivision1@gmail.com', '7448224412', 'Mauli Vision', 7448224412, '$2y$10$//g2naqV/YPi42t8OqBA7.KvyPlLwFkqOpyxKgEuHRBM2TqxOGJIO', '2025-08-24 11:32:49', '2025-11-03 08:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `tiffin_entries`
--

CREATE TABLE `tiffin_entries` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `tiffin_time` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiffin_entries`
--

INSERT INTO `tiffin_entries` (`id`, `admin_id`, `entry_date`, `tiffin_time`, `quantity`, `rate`, `paid`, `created_at`) VALUES
(60, 1, '2025-11-01', 'lunch', 1, 70.00, 1, '2025-11-02 06:17:33'),
(61, 1, '2025-11-02', 'night', 1, 100.00, 1, '2025-11-03 07:35:55'),
(62, 1, '2025-11-03', 'morning', 1, 20.00, 1, '2025-11-03 07:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(229) NOT NULL,
  `username` varchar(299) NOT NULL,
  `password` varchar(299) NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `business_role` varchar(120) DEFAULT NULL,
  `business_number` varchar(50) DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `admin_id`, `full_name`, `phone`, `email`, `username`, `password`, `business_name`, `business_role`, `business_number`, `business_address`, `status`, `created_at`) VALUES
(3, 1, 'rohit  kamble', '8055221441', 'rohit509@gmail.com', 'rohit509', 'rohit509', 'sandya sppa', 'massage center', '8055221441', 'ssd', 'inactive', '2025-09-13 07:29:14'),
(4, 1, 'pawan lakurvale', '9623665336', 'pawan123@gmail.com', '9623665336', '9623665336', 'Pawan Drycleaners', 'laundry', '9623665336', 'Shani Mandir Road, Bharati University, Ambegaon B., Pune-46', 'inactive', '2025-09-13 08:19:05'),
(5, 1, 'omkar vivek gaikwad', '7448224412', 'omkarg509@gmail.com', 'maulivision1@gmail.com', '$2y$10$5bcvRvoy0dOMfHo1DYTaVOe3BwgPOYi5lObM643X7GAnoI51cBegW', 'Mauli Vision', 'mauli vision', '7448224412', '', 'active', '2025-11-03 09:05:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `finance_entries`
--
ALTER TABLE `finance_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_date` (`admin_id`,`entry_date`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_method` (`method`);

--
-- Indexes for table `lms_customers`
--
ALTER TABLE `lms_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lms_orders`
--
ALTER TABLE `lms_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cust` (`customer_id`);

--
-- Indexes for table `mcms_customers`
--
ALTER TABLE `mcms_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vid_created` (`vid`,`created_at`);

--
-- Indexes for table `mcms_staff`
--
ALTER TABLE `mcms_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `tiffin_entries`
--
ALTER TABLE `tiffin_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_date` (`admin_id`,`entry_date`),
  ADD KEY `idx_paid` (`paid`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admin_phone` (`admin_id`,`phone`),
  ADD KEY `idx_admin_status` (`admin_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `finance_entries`
--
ALTER TABLE `finance_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `lms_customers`
--
ALTER TABLE `lms_customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lms_orders`
--
ALTER TABLE `lms_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mcms_customers`
--
ALTER TABLE `mcms_customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mcms_staff`
--
ALTER TABLE `mcms_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tiffin_entries`
--
ALTER TABLE `tiffin_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `finance_entries`
--
ALTER TABLE `finance_entries`
  ADD CONSTRAINT `fk_finance_admin` FOREIGN KEY (`admin_id`) REFERENCES `superadmin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lms_orders`
--
ALTER TABLE `lms_orders`
  ADD CONSTRAINT `fk_laundry_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `lms_customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tiffin_entries`
--
ALTER TABLE `tiffin_entries`
  ADD CONSTRAINT `fk_tiffin_admin` FOREIGN KEY (`admin_id`) REFERENCES `superadmin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendors_admin` FOREIGN KEY (`admin_id`) REFERENCES `superadmin` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
