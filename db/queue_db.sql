-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 04:00 PM
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
-- Database: `queue_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_kpi_summary`
--

CREATE TABLE `daily_kpi_summary` (
  `summary_id` int(11) NOT NULL,
  `summary_date` date NOT NULL,
  `total_queues` int(11) DEFAULT 0,
  `served_count` int(11) DEFAULT 0,
  `voided_count` int(11) DEFAULT 0,
  `avg_wait_time` decimal(8,2) DEFAULT 0.00,
  `avg_service_time` decimal(8,2) DEFAULT 0.00,
  `max_wait_time` int(11) DEFAULT 0,
  `max_service_time` int(11) DEFAULT 0,
  `total_transaction_volume` decimal(12,2) DEFAULT 0.00,
  `avg_transaction_amount` decimal(8,2) DEFAULT 0.00,
  `service_efficiency_rate` decimal(5,2) DEFAULT 0.00,
  `date_generated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`history_id`, `student_id`, `transaction_id`, `status`, `date`) VALUES
(1, 2, 1, 'completed', '2025-11-09 16:28:36'),
(2, 3, 2, 'completed', '2025-11-09 16:28:50'),
(3, 1, 3, 'completed', '2025-11-09 16:37:32'),
(4, 1, 4, 'completed', '2025-11-09 20:51:37'),
(5, 2, 5, 'completed', '2025-11-09 21:05:51'),
(6, 1, 6, 'completed', '2025-11-11 21:21:01'),
(7, 1, 10, 'completed', '2025-11-13 00:23:45'),
(8, 2, 13, 'completed', '2025-11-13 00:30:51'),
(9, 3, 14, 'completed', '2025-11-13 17:23:36'),
(10, 1, 15, 'completed', '2025-11-13 18:20:14'),
(11, 3, 16, 'completed', '2025-11-15 00:46:43'),
(12, 3, 17, 'completed', '2025-11-15 00:49:22'),
(13, 3, 18, 'completed', '2025-11-15 00:58:38'),
(14, 2, 19, 'completed', '2025-11-15 01:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queue_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `status` enum('waiting','serving','served','voided') DEFAULT 'waiting',
  `time_in` datetime DEFAULT current_timestamp(),
  `time_out` int(11) DEFAULT NULL,
  `timer_start` int(11) DEFAULT NULL,
  `estimated_wait_time` int(11) DEFAULT NULL,
  `payment_for` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `handled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queue_id`, `student_id`, `queue_number`, `status`, `time_in`, `time_out`, `timer_start`, `estimated_wait_time`, `payment_for`, `amount`, `handled_by`) VALUES
(1, 1, 1, 'voided', '2025-11-09 16:09:13', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(2, 2, 2, 'served', '2025-11-09 16:26:10', 2147483647, 2147483647, NULL, '', 0.00, 2),
(3, 3, 3, 'served', '2025-11-09 16:27:30', 2147483647, 2147483647, NULL, '', 0.00, 2),
(4, 1, 4, 'served', '2025-11-09 16:36:36', 2147483647, 2147483647, NULL, '', 0.00, 2),
(5, 1, 5, 'voided', '2025-11-09 20:39:34', 2147483647, 2147483647, NULL, 'Tuition Fee', 0.00, NULL),
(6, 1, 6, 'served', '2025-11-09 20:51:15', 2147483647, 2147483647, NULL, 'Tuition Fee', 0.00, 1),
(7, 2, 7, 'served', '2025-11-09 21:05:23', 2147483647, 2147483647, NULL, 'Tuition Fee', 0.00, 2),
(8, 3, 8, 'voided', '2025-11-09 21:11:32', 2147483647, 2147483647, NULL, 'Tuition Fee', 0.00, NULL),
(9, 1, 1, 'served', '2025-11-11 21:19:12', 2147483647, 2147483647, NULL, '', 0.00, 2),
(10, 1, 1, 'voided', '2025-11-12 23:34:55', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(11, 1, 1, 'voided', '2025-11-13 00:02:39', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(12, 2, 2, 'voided', '2025-11-13 00:03:31', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(13, 3, 3, 'voided', '2025-11-13 00:06:51', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(14, 3, 4, 'voided', '2025-11-13 00:07:26', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(15, 3, 5, 'voided', '2025-11-13 00:08:50', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(16, 2, 6, 'voided', '2025-11-13 00:08:59', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(17, 3, 7, 'voided', '2025-11-13 00:20:36', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(18, 3, 8, 'voided', '2025-11-13 00:22:24', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(19, 1, 9, 'served', '2025-11-13 00:23:00', 2147483647, 2147483647, NULL, '', 0.00, 2),
(22, 2, 10, 'served', '2025-11-13 00:28:39', 2147483647, 2147483647, NULL, '', 0.00, 2),
(23, 2, 11, 'voided', '2025-11-13 00:33:44', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(24, 2, 12, 'voided', '2025-11-13 00:35:04', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(25, 3, 13, 'voided', '2025-11-13 00:35:37', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(26, 3, 14, 'voided', '2025-11-13 00:36:36', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(27, 2, 15, 'voided', '2025-11-13 00:37:14', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(28, 1, 16, 'voided', '2025-11-13 16:45:51', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(29, 1, 17, 'voided', '2025-11-13 16:47:11', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(30, 2, 18, 'voided', '2025-11-13 16:56:50', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(31, 2, 19, 'voided', '2025-11-13 16:58:59', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(32, 3, 20, 'voided', '2025-11-13 17:01:26', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(33, 3, 21, 'served', '2025-11-13 17:23:22', 2147483647, 2147483647, NULL, '', 0.00, 2),
(34, 3, 22, 'voided', '2025-11-13 17:25:11', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(35, 2, 23, 'voided', '2025-11-13 17:25:34', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(36, 2, 24, 'voided', '2025-11-13 17:26:53', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(37, 3, 25, 'voided', '2025-11-13 17:27:19', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(38, 2, 26, '', '2025-11-13 18:12:03', 2147483647, 2147483647, NULL, 'tuition', 0.00, NULL),
(39, 1, 27, '', '2025-11-13 18:12:26', 2147483647, 2147483647, NULL, 'tuition', 0.00, NULL),
(40, 1, 28, '', '2025-11-13 18:15:16', 2147483647, 2147483647, NULL, 'tuition', 0.00, NULL),
(41, 2, 29, 'voided', '2025-11-13 18:15:37', 2147483647, 2147483647, NULL, 'tuition', 0.00, NULL),
(42, 1, 30, 'served', '2025-11-13 18:19:19', 2147483647, 2147483647, NULL, '', 0.00, 2),
(43, 3, 31, 'voided', '2025-11-13 18:19:31', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(44, 3, 1, 'voided', '2025-11-15 00:32:50', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(45, 3, 2, 'voided', '2025-11-15 00:39:51', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(46, 3, 3, 'voided', '2025-11-15 00:42:52', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(47, 3, 4, 'voided', '2025-11-15 00:43:48', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(48, 3, 5, 'served', '2025-11-15 00:46:24', 2147483647, 2147483647, NULL, '', 0.00, 2),
(49, 3, 6, 'served', '2025-11-15 00:49:00', 2147483647, 2147483647, NULL, '', 0.00, 2),
(50, 3, 7, 'served', '2025-11-15 00:58:23', 2147483647, 2147483647, NULL, '', 0.00, 2),
(51, 2, 8, 'voided', '2025-11-15 01:07:05', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(52, 2, 9, 'served', '2025-11-15 01:19:59', 2147483647, 2147483647, NULL, '', 0.00, 2),
(53, 2, 10, 'voided', '2025-11-15 01:22:50', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(54, 2, 11, 'voided', '2025-11-15 01:25:39', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(55, 2, 12, 'voided', '2025-11-15 01:28:03', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(56, 2, 13, 'voided', '2025-11-15 01:31:28', 2147483647, 2147483647, NULL, '', 0.00, NULL),
(57, 2, 14, 'served', '2025-11-15 15:32:38', 1763192241, NULL, NULL, '', 0.00, NULL),
(58, 2, 15, 'voided', '2025-11-15 15:33:06', NULL, NULL, NULL, '', 0.00, NULL),
(59, 2, 16, 'voided', '2025-11-15 15:55:44', NULL, NULL, NULL, '', 0.00, NULL),
(60, 2, 17, 'voided', '2025-11-15 15:55:58', NULL, NULL, NULL, '', 0.00, NULL),
(61, 2, 18, 'voided', '2025-11-15 16:02:02', NULL, NULL, NULL, '', 0.00, NULL),
(62, 2, 19, 'voided', '2025-11-15 16:02:58', NULL, NULL, NULL, '', 0.00, NULL),
(63, 2, 20, 'served', '2025-11-15 16:03:07', 2147483647, NULL, NULL, '', 0.00, NULL),
(64, 1, 21, 'served', '2025-11-15 16:11:39', 2147483647, NULL, NULL, '', 0.00, NULL),
(65, 1, 22, 'served', '2025-11-15 16:45:48', 2147483647, NULL, NULL, '', 0.00, NULL),
(66, 2, 23, 'voided', '2025-11-15 16:46:09', NULL, NULL, NULL, '', 0.00, NULL),
(67, 1, 1, 'served', '2025-11-20 15:06:26', 2147483647, NULL, NULL, '', 0.00, NULL),
(68, 1, 2, 'voided', '2025-11-20 15:27:22', NULL, NULL, NULL, '', 0.00, NULL),
(69, 1, 1, 'served', '2025-11-26 12:16:53', 2147483647, NULL, NULL, 'Tuition Fee', 42334.00, NULL),
(70, 1, 2, 'served', '2025-11-26 12:17:27', 2147483647, NULL, NULL, 'Tuition Fee', 42334.00, NULL),
(71, 1, 3, 'served', '2025-11-26 12:32:04', 2147483647, NULL, NULL, 'Tuition Fee', 42334.00, NULL),
(72, 1, 1, 'voided', '2025-11-30 10:28:19', NULL, NULL, NULL, 'Tuition Fee', 432121.00, NULL),
(73, 1, 1, 'served', '2025-12-02 13:22:20', 2147483647, NULL, NULL, 'Tuition Fee', 123123.00, NULL),
(74, 2, 2, 'served', '2025-12-02 13:25:42', 2147483647, NULL, NULL, 'Transcript', 12312.00, NULL),
(75, 1, 3, 'served', '2025-12-02 13:42:16', 2147483647, NULL, NULL, 'Tuition Fee', 1232.00, NULL),
(76, 1, 4, 'voided', '2025-12-02 13:46:08', NULL, NULL, NULL, 'Tuition Fee', 1232.00, NULL),
(77, 1, 5, 'served', '2025-12-02 13:55:09', 2147483647, NULL, NULL, 'Tuition Fee', 23423.00, NULL),
(78, 2, 6, 'served', '2025-12-02 13:56:49', 2147483647, NULL, NULL, 'Tuition Fee', 123123.00, NULL),
(79, 1, 7, 'served', '2025-12-02 13:59:16', 2147483647, NULL, NULL, 'Tuition Fee', 23423.00, NULL),
(80, 1, 8, 'served', '2025-12-02 18:07:12', 2147483647, NULL, NULL, 'Tuition Fee', 12331.00, NULL),
(81, 2, 9, 'served', '2025-12-02 18:15:31', 2147483647, NULL, NULL, 'Tuition Fee', 2434.00, NULL),
(82, 1, 10, 'served', '2025-12-02 18:23:07', 2147483647, NULL, NULL, 'Tuition Fee', 2345.00, NULL),
(83, 1, 11, 'served', '2025-12-02 18:54:07', 2147483647, NULL, NULL, 'Tuition Fee', 2133.00, NULL),
(84, 1, 1, 'served', '2025-12-03 13:07:46', 2147483647, NULL, NULL, 'Tuition Fee', 2345.00, NULL),
(85, 2, 2, 'voided', '2025-12-03 13:08:22', NULL, NULL, NULL, 'Tuition Fee', 23423.00, NULL),
(86, 1, 3, 'voided', '2025-12-03 13:09:47', NULL, NULL, NULL, 'Transcript', 123.00, NULL),
(87, 1, 4, 'served', '2025-12-03 20:14:58', 2147483647, NULL, NULL, 'Tuition Fee', 123123.00, NULL),
(88, 1, 1, 'served', '2025-12-04 09:27:03', 2147483647, NULL, NULL, 'Tuition Fee', 2342.00, NULL),
(89, 1, 2, 'served', '2025-12-04 09:47:28', 2147483647, NULL, NULL, 'Tuition Fee', 1234.00, NULL),
(90, 1, 3, 'voided', '2025-12-04 12:20:00', NULL, NULL, NULL, 'Tuition Fee', 1232.00, NULL),
(91, 2, 4, 'voided', '2025-12-04 13:11:18', NULL, NULL, NULL, 'Tuition Fee', 234234.00, NULL),
(92, 1, 5, 'voided', '2025-12-04 13:11:31', NULL, NULL, NULL, 'Tuition Fee', 2342.00, NULL),
(93, 1, 6, 'voided', '2025-12-04 13:34:17', NULL, NULL, NULL, 'Tuition Fee', 123.00, NULL),
(94, 7, 1, 'served', '2025-12-05 11:33:09', 2147483647, NULL, NULL, 'Tuition Fee', 1243.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_type` text NOT NULL,
  `generated_by` int(11) NOT NULL,
  `date_generated` datetime DEFAULT current_timestamp(),
  `report_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `course`, `year_level`, `email`, `created_at`, `password`) VALUES
(23100001, 'Brent Adrian Kyro L. Alabag', 'BSIT', '3rd Year', 'brentalabag@slc.edu', '2025-11-09 15:15:38', '123'),
(23100002, 'Ardy A. Aquino', 'BSIT', '3rd Year', 'adryaquino@slc.edu', '2025-11-09 15:16:08', '456'),
(23100003, 'Mark Lester Rivera', 'BSIT', '3rd Year', 'lestermark@slc.edu', '2025-11-09 15:16:48', '789'),
(23100004, 'Erich Galiste', 'BSIT', '3rd Year', 'galiste@slc.edu', '2025-12-03 22:18:42', '012'),
(23100005, 'Charlene Abenes', 'BSIT', '3rd Year', 'abenes@slc.edu', '2025-12-06 22:58:28', '345');

-- --------------------------------------------------------

--
-- Table structure for table `student_feedback`
--

CREATE TABLE `student_feedback` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `queue_id` int(11) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL COMMENT '1-5 stars',
  `comments` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_feedback`
--

INSERT INTO `student_feedback` (`id`, `student_id`, `queue_id`, `rating`, `comments`, `created_at`) VALUES
(1, 1, 0, 5, 'testing', '2025-12-06 12:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `system_metrics`
--

CREATE TABLE `system_metrics` (
  `metric_id` int(11) NOT NULL,
  `queue_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `wait_time_seconds` int(11) DEFAULT 0,
  `service_time_seconds` int(11) DEFAULT 0,
  `total_time_seconds` int(11) DEFAULT 0,
  `transaction_amount` decimal(10,2) DEFAULT 0.00,
  `payment_type` enum('cash','card','digital') DEFAULT 'cash',
  `final_status` enum('served','voided','abandoned') DEFAULT 'served',
  `was_served` tinyint(1) DEFAULT 0,
  `was_voided` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` text NOT NULL,
  `setting_value` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `description`) VALUES
(1, 'max_students_per_day', '100', 'Maximum number of students that can be served per day'),
(2, 'student_time_limit', '5', 'Time limit in minutes for students to respond when called'),
(3, 'service_start_time', '08:00', 'Service start time'),
(4, 'service_end_time', '17:00', 'Service end time'),
(5, 'cut_off_time', '16:30', 'Last queue entry time');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `queue_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','card','digital') NOT NULL,
  `date_paid` datetime DEFAULT current_timestamp(),
  `cashier_id` int(11) NOT NULL,
  `status` enum('completed','voided') NOT NULL DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `queue_id`, `amount`, `payment_type`, `date_paid`, `cashier_id`, `status`) VALUES
(1, 2, 5000.00, 'cash', '2025-11-09 16:28:36', 2, 'completed'),
(2, 3, 1000.00, 'cash', '2025-11-09 16:28:50', 2, 'completed'),
(3, 4, 6000.00, 'cash', '2025-11-09 16:37:32', 2, 'completed'),
(4, 6, 1000.00, 'cash', '2025-11-09 20:51:37', 1, 'completed'),
(5, 7, 5000.00, 'cash', '2025-11-09 21:05:51', 2, 'completed'),
(6, 9, 1000.00, 'cash', '2025-11-11 21:21:01', 2, 'completed'),
(10, 19, 1000.00, 'cash', '2025-11-13 00:23:45', 2, 'completed'),
(13, 22, 2000.00, 'cash', '2025-11-13 00:30:51', 2, 'completed'),
(14, 33, 1000.00, 'cash', '2025-11-13 17:23:36', 2, 'completed'),
(15, 42, 222.00, 'cash', '2025-11-13 18:20:13', 2, 'completed'),
(16, 48, 2000.00, 'cash', '2025-11-15 00:46:43', 2, 'completed'),
(17, 49, 1000.00, 'cash', '2025-11-15 00:49:22', 2, 'completed'),
(18, 50, 1000.00, 'cash', '2025-11-15 00:58:38', 2, 'completed'),
(19, 52, 2000.00, 'cash', '2025-11-15 01:20:13', 2, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Mark Lester', 'admin', 'admin@slc.edu', '123', 'admin', '2025-11-09 15:13:19'),
(2, 'Ardy Aquino', 'cashier', 'cashier1@slc.edu', '456', 'cashier', '2025-11-09 15:14:12'),
(7, 'James', 'james', NULL, '999', 'cashier', '2025-12-04 08:01:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_kpi_summary`
--
ALTER TABLE `daily_kpi_summary`
  ADD PRIMARY KEY (`summary_id`),
  ADD UNIQUE KEY `unique_date` (`summary_date`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_payment_history_students` (`student_id`),
  ADD KEY `fk_payment_history_transactions` (`transaction_id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `fk_queue_students` (`student_id`),
  ADD KEY `handled_by` (`handled_by`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_reports_users` (`generated_by`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `student_feedback`
--
ALTER TABLE `student_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_queue_id` (`queue_id`);

--
-- Indexes for table `system_metrics`
--
ALTER TABLE `system_metrics`
  ADD PRIMARY KEY (`metric_id`),
  ADD KEY `queue_id` (`queue_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transactions_queue` (`queue_id`),
  ADD KEY `fk_transactions_cashier` (`cashier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_kpi_summary`
--
ALTER TABLE `daily_kpi_summary`
  MODIFY `summary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23100006;

--
-- AUTO_INCREMENT for table `student_feedback`
--
ALTER TABLE `student_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_metrics`
--
ALTER TABLE `system_metrics`
  MODIFY `metric_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `fk_payment_history_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_history_transactions` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON UPDATE CASCADE;

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `fk_queue_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`handled_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_users` FOREIGN KEY (`generated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `student_feedback`
--
ALTER TABLE `student_feedback`
  ADD CONSTRAINT `fk_feedback_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `system_metrics`
--
ALTER TABLE `system_metrics`
  ADD CONSTRAINT `system_metrics_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`queue_id`),
  ADD CONSTRAINT `system_metrics_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_cashier` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_queue` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`queue_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
