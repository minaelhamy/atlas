-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 02, 2026 at 12:45 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `storemart_v_4_5_envato`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `about_content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `age_verification`
--

CREATE TABLE `age_verification` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `age_verification_on_off` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `popup_type` varchar(255) DEFAULT NULL,
  `min_age` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `android_link` varchar(255) DEFAULT NULL,
  `ios_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mobile_app_on_off` int(11) DEFAULT NULL COMMENT '1=yes,2=no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_image`
--

CREATE TABLE `banner_image` (
  `id` int(10) UNSIGNED NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1=category,2=products',
  `product_id` int(11) DEFAULT NULL,
  `section` int(11) NOT NULL COMMENT '0=sliders,1=banner1,2=banner2',
  `banner_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `item_price` varchar(255) NOT NULL COMMENT 'calculation with extra',
  `tax` varchar(255) DEFAULT NULL,
  `extras_id` varchar(255) DEFAULT NULL,
  `extras_name` varchar(255) DEFAULT NULL,
  `extras_price` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` varchar(255) NOT NULL COMMENT 'item original price with qty calculation',
  `variants_id` varchar(255) DEFAULT NULL,
  `variants_name` varchar(255) DEFAULT NULL,
  `variants_price` varchar(255) DEFAULT NULL COMMENT 'item original price',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Yes . 2 = No',
  `attribute` varchar(255) DEFAULT NULL,
  `buynow` int(11) NOT NULL DEFAULT 0 COMMENT '1=buynow,0=cart',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No	',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `currency_symbol` text NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no\r\n',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `code`, `currency_symbol`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 'USD', 'usd', '$', 1, '2025-07-29 07:30:20', '2025-07-29 10:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `currency_settings`
--

CREATE TABLE `currency_settings` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `exchange_rate` float NOT NULL,
  `currency_position` varchar(255) NOT NULL DEFAULT '1' COMMENT '1=left, 2=right',
  `currency_space` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no',
  `currency_formate` int(11) NOT NULL,
  `decimal_separator` int(11) NOT NULL DEFAULT 1 COMMENT '1=dot,2=no',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currency_settings`
--

INSERT INTO `currency_settings` (`id`, `code`, `name`, `currency`, `exchange_rate`, `currency_position`, `currency_space`, `currency_formate`, `decimal_separator`, `is_available`, `created_at`, `updated_at`) VALUES
(21, 'usd', 'USD', '$', 1, '1', 1, 2, 1, 1, '2025-07-28 12:01:44', '2025-11-07 01:41:33');

-- --------------------------------------------------------

--
-- Table structure for table `custom_domain`
--

CREATE TABLE `custom_domain` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `requested_domain` text NOT NULL,
  `current_domain` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_status`
--

CREATE TABLE `custom_status` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=default,2=process,3=complete,4=cancel',
  `is_available` int(11) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 2,
  `order_type` int(11) NOT NULL DEFAULT 1 COMMENT '1=delivery,2=pickup,3=dinein,4=pos',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_status`
--

INSERT INTO `custom_status` (`id`, `reorder_id`, `vendor_id`, `name`, `type`, `is_available`, `is_deleted`, `order_type`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'Pending', 1, 1, 2, 1, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(2, 0, 1, 'Accepted', 2, 1, 2, 1, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(3, 0, 1, 'Out For Delivery', 2, 1, 2, 1, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(4, 0, 1, 'Complete', 3, 1, 2, 1, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(5, 0, 1, 'Cancel', 4, 1, 2, 1, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(6, 0, 1, 'Pending', 1, 1, 2, 2, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(7, 0, 1, 'Accepted', 2, 1, 2, 2, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(8, 0, 1, 'Waiting For Pickup', 2, 1, 2, 2, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(9, 0, 1, 'Complete', 3, 1, 2, 2, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(10, 0, 1, 'Cancel', 4, 1, 2, 2, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(11, 0, 1, 'Pending', 1, 1, 2, 3, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(12, 0, 1, 'Accepted', 2, 1, 2, 3, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(13, 0, 1, 'In Progress', 2, 1, 2, 3, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(14, 0, 1, 'Complete', 3, 1, 2, 3, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(15, 0, 1, 'Cancel', 4, 1, 2, 3, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(16, 0, 1, 'Pending', 1, 1, 2, 4, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(17, 0, 1, 'Complete', 3, 1, 2, 4, '2025-12-11 08:56:17', '2025-12-11 08:56:17'),
(18, 0, 1, 'Cancel', 4, 1, 2, 4, '2025-12-11 08:56:17', '2025-12-11 08:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `extras`
--

CREATE TABLE `extras` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firebase`
--

CREATE TABLE `firebase` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sub_title` longtext NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no',
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=yes,2=no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footerfeatures`
--

CREATE TABLE `footerfeatures` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fun_fact`
--

CREATE TABLE `fun_fact` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fun_fact`
--

INSERT INTO `fun_fact` (`id`, `vendor_id`, `icon`, `title`, `description`, `created_at`, `updated_at`) VALUES
(5, 1, '<i class=\"fa-solid fa-home\"></i>', '50+', 'Our Store Partners', '2024-09-17 12:58:06', '2024-09-17 12:58:06'),
(6, 1, '<i class=\"fa-solid fa-calendar\"></i>', '5+', 'Total Experience', '2024-09-17 12:58:06', '2024-09-17 12:58:06'),
(7, 1, '<i class=\"fa-solid fa-users\"></i>', '100+', 'Total Customers', '2024-09-17 12:58:06', '2024-09-17 12:58:06'),
(8, 1, '<i class=\"fa-solid fa-th\"></i>', '1500+', 'Successful Orders', '2024-09-17 12:58:06', '2024-09-17 12:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `global_extras`
--

CREATE TABLE `global_extras` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `howitworks`
--

CREATE TABLE `howitworks` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `item_name` text NOT NULL,
  `description` text DEFAULT NULL,
  `item_price` float NOT NULL DEFAULT 0,
  `item_original_price` float DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT 0,
  `low_qty` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `tax` varchar(255) DEFAULT '0',
  `slug` text DEFAULT NULL,
  `min_order` int(11) DEFAULT NULL,
  `max_order` int(11) DEFAULT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=yes,2=no',
  `frequently_bought_items` varchar(255) DEFAULT NULL,
  `has_variants` int(11) NOT NULL DEFAULT 2,
  `variants_json` longtext DEFAULT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `attchment_name` varchar(255) DEFAULT NULL,
  `attchment_file` varchar(255) DEFAULT NULL,
  `download_file` varchar(255) DEFAULT NULL,
  `video_url` text DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `stock_management` int(11) NOT NULL COMMENT '1=yes,2=no',
  `is_imported` int(11) DEFAULT 2,
  `top_deals` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landing_settings`
--

CREATE TABLE `landing_settings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `landing_home_banner` varchar(255) DEFAULT NULL,
  `subscribe_image` varchar(255) DEFAULT NULL,
  `faq_image` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) DEFAULT NULL,
  `secondary_color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landing_settings`
--

INSERT INTO `landing_settings` (`id`, `vendor_id`, `landing_home_banner`, `subscribe_image`, `faq_image`, `primary_color`, `secondary_color`, `created_at`, `updated_at`) VALUES
(1, 1, 'banner-6992d9218ce60.jpeg', 'subscribe-6992d9218e882.jpeg', 'faq-6992d92190241.jpeg', '#000000', '#96c13e', '2023-08-15 05:05:24', '2026-02-16 03:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `layout` int(11) NOT NULL DEFAULT 1 COMMENT '1=ltr,2=rtl',
  `is_default` int(11) NOT NULL DEFAULT 2 COMMENT '1 = yes , 2 = no',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no',
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=yes,2=no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `image`, `layout`, `is_default`, `is_available`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'flag-65e335fed760a.png', 1, 1, 1, 2, '2022-12-13 05:15:46', '2024-03-02 14:21:50'),
(2, 'ar', 'العربية', 'flag-65e33620233d8.png', 2, 2, 1, 2, '2024-03-02 08:47:21', '2025-07-03 04:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2021_12_20_101946_create_settings_table', 2),
(3, '2021_12_20_121616_create_categories_table', 3),
(4, '2021_12_22_072131_create_cuisines_table', 4),
(5, '2021_12_23_065134_create_menuses_table', 5),
(6, '2014_10_12_100000_create_password_resets_table', 6),
(7, '2019_08_19_000000_create_failed_jobs_table', 6),
(8, '2019_12_14_000001_create_personal_access_tokens_table', 6),
(9, '2022_11_14_051836_create_banner_image_table', 6),
(10, '2022_11_14_053221_create_banner_image_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `user_id` text DEFAULT NULL,
  `order_number` varchar(100) NOT NULL,
  `order_number_start` int(11) DEFAULT NULL,
  `order_number_digit` int(11) DEFAULT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_id` text DEFAULT NULL,
  `sub_total` varchar(255) NOT NULL,
  `tax` varchar(255) DEFAULT NULL COMMENT 'tax_amount',
  `tax_name` varchar(255) DEFAULT NULL,
  `grand_total` varchar(255) NOT NULL,
  `tips` varchar(11) DEFAULT NULL,
  `order_type` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Delivery , 2 = Pickup,3="dine in",4="pos"',
  `address` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `building` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `delivery_area` varchar(255) DEFAULT NULL,
  `delivery_charge` varchar(50) DEFAULT NULL,
  `discount_amount` varchar(255) DEFAULT NULL,
  `couponcode` varchar(255) DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `vendor_note` text DEFAULT NULL,
  `customer_name` text DEFAULT NULL,
  `customer_email` text DEFAULT NULL,
  `mobile` text DEFAULT NULL,
  `delivery_date` varchar(255) DEFAULT NULL,
  `delivery_time` varchar(255) DEFAULT NULL,
  `order_from` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `status_type` int(11) NOT NULL,
  `is_notification` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Unread , 2 = Read',
  `screenshot` varchar(255) NOT NULL,
  `payment_status` int(11) NOT NULL COMMENT '1=unpaid,2=paid',
  `dinein_table` int(11) DEFAULT NULL,
  `dinein_tablename` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `extras_id` varchar(255) DEFAULT NULL,
  `extras_name` varchar(255) DEFAULT NULL,
  `extras_price` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `variants_id` varchar(255) DEFAULT NULL,
  `variants_name` varchar(255) DEFAULT NULL,
  `variants_price` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_settings`
--

CREATE TABLE `other_settings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `recent_view_product` int(11) NOT NULL DEFAULT 1 COMMENT '	1=Yes, 2=No',
  `gemini_api_key` text DEFAULT NULL,
  `gemini_version` text DEFAULT NULL,
  `estimated_delivery_on_off` int(11) DEFAULT NULL COMMENT '	1=Yes, 2=No',
  `days_of_estimated_delivery` varchar(255) DEFAULT NULL,
  `trusted_badge_image_1` text DEFAULT NULL,
  `trusted_badge_image_2` text DEFAULT NULL,
  `trusted_badge_image_3` text DEFAULT NULL,
  `trusted_badge_image_4` text DEFAULT NULL,
  `safe_secure_checkout_payment_selection` varchar(255) DEFAULT NULL,
  `safe_secure_checkout_text` varchar(255) DEFAULT NULL,
  `safe_secure_checkout_text_color` varchar(255) DEFAULT NULL,
  `maintenance_on_off` int(11) DEFAULT NULL COMMENT '1=yes,2=no	',
  `maintenance_title` varchar(255) DEFAULT NULL,
  `maintenance_description` text DEFAULT NULL,
  `maintenance_image` text DEFAULT NULL,
  `notice_on_off` int(11) DEFAULT NULL COMMENT '	1=yes,2=no	',
  `notice_title` varchar(255) DEFAULT NULL,
  `notice_description` text DEFAULT NULL,
  `tips_settings` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_settings`
--

INSERT INTO `other_settings` (`id`, `vendor_id`, `recent_view_product`, `gemini_api_key`, `gemini_version`, `estimated_delivery_on_off`, `days_of_estimated_delivery`, `trusted_badge_image_1`, `trusted_badge_image_2`, `trusted_badge_image_3`, `trusted_badge_image_4`, `safe_secure_checkout_payment_selection`, `safe_secure_checkout_text`, `safe_secure_checkout_text_color`, `maintenance_on_off`, `maintenance_title`, `maintenance_description`, `maintenance_image`, `notice_on_off`, `notice_title`, `notice_description`, `tips_settings`, `created_at`, `updated_at`) VALUES
(16, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'We\'ll be back soon!', 'Sorry for the inconvenience but we are performing some maintenance at the moment. we will be back online shortly!', 'maintenance_image-6992d98c2c73f.jpeg', 1, '🔔 Attention All Vendors – Scheduled Maintenance Notice', 'Dear Vendors,\r\n\r\nPlease be informed that scheduled maintenance will take place on the Storemart SaaS Platform.\r\n\r\n🛠️ During this period, access to the vendor dashboard and other related services will be temporarily unavailable.\r\nWe are working to improve performance and ensure a better experience for you.\r\n\r\nWe sincerely apologize for any inconvenience this may cause and greatly appreciate your understanding and patience.\r\n\r\nThank you for being a valued part of the Storemart community.\r\n\r\nBest regards,\r\nThe Storemart Team', NULL, '2025-08-01 04:08:27', '2026-02-16 03:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `unique_identifier` varchar(255) DEFAULT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `currency` varchar(255) DEFAULT '',
  `image` varchar(255) NOT NULL,
  `public_key` text DEFAULT NULL,
  `secret_key` text DEFAULT NULL,
  `encryption_key` text DEFAULT NULL,
  `environment` int(11) NOT NULL,
  `payment_description` longtext DEFAULT NULL,
  `base_url_by_region` text DEFAULT NULL,
  `is_available` int(11) NOT NULL,
  `is_activate` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `reorder_id`, `vendor_id`, `unique_identifier`, `payment_name`, `payment_type`, `currency`, `image`, `public_key`, `secret_key`, `encryption_key`, `environment`, `payment_description`, `base_url_by_region`, `is_available`, `is_activate`, `created_at`, `updated_at`) VALUES
(1, 0, 1, NULL, 'COD', 1, '', 'cod.png', NULL, NULL, '', 1, NULL, '', 1, 1, '2021-09-01 10:36:58', '2025-07-04 00:42:33'),
(2, 3, 1, 'razorpay', 'RazorPay', 2, 'INR', 'razorpay.png', 'RAZORPAY_KEY_PLACEHOLDER', 'RAZORPAY_SECRET_PLACEHOLDER', '', 1, NULL, NULL, 1, 1, '2021-09-01 10:36:58', '2025-01-21 14:27:16'),
(3, 4, 1, 'stripe', 'Stripe', 3, 'USD', 'stripe.png', 'STRIPE_PUBLIC_KEY_PLACEHOLDER', 'STRIPE_SECRET_KEY_PLACEHOLDER', 'STRIPE_SECRET_KEY_PLACEHOLDER', 1, NULL, NULL, 1, 1, '2021-09-01 10:36:58', '2025-01-21 14:27:16'),
(4, 6, 1, 'flutterwave', 'Flutterwave', 4, 'NGN', 'flutterwave.png', 'FLUTTERWAVE_PUBLIC_KEY_PLACEHOLDER', 'FLUTTERWAVE_SECRET_KEY_PLACEHOLDER', 'FLUTTERWAVE_ENCRYPTION_KEY_PLACEHOLDER', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:05', '2025-01-21 14:27:16'),
(5, 5, 1, 'paystack', 'Paystack', 5, 'GHS', 'paystack.png', 'STRIPE_PUBLIC_KEY_PLACEHOLDER', 'STRIPE_SECRET_KEY_PLACEHOLDER', '', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(6, 2, 1, 'bank_trasfer', 'Bank Transfer', 6, '', 'banktransfer.png', NULL, NULL, '', 0, '<p>Bank Information</p>\r\n\r\n<p>Bank Name : StoreMart&nbsp;Bank<br />\r\nAccount Holder Name : StoreMart<br />\r\nAccount Number :&nbsp;4242424242424242<br />\r\nIFSC code : BANK325125</p>\r\n\r\n<p>UPI Information<br />\r\n<br />\r\nGoogle Pay : +985641245223<br />\r\nPhonePe :&nbsp;+985641245223</p>', NULL, 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(7, 1, 1, 'mercado_pago', 'Mercado Pago', 7, 'R$', 'mercadopago.png', 'MERCADOPAGO_ACCESS_TOKEN_PLACEHOLDER', 'MERCADOPAGO_ACCESS_TOKEN_PLACEHOLDER', '', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:12', '2025-02-27 06:59:25'),
(8, 7, 1, 'paypal', 'PayPal', 8, 'USD', 'paypal.png', 'AcRx7vvy79nbNxBemacGKmnnRe_CtxkItyspBS_eeMIPREwfCEIfPg1uX-bdqPrS_ZFGocxEH_SJRrIJ', 'EGtgNkjt3I5lkhEEzicdot8gVH_PcFiKxx6ZBiXpVrp4QLDYcVQQMLX6MMG_fkS9_H0bwmZzBovb4jLP', '', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(9, 8, 1, 'myfatoorah', 'MyFatoorah', 9, 'KWT', 'myfatoorah.png', 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL', 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL', '', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:12', '2023-11-27 08:15:29'),
(10, 9, 1, 'toyyibpay', 'toyyibPay', 10, 'RM', 'toyyibpay.png', 'ts75iszg', 'luieh2jt-8hpa-m2xv-wrkv-ejrfvhjppnsj', '', 1, NULL, NULL, 1, 1, '2021-10-20 03:58:12', '2023-12-20 11:06:32'),
(11, 10, 1, 'phonepe', 'PhonePe', 11, 'INR', 'phonepe.png', 'PGTESTPAYUAT86', '96434309-7796-489d-8924-ab56988a6076', '', 1, NULL, '', 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(12, 11, 1, 'paytab', 'paytab', 12, 'INR', 'paytab.png', '132879', 'SZJ99G6MRL-JH66MZL26H-G9BBKKMKM6', '', 1, NULL, 'https://secure-global.paytabs.com/payment/request', 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(13, 12, 1, 'mollie', 'mollie', 13, 'EUR', 'mollie.png', '', 'test_FbVACj7UbsdkHtAUWnCnmSNGFWMuuA', '', 1, NULL, '', 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(14, 13, 1, 'khalti', 'khalti', 14, 'INR', 'khalti.png', '', 'live_secret_key_68791341fdd94846a146f0457ff7b455', '', 1, NULL, '', 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(15, 14, 1, 'xendit', 'Xendit', 15, 'INR', 'xendit.png', 'xnd_development_IqYpzXrPJZlxhQDlU9rNoiPQtTFFQAjAf211dK2UDXHkdfj3q1BRgIR3zvp25', 'xnd_development_IqYpzXrPJZlxhQDlU9rNoiPQtTFFQAjAf211dK2UDXHkdfj3q1BRgIR3zvp25', NULL, 1, NULL, '', 1, 1, '2021-10-20 03:58:12', '2025-01-21 14:27:16'),
(16, 15, 1, NULL, 'Wallet', 16, 'INR', 'wallet.png', '-', '-', NULL, 1, NULL, '', 1, 1, '2024-01-09 09:52:31', '2025-01-21 14:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pixcel_settings`
--

CREATE TABLE `pixcel_settings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `twitter_pixcel_id` varchar(255) DEFAULT '-',
  `facebook_pixcel_id` varchar(255) DEFAULT '-',
  `linkedin_pixcel_id` varchar(255) DEFAULT '-',
  `google_tag_id` varchar(255) DEFAULT '-',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` varchar(255) DEFAULT NULL,
  `name` text NOT NULL,
  `description` longtext DEFAULT NULL,
  `features` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float NOT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `themes_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_type` int(11) NOT NULL COMMENT '1 = duration, 2 = days',
  `duration` varchar(255) NOT NULL COMMENT '1=1 month\r\n2=3 month\r\n3=6 month\r\n4=1\r\n year\r\n\r\n\r\n',
  `days` int(11) NOT NULL,
  `order_limit` int(11) NOT NULL,
  `appointment_limit` int(11) NOT NULL,
  `custom_domain` int(11) NOT NULL COMMENT '1=yes,2=no',
  `google_analytics` int(11) NOT NULL COMMENT '1=yes,2=no',
  `pos` int(11) NOT NULL COMMENT '1 = Yes , 2 = No',
  `vendor_app` int(11) NOT NULL,
  `customer_app` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `role_management` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `pwa` int(11) DEFAULT NULL,
  `is_available` int(11) DEFAULT 1 COMMENT '1=Yes\r\n2=No\r\n',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `coupons` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `themes` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `blogs` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `google_login` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `facebook_login` int(11) NOT NULL,
  `sound_notification` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `whatsapp_message` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `telegram_message` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `pixel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacypolicy`
--

CREATE TABLE `privacypolicy` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `privacypolicy_content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `reorder_id` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `is_imported` int(11) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `offer_name` varchar(255) NOT NULL,
  `offer_code` varchar(255) NOT NULL,
  `offer_type` int(11) NOT NULL COMMENT '1=fixed,2=percentage',
  `offer_amount` varchar(255) NOT NULL,
  `min_amount` int(11) NOT NULL,
  `usage_type` int(11) DEFAULT NULL COMMENT '1=Limited time\r\n,2=multiple times',
  `usage_limit` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `exp_date` date NOT NULL,
  `description` longtext NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=yes,2=no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotionalbanner`
--

CREATE TABLE `promotionalbanner` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE `question_answer` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_access`
--

CREATE TABLE `role_access` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `add` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `delete` int(11) NOT NULL,
  `manage` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_manager`
--

CREATE TABLE `role_manager` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` longtext NOT NULL,
  `is_available` varchar(255) NOT NULL,
  `is_deleted` int(11) NOT NULL COMMENT '1=yes,2=no',
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `checkout_login_required` int(11) DEFAULT 2 COMMENT '1 = Yes , 2 = No',
  `is_checkout_login_required` int(11) DEFAULT NULL,
  `logo` varchar(255) NOT NULL DEFAULT 'default-logo.png',
  `darklogo` text DEFAULT NULL,
  `favicon` varchar(255) NOT NULL DEFAULT 'favicon-.png',
  `delivery_type` varchar(255) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '-',
  `email` varchar(255) NOT NULL DEFAULT '-',
  `mobile` varchar(255) NOT NULL DEFAULT '-',
  `description` text DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `website_title` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `og_image` varchar(255) NOT NULL DEFAULT 'og_image.png',
  `language` int(11) NOT NULL DEFAULT 1,
  `template` int(11) NOT NULL DEFAULT 1,
  `primary_color` varchar(255) NOT NULL,
  `secondary_color` varchar(255) NOT NULL,
  `cname_title` text DEFAULT NULL,
  `cname_text` text DEFAULT NULL,
  `interval_time` varchar(255) NOT NULL,
  `interval_type` int(11) NOT NULL,
  `time_format` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `date_format` varchar(255) DEFAULT NULL,
  `banner` varchar(255) NOT NULL DEFAULT 'default-banner.png',
  `tracking_id` varchar(255) DEFAULT NULL,
  `view_id` varchar(255) DEFAULT NULL,
  `firebase` longtext DEFAULT NULL,
  `cover_image` varchar(255) NOT NULL DEFAULT 'default-cover.png',
  `notification_sound` varchar(255) NOT NULL DEFAULT 'notification.mp3',
  `recaptcha_version` varchar(255) DEFAULT NULL,
  `google_recaptcha_site_key` varchar(255) DEFAULT NULL,
  `google_recaptcha_secret_key` varchar(255) DEFAULT NULL,
  `score_threshold` varchar(255) DEFAULT NULL,
  `cookie_text` text DEFAULT NULL,
  `cookie_button_text` text DEFAULT NULL,
  `app_title` varchar(255) DEFAULT NULL,
  `app_name` varchar(255) DEFAULT NULL,
  `background_color` varchar(255) DEFAULT NULL,
  `theme_color` varchar(255) DEFAULT NULL,
  `pwa` int(11) DEFAULT NULL,
  `app_logo` varchar(255) DEFAULT NULL,
  `mail_driver` varchar(255) DEFAULT NULL,
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` varchar(255) DEFAULT NULL,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `mail_encryption` varchar(255) DEFAULT NULL,
  `mail_fromaddress` varchar(255) DEFAULT NULL,
  `mail_fromname` varchar(255) DEFAULT NULL,
  `landing_page` int(11) NOT NULL,
  `google_client_id` varchar(255) NOT NULL DEFAULT '-',
  `google_client_secret` varchar(255) NOT NULL DEFAULT '-',
  `google_redirect_url` varchar(255) NOT NULL DEFAULT 'http://your-domain-url.com/checklogin/google/callback-google',
  `facebook_client_id` varchar(255) NOT NULL DEFAULT '-',
  `facebook_client_secret` varchar(255) NOT NULL DEFAULT '-',
  `facebook_redirect_url` varchar(255) NOT NULL DEFAULT 'http://your-domain-url.com/checklogin/facebook/callback-facebook',
  `web_host` varchar(255) DEFAULT NULL,
  `refund_policy` longtext NOT NULL,
  `facebook_mode` int(11) DEFAULT NULL,
  `google_mode` int(11) DEFAULT NULL,
  `whoweare_title` varchar(255) DEFAULT NULL,
  `whoweare_subtitle` varchar(255) DEFAULT NULL,
  `whoweare_description` text DEFAULT NULL,
  `whoweare_image` text DEFAULT NULL,
  `subscribe_image` varchar(255) DEFAULT NULL,
  `order_detail_image` varchar(255) DEFAULT NULL,
  `languages` varchar(255) NOT NULL DEFAULT 'en',
  `default_language` varchar(255) NOT NULL DEFAULT 'en',
  `default_currency` text DEFAULT NULL,
  `currencies` text DEFAULT NULL,
  `product_ratting_switch` int(11) NOT NULL DEFAULT 1,
  `ordertype_date_time` int(11) DEFAULT 2 COMMENT '1=yes,2=no',
  `per_slot_limit` int(11) DEFAULT 1,
  `online_order` int(11) NOT NULL DEFAULT 1 COMMENT '1 = yes , 2 = no',
  `custom_domain` text DEFAULT NULL,
  `google_review` text DEFAULT NULL,
  `product_type` int(11) DEFAULT 1 COMMENT '1=physical,2=digital',
  `min_order_amount` varchar(255) DEFAULT '0',
  `shopify_store_url` text DEFAULT NULL,
  `shopify_access_token` text DEFAULT NULL,
  `order_prefix` varchar(255) DEFAULT NULL,
  `order_number_start` int(11) DEFAULT NULL,
  `image_size` float DEFAULT NULL,
  `tawk_widget_id` text DEFAULT NULL,
  `tawk_on_off` int(11) DEFAULT 1,
  `order_success_image` varchar(255) DEFAULT NULL,
  `no_data_image` varchar(255) DEFAULT NULL,
  `maintenance_image` varchar(255) DEFAULT NULL,
  `store_unavailable_image` varchar(255) DEFAULT NULL,
  `wizz_chat_settings` text DEFAULT NULL,
  `wizz_chat_on_off` int(11) DEFAULT 1,
  `quick_call` int(11) NOT NULL,
  `quick_call_mobile_view_on_off` int(11) DEFAULT NULL,
  `quick_call_name` varchar(255) DEFAULT NULL,
  `quick_call_description` text NOT NULL,
  `quick_call_mobile` varchar(255) DEFAULT NULL,
  `quick_call_position` int(11) NOT NULL,
  `quick_call_image` varchar(255) DEFAULT NULL,
  `fake_sales_notification` int(11) NOT NULL,
  `product_source` int(11) NOT NULL,
  `next_time_popup` int(11) NOT NULL,
  `notification_display_time` int(11) NOT NULL,
  `sales_notification_position` int(11) NOT NULL,
  `product_fake_view` int(11) NOT NULL,
  `fake_view_message` text DEFAULT NULL,
  `min_view_count` int(11) NOT NULL,
  `max_view_count` int(11) NOT NULL,
  `cart_checkout_countdown` int(11) NOT NULL,
  `countdown_message` text DEFAULT NULL,
  `countdown_expired_message` text NOT NULL,
  `countdown_mins` int(11) NOT NULL,
  `min_order_amount_for_free_shipping` text DEFAULT NULL,
  `shipping_charges` text DEFAULT NULL,
  `shipping_area` int(11) DEFAULT NULL,
  `cart_checkout_progressbar` int(11) NOT NULL,
  `progress_message` text DEFAULT NULL,
  `progress_message_end` text DEFAULT NULL,
  `product_section_display` int(11) DEFAULT NULL,
  `product_display_limit` int(11) DEFAULT NULL,
  `forget_password_email_message` longtext DEFAULT NULL,
  `delete_account_email_message` longtext DEFAULT NULL,
  `banktransfer_request_email_message` longtext DEFAULT NULL,
  `cod_request_email_message` longtext DEFAULT NULL,
  `subscription_reject_email_message` longtext DEFAULT NULL,
  `subscription_success_email_message` longtext DEFAULT NULL,
  `admin_subscription_request_email_message` longtext DEFAULT NULL,
  `admin_subscription_success_email_message` longtext DEFAULT NULL,
  `vendor_register_email_message` longtext DEFAULT NULL,
  `admin_vendor_register_email_message` longtext DEFAULT NULL,
  `vendor_status_change_email_message` longtext DEFAULT NULL,
  `contact_email_message` longtext DEFAULT NULL,
  `new_order_invoice_email_message` longtext DEFAULT NULL,
  `vendor_new_order_email_message` longtext DEFAULT NULL,
  `order_status_email_message` longtext DEFAULT NULL,
  `admin_auth_pages_bg_image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_register` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `vendor_id`, `checkout_login_required`, `is_checkout_login_required`, `logo`, `darklogo`, `favicon`, `delivery_type`, `timezone`, `address`, `email`, `mobile`, `description`, `contact`, `copyright`, `website_title`, `meta_title`, `meta_description`, `og_image`, `language`, `template`, `primary_color`, `secondary_color`, `cname_title`, `cname_text`, `interval_time`, `interval_type`, `time_format`, `date_format`, `banner`, `tracking_id`, `view_id`, `firebase`, `cover_image`, `notification_sound`, `recaptcha_version`, `google_recaptcha_site_key`, `google_recaptcha_secret_key`, `score_threshold`, `cookie_text`, `cookie_button_text`, `app_title`, `app_name`, `background_color`, `theme_color`, `pwa`, `app_logo`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_fromaddress`, `mail_fromname`, `landing_page`, `google_client_id`, `google_client_secret`, `google_redirect_url`, `facebook_client_id`, `facebook_client_secret`, `facebook_redirect_url`, `web_host`, `refund_policy`, `facebook_mode`, `google_mode`, `whoweare_title`, `whoweare_subtitle`, `whoweare_description`, `whoweare_image`, `subscribe_image`, `order_detail_image`, `languages`, `default_language`, `default_currency`, `currencies`, `product_ratting_switch`, `ordertype_date_time`, `per_slot_limit`, `online_order`, `custom_domain`, `google_review`, `product_type`, `min_order_amount`, `shopify_store_url`, `shopify_access_token`, `order_prefix`, `order_number_start`, `image_size`, `tawk_widget_id`, `tawk_on_off`, `order_success_image`, `no_data_image`, `maintenance_image`, `store_unavailable_image`, `wizz_chat_settings`, `wizz_chat_on_off`, `quick_call`, `quick_call_mobile_view_on_off`, `quick_call_name`, `quick_call_description`, `quick_call_mobile`, `quick_call_position`, `quick_call_image`, `fake_sales_notification`, `product_source`, `next_time_popup`, `notification_display_time`, `sales_notification_position`, `product_fake_view`, `fake_view_message`, `min_view_count`, `max_view_count`, `cart_checkout_countdown`, `countdown_message`, `countdown_expired_message`, `countdown_mins`, `min_order_amount_for_free_shipping`, `shipping_charges`, `shipping_area`, `cart_checkout_progressbar`, `progress_message`, `progress_message_end`, `product_section_display`, `product_display_limit`, `forget_password_email_message`, `delete_account_email_message`, `banktransfer_request_email_message`, `cod_request_email_message`, `subscription_reject_email_message`, `subscription_success_email_message`, `admin_subscription_request_email_message`, `admin_subscription_success_email_message`, `vendor_register_email_message`, `admin_vendor_register_email_message`, `vendor_status_change_email_message`, `contact_email_message`, `new_order_invoice_email_message`, `vendor_new_order_email_message`, `order_status_email_message`, `admin_auth_pages_bg_image`, `created_at`, `updated_at`, `vendor_register`) VALUES
(1, 1, 1, NULL, 'logo-6992dbc84e8a4.jpeg', 'darklogo-6992d6ecda7ab.jpeg', 'favicon-65e322fe6cfe9.png', '', 'Asia/Kolkata', '456 Park Avenue, New York, NY 10022', 'paponapp2244@gmail.com', '', NULL, '+919016996697', 'Copyright © Papon IT Solutions. All Rights Reserved', 'StoreMart SaaS - Online Product Selling Business Website Builder', 'StoreMart SaaS - Online Product Selling Business Builder SaaS', 'StoreMart is a software as a service (SaaS) platform that allows users to build and manage an online store for selling products. It provides users with a range of features and tools to help them create and customize their store, add and manage products, process orders, and handle payments. StoreMart also includes marketing and analytics tools to help users promote their store and track their performance. It is designed to be user-friendly and easy to use, making it a good option for people who want to start an online store without a lot of technical expertise.', 'og_image-65af752416fa9.png', 1, 1, '#000000', '#96c13e', 'Read All Instructions Carefully Before Sending Custom Domain Request', '<p>If you&#39;re using cPanel or Plesk then you need to manually add custom domain in your server with the same root directory as the script&#39;s installation&nbsp;and user need to point their custom domain A record with your server IP. Example : 68.178.145.4</p>', '', 0, 2, 'd M, Y', '', 'UA-168896572-2', '284502084', 'AAAAlio1OzI:APA91bG85HXcf1TKLW_T8CqOh2HwYPTb58yxLyv93v9e1tRvEojTNFi9Um-sFQHzTZ_O6w6gjy1KNwhKF72hW0wvaHElwJGTrsVKELGAGc_Ff0r1arQBMZwwX9gNXz-mKMMZVigUUl86', '', '', 'v2', '6LejUKAqAAAAAGa93jiP2w7n54YlQTZfct3TrEXP', '6LejUKAqAAAAAPlgND3p40Ataxu1cFRuO-1tvn_B', '0.5', 'Your experience on this site will be improved by allowing cookies.', 'I Agree', NULL, NULL, NULL, NULL, NULL, NULL, 'smtp', 'smtp.gmail.com', '587', 'testgravity777@gmail.com', 'REPLACE_ME', 'tls', 'hello@example.com', 'papon', 1, 'GOOGLE_CLIENT_ID_PLACEHOLDER', 'GOOGLE_CLIENT_SECRET_PLACEHOLDER', 'https://your-domain-url.com/checklogin/google/callback-google', 'FACEBOOK_CLIENT_ID_PLACEHOLDER', 'FACEBOOK_CLIENT_SECRET_PLACEHOLDER', 'https://your-domain-url.com/checklogin/facebook/callback-facebook', 'web_host', '<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>​​​​​​​</p>', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'en', 'en', 'usd', '', 0, 2, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 8, '<script type=\"text/javascript\">\n                var Tawk_API = Tawk_API || {},\n                    Tawk_LoadStart = new Date();\n                (function() {\n                    var s1 = document.createElement(\"script\"),\n                        s0 = document.getElementsByTagName(\"script\")[0];\n                    s1.async = true;\n                    s1.src =\n                        \'https://embed.tawk.to/65d7258a9131ed19d9700056/1hn86l9qi\';\n                    s1.charset = \'UTF-8\';\n                    s1.setAttribute(\'crossorigin\', \'*\');\n                    s0.parentNode.insertBefore(s1, s0);\n                })();\n            </script>', 1, 'order_success-65e0332a52176.png', 'no_data-65e57727a3554.png', 'maintenance-6992d92192191.jpeg', 'store_unavailable-6992d92194b55.jpeg', '<script id=\"chat-init\" src=\"https://app.wizzchat.com/account/js/init.js?id=6505747\"></script>', 1, 1, 1, 'Holly J.', 'Hey there 👋 Need help? I\'m here for you, so just give me a call.', '+919016996697', 2, 'quick-call-66d9bf8d1a601.png', 1, 1, 3000, 2000, 1, 0, NULL, 0, 0, 0, NULL, '', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Dear {user},\r\n\r\nYour Temporary Password Is : {password}', 'Dear {vendorname},\r\n\r\nWe hope this message finds you well. We regret to inform you that your account has been deleted', 'Dear {vendorname},\r\n\r\nWe hope this email finds you well. We are writing to confirm that we have received your recent subscription request and payment via bank transfer. We appreciate your business and thank you for choosing our services.\r\n\r\nWe are currently processing your subscription request and will be in touch with you shortly. Depending on the nature of the subscription, you may receive further instructions, access to a service, or confirmation of your subscription.\r\n\r\nIf you have any questions or concerns, please do not hesitate to reach out to us. Our customer support team is available to assist you with any inquiries you may have.\r\n\r\nThank you again for choosing our services. We look forward to providing you with the best possible experience.\r\n\r\nSincerely\r\n{adminname}\r\n{adminemail}', 'Dear {vendorname},\r\n\r\nWe hope this email finds you well. We are writing to confirm that we have received your recent subscription request and payment via COD. We appreciate your business and thank you for choosing our services.\r\n\r\nWe are currently processing your subscription request and will be in touch with you shortly. Depending on the nature of the subscription, you may receive further instructions, access to a service, or confirmation of your subscription.\r\n\r\nIf you have any questions or concerns, please do not hesitate to reach out to us. Our customer support team is available to assist you with any inquiries you may have.\r\n\r\nThank you again for choosing our services. We look forward to providing you with the best possible experience.\r\n\r\nSincerely\r\n{adminname}\r\n{adminemail}', 'Dear {vendorname},\r\n\r\nI am writing to inform you that your recent {payment_type} request has been rejected. After careful review of your account and the transaction, we have identified a some issues.\r\n\r\nHere are the details of your purchase\r\n\r\nSubscription Plans : {plan_name}\r\nPayment Type : {payment_type}\r\n\r\nYou can take benefits of our online payment system\r\n\r\nIf you have any questions or concerns regarding your subscription, please do not hesitate to contact our customer support team. We are always available to assist you with any queries you may have.\r\n\r\nSincerely\r\n{adminname}\r\n{adminemail}', 'Dear {vendorname},\r\n\r\nI hope this email finds you well. I am writing to confirm your recent subscription purchase with our company.\r\n\r\nWe are thrilled to have you as a subscriber and we appreciate your trust in us. Your subscription will provide you access to our premium services, exclusive content and special offers throughout the duration of your subscription period.\r\n\r\nHere are the details of your purchase\r\n\r\nSubscription Plans :  {plan_name}\r\nSubscription Duration : {subscription_duration}\r\nSubscription Cost : {subscription_price}\r\nPayment Type : {payment_type}\r\n\r\nYour subscription is now active and you can start enjoying the benefits of our services right away. You can log in to your account using the email address and password you provided during registration.\r\n\r\nIf you have any questions or concerns regarding your subscription, please do not hesitate to contact our customer support team. We are always available to assist you with any queries you may have.\r\n\r\nThank you once again for choosing us as your preferred service provider. We look forward to providing you with the best experience possible.\r\n\r\nSincerely\r\n{adminname}\r\n{adminemail}', 'Dear {adminname},\r\n\r\nYou have received new subscription request from {vendorname} and the email is {vendoremail}\r\n\r\nLogin to your account and check the details. You may Approve OR Reject\r\n\r\nHere are the details\r\n\r\nSubscription Plans : {plan_name}\r\n\r\nSubscription Duration : {subscription_duration}\r\n\r\nSubscription Cost : {subscription_price}\r\n\r\nPayment Type : {payment_type}', 'Dear {adminname},\r\n\r\nI am writing to inform you that a new subscription has been purchased for our service. The details of the subscription are as follows:\r\n\r\nName of Subscriber : {vendorname}\r\nSubscription Plans : {plan_name}\r\nSubscription Duration : {subscription_duration}\r\nSubscription Cost : {subscription_price}\r\nPayment Type : {payment_type}\r\n\r\nThe payment for the subscription has been successfully processed, and the subscriber is now able to access the features of their subscription.\r\n\r\nBest Regards\r\n{vendorname}\r\n{vendoremail}', 'Dear {vendorname},\r\n\r\nThank you for choosing to join our vibrant community! We\'re thrilled to have you on board and want to extend a warm welcome to you.', 'Dear {adminname},\r\n\r\nI am writing to inform you that new vendor registration has been done successfully.\r\n\r\nName : {vendorname}\r\nEmail : {vendoremail}\r\nMobile : {vendormobile}', 'Dear {vendorname},\r\n\r\nWe hope this message finds you well. We regret to inform you that your account has been suspended', 'Dear {vendorname},\n\nYou have received new inquiry\n\nFull Name : {username}\n\nEmail : {useremail}\n\nMobile : {usermobile}\n\nMessage : {usermessage}', 'Dear {customername},\n\nWe are pleased to confirm that we have received your Order.\n\nOrder details\n\nOrder number : #{ordernumber}\nOrder Date : {date}\nGrand Total : {grandtotal}\n\nClick Here : {track_order_url}\n\nThank you for choosing.\n\nSincerely,\n{vendorname}', 'Dear {vendorname},\n\nWe are writing to confirm that you have received new Order.\n\nOrder details\n\nOrder number : #{ordernumber}\nOrder Date : {date}\nGrand Total : {grandtotal}\n\nSincerely,\n{customername}', 'Dear {customername},\n\nI am writing to inform you that {status_message}\n\nSincerely\n{vendorname}', 'admin_auth-6992d92197041.jpeg', '2023-07-26 21:04:57', '2026-02-16 03:26:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_area`
--

CREATE TABLE `shipping_area` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `reorder_id` int(11) DEFAULT NULL,
  `area_name` varchar(255) NOT NULL,
  `delivery_charge` double NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1 = yes, 2 = no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `icon` text NOT NULL,
  `link` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_category`
--

CREATE TABLE `store_category` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `systemaddons`
--

CREATE TABLE `systemaddons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unique_identifier` varchar(255) NOT NULL,
  `version` varchar(20) NOT NULL,
  `activated` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `systemaddons`
--

INSERT INTO `systemaddons` (`id`, `name`, `unique_identifier`, `version`, `activated`, `image`, `type`, `created_at`, `updated_at`) VALUES
(2, 'Blogs', 'blog', '4.5', 1, 'blog.jpg', 1, '2025-04-02 11:47:41', '2024-12-10 06:05:21'),
(3, 'Coupons', 'coupon', '4.5', 1, 'coupons.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(6, 'Language Translation', 'language', '4.5', 1, 'language.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(8, 'Sound Notification', 'notification', '4.5', 1, 'notification.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(10, 'Personalised Store Link', 'unique_slug', '4.5', 1, 'unique_slug.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(12, 'Whatsapp Message (Manual)', 'whatsapp_message', '4.5', 1, 'whatsapp_message.jpg', 1, '2025-04-02 11:47:41', '2025-06-28 06:02:29'),
(18, 'Subscription', 'subscription', '4.5', 1, 'subscription.jpg', 1, '2025-04-02 11:47:41', '2025-07-03 00:46:18'),
(20, 'Cookie Consent', 'cookie', '4.5', 1, 'cookie.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(40, 'Grocery Theme', 'theme_1', '4.5', 1, 'theme_1.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41'),
(52, 'Store Reviews', 'store_reviews', '4.5', 1, 'store_reviews.jpg', 1, '2025-04-02 11:47:41', '2024-05-28 11:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

CREATE TABLE `tax` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `tax` varchar(255) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_message`
--

CREATE TABLE `telegram_message` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `item_message` longtext NOT NULL,
  `telegram_message` longtext NOT NULL,
  `order_created` int(11) NOT NULL,
  `telegram_access_token` text NOT NULL,
  `telegram_chat_id` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `telegram_message`
--

INSERT INTO `telegram_message` (`id`, `vendor_id`, `item_message`, `telegram_message`, `order_created`, `telegram_access_token`, `telegram_chat_id`, `created_at`, `updated_at`) VALUES
(1, 1, '🔵 {item_name}{variantsdata} ({item_price} * {qty}) = {total}', 'Hi, \nI would like to place an order 👇\n\nOrder No: {order_no}\n---------------------------\n{item_variable}\n---------------------------\n👉Subtotal : {sub_total}\n{total_tax}\n👉Delivery charge : {delivery_charge}\n👉Discount : - {discount_amount}\n---------------------------\n📃 Total : {grand_total}\n📃 Tips : {tips}\n---------------------------\n📄 Comment : {notes}\n✅ Customer Info\n---------------------------\nCustomer name : {customer_name}\nCustomer email: {customer_email}\nCustomer phone : {customer_mobile}\n---------------------------\n📍 Billing Details\nAddress : {billing_address}, {billing_landmark}, {billing_postal_code}, {billing_city}, {billing_state}, {billing_country}.\n---------------------------\n📍 Shipping Details\nAddress : {shipping_address}, {shipping_landmark}, {shipping_postal_code}, {shipping_city}, {shipping_state}, {shipping_country}.\n---------------------------\n👉 Payment status : {payment_status}\n💳 Payment type : {payment_type}\n\n{store_name} will confirm your order upon receiving the message.\n\nTrack your order 👇\n{track_order_url}\n\nClick here for next order 👇\n{store_url}\n\nThanks for the Order 🥳', 1, '5500991005:AAE_2nAxls6jkJmVjKoun_IjZd3N6b-NJX0', '756897635', '2025-03-28 06:35:07', '2025-03-28 12:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `terms_content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `star` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `reorder_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`id`, `reorder_id`, `vendor_id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Grocery Store Theme', 'theme-65e328015592f.webp', '2023-08-14 10:24:27', '2024-10-21 11:23:45'),
(2, 4, 1, 'Restaurant Theme', 'theme-65e328091ad08.webp', '2023-08-14 10:34:59', '2025-02-27 18:07:30'),
(3, 5, 1, 'Pharmacy Theme', 'theme-65e328103351a.webp', '2023-08-14 10:35:12', '2025-02-27 18:07:30'),
(4, 3, 1, 'Plants Theme', 'theme-65e32818449a1.webp', '2023-08-14 10:35:27', '2025-02-27 18:07:30'),
(5, 6, 1, 'Liquor Shop Theme', 'theme-65e3282353bf7.webp', '2023-08-14 10:35:39', '2025-02-27 18:07:30'),
(7, 7, 1, 'Furniture Store Theme', 'theme-65e3282c7e830.webp', '2024-01-18 16:14:53', '2025-02-27 18:07:30'),
(8, 8, 1, 'Kids Store Theme', 'theme-65e32833aaf58.webp', '2024-01-18 16:15:08', '2025-02-27 18:07:30'),
(9, 9, 1, 'Bakery Shop Theme', 'theme-65e3283b7926e.webp', '2024-01-18 16:15:21', '2025-02-27 18:07:30'),
(10, 10, 1, 'Clothes Store Theme', 'theme-65e3284488433.webp', '2024-01-18 16:15:36', '2025-02-27 18:07:30'),
(11, 10, 1, 'Gadgets Store Theme', 'theme-65e3284b1a0ed.webp', '2024-01-18 16:15:46', '2024-03-04 11:01:25'),
(12, 2, 1, 'Book Store Theme', 'theme-674fe97d4a200.png', '2024-12-04 11:02:45', '2025-02-27 18:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `timings`
--

CREATE TABLE `timings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `day` varchar(50) NOT NULL,
  `open_time` varchar(30) NOT NULL,
  `break_start` varchar(255) NOT NULL,
  `break_end` varchar(255) NOT NULL,
  `close_time` varchar(30) NOT NULL,
  `is_always_close` tinyint(1) NOT NULL COMMENT '1 For Yes, 2 For No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `top_deals`
--

CREATE TABLE `top_deals` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `offer_type` int(11) NOT NULL,
  `deal_type` int(11) NOT NULL COMMENT '1=one time,2=daily',
  `top_deals_switch` int(11) NOT NULL DEFAULT 2 COMMENT '1=yes,2=no',
  `offer_amount` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `user_id` text DEFAULT NULL,
  `order_id` text DEFAULT NULL,
  `order_number` text DEFAULT NULL,
  `transaction_number` varchar(255) DEFAULT NULL,
  `transaction_type` text NOT NULL COMMENT '1 = added-money-wallet,\r\n2 = order placed (using wallet),\r\n3 = order cancel ,\r\n',
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL COMMENT 'payment_type = COD : 1,RazorPay : 2, Stripe : 3, Flutterwave : 4, Paystack : 5, Mercado Pago : 7, PayPal : 8, MyFatoorah : 9, toyyibpay : 10 , \r\nwallet:16\r\n ',
  `payment_id` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `tips` varchar(11) DEFAULT NULL,
  `grand_total` float NOT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `offer_amount` float DEFAULT NULL,
  `offer_code` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL COMMENT '1=1 Month,\r\n2=3Month\r\n3=6 Month\r\n4=1 Year',
  `days` int(11) DEFAULT NULL,
  `purchase_date` varchar(255) NOT NULL,
  `service_limit` varchar(255) NOT NULL,
  `appoinment_limit` varchar(255) NOT NULL,
  `custom_domain` int(11) NOT NULL COMMENT '1 = yes, 2 = no',
  `google_analytics` int(11) NOT NULL COMMENT '1 = yes, 2 = no',
  `pos` int(11) NOT NULL COMMENT '1 = yes, 2 = no',
  `vendor_app` int(11) NOT NULL COMMENT '1 = Yes , 2 = No',
  `customer_app` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `role_management` int(11) DEFAULT NULL COMMENT '1=yes,2=no',
  `pwa` int(11) DEFAULT NULL,
  `coupons` int(11) DEFAULT NULL,
  `themes` int(11) NOT NULL,
  `expire_date` varchar(255) NOT NULL,
  `themes_id` varchar(255) DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 = pending, 2 = yes/BankTransferAccepted,3=no/BankTransferDeclined',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `blogs` int(11) DEFAULT NULL,
  `google_login` int(11) DEFAULT NULL,
  `facebook_login` int(11) NOT NULL,
  `sound_notification` int(11) DEFAULT NULL,
  `whatsapp_message` int(11) DEFAULT NULL,
  `telegram_message` int(11) DEFAULT NULL,
  `pixel` int(11) DEFAULT NULL,
  `features` varchar(255) DEFAULT NULL,
  `tax_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_text` text DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `login_type` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=Admin,2=vendor,4=driver,3=User/Customer',
  `description` text DEFAULT NULL,
  `token` longtext DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `plan_id` varchar(255) DEFAULT NULL,
  `purchase_amount` varchar(255) DEFAULT NULL,
  `purchase_date` varchar(255) DEFAULT NULL,
  `available_on_landing` int(11) NOT NULL DEFAULT 2 COMMENT '1 = Yes , 2 = No',
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `free_plan` int(11) NOT NULL DEFAULT 0,
  `is_delivery` tinyint(1) DEFAULT NULL COMMENT '1=Yes,2=No',
  `allow_without_subscription` int(11) NOT NULL DEFAULT 2 COMMENT '1=Yes,2=No',
  `is_verified` tinyint(1) NOT NULL COMMENT '1=Yes,2=No',
  `is_available` tinyint(1) NOT NULL COMMENT '1=Yes,2=No',
  `is_deleted` int(11) NOT NULL DEFAULT 2 COMMENT '1=yes,2=no',
  `remember_token` varchar(100) DEFAULT NULL,
  `license_type` text DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `wallet` varchar(11) NOT NULL DEFAULT '0',
  `custom_domain` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `store_id`, `name`, `slug`, `email`, `mobile`, `image`, `password`, `password_text`, `google_id`, `facebook_id`, `login_type`, `type`, `description`, `token`, `country_id`, `city_id`, `plan_id`, `purchase_amount`, `purchase_date`, `available_on_landing`, `payment_id`, `payment_type`, `free_plan`, `is_delivery`, `allow_without_subscription`, `is_verified`, `is_available`, `is_deleted`, `remember_token`, `license_type`, `role_id`, `vendor_id`, `wallet`, `custom_domain`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin', NULL, 'admin@gmail.com', '+919016996697', 'profile-6992d97500067.jpeg', '$2y$10$NGfPKp30kyW6d17AlYWy6.UsuIU9Fu24SF3sCaSCLEcyDh.UEbsS2', NULL, NULL, NULL, 'normal', 1, NULL, 'cNjSsm-TREC9n58ZQeIDBL:APA91bHSLQ2S9VFhM2yGoQJG7d-noXkcsVXRQi8Y-XSUJIFZQgzF75Kbu5beKH8dGUZ9SqIND3yauVdcG6-SwcVjU4oIjpJUx5JC9cORZp-NvPtNkJT1IMLb0KgnN68UWAtzwvri8KqT', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 0, NULL, 2, 2, 1, 2, 'f9g1bbvuV5bFPKvM4r8MJKHnrF27SpcnzQFyAcMbvgluENc9TFFh9GOBqeIO', 'Extended License', 0, 0, '0', NULL, '2022-08-15 23:01:17', '2026-02-16 03:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `original_price` float DEFAULT 0,
  `qty` int(11) DEFAULT 0,
  `min_order` int(11) DEFAULT NULL,
  `max_order` int(11) DEFAULT NULL,
  `low_qty` int(11) DEFAULT NULL,
  `stock_management` int(11) NOT NULL COMMENT '1=Yes,2=No',
  `is_available` int(11) NOT NULL DEFAULT 1 COMMENT '1=Yes,2=No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_message`
--

CREATE TABLE `whatsapp_message` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `item_message` longtext NOT NULL,
  `order_whatsapp_message` longtext DEFAULT NULL,
  `order_status_message` longtext DEFAULT NULL,
  `whatsapp_number` varchar(255) NOT NULL,
  `whatsapp_phone_number_id` varchar(255) NOT NULL,
  `whatsapp_access_token` longtext NOT NULL,
  `whatsapp_chat_on_off` int(11) NOT NULL,
  `whatsapp_mobile_view_on_off` int(11) NOT NULL,
  `whatsapp_chat_position` int(11) NOT NULL DEFAULT 1 COMMENT '1=left, 2=right',
  `order_created` int(11) NOT NULL COMMENT '1 = Yes , 2 = No',
  `status_change` int(11) NOT NULL COMMENT '1 = Yes , 2 = No',
  `message_type` int(11) NOT NULL COMMENT '1 = automatic_using_api , 2 = manually	',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `whatsapp_message`
--

INSERT INTO `whatsapp_message` (`id`, `vendor_id`, `item_message`, `order_whatsapp_message`, `order_status_message`, `whatsapp_number`, `whatsapp_phone_number_id`, `whatsapp_access_token`, `whatsapp_chat_on_off`, `whatsapp_mobile_view_on_off`, `whatsapp_chat_position`, `order_created`, `status_change`, `message_type`, `created_at`, `updated_at`) VALUES
(1, 1, '🔵 {item_name}{variantsdata} ({item_price} * {qty}) = {total}', 'Hi, \nI would like to place an order 👇\n\nOrder No: {order_no}\n---------------------------\n{item_variable}\n---------------------------\n👉Subtotal : {sub_total}\n{total_tax}\n👉Delivery charge : {delivery_charge}\n👉Discount : - {discount_amount}\n---------------------------\n📃 Total : {grand_total}\n📃 Tips : {tips} \n---------------------------\n📄 Comment : {notes}\n✅ Customer Info\n---------------------------\nCustomer name : {customer_name}\nCustomer email: {customer_email}\nCustomer phone : {customer_mobile}\n---------------------------\n📍 Billing Details\nAddress : {billing_address}, {billing_landmark}, {billing_postal_code}, {billing_city}, {billing_state}, {billing_country}.\n---------------------------\n📍 Shipping Details\nAddress : {shipping_address}, {shipping_landmark}, {shipping_postal_code}, {shipping_city}, {shipping_state}, {shipping_country}.\n---------------------------\n👉 Payment status : {payment_status}\n💳 Payment type : {payment_type}\n\n{store_name} will confirm your order upon receiving the message.\n\nTrack your order 👇\n{track_order_url}\n\nClick here for next order 👇\n{store_url}\n\nThanks for the Order 🥳', '🛍️ Order Status Update 📦\r\n\r\nHello {customer_name},\r\n\r\nWe\'re excited to share the latest status of your order with us. Here are the details:\r\n\r\n📝 Order Number: #{order_no}\r\n\r\n📦 **Order Status**: {status}\r\n\r\n📌 **Tracking Information**:\r\n   - You can track your order with the tracking number: #{order_no}.\r\n   - Tracking Link: {track_order_url}\r\n\r\nIf you have any questions or need assistance, feel free to reply to this message.\r\n\r\nWe appreciate your business and hope you enjoy your purchase.\r\n\r\nBest regards', '919016996697', '109087992245712', 'EAAVIMtjwDLUBO0GDmKiv9ZA7sc6VCjIQoZCqT1a5rZCj3orHVrJImr9YncYAQkV3S96V0ZCfuS4qXbN9Kt6ZB6Od0ZAKZAMxRZAYxcY0vulRc77kCKAoOaZCjKCsZAp0ZAuVRKbaFWomvbTdUgdZBGrPFYzjquH0V9kibR8KLA8ZArjnESZCjYurRBeGNnO9pIIdR3ZCeyvEnOETZAfhGm5dOnb7DI8c3ZCZCbWJYiJKrDzfXyISZAAxfAZD', 1, 1, 1, 1, 1, 2, '2025-06-28 07:07:17', '2025-06-28 01:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `whoweare`
--

CREATE TABLE `whoweare` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `reorder_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `age_verification`
--
ALTER TABLE `age_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_image`
--
ALTER TABLE `banner_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_item_id_foreign` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_settings`
--
ALTER TABLE `currency_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_domain`
--
ALTER TABLE `custom_domain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_status`
--
ALTER TABLE `custom_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firebase`
--
ALTER TABLE `firebase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footerfeatures`
--
ALTER TABLE `footerfeatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_fact`
--
ALTER TABLE `fun_fact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_extras`
--
ALTER TABLE `global_extras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `howitworks`
--
ALTER TABLE `howitworks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landing_settings`
--
ALTER TABLE `landing_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
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
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_settings`
--
ALTER TABLE `other_settings`
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
-- Indexes for table `pixcel_settings`
--
ALTER TABLE `pixcel_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacypolicy`
--
ALTER TABLE `privacypolicy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotionalbanner`
--
ALTER TABLE `promotionalbanner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_access`
--
ALTER TABLE `role_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_manager`
--
ALTER TABLE `role_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_area`
--
ALTER TABLE `shipping_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_category`
--
ALTER TABLE `store_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systemaddons`
--
ALTER TABLE `systemaddons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax`
--
ALTER TABLE `tax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram_message`
--
ALTER TABLE `telegram_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timings`
--
ALTER TABLE `timings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_deals`
--
ALTER TABLE `top_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whatsapp_message`
--
ALTER TABLE `whatsapp_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whoweare`
--
ALTER TABLE `whoweare`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `age_verification`
--
ALTER TABLE `age_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `banner_image`
--
ALTER TABLE `banner_image`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=797;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `currency_settings`
--
ALTER TABLE `currency_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `custom_domain`
--
ALTER TABLE `custom_domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_status`
--
ALTER TABLE `custom_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=505;

--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=947;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `firebase`
--
ALTER TABLE `firebase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `footerfeatures`
--
ALTER TABLE `footerfeatures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `fun_fact`
--
ALTER TABLE `fun_fact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `global_extras`
--
ALTER TABLE `global_extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `howitworks`
--
ALTER TABLE `howitworks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=578;

--
-- AUTO_INCREMENT for table `landing_settings`
--
ALTER TABLE `landing_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `other_settings`
--
ALTER TABLE `other_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pixcel_settings`
--
ALTER TABLE `pixcel_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `privacypolicy`
--
ALTER TABLE `privacypolicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2209;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `promotionalbanner`
--
ALTER TABLE `promotionalbanner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `question_answer`
--
ALTER TABLE `question_answer`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_access`
--
ALTER TABLE `role_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2215;

--
-- AUTO_INCREMENT for table `role_manager`
--
ALTER TABLE `role_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shipping_area`
--
ALTER TABLE `shipping_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `store_category`
--
ALTER TABLE `store_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `systemaddons`
--
ALTER TABLE `systemaddons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tax`
--
ALTER TABLE `tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `telegram_message`
--
ALTER TABLE `telegram_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `timings`
--
ALTER TABLE `timings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `top_deals`
--
ALTER TABLE `top_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1912;

--
-- AUTO_INCREMENT for table `whatsapp_message`
--
ALTER TABLE `whatsapp_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `whoweare`
--
ALTER TABLE `whoweare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
