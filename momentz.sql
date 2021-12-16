-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2021 at 01:07 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `momentz`
--

-- --------------------------------------------------------

--
-- Table structure for table `dresscodes`
--

CREATE TABLE `dresscodes` (
  `id` int(10) NOT NULL,
  `description` text NOT NULL,
  `event_id` int(10) NOT NULL,
  `dress_code_category_id` int(10) NOT NULL,
  `color_one` text NOT NULL,
  `color_two` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dresscodes`
--

INSERT INTO `dresscodes` (`id`, `description`, `event_id`, `dress_code_category_id`, `color_one`, `color_two`, `created_at`, `updated_at`) VALUES
(1, 'this is game for friends and family', 4, 2, 'red', 'blue', '2021-11-23 14:40:22', '2021-11-23 14:40:22'),
(2, 'this is game for friends and family', 5, 499, 'red', 'blue', '2021-11-24 02:27:52', '2021-11-24 02:27:52'),
(3, 'this is game for friends and family', 4, 3, 'red', 'blue', '2021-11-30 02:03:09', '2021-11-30 02:03:09'),
(4, 'this is game for friends and family', 4, 3, 'red', 'blue', '2021-11-30 02:03:18', '2021-11-30 02:03:18'),
(5, 'this is game for friends and family', 10, 3, 'red', 'blue', '2021-11-30 04:49:06', '2021-11-30 04:49:06'),
(6, 'this is game for friends and family', 10, 3, 'red', 'blue', '2021-12-10 03:43:50', '2021-12-10 03:43:50'),
(7, 'this is game for friends and family', 10, 3, 'red', 'blue', '2021-12-16 05:44:14', '2021-12-16 05:44:14'),
(8, 'this is cricket for friends and family', 10, 3, 'pink', 'blue', '2021-12-16 05:44:38', '2021-12-16 05:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) NOT NULL,
  `title` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `isActive` tinyint(5) NOT NULL DEFAULT 0,
  `customer_id` int(10) NOT NULL COMMENT 'this is user_id',
  `event_type_id` int(10) NOT NULL,
  `category_interest_name` text NOT NULL,
  `budget` int(100) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `location_title` varchar(100) NOT NULL,
  `location_lat` decimal(11,8) DEFAULT NULL,
  `location_lon` decimal(10,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `isActive`, `customer_id`, `event_type_id`, `category_interest_name`, `budget`, `datetime`, `location_title`, `location_lat`, `location_lon`, `created_at`, `updated_at`) VALUES
(1, 'unnu game', 'this is game for friends and family', 1, 62, 1, 'Sports', 499, '2021-12-20 14:43:45', 'Iyana paja, Lagos', '27.20460000', '80.49770000', '2021-11-23 06:17:54', '2021-11-23 06:17:54'),
(4, 'ludu king  game', 'this is game for friends and family', 1, 63, 2, 'Sports', 499, '2021-12-25 08:00:57', 'Iyana paja, Lagos', '27.20460000', '77.49760000', '2021-11-23 06:51:24', '2021-12-01 07:18:55'),
(5, 'cross game', 'this is game for friends and family', 1, 63, 1, 'Sports', 499, '2021-12-30 11:00:34', 'Iyana paja, Lagos', '27.20450000', '80.59780000', '2021-11-23 12:50:51', '2021-12-10 05:30:34'),
(9, 'online cricket game', 'this is game for friends and family', 2, 62, 1, 'Eating', 499, '2021-12-18 17:41:53', 'Iyana paja, Lagos', '28.70410000', '77.10250000', '2021-11-30 02:07:19', '2021-12-02 12:47:42'),
(10, 'unnu online  game', 'this is game for friends and family', 1, 63, 1, 'Sports', 499, '2021-11-20 09:02:18', 'Iyana paja, Lagos', '80.33190000', '26.44990000', '2021-11-30 04:26:29', '2021-12-04 07:47:43'),
(11, 'car game', 'this is game for friends', 0, 63, 1, 'Sports', 299, '2021-12-04 13:15:44', 'Iyana paja, Lagos', '80.33190000', '26.44990000', '2021-12-01 06:53:52', '2021-12-01 06:53:52'),
(12, 'football game', 'this is game for friends', 0, 63, 1, 'Music', 299, '2021-11-20 09:02:18', 'Iyana paja, Lagos', '80.33190000', '26.44990000', '2021-12-04 07:45:09', '2021-12-04 07:45:09'),
(13, 'ceicket game', 'this is game for friends', 0, 71, 1, 'Music', 299, '2021-11-20 09:02:18', 'Iyana paja, Lagos', '80.33190000', '26.44990000', '2021-12-16 06:02:19', '2021-12-16 06:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `event_dashboard_statuses`
--

CREATE TABLE `event_dashboard_statuses` (
  `id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  `ticket` tinyint(4) DEFAULT NULL,
  `vendor` tinyint(4) DEFAULT NULL,
  `dresscode` tinyint(4) DEFAULT NULL,
  `wishlist` tinyint(4) DEFAULT NULL,
  `to_dos` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_dashboard_statuses`
--

INSERT INTO `event_dashboard_statuses` (`id`, `event_id`, `ticket`, `vendor`, `dresscode`, `wishlist`, `to_dos`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2021-11-30 04:26:29', '2021-12-01 06:01:21'),
(2, 5, 1, 0, 1, 0, 0, '2021-12-01 06:53:52', '2021-12-01 06:53:52'),
(3, 10, 0, 0, 1, 1, 0, '2021-12-04 07:45:09', '2021-12-16 05:55:00'),
(4, 13, 0, 0, 0, 0, 0, '2021-12-16 06:02:19', '2021-12-16 06:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interest_categories`
--

CREATE TABLE `interest_categories` (
  `id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `name` text NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `radius` int(10) NOT NULL,
  `margin_top` int(10) NOT NULL,
  `margin_left` int(10) NOT NULL,
  `bg_color` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `interest_categories`
--

INSERT INTO `interest_categories` (`id`, `category_id`, `name`, `icon_url`, `radius`, `margin_top`, `margin_left`, `bg_color`, `created_at`, `updated_at`) VALUES
(1, 1, 'Music', NULL, 50, 10, 10, '#D6E4E8', '2021-11-18 18:26:20', '2021-11-18 18:26:20'),
(2, 2, 'Sports', NULL, 50, 10, 50, '#D6E4E8', '2021-11-18 18:28:35', '2021-11-18 18:28:35'),
(3, 3, 'Games', NULL, 50, 50, 10, '#D6E4E8', '2021-11-18 18:34:12', '2021-11-18 18:34:12'),
(4, 4, 'Eating', NULL, 60, 10, 10, '#D6E4E8', '2021-11-18 18:34:12', '2021-11-18 18:34:12'),
(5, 5, 'Dancing', NULL, 85, 10, 10, '#D6E4E8', '2021-11-18 18:35:09', '2021-11-18 18:35:09'),
(6, 6, 'Tech', NULL, 70, 10, 10, '#D6E4E8', '2021-11-18 18:37:25', '2021-11-18 18:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) NOT NULL,
  `vendor_id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  `total_quantity` int(10) NOT NULL,
  `total_price` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `vendor_id`, `event_id`, `total_quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(10, 69, 10, 8, 700, '2021-12-08 18:53:34', '2021-12-08 18:53:34'),
(11, 69, 10, 2, 100, '2021-12-08 19:04:59', '2021-12-08 19:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL COMMENT 'i.e user_id',
  `quantity` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `customer_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 10, 2, 63, 2, '2021-12-08 18:44:19', '2021-12-08 18:44:19'),
(2, 10, 1, 63, 3, '2021-12-08 18:44:44', '2021-12-08 18:44:44'),
(3, 11, 2, 63, 2, '2021-12-08 19:05:39', '2021-12-08 19:05:39'),
(4, 10, 1, 63, 3, '2021-12-08 18:44:44', '2021-12-08 18:44:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) NOT NULL,
  `payment_id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL COMMENT 'i.e user_id',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL COMMENT 'this is vendor id',
  `product_name` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `price_per_quantity` int(11) NOT NULL,
  `description` text NOT NULL,
  `minimum_notice_period` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `product_name`, `location`, `price_per_quantity`, `description`, `minimum_notice_period`, `created_at`, `updated_at`) VALUES
(1, 69, 'Noodles', 'logos,Nigeria', 100, 'very nice', '2021-12-08 08:50:32', '2021-12-08 03:20:32', '2021-12-08 03:20:32'),
(2, 69, 'burger', 'logos,Nigeria', 50, 'spicy', '2021-12-08 08:50:32', '2021-12-08 03:20:32', '2021-12-08 03:20:32'),
(3, 69, 'Manchourian', 'logos,Nigeria', 500, 'very nice', '2021-12-16 11:59:49', '2021-12-16 06:29:49', '2021-12-16 06:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `event_id` int(10) NOT NULL,
  `stock` int(10) NOT NULL,
  `price` int(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `name`, `description`, `event_id`, `stock`, `price`, `created_at`, `updated_at`) VALUES
(1, 'carrom game', 'this is game for friends and family', 1, 499, 150, '2021-11-23 13:49:50', '2021-11-23 13:49:50'),
(2, '1st ticket name', 'this is game for friends and family', 5, 499, 100, '2021-11-23 14:26:35', '2021-11-23 14:26:35'),
(3, '2nd ticket name', 'this is game for friends and family', 5, 299, 200, '2021-11-24 02:23:38', '2021-11-24 02:23:38'),
(4, '3rd ticket name', 'this is game for friends and family', 5, 199, 300, '2021-11-30 01:58:05', '2021-11-30 01:58:05'),
(5, '4th ticket name', 'this is ticket', 10, 399, 180, '2021-11-30 04:42:12', '2021-11-30 04:42:12'),
(6, '5th ticket name', 'this is ticket', 10, 399, 60000, '2021-11-30 04:43:39', '2021-11-30 04:43:39'),
(7, 'vip dictrate pavillain', 'this is ticket', 10, 389, 69000, '2021-11-30 04:45:15', '2021-12-02 02:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_Signup_Complete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `is_Signup_Complete`, `user_type`, `password`, `phone_number`, `country_code`, `date`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(63, 'harshitmishra5348@gmail.com', '1', 'customer', '$2y$10$iPsl9CDk70OP7iAqmgXMG.q2Nh459OdeihPE64nTu.cZ7QYHSCK.i', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-11-20 09:02:18', '2021-11-24 02:32:33'),
(64, 'harshit123@gmail.com', NULL, 'customer', '$2y$10$Tk6xI8./oULWdyhRLkjNL.i14RbXeqA7dj.TmM3DLPaiDnsniqpUW', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-11-21 13:12:07', '2021-11-21 13:12:07'),
(65, 'harshitmishra7921@gmail.com', NULL, 'customer', '$2y$10$4TQrrGKQB4joNF2lMYMI9u8ysDiBXO2gQZjWn1slD3wP10jEjAnIS', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-11-23 04:28:26', '2021-11-23 04:28:26'),
(66, 'harshitmishra1297@gmail.com', NULL, 'customer', '$2y$10$qCSMTFIBisvyi.nT9p3UmuQlbXok4WltJoe9.4hrRYq/YB9.Arp9C', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-11-27 07:53:58', '2021-11-27 07:53:58'),
(67, 'harshitmishra1234@gmail.com', NULL, 'customer', '$2y$10$t9.omFTOMtAPY7AuwJtCd.vV3YURbDuEkWFD.9slR8nNkuv6fVuIy', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-12-08 00:46:14', '2021-12-08 00:46:14'),
(68, 'john123@gmail.com', '1', 'vendor', '$2y$10$t8XstMVrgxZX7VpUIx7Qp.ouSDJF5oD22DX7LnPh/A9Pnh9jIkPTK', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-12-08 00:56:59', '2021-12-08 01:14:30'),
(69, 'vendor123@gmail.com', '1', 'vendor', '$2y$10$/87lrbCvCXlMmD7Bio/7L.gbvLNmBM73zL03vHjvPJ1sBIc2JLyR2', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-12-08 02:06:46', '2021-12-08 02:12:06'),
(70, 'stevesmith@gmail.com', NULL, 'customer', '$2y$10$.bdKMOjYhGcx1ubp5P0xde0yqc7D5xu35L5LS/C61hU4vXqnXiUai', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-12-16 03:47:03', '2021-12-16 03:47:03'),
(71, 'davidwarner@gmail.com', '1', 'customer', '$2y$10$QXXs.ITdX9ZzbMcmPsrnhuASzkyRLFj6tgdHOZIQ6pkApz2sTL7fa', '+919305364771', '+91', '2021-11-16', NULL, NULL, '2021-12-16 05:28:34', '2021-12-16 05:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

CREATE TABLE `user_interests` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `interest_category_id` int(10) NOT NULL,
  `is_active` tinyint(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_interests`
--

INSERT INTO `user_interests` (`id`, `user_id`, `interest_category_id`, `is_active`, `created_at`, `updated_at`) VALUES
(37, 70, 1, 0, '2021-12-16 04:52:43', '2021-12-16 05:18:21'),
(38, 70, 2, 0, '2021-12-16 04:52:43', '2021-12-16 05:18:21'),
(39, 70, 5, 0, '2021-12-16 04:52:43', '2021-12-16 05:18:21'),
(40, 70, 1, 0, '2021-12-16 04:52:50', '2021-12-16 05:18:21'),
(41, 70, 2, 0, '2021-12-16 04:52:50', '2021-12-16 05:18:21'),
(42, 70, 5, 0, '2021-12-16 04:52:50', '2021-12-16 05:18:21'),
(43, 70, 3, 0, '2021-12-16 04:53:13', '2021-12-16 05:18:21'),
(44, 70, 4, 0, '2021-12-16 04:53:13', '2021-12-16 05:18:21'),
(45, 70, 5, 0, '2021-12-16 04:53:13', '2021-12-16 05:18:21'),
(46, 70, 1, 1, '2021-12-16 05:18:21', '2021-12-16 05:18:21'),
(47, 70, 4, 1, '2021-12-16 05:18:21', '2021-12-16 05:18:21'),
(48, 70, 3, 1, '2021-12-16 05:18:21', '2021-12-16 05:18:21'),
(49, 71, 1, 0, '2021-12-16 05:29:42', '2021-12-16 05:32:18'),
(50, 71, 5, 0, '2021-12-16 05:29:42', '2021-12-16 05:32:18'),
(51, 71, 3, 0, '2021-12-16 05:29:42', '2021-12-16 05:32:18'),
(52, 71, 1, 1, '2021-12-16 05:32:18', '2021-12-16 05:32:18'),
(53, 71, 5, 1, '2021-12-16 05:32:18', '2021-12-16 05:32:18'),
(54, 71, 3, 1, '2021-12-16 05:32:18', '2021-12-16 05:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `full_name` text NOT NULL,
  `location_city` varchar(20) NOT NULL,
  `location_country` varchar(20) NOT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `full_name`, `location_city`, `location_country`, `profile_image_url`, `created_at`, `updated_at`) VALUES
(14, 62, 'Harshit Mishra', 'kanpur', 'india', 'Harshit_Mishra_62.PNG', '2021-11-18 07:36:44', '2021-11-18 07:39:33'),
(15, 63, 'steve smith', 'canberra', 'Australia', 'steve_smith_63.PNG', '2021-11-20 09:22:44', '2021-11-20 09:22:44'),
(17, 64, 'john calamer', 'cape town', 'South Africa', 'john_calamer_64.PNG', '2021-12-08 01:01:57', '2021-12-08 01:01:57'),
(18, 68, 'john calamer', 'cape town', 'South Africa', 'john_calamer_68.PNG', '2021-12-08 01:14:30', '2021-12-08 01:14:30'),
(19, 70, 'steve smith', 'adelede', 'Australia', 'steve_smith_70.PNG', '2021-12-16 03:54:02', '2021-12-16 03:54:02'),
(20, 71, 'david warner', 'gabba', 'Australia', 'david_warner_71.PNG', '2021-12-16 05:29:25', '2021-12-16 05:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_signup_statuses`
--

CREATE TABLE `user_signup_statuses` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `is_otp_verified` text NOT NULL,
  `is_profile_complete` text NOT NULL,
  `is_interest_choosen` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_signup_statuses`
--

INSERT INTO `user_signup_statuses` (`id`, `user_id`, `is_otp_verified`, `is_profile_complete`, `is_interest_choosen`, `created_at`, `updated_at`) VALUES
(12, 61, 'Yes', 'No', 'No', '2021-11-18 07:34:50', '2021-11-18 07:35:24'),
(13, 62, 'Yes', 'Yes', 'No', '2021-11-18 07:35:55', '2021-11-18 07:36:44'),
(14, 63, 'Yes', 'Yes', 'Yes', '2021-11-20 09:02:18', '2021-11-20 09:27:53'),
(15, 64, 'No', 'Yes', 'No', '2021-11-21 13:12:07', '2021-12-08 01:01:57'),
(16, 65, 'No', 'No', 'No', '2021-11-23 04:28:26', '2021-11-23 04:28:26'),
(17, 66, 'No', 'No', 'No', '2021-11-27 07:53:58', '2021-11-27 07:53:58'),
(18, 67, 'No', 'No', 'No', '2021-12-08 00:46:14', '2021-12-08 00:46:14'),
(19, 68, 'Yes', 'Yes', 'No', '2021-12-08 00:56:59', '2021-12-08 01:14:30'),
(20, 69, 'Yes', 'Yes', 'No', '2021-12-08 02:06:46', '2021-12-08 02:12:06'),
(21, 70, 'Yes', 'Yes', 'No', '2021-12-16 03:47:03', '2021-12-16 03:54:02'),
(22, 71, 'Yes', 'Yes', 'Yes', '2021-12-16 05:28:34', '2021-12-16 05:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_profiles`
--

CREATE TABLE `vendor_profiles` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL COMMENT 'i.e vendor_id',
  `business_name` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `profile_image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendor_profiles`
--

INSERT INTO `vendor_profiles` (`id`, `user_id`, `business_name`, `location`, `profile_image_url`, `created_at`, `updated_at`) VALUES
(2, 69, 'Seoul Pot', 'lagos,nigeria', '_69.PNG', '2021-12-08 02:12:06', '2021-12-08 02:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `otp` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`id`, `user_id`, `otp`, `created_at`, `updated_at`) VALUES
(46, 61, 134044, '2021-11-18 07:34:50', '2021-11-18 07:34:50'),
(47, 62, 544748, '2021-11-18 07:35:55', '2021-11-18 07:35:55'),
(48, 63, 519811, '2021-11-20 09:02:18', '2021-11-20 09:02:18'),
(49, 64, 669426, '2021-11-21 13:12:07', '2021-11-21 13:12:07'),
(50, 65, 117727, '2021-11-23 04:28:26', '2021-11-23 04:28:26'),
(51, 66, 513163, '2021-11-27 07:53:58', '2021-11-27 07:53:58'),
(52, 67, 537989, '2021-12-08 00:46:14', '2021-12-08 00:46:14'),
(53, 68, 223384, '2021-12-08 00:56:59', '2021-12-08 00:56:59'),
(54, 69, 561181, '2021-12-08 02:06:46', '2021-12-08 02:06:46'),
(55, 70, 753173, '2021-12-16 03:47:03', '2021-12-16 03:47:03'),
(56, 71, 149195, '2021-12-16 05:28:34', '2021-12-16 05:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  `gift_name` varchar(20) NOT NULL,
  `account_info` text NOT NULL,
  `price` int(10) NOT NULL,
  `additional_info` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `event_id`, `gift_name`, `account_info`, `price`, `additional_info`, `created_at`, `updated_at`) VALUES
(1, 10, 'ollu', 'IBAN 000999000000001', 4000, 'Additional info', '2021-12-01 04:38:00', '2021-12-02 02:40:10'),
(2, 10, 'coupens', '9232434', 70000, 'hfgufffffffffffffff', '2021-12-01 04:40:58', '2021-12-01 04:40:58'),
(3, 10, 'coupens 2', '9232434', 70000, 'hfgufffffffffffffff', '2021-12-01 05:42:35', '2021-12-01 05:42:35'),
(4, 11, 'Gift name', 'IBAN 000999000000000', 5000, 'Additional info', '2021-12-01 06:00:53', '2021-12-01 06:00:53'),
(5, 11, 'Gift name 2', 'IBAN 000999000000000', 5000, 'Additional info', '2021-12-01 06:01:21', '2021-12-01 06:01:21'),
(6, 10, 'Gift name2', 'IBAN 000999000000000', 5000, 'Additional info', '2021-12-16 05:55:00', '2021-12-16 05:55:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dresscodes`
--
ALTER TABLE `dresscodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_dashboard_statuses`
--
ALTER TABLE `event_dashboard_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `interest_categories`
--
ALTER TABLE `interest_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_signup_statuses`
--
ALTER TABLE `user_signup_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_profiles`
--
ALTER TABLE `vendor_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dresscodes`
--
ALTER TABLE `dresscodes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `event_dashboard_statuses`
--
ALTER TABLE `event_dashboard_statuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interest_categories`
--
ALTER TABLE `interest_categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user_interests`
--
ALTER TABLE `user_interests`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_signup_statuses`
--
ALTER TABLE `user_signup_statuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vendor_profiles`
--
ALTER TABLE `vendor_profiles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
