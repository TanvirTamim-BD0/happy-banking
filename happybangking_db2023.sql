-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 13, 2023 at 02:35 AM
-- Server version: 8.0.32
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happybangking_db2023`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_balance` double(8,2) DEFAULT NULL,
  `is_inactive` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `bank_id`, `mobile_wallet_id`, `branch`, `bank_account_type`, `account_number`, `current_balance`, `is_inactive`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 6, NULL, NULL, '5000', 25000.00, 0, '2023-08-13 02:14:32', '2023-08-13 02:14:32'),
(2, 2, NULL, 5, NULL, NULL, '6000', 54250.00, 0, '2023-08-13 02:14:47', '2023-08-13 02:14:47'),
(3, 2, 4, NULL, 'Hi', 'Savings', '8855', 85400.00, 0, '2023-08-13 02:15:04', '2023-08-13 02:15:04'),
(4, 2, 5, NULL, 'Hi Dhaka', 'Current', '54785', 58500.00, 0, '2023-08-13 02:15:29', '2023-08-13 02:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `account_payments`
--

CREATE TABLE `account_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_credit_card_id` bigint UNSIGNED DEFAULT NULL,
  `from_account_id` bigint UNSIGNED DEFAULT NULL,
  `from_pocket_account_id` bigint UNSIGNED DEFAULT NULL,
  `to_account_id` bigint UNSIGNED DEFAULT NULL,
  `to_credit_card_id` bigint UNSIGNED DEFAULT NULL,
  `to_beneficiary_account_id` bigint UNSIGNED DEFAULT NULL,
  `to_pocket_account_id` bigint UNSIGNED DEFAULT NULL,
  `transfer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_currency_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usd_in_bdt_rate` double(8,2) DEFAULT NULL,
  `pay_amount` double(8,2) DEFAULT NULL,
  `pay_fee` double(8,2) DEFAULT NULL,
  `pay_fee_amount` double(8,2) DEFAULT NULL,
  `total_pay_amount` double(8,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_bill_payment` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `active_sessions`
--

CREATE TABLE `active_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `session_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `user_id`, `bank_type`, `bank_name`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'State-owned commercial banks (SOCBs)', 'AB Bank Limited', '1691866929.png', '2023-08-13 01:02:09', '2023-08-13 01:02:09'),
(2, 1, 'State-owned commercial banks (SOCBs)', 'Agrani Bank Limited', '1691866962.jpg', '2023-08-13 01:02:42', '2023-08-13 01:02:42'),
(3, 1, 'Specialized banks (SDBs)', 'Bank Asia Limited', '1691867001.jpeg', '2023-08-13 01:03:21', '2023-08-13 01:03:21'),
(4, 1, 'Private commercial banks (PCBs)', 'BRAC Bank Limited', '1691867044.jpeg', '2023-08-13 01:04:04', '2023-08-13 01:04:04'),
(5, 1, 'Foreign commercial banks (FCBs)', 'Dhaka Bank Limited', '1691867088.jpeg', '2023-08-13 01:04:48', '2023-08-13 01:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `account_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `blog_category_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `solid_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `blog_category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` bigint DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_cards`
--

CREATE TABLE `credit_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `card_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_limit` double(8,2) DEFAULT NULL,
  `total_bdt_limit` double(8,2) DEFAULT NULL,
  `total_usd_limit` double(8,2) DEFAULT NULL,
  `is_dual_currency` tinyint(1) NOT NULL DEFAULT '0',
  `is_inactive` tinyint(1) NOT NULL DEFAULT '0',
  `current_bdt_outstanding` double(8,2) DEFAULT NULL,
  `current_usd_outstanding` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_cards`
--

INSERT INTO `credit_cards` (`id`, `user_id`, `bank_id`, `card_type`, `card_number`, `billing_date`, `total_limit`, `total_bdt_limit`, `total_usd_limit`, `is_dual_currency`, `is_inactive`, `current_bdt_outstanding`, `current_usd_outstanding`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Master Card', '54800', '2023-08-13', 500000.00, 5000.00, 250.00, 1, 0, NULL, NULL, '2023-08-13 02:15:50', '2023-08-13 02:20:22');

-- --------------------------------------------------------

--
-- Table structure for table `credit_card_reminders`
--

CREATE TABLE `credit_card_reminders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `credit_card_id` bigint UNSIGNED DEFAULT NULL,
  `active_session_id` bigint UNSIGNED DEFAULT NULL,
  `billing_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_payment_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_due` double(8,2) DEFAULT NULL,
  `minimum_due` double(8,2) DEFAULT NULL,
  `total_bdt_due` double(8,2) DEFAULT NULL,
  `total_usd_due` double(8,2) DEFAULT NULL,
  `bdt_minimum_due` double(8,2) DEFAULT NULL,
  `usd_minimum_due` double(8,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentations`
--

CREATE TABLE `documentations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `documentation_category_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `solid_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentation_categories`
--

CREATE TABLE `documentation_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `documentation_category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `pocket_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `from_account_id` bigint UNSIGNED DEFAULT NULL,
  `from_credit_card_id` bigint UNSIGNED DEFAULT NULL,
  `source_of_expense_id` bigint UNSIGNED DEFAULT NULL,
  `expense_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontend_notes`
--

CREATE TABLE `frontend_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `solid_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `pocket_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `from_account_id` bigint UNSIGNED DEFAULT NULL,
  `source_of_income_id` bigint UNSIGNED DEFAULT NULL,
  `income_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_expenses`
--

CREATE TABLE `income_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `pocket_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `from_account_id` bigint UNSIGNED DEFAULT NULL,
  `from_credit_card_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_category_id` bigint UNSIGNED DEFAULT NULL,
  `income_expense_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `amount` double(8,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logos`
--

CREATE TABLE `logos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `logo_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2022_02_14_070151_create_permission_tables', 1),
(11, '2022_12_29_094456_create_logos_table', 1),
(12, '2022_12_30_061646_create_contacts_table', 1),
(13, '2022_12_30_064931_create_push_notifications_table', 1),
(14, '2023_01_04_050538_create_blog_categories_table', 1),
(15, '2023_01_04_050539_create_blogs_table', 1),
(16, '2023_04_07_082226_create_mobile_wallets_table', 1),
(17, '2023_04_07_082242_create_banks_table', 1),
(18, '2023_04_07_094910_create_accounts_table', 1),
(19, '2023_04_08_044112_create_credit_cards_table', 1),
(20, '2023_04_10_023934_create_beneficiaries_table', 1),
(21, '2023_04_10_031238_create_account_payments_table', 1),
(22, '2023_04_11_055441_create_active_sessions_table', 1),
(23, '2023_04_11_055442_create_credit_card_reminders_table', 1),
(24, '2023_04_12_055615_create_transaction_categories_table', 1),
(25, '2023_04_17_045124_create_incomes_table', 1),
(26, '2023_04_26_051731_create_expenses_table', 1),
(27, '2023_04_27_062550_create_income_expenses_table', 1),
(28, '2023_04_30_163012_create_webusers_table', 1),
(29, '2023_05_30_163012_create_user_professions_table', 1),
(30, '2023_06_17_073137_create_user_activities_table', 1),
(31, '2023_06_20_162436_create_documentation_categories_table', 1),
(32, '2023_06_20_162437_create_documentations_table', 1),
(33, '2023_06_24_152724_create_payment_types_table', 1),
(34, '2023_06_26_130147_create_frontend_notes_table', 1),
(35, '2023_07_15_175950_create_add_column_in_account_payments_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_wallets`
--

CREATE TABLE `mobile_wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `mobile_wallet_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobile_wallets`
--

INSERT INTO `mobile_wallets` (`id`, `user_id`, `mobile_wallet_name`, `parent_company`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Rocket', 'Dutch Bangla Bank Ltd', '1691866487.png', '2023-08-13 00:54:47', '2023-08-13 00:54:47'),
(2, 1, 'bKash', 'bKash Ltd', '1691866543.png', '2023-08-13 00:55:44', '2023-08-13 00:55:44'),
(3, 1, 'MYCash', 'Mercantile Bank Ltd', '1691866614.png', '2023-08-13 00:56:54', '2023-08-13 00:56:54'),
(4, 1, 'Islami Bank mCash', 'Islami Bank Bangladesh Ltd', '1691866665.png', '2023-08-13 00:57:45', '2023-08-13 00:57:45'),
(5, 1, 'Trust Axiata pay: TAP', 'Trust Axiata Digital Ltd', '1691866742.png', '2023-08-13 00:59:02', '2023-08-13 00:59:02'),
(6, 1, 'FSIBL FirstPay', 'First Security Islami Bank Ltd', '1691866831.png', '2023-08-13 01:00:31', '2023-08-13 01:00:31');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('a4f8fa459e3926e347d3e925f1ffbd55c9604c1d1f1446702658333021fa89eecd729424b1ef9a3c', 2, 1, 'BankSoft2023', '[]', 0, '2023-08-13 01:12:59', '2023-08-13 01:12:59', '2024-08-13 01:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', '16js549GqUQTI4HZi8IBRpTVj4fwaJOmlxscfxF4', NULL, 'http://localhost', 1, 0, 0, '2023-08-12 18:45:48', '2023-08-12 18:45:48'),
(2, NULL, 'Laravel Password Grant Client', '4CyyFheAdNq8Pp0ThabHbAX86v2o7y8URwUpo9gb', 'users', 'http://localhost', 0, 1, 0, '2023-08-12 18:45:48', '2023-08-12 18:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-08-12 18:45:48', '2023-08-12 18:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `user_id`, `type_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Regular', 1, '2023-08-13 01:10:46', '2023-08-13 01:10:46'),
(2, 1, 'Occetional', 1, '2023-08-13 01:10:54', '2023-08-13 01:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'user-list', 'web', 'user', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(2, 'user-create', 'web', 'user', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(3, 'user-edit', 'web', 'user', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(4, 'user-delete', 'web', 'user', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(5, 'role-list', 'web', 'role', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(6, 'role-create', 'web', 'role', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(7, 'role-edit', 'web', 'role', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(8, 'role-delete', 'web', 'role', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(9, 'bank-list', 'web', 'bank', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(10, 'bank-create', 'web', 'bank', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(11, 'bank-edit', 'web', 'bank', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(12, 'bank-delete', 'web', 'bank', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(13, 'mobile-wallet-list', 'web', 'mobile-wallet', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(14, 'mobile-wallet-create', 'web', 'mobile-wallet', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(15, 'mobile-wallet-edit', 'web', 'mobile-wallet', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(16, 'mobile-wallet-delete', 'web', 'mobile-wallet', '2023-08-12 18:45:29', '2023-08-12 18:45:29'),
(17, 'transaction-category-list', 'web', 'transaction-category', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(18, 'transaction-category-create', 'web', 'transaction-category', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(19, 'transaction-category-edit', 'web', 'transaction-category', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(20, 'transaction-category-delete', 'web', 'transaction-category', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(21, 'active-session-list', 'web', 'active-session', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(22, 'active-session-create', 'web', 'active-session', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(23, 'active-session-edit', 'web', 'active-session', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(24, 'active-session-delete', 'web', 'active-session', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(25, 'category-of-blog-list', 'web', 'category-of-blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(26, 'category-of-blog-create', 'web', 'category-of-blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(27, 'category-of-blog-edit', 'web', 'category-of-blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(28, 'category-of-blog-delete', 'web', 'category-of-blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(29, 'blog-list', 'web', 'blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(30, 'blog-create', 'web', 'blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(31, 'blog-edit', 'web', 'blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(32, 'blog-delete', 'web', 'blog', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(33, 'category-of-documentation-list', 'web', 'category-of-documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(34, 'category-of-documentation-create', 'web', 'category-of-documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(35, 'category-of-documentation-edit', 'web', 'category-of-documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(36, 'category-of-documentation-delete', 'web', 'category-of-documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(37, 'documentation-list', 'web', 'documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(38, 'documentation-create', 'web', 'documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(39, 'documentation-edit', 'web', 'documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(40, 'documentation-delete', 'web', 'documentation', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(41, 'contact-list', 'web', 'contact', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(42, 'contact-create', 'web', 'contact', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(43, 'contact-edit', 'web', 'contact', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(44, 'contact-delete', 'web', 'contact', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(45, 'profession-list', 'web', 'profession', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(46, 'profession-create', 'web', 'profession', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(47, 'profession-edit', 'web', 'profession', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(48, 'profession-delete', 'web', 'profession', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(49, 'push-notification-list', 'web', 'push-notification', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(50, 'push-notification-create', 'web', 'push-notification', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(51, 'push-notification-edit', 'web', 'push-notification', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(52, 'push-notification-delete', 'web', 'push-notification', '2023-08-12 18:45:30', '2023-08-12 18:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `notification_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sending_date` date DEFAULT NULL,
  `sending_time` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(2, 'admin', 'web', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(3, 'manager', 'web', '2023-08-12 18:45:30', '2023-08-12 18:45:30'),
(4, 'user', 'web', '2023-08-12 18:45:30', '2023-08-12 18:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_categories`
--

CREATE TABLE `transaction_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `category_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_categories`
--

INSERT INTO `transaction_categories` (`id`, `user_id`, `category_type`, `category_name`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Expense', 'Housing', '1691867235.png', '2023-08-13 01:07:16', '2023-08-13 01:07:16'),
(2, 1, 'Expense', 'Transportation', '1691867249.png', '2023-08-13 01:07:29', '2023-08-13 01:07:29'),
(3, 1, 'Expense', 'Food', '1691867261.png', '2023-08-13 01:07:41', '2023-08-13 01:07:41'),
(4, 1, 'Expense', 'Utilities', '1691867272.png', '2023-08-13 01:07:53', '2023-08-13 01:07:53'),
(5, 1, 'Expense', 'Insurance', '1691867283.png', '2023-08-13 01:08:03', '2023-08-13 01:08:03'),
(6, 1, 'Expense', 'Medical & Healthcare', '1691867298.png', '2023-08-13 01:08:18', '2023-08-13 01:08:18'),
(7, 1, 'Income', 'Salary', '1691867351.png', '2023-08-13 01:09:11', '2023-08-13 01:09:11'),
(8, 1, 'Income', 'Pension', '1691867375.png', '2023-08-13 01:09:35', '2023-08-13 01:09:35'),
(9, 1, 'Income', 'Business', '1691867394.png', '2023-08-13 01:09:54', '2023-08-13 01:10:17'),
(10, 1, 'Income', 'House Rent', '1691867410.png', '2023-08-13 01:10:10', '2023-08-13 01:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `profession_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_expires_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet` double(8,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `package_id`, `profession_id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `verify_code`, `verify_expires_at`, `status`, `role`, `gender`, `wallet`, `image`, `admin_id`, `manager_id`, `address`, `device_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'superadmin', 'admindhaka@gmail.com', '01799646660', NULL, '$2y$10$tKlRyhAlZuiLe/YdXAyl4.j5qfmI3xZM932KNhYHi1kbcLE1Oq6Ki', NULL, NULL, 1, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-12 18:45:31', '2023-08-12 18:45:31'),
(2, NULL, 4, 'Dhrubo Jyoti Das', 'dhrubod@gmail.com', '01780363336', NULL, '$2y$10$ofPEKerqxN/JKj2Pb5vHKeuFqHBWFYksy5.hRXnMMm7alopSCuMgy', NULL, NULL, 1, 'user', 'male', 50000.00, NULL, NULL, NULL, 'Birganj', NULL, NULL, '2023-08-13 01:12:59', '2023-08-13 02:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `start_activity_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `end_activity_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_hit` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `start_activity_url`, `end_activity_url`, `start_time`, `end_time`, `date`, `total_hit`, `created_at`, `updated_at`) VALUES
(1, 1, 'https://happybanking.org/admin-login', 'https://www.happybanking.org/admin/payment-type', '00:47:00', '01:10:54', '2023-08-13', 1, '2023-08-13 00:47:00', '2023-08-13 01:10:54'),
(2, 2, 'https://happybanking.org/webuser/login', 'https://happybanking.org/webuser/user-activity-total-hit-increase', '01:13:28', '02:22:13', '2023-08-13', 74, '2023-08-13 01:13:28', '2023-08-13 02:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `user_professions`
--

CREATE TABLE `user_professions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `profession_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_professions`
--

INSERT INTO `user_professions` (`id`, `user_id`, `profession_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Salaried', '2023-08-13 00:51:33', '2023-08-13 00:51:33'),
(2, 1, 'Businessman', '2023-08-13 00:51:43', '2023-08-13 00:51:43'),
(3, 1, 'Government Employee', '2023-08-13 00:52:19', '2023-08-13 00:52:19'),
(4, 1, 'Self Employed', '2023-08-13 00:52:31', '2023-08-13 00:52:31'),
(5, 1, 'Businessman', '2023-08-13 00:52:41', '2023-08-13 00:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `webusers`
--

CREATE TABLE `webusers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_expires_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet` double(8,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_user_id_foreign` (`user_id`),
  ADD KEY `accounts_bank_id_foreign` (`bank_id`),
  ADD KEY `accounts_mobile_wallet_id_foreign` (`mobile_wallet_id`);

--
-- Indexes for table `account_payments`
--
ALTER TABLE `account_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `account_payments_user_id_foreign` (`user_id`),
  ADD KEY `account_payments_from_credit_card_id_foreign` (`from_credit_card_id`),
  ADD KEY `account_payments_from_account_id_foreign` (`from_account_id`),
  ADD KEY `account_payments_from_pocket_account_id_foreign` (`from_pocket_account_id`),
  ADD KEY `account_payments_to_account_id_foreign` (`to_account_id`),
  ADD KEY `account_payments_to_credit_card_id_foreign` (`to_credit_card_id`),
  ADD KEY `account_payments_to_beneficiary_account_id_foreign` (`to_beneficiary_account_id`),
  ADD KEY `account_payments_to_pocket_account_id_foreign` (`to_pocket_account_id`);

--
-- Indexes for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active_sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banks_user_id_foreign` (`user_id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beneficiaries_user_id_foreign` (`user_id`),
  ADD KEY `beneficiaries_bank_id_foreign` (`bank_id`),
  ADD KEY `beneficiaries_mobile_wallet_id_foreign` (`mobile_wallet_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogs_user_id_foreign` (`user_id`),
  ADD KEY `blogs_blog_category_id_foreign` (`blog_category_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_email_unique` (`email`),
  ADD UNIQUE KEY `contacts_mobile_unique` (`mobile`),
  ADD KEY `contacts_user_id_foreign` (`user_id`);

--
-- Indexes for table `credit_cards`
--
ALTER TABLE `credit_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_cards_user_id_foreign` (`user_id`),
  ADD KEY `credit_cards_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `credit_card_reminders`
--
ALTER TABLE `credit_card_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_card_reminders_user_id_foreign` (`user_id`),
  ADD KEY `credit_card_reminders_credit_card_id_foreign` (`credit_card_id`),
  ADD KEY `credit_card_reminders_active_session_id_foreign` (`active_session_id`);

--
-- Indexes for table `documentations`
--
ALTER TABLE `documentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentations_user_id_foreign` (`user_id`),
  ADD KEY `documentations_documentation_category_id_foreign` (`documentation_category_id`);

--
-- Indexes for table `documentation_categories`
--
ALTER TABLE `documentation_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentation_categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`),
  ADD KEY `expenses_bank_id_foreign` (`bank_id`),
  ADD KEY `expenses_mobile_wallet_id_foreign` (`mobile_wallet_id`),
  ADD KEY `expenses_pocket_wallet_id_foreign` (`pocket_wallet_id`),
  ADD KEY `expenses_from_account_id_foreign` (`from_account_id`),
  ADD KEY `expenses_from_credit_card_id_foreign` (`from_credit_card_id`),
  ADD KEY `expenses_source_of_expense_id_foreign` (`source_of_expense_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `frontend_notes`
--
ALTER TABLE `frontend_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `frontend_notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incomes_user_id_foreign` (`user_id`),
  ADD KEY `incomes_bank_id_foreign` (`bank_id`),
  ADD KEY `incomes_mobile_wallet_id_foreign` (`mobile_wallet_id`),
  ADD KEY `incomes_pocket_wallet_id_foreign` (`pocket_wallet_id`),
  ADD KEY `incomes_from_account_id_foreign` (`from_account_id`),
  ADD KEY `incomes_source_of_income_id_foreign` (`source_of_income_id`);

--
-- Indexes for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `income_expenses_transaction_id_unique` (`transaction_id`),
  ADD KEY `income_expenses_user_id_foreign` (`user_id`),
  ADD KEY `income_expenses_bank_id_foreign` (`bank_id`),
  ADD KEY `income_expenses_mobile_wallet_id_foreign` (`mobile_wallet_id`),
  ADD KEY `income_expenses_pocket_wallet_id_foreign` (`pocket_wallet_id`),
  ADD KEY `income_expenses_from_account_id_foreign` (`from_account_id`),
  ADD KEY `income_expenses_from_credit_card_id_foreign` (`from_credit_card_id`),
  ADD KEY `income_expenses_transaction_category_id_foreign` (`transaction_category_id`);

--
-- Indexes for table `logos`
--
ALTER TABLE `logos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logos_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_wallets`
--
ALTER TABLE `mobile_wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile_wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_types_user_id_foreign` (`user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `push_notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_professions`
--
ALTER TABLE `user_professions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_professions_user_id_foreign` (`user_id`);

--
-- Indexes for table `webusers`
--
ALTER TABLE `webusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `webusers_email_unique` (`email`),
  ADD UNIQUE KEY `webusers_mobile_unique` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `account_payments`
--
ALTER TABLE `account_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `active_sessions`
--
ALTER TABLE `active_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_cards`
--
ALTER TABLE `credit_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credit_card_reminders`
--
ALTER TABLE `credit_card_reminders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documentations`
--
ALTER TABLE `documentations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documentation_categories`
--
ALTER TABLE `documentation_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontend_notes`
--
ALTER TABLE `frontend_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_expenses`
--
ALTER TABLE `income_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logos`
--
ALTER TABLE `logos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `mobile_wallets`
--
ALTER TABLE `mobile_wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notifications`
--
ALTER TABLE `push_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_professions`
--
ALTER TABLE `user_professions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `webusers`
--
ALTER TABLE `webusers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_mobile_wallet_id_foreign` FOREIGN KEY (`mobile_wallet_id`) REFERENCES `mobile_wallets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_payments`
--
ALTER TABLE `account_payments`
  ADD CONSTRAINT `account_payments_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_from_credit_card_id_foreign` FOREIGN KEY (`from_credit_card_id`) REFERENCES `credit_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_from_pocket_account_id_foreign` FOREIGN KEY (`from_pocket_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_to_account_id_foreign` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_to_beneficiary_account_id_foreign` FOREIGN KEY (`to_beneficiary_account_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_to_credit_card_id_foreign` FOREIGN KEY (`to_credit_card_id`) REFERENCES `credit_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_to_pocket_account_id_foreign` FOREIGN KEY (`to_pocket_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD CONSTRAINT `active_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banks`
--
ALTER TABLE `banks`
  ADD CONSTRAINT `banks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_mobile_wallet_id_foreign` FOREIGN KEY (`mobile_wallet_id`) REFERENCES `mobile_wallets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD CONSTRAINT `blog_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_cards`
--
ALTER TABLE `credit_cards`
  ADD CONSTRAINT `credit_cards_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_card_reminders`
--
ALTER TABLE `credit_card_reminders`
  ADD CONSTRAINT `credit_card_reminders_active_session_id_foreign` FOREIGN KEY (`active_session_id`) REFERENCES `active_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_card_reminders_credit_card_id_foreign` FOREIGN KEY (`credit_card_id`) REFERENCES `credit_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_card_reminders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documentations`
--
ALTER TABLE `documentations`
  ADD CONSTRAINT `documentations_documentation_category_id_foreign` FOREIGN KEY (`documentation_category_id`) REFERENCES `documentation_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documentations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documentation_categories`
--
ALTER TABLE `documentation_categories`
  ADD CONSTRAINT `documentation_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_from_credit_card_id_foreign` FOREIGN KEY (`from_credit_card_id`) REFERENCES `credit_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_mobile_wallet_id_foreign` FOREIGN KEY (`mobile_wallet_id`) REFERENCES `mobile_wallets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_pocket_wallet_id_foreign` FOREIGN KEY (`pocket_wallet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_source_of_expense_id_foreign` FOREIGN KEY (`source_of_expense_id`) REFERENCES `transaction_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `frontend_notes`
--
ALTER TABLE `frontend_notes`
  ADD CONSTRAINT `frontend_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_mobile_wallet_id_foreign` FOREIGN KEY (`mobile_wallet_id`) REFERENCES `mobile_wallets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_pocket_wallet_id_foreign` FOREIGN KEY (`pocket_wallet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_source_of_income_id_foreign` FOREIGN KEY (`source_of_income_id`) REFERENCES `transaction_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD CONSTRAINT `income_expenses_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_from_credit_card_id_foreign` FOREIGN KEY (`from_credit_card_id`) REFERENCES `credit_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_mobile_wallet_id_foreign` FOREIGN KEY (`mobile_wallet_id`) REFERENCES `mobile_wallets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_pocket_wallet_id_foreign` FOREIGN KEY (`pocket_wallet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_transaction_category_id_foreign` FOREIGN KEY (`transaction_category_id`) REFERENCES `transaction_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logos`
--
ALTER TABLE `logos`
  ADD CONSTRAINT `logos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mobile_wallets`
--
ALTER TABLE `mobile_wallets`
  ADD CONSTRAINT `mobile_wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD CONSTRAINT `payment_types_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD CONSTRAINT `push_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  ADD CONSTRAINT `transaction_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_professions`
--
ALTER TABLE `user_professions`
  ADD CONSTRAINT `user_professions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
