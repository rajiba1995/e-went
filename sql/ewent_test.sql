-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 07:28 AM
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
-- Database: `ewent_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `role`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Rajib Ali Khan', 'Super Admin', 'admin@gmail.com', '$2y$12$NWGYDt34Sqok3efRp.ZLM.MwUkMQHU/jbmh0IvUqpUuZ51oCXyCOO', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_ratings`
--

CREATE TABLE `admin_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_vehicles`
--

CREATE TABLE `assigned_vehicles` (
  `id` bigint(11) NOT NULL,
  `order_item_id` bigint(11) UNSIGNED NOT NULL,
  `vehicle_id` int(11) UNSIGNED NOT NULL,
  `status` enum('assigned','returned','cancelled','sold') NOT NULL DEFAULT 'assigned',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_vehicles`
--

INSERT INTO `assigned_vehicles` (`id`, `order_item_id`, `vehicle_id`, `status`, `assigned_at`) VALUES
(1, 34, 30, 'assigned', '2025-02-16 03:49:56'),
(5, 32, 29, 'returned', '2025-02-16 04:30:34'),
(6, 31, 25, 'returned', '2025-02-16 04:44:27'),
(7, 30, 25, 'sold', '2025-02-16 04:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'banner 1', 'storage/uploads/banner/3946_1737965784.jpg', 1, '2025-01-27 02:46:24', '2025-01-27 02:46:24'),
(2, 'banner 2', 'storage/uploads/banner/5316_1737965828.jpg', 1, '2025-01-27 02:47:08', '2025-01-27 02:47:08'),
(3, 'banner 3', 'storage/uploads/banner/6633_1737965844.jpg', 1, '2025-01-27 02:47:24', '2025-01-27 02:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'bike', NULL, 1, NULL, '2025-01-27 02:54:19', '2025-01-27 02:54:19'),
(2, 'Scooty', NULL, 1, NULL, '2025-01-27 02:54:26', '2025-01-27 02:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`, `country`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kolkata', 2, 'India', 1, '2025-02-13 07:19:23', '2025-02-13 07:19:23'),
(2, 'Howrah', 2, 'India', 1, '2025-02-13 07:19:23', '2025-02-22 19:51:58'),
(3, 'Durgapur', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:11'),
(4, 'Asansol', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:16'),
(5, 'Siliguri', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:14'),
(6, 'Kharagpur', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:17'),
(7, 'Darjeeling', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:18'),
(8, 'Bardhaman', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:19'),
(9, 'Malda', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:21'),
(10, 'Cooch Behar', 2, 'India', 0, '2025-02-13 07:19:23', '2025-02-13 02:15:22');

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
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(5, 'What does \"Keyless Ride\" mean for electric bikes?', '<ul>\n	<li>Start and stop the bike without a physical key.</li>\n	<li>Unlock and ride using your phone or a secure code.</li>\n</ul>\n', '2025-01-21 00:48:44', '2025-01-21 00:48:44'),
(7, ' Who is responsible for recharging the electric bike?', '<ul>\n	<li>Short-term rentals: The bike is fully charged before the rental starts.</li>\n	<li>Long-term rentals: You are responsible for recharging the bike.</li>\n	<li>A charger and instructions are provided for convenient recharging.</li>\n</ul>\n', '2025-01-21 00:49:15', '2025-01-21 00:49:15'),
(9, 'Are helmets provided with the electric bike?', '<ul>\n	<li>Yes, a helmet is provided as part of the rental.</li>\n	<li>Wearing a helmet is mandatory for your safety.</li>\n</ul>\n', '2025-01-21 00:50:09', '2025-01-21 00:50:09'),
(10, 'Is there any penalty for late return of the bike?', '<ul>\n	<li>Yes, late return penalties apply.</li>\n	<li>Details about penalties are shared during booking and in the rental agreement.</li>\n</ul>\n', '2025-01-21 00:50:52', '2025-01-21 00:50:52'),
(11, ' Can I take the electric bike to any location?', '<ul>\n	<li>Bikes have specific location boundaries.</li>\n	<li>Traveling outside designated areas may lead to penalties.</li>\n	<li>Check allowed zones before starting your ride.</li>\n</ul>\n', '2025-01-21 00:51:10', '2025-01-21 00:51:10'),
(12, 'What should I do in case of a breakdown?', '<ul>\n	<li>Contact 24/7 customer support.</li>\n	<li>We will guide you on the next steps, which may include:\n	<ul>\n		<li>Arranging a replacement bike.</li>\n		<li>Providing on-road assistance.</li>\n	</ul>\n	</li>\n</ul>\n', '2025-01-21 00:51:29', '2025-01-21 00:51:29'),
(13, 'aasa', '<p>The following agreement captures the terms and conditions of use (&quot;<strong><strong>Agreement</strong></strong>&quot;), applicable to Your use of MILAAPP.IN (&quot;<strong><strong>Web Site</strong></strong>&quot;), which promotes business between suppliers and buyers globally. It is an agreement between You as the user of the Web Site/SMTPL Services and Sarv-Megh Technology (OPC) Private Limited. (&quot;<strong><strong>SMTPL</strong></strong>&quot;). The expressions &ldquo;You&rdquo; &ldquo;Your&rdquo; or &ldquo;User(s)&rdquo; refers to any person who accesses or uses the Web Site for any purpose.<br />\n<br />\nBy subscribing to or interacting with other User(s) on or entering into negotiations in respect of sale or supply of goods or services on or using the Web Site or SMTPL Services in any manner for any purpose, You undertake and agree that You have fully read, understood and accepted the Agreement.<br />\n<br />\nIf You do not agree to or do not wish to be bound by the Agreement, You may not access or otherwise use the Web Site in any manner.</p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#i\">Web site-Merely a Venue/Platform</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ii\">Services Provided by SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iii\">User(s) Eligibility</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iv\">User(s) Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#v\">Amendment to the Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vi\">Intellectual Property Rights</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vii\">Links to Third Party Sites/Content</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#viii\">Termination</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ix\">Registered User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#x\">Data Protection/Personal Information</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xi\">Posting your Contents on SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xii\">Interactions between User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiii\">Limitations of Liability/Disclaimer</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiv\">Notices</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xv\">Governing Law and Disputes Resolutions</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xvi\">General/Miscellaneous</a></li>\n</ol>\n\n<p>&nbsp;</p>\n\n<p><br />\n<strong><strong>I.WEBSITE-MERELY A VENUE/PLATFORM</strong></strong></p>\n\n<p><br />\nThe Web Site acts as a match-making platform for User(s) to negotiate and interact with other User(s) for entering into negotiations in respect thereof for sale or supply of goods or services. SMTPL or MILAAPP.IN are not parties to any negotiations that take place between the User(s) of the Web Site and are further not parties to any agreement including an agreement for sale or supply of goods or services or otherwise, concluded between the User(s) of the Web Site.<br />\n<br />\nSMTPL does not control and is not liable in respect of or responsible for the quality, safety, genuineness, lawfulness or availability of the products or services offered for sale&nbsp;on the Web Site or the ability of the User(s) selling or supplying the goods or services to complete a sale or the ability of User(s) purchasing goods or services to complete a purchase. This agreement shall not be deemed to create any partnership, joint venture, or any other joint business relationship between SMTPL and any other party.<br />\n<br />\n<strong><strong>II. SERVICES PROVIDED BY SMTPL</strong></strong></p>\n\n<p><br />\nSMTPL provides the following services to its Customers and their respective definitions are classified here under: -<br />\n&nbsp;</p>\n\n<ul>\n	<li>&ldquo; Featured badge&quot;: It gives the User(s)s priority listing within categories of their choice as available on MILAAPP.IN, thus increasing visibility of their products.</li>\n</ul>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ul>\n	<li>&quot;Featured badge 2 and 3&quot; : It is add-on service by SMTPL which gives its User(s) priority listing in their chosen category of products. By availing this service the User(s) will get benefits of increased leads and enquiries.</li>\n	<li>&quot;Verified badge&quot;: is a seal that User(s) gets after getting its business-related documents and information verified.</li>\n	<li>&quot;Trusted by others badge&quot; : User(s) get this badge when they are added through other buyers/suppliers..</li>\n	<li>&ldquo;Supplier/Buyer Dashboard&rdquo; : User (s) can create an auction or bid on an auction by buying respective credits.</li>\n</ul>\n\n<p><br />\n<strong><strong>III.USER(S) ELIGIBILITY</strong></strong></p>\n\n<p><br />\nUser(s) represent and warrant that they have the right to avail or use the services provided by SMTPL, including but limited to the Web Site or any other services provided by SMTPL in relation to the use of the Web Site (&quot;<strong><strong>SMTPL&rsquo;s Services</strong></strong>&quot;). SMTPL&rsquo;s Services can only be availed by those individuals or business entities, including sole proprietorship firms, companies and partnerships, which are authorised under applicable law to form legally binding agreements. As such, natural persons below 18 years of age and business entities or organisations that are not authorised by law to operate in India or other countries are not authorised to avail or use SMTPL&rsquo;s Services.</p>\n\n<p><br />\n<br />\nUser(s) agree to abide by the Agreement and any other rules and regulations imposed by the applicable law from time to time.SMTPL or the website shall have no liability to the User(s) or anyone else for any content, information or any other material transmitted over SMTPL&rsquo;s Services, including any fraudulent, untrue, misleading, inaccurate, defamatory, offensive or illicit material and that the risk of damage from such material rests entirely with each User(s).The user shall do its own due diligence before entering into any transaction with other users on the website. SMTPL at it&rsquo;s sole discretion reserves the right to refuse SMTPL&rsquo;s Services to anyone at any time. SMTPL&rsquo;s Services are not available and may not be availed or used by User(s) whose Accounts have been temporarily or indefinitely suspended by SMTPL.<br />\n<br />\n<strong><strong>IV. USER(S) AGREEMENT</strong></strong></p>\n', '2025-01-25 00:29:03', '2025-01-25 00:29:03');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  `finished_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2025_01_27_075337_create_ewent_test_tables', 1),
(3, '2025_01_27_131112_create_wallet_transactions', 2),
(4, '2025_01_27_143248_create_stock_ledgers_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `discount_type` enum('flat','percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `minimum_order_amount` decimal(10,2) DEFAULT NULL,
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int(10) UNSIGNED DEFAULT NULL,
  `usage_per_user` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `coupon_code`, `discount_type`, `discount_value`, `minimum_order_amount`, `maximum_discount`, `start_date`, `end_date`, `usage_limit`, `usage_per_user`, `status`, `created_at`, `updated_at`) VALUES
(1, 'xaas', 'flat', 150.00, 5000.00, 450.00, '2025-01-27 15:04:00', '2025-01-31 15:04:00', NULL, NULL, 'active', '2025-01-27 04:04:33', '2025-01-27 04:24:16'),
(2, 'first10percentage', 'percentage', 14.00, 1000.00, NULL, '2025-01-26 16:25:00', '2025-02-28 15:25:00', NULL, NULL, 'active', '2025-01-27 04:25:40', '2025-01-27 06:28:51');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('Rent','Sell') NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','completed','cancelled','returned','ongoing','ready to pickup') NOT NULL DEFAULT 'pending',
  `offer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` enum('Card','COD','PhonePay','GooglePay','UPI','NetBanking','Wallet') NOT NULL DEFAULT 'COD',
  `payment_mode` enum('Online','Offline') NOT NULL DEFAULT 'Online',
  `payment_status` enum('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `rent_duration` int(10) UNSIGNED DEFAULT NULL,
  `rent_start_date` datetime DEFAULT NULL,
  `rent_end_date` datetime DEFAULT NULL,
  `rent_status` enum('pending','ongoing','completed','late','ready to pickup','cancelled') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_type`, `order_number`, `total_price`, `discount_amount`, `final_amount`, `quantity`, `status`, `offer_id`, `payment_type`, `payment_mode`, `payment_status`, `shipping_address`, `rent_duration`, `rent_start_date`, `rent_end_date`, `rent_status`, `created_at`, `updated_at`) VALUES
(2, 1, 'Rent', 'ORD12345', 100.00, 0.00, 0.00, 1, 'completed', 1, 'COD', 'Online', 'pending', '123 Main St, City, Country', 24, '2025-02-01 10:00:00', '2025-02-02 10:00:00', 'completed', '2025-01-22 08:03:12', '2025-01-26 07:18:25'),
(18, 1, 'Rent', 'EW-202501000010', 6498.00, 909.72, 5588.28, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'ready to pickup', '2025-01-27 06:37:43', '2025-01-27 06:37:43'),
(19, 1, 'Rent', 'EW-202501000011', 6498.00, 0.00, 6498.00, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 06:39:08', '2025-01-27 06:39:08'),
(20, 1, 'Rent', 'EW-202501000012', 6498.00, 0.00, 6498.00, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 06:39:15', '2025-01-27 06:39:15'),
(21, 1, 'Rent', 'EW-202501000013', 6498.00, 0.00, 6498.00, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 06:40:30', '2025-01-27 06:40:30'),
(22, 1, 'Rent', 'EW-202501000014', 6498.00, 909.72, 5588.28, 3, 'pending', 2, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 06:41:51', '2025-01-27 06:41:51'),
(30, 1, 'Rent', 'EW-202501000015', 6498.00, 0.00, 6498.00, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 08:21:58', '2025-01-27 08:21:58'),
(31, 1, 'Rent', 'EW-202501000016', 6498.00, 0.00, 6498.00, 3, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 08:22:20', '2025-01-27 08:22:20'),
(32, 1, 'Rent', 'EW-202501000017', 3998.00, 0.00, 3998.00, 2, 'pending', NULL, 'COD', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 08:22:47', '2025-01-27 08:22:47'),
(33, 1, 'Rent', 'EW-202501000018', 3998.00, 0.00, 3998.00, 2, 'pending', NULL, 'Wallet', 'Online', 'pending', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 08:25:19', '2025-01-27 08:25:19'),
(34, 1, 'Rent', 'EW-202501000019', 3998.00, 0.00, 3998.00, 2, 'pending', NULL, 'Wallet', 'Online', 'completed', '123 Main St, City, Country', 30, '2025-02-01 12:15:58', '2025-03-03 12:15:58', 'pending', '2025-01-27 08:28:00', '2025-01-27 08:28:00'),
(40, 1, 'Sell', 'EW-202501000020', 270000.00, 0.00, 270000.00, 2, 'pending', NULL, 'Wallet', 'Online', 'completed', '123 Main St, City, Country', NULL, NULL, NULL, NULL, '2025-01-27 08:38:40', '2025-01-27 08:38:40'),
(41, 1, 'Rent', 'EW-202502000001', 150.00, 0.00, 150.00, 1, 'pending', NULL, 'PhonePay', 'Online', 'pending', '123 Main St, City, Country', NULL, '2025-02-01 12:15:58', '2025-02-01 12:15:58', 'pending', '2025-02-19 23:19:20', '2025-02-19 23:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_addresses`
--

CREATE TABLE `order_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 1, 100.00, 100.00, NULL, NULL),
(2, 18, 2, 2, 1999.00, 3998.00, '2025-01-27 06:37:43', '2025-01-27 06:37:43'),
(3, 18, 1, 1, 2500.00, 2500.00, '2025-01-27 06:37:43', '2025-01-27 06:37:43'),
(4, 19, 2, 2, 1999.00, 3998.00, '2025-01-27 06:39:08', '2025-01-27 06:39:08'),
(5, 19, 1, 1, 2500.00, 2500.00, '2025-01-27 06:39:08', '2025-01-27 06:39:08'),
(6, 20, 2, 2, 1999.00, 3998.00, '2025-01-27 06:39:15', '2025-01-27 06:39:15'),
(7, 20, 1, 1, 2500.00, 2500.00, '2025-01-27 06:39:15', '2025-01-27 06:39:15'),
(8, 21, 2, 2, 1999.00, 3998.00, '2025-01-27 06:40:30', '2025-01-27 06:40:30'),
(9, 21, 1, 1, 2500.00, 2500.00, '2025-01-27 06:40:30', '2025-01-27 06:40:30'),
(10, 22, 2, 2, 1999.00, 3998.00, '2025-01-27 06:41:51', '2025-01-27 06:41:51'),
(11, 22, 1, 1, 2500.00, 2500.00, '2025-01-27 06:41:51', '2025-01-27 06:41:51'),
(26, 30, 2, 2, 1999.00, 3998.00, '2025-01-27 08:21:58', '2025-01-27 08:21:58'),
(27, 30, 1, 1, 2500.00, 2500.00, '2025-01-27 08:21:59', '2025-01-27 08:21:59'),
(28, 31, 2, 2, 1999.00, 3998.00, '2025-01-27 08:22:20', '2025-01-27 08:22:20'),
(29, 31, 1, 1, 2500.00, 2500.00, '2025-01-27 08:22:20', '2025-01-27 08:22:20'),
(30, 32, 2, 2, 1999.00, 3998.00, '2025-01-27 08:22:47', '2025-01-27 08:22:47'),
(31, 33, 2, 2, 1999.00, 3998.00, '2025-01-27 08:25:19', '2025-01-27 08:25:19'),
(32, 34, 2, 2, 1999.00, 3998.00, '2025-01-27 08:28:00', '2025-01-27 08:28:00'),
(34, 40, 2, 2, 135000.00, 270000.00, '2025-01-27 08:38:40', '2025-01-27 08:38:40'),
(35, 41, 1, 1, 150.00, 150.00, '2025-02-19 23:19:20', '2025-02-19 23:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_item_returns`
--

CREATE TABLE `order_item_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `return_date` datetime NOT NULL,
  `return_status` enum('on_time','late','damaged','good_condition') NOT NULL DEFAULT 'on_time',
  `return_condition` text DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_item_returns`
--

INSERT INTO `order_item_returns` (`id`, `order_item_id`, `return_date`, `return_status`, `return_condition`, `refund_amount`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-01-27 11:01:19', 'on_time', 'good condition', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `mobile` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` enum('Card','COD','PhonePay','GooglePay','UPI','NetBanking','Wallet') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'INR',
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `transaction_id`, `amount`, `currency`, `payment_date`, `created_at`, `updated_at`) VALUES
(12, 18, 'Card', 'pending', NULL, 5588.28, '₹', NULL, '2025-01-27 06:37:43', '2025-01-27 06:37:43'),
(13, 19, 'Card', 'pending', NULL, 6498.00, '₹', NULL, '2025-01-27 06:39:08', '2025-01-27 06:39:08'),
(14, 20, 'Card', 'pending', NULL, 6498.00, '₹', NULL, '2025-01-27 06:39:15', '2025-01-27 06:39:15'),
(15, 21, 'Card', 'pending', NULL, 6498.00, '₹', NULL, '2025-01-27 06:40:30', '2025-01-27 06:40:30'),
(16, 22, 'Card', 'pending', NULL, 5588.28, '₹', NULL, '2025-01-27 06:41:51', '2025-01-27 06:41:51'),
(17, 30, 'Wallet', 'pending', NULL, 6498.00, 'INR', NULL, '2025-01-27 08:21:59', '2025-01-27 08:21:59'),
(18, 31, 'Wallet', 'pending', NULL, 6498.00, 'INR', NULL, '2025-01-27 08:22:20', '2025-01-27 08:22:20'),
(19, 32, 'Wallet', 'pending', NULL, 3998.00, 'INR', NULL, '2025-01-27 08:22:47', '2025-01-27 08:22:47'),
(20, 33, 'Wallet', 'pending', NULL, 3998.00, 'INR', NULL, '2025-01-27 08:25:19', '2025-01-27 08:25:19'),
(21, 34, 'Wallet', 'pending', NULL, 3998.00, 'INR', NULL, '2025-01-27 08:28:00', '2025-01-27 08:28:00'),
(22, 40, 'Wallet', 'completed', NULL, 270000.00, 'INR', NULL, '2025-01-27 08:38:40', '2025-01-27 08:38:40'),
(23, 41, 'PhonePay', 'pending', NULL, 150.00, 'INR', NULL, '2025-02-19 23:19:21', '2025-02-19 23:19:21');

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'Ewent', '351dd070b9f5fc1424c4632dff816d030f386c124c4c5d904b5e6efdf448c315', '[\"*\"]', NULL, NULL, '2025-01-28 04:25:32', '2025-01-28 04:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `pincodes`
--

CREATE TABLE `pincodes` (
  `id` int(11) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `city_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pincodes`
--

INSERT INTO `pincodes` (`id`, `pincode`, `city_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '711204', 2, 1, '2025-02-13 01:51:06', '2025-02-13 01:51:06'),
(2, '711106', 2, 1, '2025-02-13 01:51:06', '2025-02-13 01:51:06'),
(3, '711205', 2, 1, '2025-02-13 01:51:06', '2025-02-13 01:51:06'),
(4, '711303', 2, 1, '2025-02-13 01:51:06', '2025-02-13 01:51:06'),
(6, '700007', 1, 1, '2025-02-13 01:51:06', '2025-02-13 01:51:20'),
(7, '700008', 1, 1, '2025-02-13 01:51:06', '2025-02-17 08:47:23'),
(8, '700009', 1, 1, '2025-02-13 01:51:06', '2025-02-13 01:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 'Safety', '<p>The following agreement captures the terms and conditions of use (&quot;<strong><strong>Agreement</strong></strong>&quot;), applicable to Your use of MILAAPP.IN (&quot;<strong><strong>Web Site</strong></strong>&quot;), which promotes business between suppliers and buyers globally. It is an agreement between You as the user of the Web Site/SMTPL Services and Sarv-Megh Technology (OPC) Private Limited. (&quot;<strong><strong>SMTPL</strong></strong>&quot;). The expressions &ldquo;You&rdquo; &ldquo;Your&rdquo; or &ldquo;User(s)&rdquo; refers to any person who accesses or uses the Web Site for any purpose.<br />\r\n<br />\r\nBy subscribing to or interacting with other User(s) on or entering into negotiations in respect of sale or supply of goods or services on or using the Web Site or SMTPL Services in any manner for any purpose, You undertake and agree that You have fully read, understood and accepted the Agreement.<br />\r\n<br />\r\nIf You do not agree to or do not wish to be bound by the Agreement, You may not access or otherwise use the Web Site in any manner.</p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#i\">Web site-Merely a Venue/Platform</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ii\">Services Provided by SMTPL</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iii\">User(s) Eligibility</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iv\">User(s) Agreement</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#v\">Amendment to the Agreement</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vi\">Intellectual Property Rights</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vii\">Links to Third Party Sites/Content</a></li>\r\n</ol>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#viii\">Termination</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ix\">Registered User(s)</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#x\">Data Protection/Personal Information</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xi\">Posting your Contents on SMTPL</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xii\">Interactions between User(s)</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiii\">Limitations of Liability/Disclaimer</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiv\">Notices</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xv\">Governing Law and Disputes Resolutions</a></li>\r\n</ol>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xvi\">General/Miscellaneous</a></li>\r\n</ol>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n<strong><strong>I.WEBSITE-MERELY A VENUE/PLATFORM</strong></strong></p>\r\n\r\n<p><br />\r\nThe Web Site acts as a match-making platform for User(s) to negotiate and interact with other User(s) for entering into negotiations in respect thereof for sale or supply of goods or services. SMTPL or MILAAPP.IN are not parties to any negotiations that take place between the User(s) of the Web Site and are further not parties to any agreement including an agreement for sale or supply of goods or services or otherwise, concluded between the User(s) of the Web Site.<br />\r\n<br />\r\nSMTPL does not control and is not liable in respect of or responsible for the quality, safety, genuineness, lawfulness or availability of the products or services offered for sale&nbsp;on the Web Site or the ability of the User(s) selling or supplying the goods or services to complete a sale or the ability of User(s) purchasing goods or services to complete a purchase. This agreement shall not be deemed to create any partnership, joint venture, or any other joint business relationship between SMTPL and any other party.<br />\r\n<br />\r\n<strong><strong>II. SERVICES PROVIDED BY SMTPL</strong></strong></p>\r\n\r\n<p><br />\r\nSMTPL provides the following services to its Customers and their respective definitions are classified here under: -<br />\r\n&nbsp;</p>\r\n\r\n<ul>\r\n	<li>&ldquo; Featured badge&quot;: It gives the User(s)s priority listing within categories of their choice as available on MILAAPP.IN, thus increasing visibility of their products.</li>\r\n</ul>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ul>\r\n	<li>&quot;Featured badge 2 and 3&quot; : It is add-on service by SMTPL which gives its User(s) priority listing in their chosen category of products. By availing this service the User(s) will get benefits of increased leads and enquiries.</li>\r\n	<li>&quot;Verified badge&quot;: is a seal that User(s) gets after getting its business-related documents and information verified.</li>\r\n	<li>&quot;Trusted by others badge&quot; : User(s) get this badge when they are added through other buyers/suppliers..</li>\r\n	<li>&ldquo;Supplier/Buyer Dashboard&rdquo; : User (s) can create an auction or bid on an auction by buying respective credits.</li>\r\n</ul>\r\n\r\n<p><br />\r\n<strong><strong>III.USER(S) ELIGIBILITY</strong></strong></p>\r\n\r\n<p><br />\r\nUser(s) represent and warrant that they have the right to avail or use the services provided by SMTPL, including but limited to the Web Site or any other services provided by SMTPL in relation to the use of the Web Site (&quot;<strong><strong>SMTPL&rsquo;s Services</strong></strong>&quot;). SMTPL&rsquo;s Services can only be availed by those individuals or business entities, including sole proprietorship firms, companies and partnerships, which are authorised under applicable law to form legally binding agreements. As such, natural persons below 18 years of age and business entities or organisations that are not authorised by law to operate in India or other countries are not authorised to avail or use SMTPL&rsquo;s Services.</p>\r\n\r\n<p><br />\r\n<br />\r\nUser(s) agree to abide by the Agreement and any other rules and regulations imposed by the applicable law from time to time.SMTPL or the website shall have no liability to the User(s) or anyone else for any content, information or any other material transmitted over SMTPL&rsquo;s Services, including any fraudulent, untrue, misleading, inaccurate, defamatory, offensive or illicit material and that the risk of damage from such material rests entirely with each User(s).The user shall do its own due diligence before entering into any transaction with other users on the website. SMTPL at it&rsquo;s sole discretion reserves the right to refuse SMTPL&rsquo;s Services to anyone at any time. SMTPL&rsquo;s Services are not available and may not be availed or used by User(s) whose Accounts have been temporarily or indefinitely suspended by SMTPL.<br />\r\n<br />\r\n<strong><strong>IV. USER(S) AGREEMENT</strong></strong></p>\r\n', '2025-01-25 05:59:50', '2025-01-25 05:59:50'),
(2, 'Availability', '<p>The following agreement captures the terms and conditions of use (&quot;<strong><strong>Agreement</strong></strong>&quot;), applicable to Your use of MILAAPP.IN (&quot;<strong><strong>Web Site</strong></strong>&quot;), which promotes business between suppliers and buyers globally. It is an agreement between You as the user of the Web Site/SMTPL Services and Sarv-Megh Technology (OPC) Private Limited. (&quot;<strong><strong>SMTPL</strong></strong>&quot;). The expressions &ldquo;You&rdquo; &ldquo;Your&rdquo; or &ldquo;User(s)&rdquo; refers to any person who accesses or uses the Web Site for any purpose.<br />\r\n<br />\r\nBy subscribing to or interacting with other User(s) on or entering into negotiations in respect of sale or supply of goods or services on or using the Web Site or SMTPL Services in any manner for any purpose, You undertake and agree that You have fully read, understood and accepted the Agreement.<br />\r\n<br />\r\nIf You do not agree to or do not wish to be bound by the Agreement, You may not access or otherwise use the Web Site in any manner.</p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#i\">Web site-Merely a Venue/Platform</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ii\">Services Provided by SMTPL</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iii\">User(s) Eligibility</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iv\">User(s) Agreement</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#v\">Amendment to the Agreement</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vi\">Intellectual Property Rights</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vii\">Links to Third Party Sites/Content</a></li>\r\n</ol>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#viii\">Termination</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ix\">Registered User(s)</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#x\">Data Protection/Personal Information</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xi\">Posting your Contents on SMTPL</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xii\">Interactions between User(s)</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiii\">Limitations of Liability/Disclaimer</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiv\">Notices</a></li>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xv\">Governing Law and Disputes Resolutions</a></li>\r\n</ol>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ol>\r\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xvi\">General/Miscellaneous</a></li>\r\n</ol>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n<strong><strong>I.WEBSITE-MERELY A VENUE/PLATFORM</strong></strong></p>\r\n\r\n<p><br />\r\nThe Web Site acts as a match-making platform for User(s) to negotiate and interact with other User(s) for entering into negotiations in respect thereof for sale or supply of goods or services. SMTPL or MILAAPP.IN are not parties to any negotiations that take place between the User(s) of the Web Site and are further not parties to any agreement including an agreement for sale or supply of goods or services or otherwise, concluded between the User(s) of the Web Site.<br />\r\n<br />\r\nSMTPL does not control and is not liable in respect of or responsible for the quality, safety, genuineness, lawfulness or availability of the products or services offered for sale&nbsp;on the Web Site or the ability of the User(s) selling or supplying the goods or services to complete a sale or the ability of User(s) purchasing goods or services to complete a purchase. This agreement shall not be deemed to create any partnership, joint venture, or any other joint business relationship between SMTPL and any other party.<br />\r\n<br />\r\n<strong><strong>II. SERVICES PROVIDED BY SMTPL</strong></strong></p>\r\n\r\n<p><br />\r\nSMTPL provides the following services to its Customers and their respective definitions are classified here under: -<br />\r\n&nbsp;</p>\r\n\r\n<ul>\r\n	<li>&ldquo; Featured badge&quot;: It gives the User(s)s priority listing within categories of their choice as available on MILAAPP.IN, thus increasing visibility of their products.</li>\r\n</ul>\r\n\r\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\r\n\r\n<ul>\r\n	<li>&quot;Featured badge 2 and 3&quot; : It is add-on service by SMTPL which gives its User(s) priority listing in their chosen category of products. By availing this service the User(s) will get benefits of increased leads and enquiries.</li>\r\n	<li>&quot;Verified badge&quot;: is a seal that User(s) gets after getting its business-related documents and information verified.</li>\r\n	<li>&quot;Trusted by others badge&quot; : User(s) get this badge when they are added through other buyers/suppliers..</li>\r\n	<li>&ldquo;Supplier/Buyer Dashboard&rdquo; : User (s) can create an auction or bid on an auction by buying respective credits.</li>\r\n</ul>\r\n\r\n<p><br />\r\n<strong><strong>III.USER(S) ELIGIBILITY</strong></strong></p>\r\n\r\n<p><br />\r\nUser(s) represent and warrant that they have the right to avail or use the services provided by SMTPL, including but limited to the Web Site or any other services provided by SMTPL in relation to the use of the Web Site (&quot;<strong><strong>SMTPL&rsquo;s Services</strong></strong>&quot;). SMTPL&rsquo;s Services can only be availed by those individuals or business entities, including sole proprietorship firms, companies and partnerships, which are authorised under applicable law to form legally binding agreements. As such, natural persons below 18 years of age and business entities or organisations that are not authorised by law to operate in India or other countries are not authorised to avail or use SMTPL&rsquo;s Services.</p>\r\n\r\n<p><br />\r\n<br />\r\nUser(s) agree to abide by the Agreement and any other rules and regulations imposed by the applicable law from time to time.SMTPL or the website shall have no liability to the User(s) or anyone else for any content, information or any other material transmitted over SMTPL&rsquo;s Services, including any fraudulent, untrue, misleading, inaccurate, defamatory, offensive or illicit material and that the risk of damage from such material rests entirely with each User(s).The user shall do its own due diligence before entering into any transaction with other users on the website. SMTPL at it&rsquo;s sole discretion reserves the right to refuse SMTPL&rsquo;s Services to anyone at any time. SMTPL&rsquo;s Services are not available and may not be availed or used by User(s) whose Accounts have been temporarily or indefinitely suspended by SMTPL.<br />\r\n<br />\r\n<strong><strong>IV. USER(S) AGREEMENT</strong></strong></p>\r\n', '2025-01-25 05:59:50', '2025-01-25 05:59:50'),
(3, 'Rules', '<p>The following agreement captures the terms and conditions of use (&quot;<strong><strong>Agreement</strong></strong>&quot;), applicable to Your use of MILAAPP.IN (&quot;<strong><strong>Web Site</strong></strong>&quot;), which promotes business between suppliers and buyers globally. It is an agreement between You as the user of the Web Site/SMTPL Services and Sarv-Megh Technology (OPC) Private Limited. (&quot;<strong><strong>SMTPL</strong></strong>&quot;). The expressions &ldquo;You&rdquo; &ldquo;Your&rdquo; or &ldquo;User(s)&rdquo; refers to any person who accesses or uses the Web Site for any purpose.<br />\n<br />\nBy subscribing to or interacting with other User(s) on or entering into negotiations in respect of sale or supply of goods or services on or using the Web Site or SMTPL Services in any manner for any purpose, You undertake and agree that You have fully read, understood and accepted the Agreement.<br />\n<br />\nIf You do not agree to or do not wish to be bound by the Agreement, You may not access or otherwise use the Web Site in any manner.</p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#i\">Web site-Merely a Venue/Platform</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ii\">Services Provided by SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iii\">User(s) Eligibility</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iv\">User(s) Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#v\">Amendment to the Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vi\">Intellectual Property Rights</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vii\">Links to Third Party Sites/Content</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#viii\">Termination</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ix\">Registered User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#x\">Data Protection/Personal Information</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xi\">Posting your Contents on SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xii\">Interactions between User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiii\">Limitations of Liability/Disclaimer</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiv\">Notices</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xv\">Governing Law and Disputes Resolutions</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xvi\">General/Miscellaneous</a></li>\n</ol>\n\n<p>&nbsp;</p>\n\n<p><br />\n<strong><strong>I.WEBSITE-MERELY A VENUE/PLATFORM</strong></strong></p>\n\n<p><br />\nThe Web Site acts as a match-making platform for User(s) to negotiate and interact with other User(s) for entering into negotiations in respect thereof for sale or supply of goods or services. SMTPL or MILAAPP.IN are not parties to any negotiations that take place between the User(s) of the Web Site and are further not parties to any agreement including an agreement for sale or supply of goods or services or otherwise, concluded between the User(s) of the Web Site.<br />\n<br />\nSMTPL does not control and is not liable in respect of or responsible for the quality, safety, genuineness, lawfulness or availability of the products or services offered for sale&nbsp;on the Web Site or the ability of the User(s) selling or supplying the goods or services to complete a sale or the ability of User(s) purchasing goods or services to complete a purchase. This agreement shall not be deemed to create any partnership, joint venture, or any other joint business relationship between SMTPL and any other party.<br />\n<br />\n<strong><strong>II. SERVICES PROVIDED BY SMTPL</strong></strong></p>\n\n<p><br />\nSMTPL provides the following services to its Customers and their respective definitions are classified here under: -<br />\n&nbsp;</p>\n\n<ul>\n	<li>&ldquo; Featured badge&quot;: It gives the User(s)s priority listing within categories of their choice as available on MILAAPP.IN, thus increasing visibility of their products.</li>\n</ul>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ul>\n	<li>&quot;Featured badge 2 and 3&quot; : It is add-on service by SMTPL which gives its User(s) priority listing in their chosen category of products. By availing this service the User(s) will get benefits of increased leads and enquiries.</li>\n	<li>&quot;Verified badge&quot;: is a seal that User(s) gets after getting its business-related documents and information verified.</li>\n	<li>&quot;Trusted by others badge&quot; : User(s) get this badge when they are added through other buyers/suppliers..</li>\n	<li>&ldquo;Supplier/Buyer Dashboard&rdquo; : User (s) can create an auction or bid on an auction by buying respective credits.</li>\n</ul>\n\n<p><br />\n<strong><strong>III.USER(S) ELIGIBILITY</strong></strong></p>\n\n<p><br />\nUser(s) represent and warrant that they have the right to avail or use the services provided by SMTPL, including but limited to the Web Site or any other services provided by SMTPL in relation to the use of the Web Site (&quot;<strong><strong>SMTPL&rsquo;s Services</strong></strong>&quot;). SMTPL&rsquo;s Services can only be availed by those individuals or business entities, including sole proprietorship firms, companies and partnerships, which are authorised under applicable law to form legally binding agreements. As such, natural persons below 18 years of age and business entities or organisations that are not authorised by law to operate in India or other countries are not authorised to avail or use SMTPL&rsquo;s Services.</p>\n\n<p><br />\n<br />\nUser(s) agree to abide by the Agreement and any other rules and regulations imposed by the applicable law from time to time.SMTPL or the website shall have no liability to the User(s) or anyone else for any content, information or any other material transmitted over SMTPL&rsquo;s Services, including any fraudulent, untrue, misleading, inaccurate, defamatory, offensive or illicit material and that the risk of damage from such material rests entirely with each User(s).The user shall do its own due diligence before entering into any transaction with other users on the website. SMTPL at it&rsquo;s sole discretion reserves the right to refuse SMTPL&rsquo;s Services to anyone at any time. SMTPL&rsquo;s Services are not available and may not be availed or used by User(s) whose Accounts have been temporarily or indefinitely suspended by SMTPL.<br />\n<br />\n<strong><strong>IV. USER(S) AGREEMENT</strong></strong></p>\n', '2025-01-25 01:26:45', '2025-01-25 01:26:45'),
(4, 'Booking Policy', '<p>The following agreement captures the terms and conditions of use (&quot;<strong><strong>Agreement</strong></strong>&quot;), applicable to Your use of MILAAPP.IN (&quot;<strong><strong>Web Site</strong></strong>&quot;), which promotes business between suppliers and buyers globally. It is an agreement between You as the user of the Web Site/SMTPL Services and Sarv-Megh Technology (OPC) Private Limited. (&quot;<strong><strong>SMTPL</strong></strong>&quot;). The expressions &ldquo;You&rdquo; &ldquo;Your&rdquo; or &ldquo;User(s)&rdquo; refers to any person who accesses or uses the Web Site for any purpose.<br />\n<br />\nBy subscribing to or interacting with other User(s) on or entering into negotiations in respect of sale or supply of goods or services on or using the Web Site or SMTPL Services in any manner for any purpose, You undertake and agree that You have fully read, understood and accepted the Agreement.<br />\n<br />\nIf You do not agree to or do not wish to be bound by the Agreement, You may not access or otherwise use the Web Site in any manner.</p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#i\">Web site-Merely a Venue/Platform</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ii\">Services Provided by SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iii\">User(s) Eligibility</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#iv\">User(s) Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#v\">Amendment to the Agreement</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vi\">Intellectual Property Rights</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#vii\">Links to Third Party Sites/Content</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#viii\">Termination</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#ix\">Registered User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#x\">Data Protection/Personal Information</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xi\">Posting your Contents on SMTPL</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xii\">Interactions between User(s)</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiii\">Limitations of Liability/Disclaimer</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xiv\">Notices</a></li>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xv\">Governing Law and Disputes Resolutions</a></li>\n</ol>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ol>\n	<li><a href=\"https://www.milaapp.in/terms-and-conditions#xvi\">General/Miscellaneous</a></li>\n</ol>\n\n<p>&nbsp;</p>\n\n<p><br />\n<strong><strong>I.WEBSITE-MERELY A VENUE/PLATFORM</strong></strong></p>\n\n<p><br />\nThe Web Site acts as a match-making platform for User(s) to negotiate and interact with other User(s) for entering into negotiations in respect thereof for sale or supply of goods or services. SMTPL or MILAAPP.IN are not parties to any negotiations that take place between the User(s) of the Web Site and are further not parties to any agreement including an agreement for sale or supply of goods or services or otherwise, concluded between the User(s) of the Web Site.<br />\n<br />\nSMTPL does not control and is not liable in respect of or responsible for the quality, safety, genuineness, lawfulness or availability of the products or services offered for sale&nbsp;on the Web Site or the ability of the User(s) selling or supplying the goods or services to complete a sale or the ability of User(s) purchasing goods or services to complete a purchase. This agreement shall not be deemed to create any partnership, joint venture, or any other joint business relationship between SMTPL and any other party.<br />\n<br />\n<strong><strong>II. SERVICES PROVIDED BY SMTPL</strong></strong></p>\n\n<p><br />\nSMTPL provides the following services to its Customers and their respective definitions are classified here under: -<br />\n&nbsp;</p>\n\n<ul>\n	<li>&ldquo; Featured badge&quot;: It gives the User(s)s priority listing within categories of their choice as available on MILAAPP.IN, thus increasing visibility of their products.</li>\n</ul>\n\n<p><img src=\"data:image/png;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOwICVAEAOw==\" /></p>\n\n<ul>\n	<li>&quot;Featured badge 2 and 3&quot; : It is add-on service by SMTPL which gives its User(s) priority listing in their chosen category of products. By availing this service the User(s) will get benefits of increased leads and enquiries.</li>\n	<li>&quot;Verified badge&quot;: is a seal that User(s) gets after getting its business-related documents and information verified.</li>\n	<li>&quot;Trusted by others badge&quot; : User(s) get this badge when they are added through other buyers/suppliers..</li>\n	<li>&ldquo;Supplier/Buyer Dashboard&rdquo; : User (s) can create an auction or bid on an auction by buying respective credits.</li>\n</ul>\n\n<p><br />\n<strong><strong>III.USER(S) ELIGIBILITY</strong></strong></p>\n\n<p><br />\nUser(s) represent and warrant that they have the right to avail or use the services provided by SMTPL, including but limited to the Web Site or any other services provided by SMTPL in relation to the use of the Web Site (&quot;<strong><strong>SMTPL&rsquo;s Services</strong></strong>&quot;). SMTPL&rsquo;s Services can only be availed by those individuals or business entities, including sole proprietorship firms, companies and partnerships, which are authorised under applicable law to form legally binding agreements. As such, natural persons below 18 years of age and business entities or organisations that are not authorised by law to operate in India or other countries are not authorised to avail or use SMTPL&rsquo;s Services.</p>\n\n<p><br />\n<br />\nUser(s) agree to abide by the Agreement and any other rules and regulations imposed by the applicable law from time to time.SMTPL or the website shall have no liability to the User(s) or anyone else for any content, information or any other material transmitted over SMTPL&rsquo;s Services, including any fraudulent, untrue, misleading, inaccurate, defamatory, offensive or illicit material and that the risk of damage from such material rests entirely with each User(s).The user shall do its own due diligence before entering into any transaction with other users on the website. SMTPL at it&rsquo;s sole discretion reserves the right to refuse SMTPL&rsquo;s Services to anyone at any time. SMTPL&rsquo;s Services are not available and may not be availed or used by User(s) whose Accounts have been temporarily or indefinitely suspended by SMTPL.<br />\n<br />\n<strong><strong>IV. USER(S) AGREEMENT</strong></strong></p>\n', '2025-01-25 01:27:07', '2025-01-25 01:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `stock` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1: Stock in, 0:Stock Out',
  `title` varchar(255) NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `types` varchar(255) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `long_desc` longtext DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_selling` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:inactive, 1:active',
  `base_price` decimal(10,2) DEFAULT NULL,
  `display_price` decimal(10,2) DEFAULT NULL,
  `is_rent` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:inactive, 1:active',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_bestseller` tinyint(4) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(4) NOT NULL DEFAULT 1,
  `is_featured` tinyint(4) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `stock_qty`, `stock`, `title`, `product_sku`, `position`, `types`, `short_desc`, `long_desc`, `category_id`, `sub_category_id`, `image`, `is_selling`, `base_price`, `display_price`, `is_rent`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `is_bestseller`, `is_new_arrival`, `is_featured`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 744, 1, 'Bajaj Electric Scooter With Lithium Ion Battery', 'BESWLIB', NULL, '', '<ul>\n	<li>Specifications: 113 kms range in single charge; 63 kmph top speed</li>\n	<li>Hasslefree: Charge your Chetak with the offbaord charger using any normal 220V, 15A, 3 pin earthed socket.</li>\n	<li>Thudproof &amp; splashproof: Seamless steel unibody that is built to last with IP67 rating for water resistance</li>\n	<li>Stressproof: Future ready with key FOB; Bluetooth; Call Accept/Reject &ndash; all via the Chetak app!</li>\n	<li>Other Features: Colour LCD Display with Digital speedometer, odometer, and tripmeter; Touch Sensitive Switches; Braking: Front &amp; Rear Drum Brakes; Motor Type: PMSM; Storage: Glove Box 5.5 L, Underseat storage 21 L</li>\n	<li>Additional features with TecPac - Top speed of 73 kmph with 3 ride modes (2 Forward - Eco and Sports and 1 Reverse); Hill-hold Assist; Geo-fencing; Trip Data Analytics. The scooter is activated with TecPac for upto 1000 km / 30 days from invoice date as a trial pack, after which it will be de-activated. *Subscription of TecPac has to be purchased additionally, directly from the dealer</li>\n</ul>\n', '<ul>\n	<li>Specifications: 113 kms range in single charge; 63 kmph top speed</li>\n	<li>Hasslefree: Charge your Chetak with the offbaord charger using any normal 220V, 15A, 3 pin earthed socket.</li>\n	<li>Thudproof &amp; splashproof: Seamless steel unibody that is built to last with IP67 rating for water resistance</li>\n	<li>Stressproof: Future ready with key FOB; Bluetooth; Call Accept/Reject &ndash; all via the Chetak app!</li>\n	<li>Other Features: Colour LCD Display with Digital speedometer, odometer, and tripmeter; Touch Sensitive Switches; Braking: Front &amp; Rear Drum Brakes; Motor Type: PMSM; Storage: Glove Box 5.5 L, Underseat storage 21 L</li>\n	<li>Additional features with TecPac - Top speed of 73 kmph with 3 ride modes (2 Forward - Eco and Sports and 1 Reverse); Hill-hold Assist; Geo-fencing; Trip Data Analytics. The scooter is activated with TecPac for upto 1000 km / 30 days from invoice date as a trial pack, after which it will be de-activated. *Subscription of TecPac has to be purchased additionally, directly from the dealer</li>\n</ul>\n', 2, 5, 'storage/uploads/product/2140_1737967692.png', 1, 85000.00, 75000.00, 1, NULL, NULL, NULL, 1, 0, 1, 1, NULL, '2025-01-27 03:18:12', '2025-02-19 23:19:21'),
(2, 1997, 1, 'Kinectic Electric Scooter', 'KEST', NULL, 'Waterproof,Lightweight,Chargeable', '<p>Kinetic Electric Scooter.&nbsp;A budget-friendly, low-speed scooter with a top speed of 25 km/h.&nbsp;It&#39;s designed for young riders and doesn&#39;t require a license or registration.&nbsp;Some say it&#39;s a good choice for city commuting, with a responsive handling and a peppy engine.&nbsp;</p>\n', '<p>Kinetic Electric Scooter.&nbsp;A budget-friendly, low-speed scooter with a top speed of 25 km/h.&nbsp;It&#39;s designed for young riders and doesn&#39;t require a license or registration.&nbsp;Some say it&#39;s a good choice for city commuting, with a responsive handling and a peppy engine.&nbsp;</p>\n', 2, 2, 'storage/uploads/product/8189_1737968239.jpg', 1, 150000.00, 135000.00, 1, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '2025-01-27 03:27:19', '2025-02-19 23:02:08'),
(5, 0, 1, 'New Product For Live Demo', 'NPFLD', NULL, 'Lightweight,Electronic', NULL, NULL, 2, 2, 'assets/img/default-product.webp', 1, 48000.00, 42000.00, 1, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '2025-02-16 10:15:17', '2025-02-19 21:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_features`
--

CREATE TABLE `product_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_features`
--

INSERT INTO `product_features` (`id`, `product_id`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 'Battery Type: Lithium-ion', '2025-01-27 03:18:12', '2025-02-19 23:01:46'),
(2, 1, 'Battery Capacity: 72V, 20Ah', '2025-01-27 03:18:12', '2025-02-19 23:01:46'),
(3, 1, 'Range: 60 - 80 km on a single charge', '2025-01-27 03:18:12', '2025-02-19 23:01:46'),
(4, 1, 'Charging Time: 4 - 6 hours', '2025-01-27 03:18:12', '2025-02-19 23:01:46'),
(5, 1, 'Charging Time: 4 - 6 hours', '2025-01-27 03:18:12', '2025-02-19 23:01:46'),
(6, 1, 'Top Speed: 45 km/h', '2025-01-27 03:18:12', '2025-02-19 23:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'storage/uploads/product/3295_1737967692.jpg', '2025-01-27 03:18:12', '2025-01-27 03:18:12'),
(2, 1, 'storage/uploads/product/1847_1737967692.jpg', '2025-01-27 03:18:12', '2025-01-27 03:18:12'),
(3, 1, 'storage/uploads/product/5246_1737967692.jpg', '2025-01-27 03:18:12', '2025-01-27 03:18:12'),
(4, 1, 'storage/uploads/product/3661_1737967692.webp', '2025-01-27 03:18:12', '2025-01-27 03:18:12'),
(5, 1, 'storage/uploads/product/6644_1737967692.jpg', '2025-01-27 03:18:12', '2025-01-27 03:18:12'),
(6, 2, 'storage/uploads/product/6993_1737968239.jpg', '2025-01-27 03:27:19', '2025-01-27 03:27:19'),
(7, 2, 'storage/uploads/product/5911_1737968239.jpg', '2025-01-27 03:27:19', '2025-01-27 03:27:19'),
(8, 2, 'storage/uploads/product/6283_1737968239.webp', '2025-01-27 03:27:19', '2025-01-27 03:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `title`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Electronic', 1, NULL, '2025-01-27 03:13:47', '2025-01-27 03:13:47'),
(2, 'Chargeable', 1, NULL, '2025-01-27 03:13:55', '2025-01-27 03:13:55'),
(3, '2 Seats', 1, NULL, '2025-01-27 03:16:00', '2025-01-27 03:16:00'),
(4, 'Waterproof', 1, NULL, '2025-01-27 03:17:04', '2025-01-27 03:17:04'),
(5, 'Lightweight', 1, NULL, '2025-01-27 03:17:12', '2025-01-27 03:17:12');

-- --------------------------------------------------------

--
-- Table structure for table `rental_prices`
--

CREATE TABLE `rental_prices` (
  `id` bigint(20) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `duration` int(11) NOT NULL,
  `duration_type` enum('day','week','biweekly','month') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_prices`
--

INSERT INTO `rental_prices` (`id`, `product_id`, `duration`, `duration_type`, `price`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'day', 150.00, '2025-02-16 10:15:17', '2025-02-19 21:23:29'),
(2, 5, 7, 'week', 750.00, '2025-02-16 10:15:17', '2025-02-19 21:23:29'),
(3, 5, 15, 'biweekly', 1200.00, '2025-02-16 10:15:17', '2025-02-19 21:23:29'),
(4, 5, 30, 'month', 2000.00, '2025-02-16 10:15:17', '2025-02-19 21:23:29'),
(5, 1, 7, 'week', 780.00, '2025-02-19 21:32:42', '2025-02-19 23:01:46'),
(6, 2, 15, 'biweekly', 2500.00, '2025-02-19 21:33:18', '2025-02-19 23:02:08'),
(7, 1, 1, 'day', 150.00, '2025-02-19 23:01:46', '2025-02-19 23:01:46'),
(8, 2, 7, 'week', 500.00, '2025-02-19 23:02:08', '2025-02-19 23:02:08');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('eRhYHv14qefCUY9fDmRcFrqRfSD8CQqdpNeZWuL5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTHcxa2Q4cnExalE2cUpuSEswbzYzd2I2VGNNbUk4TUo4UzNCanIwTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jdXN0b21lci9kZXRhaWxzLzEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1740290070);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_activities`
--

CREATE TABLE `shipping_activities` (
  `id` int(11) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Ride Booked','Payment Received','Ride Canceled','Vehicle Assigned','Ride Started','Ride Completed') NOT NULL,
  `vehicle_id` bigint(50) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payment_status` enum('Pending','Paid','Refunded') DEFAULT 'Pending',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_activities`
--

INSERT INTO `shipping_activities` (`id`, `order_id`, `status`, `vehicle_id`, `user_id`, `payment_status`, `description`, `created_at`, `updated_at`) VALUES
(1, 41, 'Ride Booked', NULL, 1, 'Pending', 'User booked a bike ride', '2025-02-23 05:07:49', '2025-02-23 05:11:14'),
(2, 41, 'Payment Received', NULL, 1, 'Paid', 'Payment confirmed via wallet', '2025-02-23 05:07:49', '2025-02-23 05:11:11'),
(3, 41, 'Vehicle Assigned', 34, 1, 'Paid', 'Bike assigned for the ride', '2025-02-23 05:07:49', '2025-02-23 05:11:09'),
(4, 41, 'Ride Started', 34, 1, 'Paid', 'User started the ride', '2025-02-23 05:07:49', '2025-02-23 05:11:05'),
(5, 41, 'Ride Completed', 34, 1, 'Paid', 'Ride successfully completed', '2025-02-23 05:07:49', '2025-02-23 05:11:01'),
(6, 41, 'Ride Started', NULL, 1, 'Pending', 'User started the ride\', \'Yamaha R15 - Black', '2025-02-23 00:19:09', '2025-02-23 00:19:09'),
(7, 41, 'Ride Completed', NULL, 1, 'Pending', 'Ride successfully completed\', \'Yamaha R15 - Black', '2025-02-23 00:19:53', '2025-02-23 00:19:53'),
(8, 41, 'Ride Canceled', NULL, 1, 'Refunded', 'User canceled the ride, refund processed', '2025-02-23 00:20:39', '2025-02-23 00:20:39'),
(9, 41, 'Vehicle Assigned', NULL, 1, 'Pending', 'Bike assigned for the ride\', \'Yamaha R15 - Black', '2025-02-23 00:20:50', '2025-02-23 00:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Maharashtra', 'India', 1, '2025-02-13 07:18:37', '2025-02-13 07:18:37'),
(2, 'West Bengal', 'India', 1, '2025-02-13 07:18:37', '2025-02-13 07:18:37'),
(3, 'Karnataka', 'India', 1, '2025-02-13 07:18:37', '2025-02-13 07:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` bigint(11) UNSIGNED NOT NULL,
  `vehicle_number` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `vehicle_number`, `status`, `created_at`, `updated_at`) VALUES
(25, 1, 'WB20250185', 1, '2025-02-13 02:15:00', '2025-02-16 00:26:46'),
(28, 1, 'WB202501423', 1, '2025-02-13 02:15:00', '2025-02-16 00:26:45'),
(29, 1, 'WB20250152', 1, '2025-02-13 02:15:00', '2025-02-16 00:27:22'),
(30, 2, 'WB20240185', 1, '2025-02-13 02:15:00', '2025-02-13 02:15:00'),
(31, 2, 'WB20240159', 1, '2025-02-13 02:15:00', '2025-02-13 02:15:00'),
(34, 2, 'WB20240152', 1, '2025-02-13 02:15:00', '2025-02-13 02:15:00'),
(35, 1, 'WB784124', 1, '2025-02-16 01:09:56', '2025-02-16 09:14:39'),
(36, 1, 'WB001245', 1, '2025-02-16 01:09:56', '2025-02-16 01:09:56'),
(37, 1, 'WB20250158', 1, '2025-02-16 09:13:16', '2025-02-17 08:48:56'),
(38, 1, 'WB20250159', 1, '2025-02-16 09:14:17', '2025-02-16 09:14:17'),
(39, 1, 'WB20250160', 1, '2025-02-16 09:14:27', '2025-02-16 09:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `stock_ledgers`
--

CREATE TABLE `stock_ledgers` (
  `id` int(11) NOT NULL,
  `product_id` bigint(11) UNSIGNED NOT NULL,
  `order_id` bigint(11) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `type` enum('Credit','Debit') NOT NULL,
  `purpose` enum('Rent','Sell','New') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_ledgers`
--

INSERT INTO `stock_ledgers` (`id`, `product_id`, `order_id`, `quantity`, `type`, `purpose`, `created_at`, `updated_at`) VALUES
(7, 1, NULL, 5, 'Credit', 'New', '2025-02-13 02:15:00', '2025-02-13 02:15:00'),
(8, 2, NULL, 5, 'Credit', 'New', '2025-02-13 02:15:00', '2025-02-13 02:15:00'),
(9, 1, NULL, 2, 'Credit', 'New', '2025-02-16 01:09:56', '2025-02-16 01:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `title`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'High-Speed Scooty', 1, '2025-01-27 02:54:57', '2025-01-27 02:54:57', NULL),
(2, 2, 'Foldable Scooty', 1, '2025-01-27 02:55:04', '2025-01-27 02:55:04', NULL),
(3, 2, 'Compact Scooty', 1, '2025-01-27 02:55:12', '2025-01-27 02:55:12', NULL),
(4, 2, 'Sporty Scooty', 1, '2025-01-27 02:55:20', '2025-01-27 02:55:20', NULL),
(5, 2, 'Women-Friendly Scooty', 1, '2025-01-27 02:55:29', '2025-01-27 02:55:29', NULL),
(6, 2, 'Cargo Scooty', 1, '2025-01-27 02:55:40', '2025-01-27 02:55:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `driving_license` varchar(255) DEFAULT NULL,
  `driving_license_status` int(11) NOT NULL DEFAULT 0 COMMENT '0:Pending, 1:uploaded, 2:verified, 3:cancelled',
  `govt_id_card` varchar(255) DEFAULT NULL,
  `govt_id_card_status` int(11) NOT NULL DEFAULT 0 COMMENT '0:Pending, 1:uploaded, 2:verified, 3:cancelled',
  `cancelled_cheque` varchar(255) DEFAULT NULL,
  `cancelled_cheque_status` int(11) NOT NULL DEFAULT 0 COMMENT '0:Pending, 1:uploaded, 2:verified, 3:cancelled',
  `current_address_proof` varchar(255) DEFAULT NULL,
  `current_address_proof_status` int(11) NOT NULL DEFAULT 0 COMMENT '0:Pending, 1:uploaded, 2:verified, 3:cancelled',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `customer_id`, `name`, `mobile`, `email`, `email_verified_at`, `password`, `address`, `city`, `pincode`, `profile_image`, `driving_license`, `driving_license_status`, `govt_id_card`, `govt_id_card_status`, `cancelled_cheque`, `cancelled_cheque_status`, `current_address_proof`, `current_address_proof_status`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'EW-00000001', 'Souvik Mondal', '7894562589', 'souvik@gmail.com', NULL, '$2y$12$BMOMrGoAhVKMuq4.z72HruUeoEySAZWXF7Ko1h0YacNJs292Rx3wm', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, 1, NULL, '2025-01-27 02:53:40', '2025-01-27 02:53:40'),
(2, 'EW-00000002', 'Rajib Ali Khan', '8617207528', 'rajib45@gmail.co', NULL, '$2y$12$.qrxUJbC1ptVNvhn4v5F7.2462q8cDbdxtQHgJDBjKVIcrqvO.UhW', 'ghatal', NULL, NULL, NULL, NULL, 1, NULL, 2, NULL, 0, NULL, 0, 1, NULL, '2025-01-28 04:25:10', '2025-02-16 11:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('home','work','other','') NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 60010.00, '2025-01-22 13:24:19', '2025-01-27 08:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type` enum('credit','debit','refund') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `wallet_id`, `order_id`, `transaction_type`, `amount`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 1, 30, 'debit', 6498.00, 'Purpose of New Order', '2025-01-27 13:51:59', '2025-01-27 08:21:59', '2025-01-27 08:21:59'),
(2, 1, 31, 'debit', 6498.00, 'Purpose of New Order', '2025-01-27 13:52:20', '2025-01-27 08:22:20', '2025-01-27 08:22:20'),
(3, 1, 32, 'debit', 3998.00, 'Purpose of New Order', '2025-01-27 13:52:47', '2025-01-27 08:22:47', '2025-01-27 08:22:47'),
(4, 1, 33, 'debit', 3998.00, 'Purpose of New Order', '2025-01-27 13:55:19', '2025-01-27 08:25:19', '2025-01-27 08:25:19'),
(5, 1, 34, 'debit', 3998.00, 'Purpose of New Order', '2025-01-27 13:58:00', '2025-01-27 08:28:00', '2025-01-27 08:28:00'),
(9, 1, NULL, 'credit', 200000.00, 'wallet recharge', '2025-01-27 14:07:24', '2025-01-02 14:07:28', '2025-01-15 14:07:33'),
(10, 1, 40, 'debit', 270000.00, 'Purpose of New Order', '2025-01-27 14:08:40', '2025-01-27 08:38:40', '2025-01-27 08:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `why_ewent`
--

CREATE TABLE `why_ewent` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `why_ewent`
--

INSERT INTO `why_ewent` (`id`, `title`, `image`, `status`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Title 1', 'storage/upload/banner/8308_1737965900.jpg', 1, 1, '2025-01-27 02:48:20', '2025-01-27 02:48:20'),
(2, 'Title 2', 'storage/upload/banner/6793_1737965967.png', 1, 1, '2025-01-27 02:49:27', '2025-01-27 02:49:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_ratings`
--
ALTER TABLE `admin_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_ratings_user_id_foreign` (`user_id`),
  ADD KEY `admin_ratings_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `assigned_vehicles`
--
ALTER TABLE `assigned_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_k1` (`order_item_id`),
  ADD KEY `fk_vehicle` (`vehicle_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

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
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_product_id_foreign` (`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `offers_coupon_code_unique` (`coupon_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_offer_id_foreign` (`offer_id`);

--
-- Indexes for table `order_addresses`
--
ALTER TABLE `order_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_address_id` (`user_address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_item_returns`
--
ALTER TABLE `order_item_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_returns_order_item_id_foreign` (`order_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_tokenable_type_tokenable_id_unique` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pincodes`
--
ALTER TABLE `pincodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `product_features`
--
ALTER TABLE `product_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_features_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_prices`
--
ALTER TABLE `rental_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `shipping_activities`
--
ALTER TABLE `shipping_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_k1` (`product_id`);

--
-- Indexes for table `stock_ledgers`
--
ALTER TABLE `stock_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id_k1` (`order_id`),
  ADD KEY `product_id_k2` (`product_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_dk_1` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  ADD KEY `wallet_transactions_order_id_foreign` (`order_id`);

--
-- Indexes for table `why_ewent`
--
ALTER TABLE `why_ewent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_ratings`
--
ALTER TABLE `admin_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_vehicles`
--
ALTER TABLE `assigned_vehicles`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `order_addresses`
--
ALTER TABLE `order_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `order_item_returns`
--
ALTER TABLE `order_item_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pincodes`
--
ALTER TABLE `pincodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_features`
--
ALTER TABLE `product_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rental_prices`
--
ALTER TABLE `rental_prices`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shipping_activities`
--
ALTER TABLE `shipping_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `stock_ledgers`
--
ALTER TABLE `stock_ledgers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `why_ewent`
--
ALTER TABLE `why_ewent`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_ratings`
--
ALTER TABLE `admin_ratings`
  ADD CONSTRAINT `admin_ratings_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `admin_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `assigned_vehicles`
--
ALTER TABLE `assigned_vehicles`
  ADD CONSTRAINT `fk_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_k1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_addresses`
--
ALTER TABLE `order_addresses`
  ADD CONSTRAINT `order_addresses_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_addresses_ibfk_2` FOREIGN KEY (`user_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_item_returns`
--
ALTER TABLE `order_item_returns`
  ADD CONSTRAINT `order_item_returns_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pincodes`
--
ALTER TABLE `pincodes`
  ADD CONSTRAINT `pincodes_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_features`
--
ALTER TABLE `product_features`
  ADD CONSTRAINT `product_features_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rental_prices`
--
ALTER TABLE `rental_prices`
  ADD CONSTRAINT `rental_prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `product_id_k1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_ledgers`
--
ALTER TABLE `stock_ledgers`
  ADD CONSTRAINT `order_id_k1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `user_dk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
