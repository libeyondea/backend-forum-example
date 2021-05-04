-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2021 at 09:45 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum-example`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(66) NOT NULL,
  `slug` varchar(66) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `slug`, `content`, `created_at`, `updated_at`) VALUES
(1, 'Developer', 'developer', 'Developer', '2020-10-25 14:25:04', '2020-10-25 14:25:04'),
(2, 'Software', 'software', 'Software', '2020-10-25 14:25:04', '2020-10-25 14:25:04'),
(3, 'Hacking', 'hacking', 'Hacking', '2020-10-25 14:25:04', '2020-10-25 14:25:04'),
(4, 'Tools', 'tools', 'Tools', '2020-10-25 14:25:04', '2020-10-25 14:25:04'),
(5, 'Books', 'books', 'Books', '2020-10-25 14:25:04', '2020-10-25 14:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `published` tinyint(1) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `user_id`, `parent_id`, `content`, `published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'First comment', 1, '2021-04-21 20:05:09', '2021-04-21 13:06:14', '2021-04-21 13:06:14');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_post`
--

CREATE TABLE `favorite_post` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `following_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(8, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'CajQOiWfwJzwLHvlYmOP9IJLy6s1vutl8vb6BSBp', NULL, 'http://localhost', 1, 0, 0, '2021-05-04 07:44:20', '2021-05-04 07:44:20'),
(2, NULL, 'Laravel Password Grant Client', 'Ojp9uaH0R6G8sCnxnKqj9H9bciSV2fCkmTxj5DGV', 'users', 'http://localhost', 0, 1, 0, '2021-05-04 07:44:21', '2021-05-04 07:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-05-04 07:44:21', '2021-05-04 07:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(66) NOT NULL,
  `slug` varchar(66) NOT NULL,
  `description` varchar(666) NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `title`, `slug`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Create Post', 'create-post', 'Create Post', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(2, 'Read Post', 'read-post', 'Read Post', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(3, 'Update Post', 'update-post', 'Update Post', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(4, 'Delete Post', 'delete-post', 'Delete Post', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(5, 'Create Comment', 'create-comment', 'Create Comment', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(6, 'Read Comment', 'read-comment', 'Read Comment', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(7, 'Update Comment', 'update-comment', 'Update Comment', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(8, 'Delete Comment', 'delete-comment', 'Delete Comment', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(9, 'Post Management', 'post-management', 'Post Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(10, 'Comment Management', 'comment-management', 'Comment Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(11, 'Category Management', 'category-management', 'Category Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(12, 'Tag Management', 'tag-management', 'Tag Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(13, 'User Management', 'user-management', 'User Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05'),
(14, 'System Management', 'system-management', 'System Management', 1, '2020-10-25 14:25:05', '2020-10-25 14:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(666) NOT NULL,
  `slug` varchar(166) NOT NULL,
  `excerpt` varchar(666) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `content` text NOT NULL,
  `published` tinyint(1) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `category_id`, `user_id`, `parent_id`, `title`, `slug`, `excerpt`, `image`, `content`, `published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'Hello World 1', 'hello-world-1', 'Hello World 1 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 1 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-12-17 01:31:21', '2021-05-03 09:33:49'),
(2, 1, 1, NULL, 'Hello World 2', 'hello-world-2', 'Hello World 2 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 2 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-12-14 20:56:22', '2020-12-14 22:04:01'),
(3, 1, 1, NULL, 'Hello World 3', 'hello-world-3', 'Hello World 3 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 3 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 18:59:43'),
(4, 1, 1, NULL, 'Hello World 4', 'hello-world-4', 'Hello World 4 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 4 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 18:59:46'),
(5, 1, 1, NULL, 'Hello World 5', 'hello-world-5', 'Hello World 5 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 5 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 18:59:48'),
(6, 1, 1, NULL, 'Hello World 6', 'hello-world-6', 'Hello World 6 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 6 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-31 21:56:24', '2020-12-14 18:59:49'),
(7, 1, 1, NULL, 'Hello World 7', 'hello-world-7', 'Hello World 7 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 7 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 18:59:53'),
(8, 1, 1, NULL, 'Hello World 8', 'hello-world-8', 'Hello World 8 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 8 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 18:59:54'),
(9, 1, 1, NULL, 'Hello World 9', 'hello-world-9', 'Hello World 9 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 9 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 18:59:57'),
(10, 1, 1, NULL, 'Hello World 10', 'hello-world-10', 'Hello World 10 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 10 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 18:59:59'),
(11, 1, 1, NULL, 'Hello World 11', 'hello-world-11', 'Hello World 11 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 11 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:00:01'),
(12, 1, 1, NULL, 'Hello World 12', 'hello-world-12', 'Hello World 12 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 12 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:03'),
(13, 1, 1, NULL, 'Hello World 13', 'hello-world-13', 'Hello World 13 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 13 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:00:04'),
(14, 1, 1, NULL, 'Hello World 14', 'hello-world-14', 'Hello World 14 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 14 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:07'),
(15, 1, 1, NULL, 'Hello World 15', 'hello-world-15', 'Hello World 15 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 15 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:00:10'),
(16, 1, 1, NULL, 'Hello World 16', 'hello-world-16', 'Hello World 16 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 16 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:13'),
(17, 1, 1, NULL, 'Hello World 17', 'hello-world-17', 'Hello World 17 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 17 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:00:16'),
(18, 1, 1, NULL, 'Hello World 18', 'hello-world-18', 'Hello World 18 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 18 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:00:19'),
(19, 1, 1, NULL, 'Hello World 19', 'hello-world-19', 'Hello World 19 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 19 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:00:23'),
(20, 1, 1, NULL, 'Hello World 20', 'hello-world-20', 'Hello World 20 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 20 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 19:00:28'),
(21, 2, 1, NULL, 'Hello World 21', 'hello-world-21', 'Hello World 21 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 21 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:00:30'),
(22, 2, 1, NULL, 'Hello World 22', 'hello-world-22', 'Hello World 22 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 22 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:33'),
(23, 2, 1, NULL, 'Hello World 23', 'hello-world-23', 'Hello World 23 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 23 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:34'),
(24, 2, 1, NULL, 'Hello World 24', 'hello-world-24', 'Hello World 24 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 24 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:35'),
(25, 2, 1, NULL, 'Hello World 25', 'hello-world-25', 'Hello World 25 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 25 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:40'),
(26, 2, 1, NULL, 'Hello World 26', 'hello-world-26', 'Hello World 26 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 26 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:57'),
(27, 2, 1, NULL, 'Hello World 27', 'hello-world-27', 'Hello World 27 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 27 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:58'),
(28, 2, 1, NULL, 'Hello World 28', 'hello-world-28', 'Hello World 28 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 28 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:00'),
(29, 2, 1, NULL, 'Hello World 29', 'hello-world-29', 'Hello World 29 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 29 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:03'),
(30, 2, 1, NULL, 'Hello World 30', 'hello-world-30', 'Hello World 30 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 30 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:02:07'),
(31, 2, 1, NULL, 'Hello World 31', 'hello-world-31', 'Hello World 31 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 31 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:08'),
(32, 2, 1, NULL, 'Hello World 32', 'hello-world-32', 'Hello World 32 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 32 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:10'),
(33, 2, 1, NULL, 'Hello World 33', 'hello-world-33', 'Hello World 33 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 33 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:02:13'),
(34, 2, 1, NULL, 'Hello World 34', 'hello-world-34', 'Hello World 34 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 34 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:16'),
(35, 2, 1, NULL, 'Hello World 35', 'hello-world-35', 'Hello World 35 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 35 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:19'),
(36, 2, 1, NULL, 'Hello World 36', 'hello-world-36', 'Hello World 36 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 36 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:02:21'),
(37, 2, 1, NULL, 'Hello World 37', 'hello-world-37', 'Hello World 37 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 37 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:02:22'),
(38, 2, 1, NULL, 'Hello World 38', 'hello-world-38', 'Hello World 38 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 38 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:02:25'),
(39, 2, 1, NULL, 'Hello World 39', 'hello-world-39', 'Hello World 39 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 39 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:02:26'),
(40, 2, 1, NULL, 'Hello World 40', 'hello-world-40', 'Hello World 40 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 40 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:02:28'),
(41, 3, 1, NULL, 'Hello World 41', 'hello-world-41', 'Hello World 41 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 41 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 19:02:43'),
(42, 3, 1, NULL, 'Hello World 42', 'hello-world-42', 'Hello World 42 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 42 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 19:01:55'),
(43, 3, 1, NULL, 'Hello World 43', 'hello-world-43', 'Hello World 43 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 43 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:51'),
(44, 3, 1, NULL, 'Hello World 44', 'hello-world-44', 'Hello World 44 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 44 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 19:01:49'),
(45, 3, 1, NULL, 'Hello World 45', 'hello-world-45', 'Hello World 45 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 45 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:47'),
(46, 3, 1, NULL, 'Hello World 46', 'hello-world-46', 'Hello World 46 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 46 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:45'),
(47, 3, 1, NULL, 'Hello World 47', 'hello-world-47', 'Hello World 47 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 47 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:44'),
(48, 3, 1, NULL, 'Hello World 48', 'hello-world-48', 'Hello World 48 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 48 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:42'),
(49, 3, 1, NULL, 'Hello World 49', 'hello-world-49', 'Hello World 49 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 49 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:40'),
(50, 3, 1, NULL, 'Hello World 50', 'hello-world-50', 'Hello World 50 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 50 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:36'),
(51, 4, 1, NULL, 'Hello World 51', 'hello-world-51', 'Hello World 51 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 51 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:37'),
(52, 4, 1, NULL, 'Hello World 52', 'hello-world-52', 'Hello World 52 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 52 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:31'),
(53, 4, 1, NULL, 'Hello World 53', 'hello-world-53', 'Hello World 53 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 53 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:25'),
(54, 4, 1, NULL, 'Hello World 54', 'hello-world-54', 'Hello World 54 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 54 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:20'),
(55, 4, 1, NULL, 'Hello World 55', 'hello-world-55', 'Hello World 55 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 55 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:17'),
(56, 4, 1, NULL, 'Hello World 56', 'hello-world-56', 'Hello World 56 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 56 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:15'),
(57, 4, 1, NULL, 'Hello World 57', 'hello-world-57', 'Hello World 57 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 57 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:13'),
(58, 4, 1, NULL, 'Hello World 58', 'hello-world-58', 'Hello World 58 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 58 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:11'),
(59, 4, 1, NULL, 'Hello World 59', 'hello-world-59', 'Hello World 59 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 59 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:23', '2020-12-14 19:01:10'),
(60, 4, 1, NULL, 'Hello World 60', 'hello-world-60', 'Hello World 60 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 60 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:07'),
(61, 4, 1, NULL, 'Hello World 61', 'hello-world-61', 'Hello World 61 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 61 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:05'),
(62, 4, 1, NULL, 'Hello World 62', 'hello-world-62', 'Hello World 62 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 62 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:01:03'),
(63, 4, 1, NULL, 'Hello World 63', 'hello-world-63', 'Hello World 63 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 63 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:21', '2020-12-14 19:01:01'),
(64, 4, 1, NULL, 'Hello World 64', 'hello-world-64', 'Hello World 64 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 64 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:58'),
(65, 4, 1, NULL, 'Hello World 65', 'hello-world-65', 'Hello World 65 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 65 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:24', '2020-12-14 19:00:56'),
(66, 4, 1, NULL, 'Hello World 66', 'hello-world-66', 'Hello World 66 is the best', 'https://i.imgur.com/mCg2Cyz.png', '<p style=\"text-align: center;\"><span style=\"background-color: #c2e0f4; color: #236fa1;\"><strong>Hello World 66 is the best</strong></span></p>', 1, '2020-10-31 03:14:14', '2020-10-30 20:56:22', '2020-12-14 19:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE `post_tag` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_tag`
--

INSERT INTO `post_tag` (`post_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-04-21 10:57:14', '2021-04-21 10:57:14'),
(2, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(3, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(4, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(5, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(6, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(7, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(8, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(9, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(10, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(11, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(12, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(13, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(14, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(15, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(16, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(17, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(18, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(19, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(20, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(21, 2, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(22, 2, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(23, 3, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(24, 3, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(25, 4, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(26, 5, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(27, 6, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(28, 6, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(29, 7, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(30, 7, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(31, 8, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(32, 8, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(33, 9, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(34, 9, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(35, 10, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(36, 10, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(37, 11, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(38, 11, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(39, 12, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(40, 12, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(41, 13, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(42, 13, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(43, 14, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(44, 14, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(45, 15, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(46, 15, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(47, 16, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(48, 16, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(49, 17, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(50, 17, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(51, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(52, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(53, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(54, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(55, 1, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(56, 2, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(57, 3, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(58, 4, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(59, 5, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(60, 6, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(61, 7, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(62, 8, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(63, 9, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(64, 10, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(65, 11, '2021-04-21 11:14:02', '2021-04-21 11:14:02'),
(66, 12, '2021-04-21 11:14:02', '2021-04-21 11:14:02');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(66) NOT NULL,
  `slug` varchar(66) NOT NULL,
  `description` varchar(666) NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `title`, `slug`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', 'Administrator', 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 'Moderator', 'moderator', 'Moderator', 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 'User', 'user', 'User', 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 2, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 3, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 4, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 5, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 6, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 7, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 8, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 9, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 10, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 11, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 12, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 13, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(1, 14, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 2, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 3, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 4, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 5, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 6, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 7, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 8, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 9, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(2, 10, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 1, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 2, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 3, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 4, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 5, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 6, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 7, '2020-10-25 14:25:08', '2020-10-25 14:25:08'),
(3, 8, '2020-10-25 14:25:08', '2020-10-25 14:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(666) NOT NULL,
  `slug` varchar(66) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `title`, `slug`, `content`, `created_at`, `updated_at`) VALUES
(1, 'Javascript', 'javascript', 'Javascript', '2020-10-29 14:08:39', '2021-04-21 08:10:36'),
(2, 'Angular', 'angular', 'Angular', '2020-10-29 14:08:39', '2021-04-21 08:22:59'),
(3, 'Vue', 'vue', 'Vue', '2020-10-29 14:08:39', '2021-04-21 08:23:04'),
(4, 'React', 'react', 'React', '2020-10-29 14:08:39', '2021-04-21 08:23:08'),
(5, 'Android', 'android', 'Android', '2020-10-30 02:30:00', '2021-04-21 08:23:13'),
(6, 'Next', 'next', 'next', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(7, 'Web Development', 'webdev', 'Web Development', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(8, 'Beginners', 'beginners', 'Beginners', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(9, 'Programming', 'programming', 'Programming', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(10, 'Tutorial', 'tutorial', 'Tutorial', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(11, 'Python', 'python', 'Python', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(12, 'CSS', 'css', 'CSS', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(13, 'Devops', 'devops', 'Devops', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(14, 'Node', 'node', 'Node', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(15, 'HTML', 'html', 'HTML', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(16, 'Github', 'github', 'Github', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(17, 'PHP', 'php', 'PHP', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(18, 'VS Code', 'vscode', 'VS Code', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(19, 'Database', 'database', 'Database', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(20, 'Typescript', 'typescript', 'Typescript', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(21, 'Linux', 'linux', 'Linux', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(22, 'Ruby', 'ruby', 'Ruby', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(23, 'Laravel', 'laravel', 'Laravel', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(24, 'Machine Learning', 'machinelearning', 'Machine Learning', '2021-04-21 08:24:30', '2021-04-21 08:24:37'),
(25, 'Go', 'go', 'Go', '2021-04-21 08:24:30', '2021-04-21 08:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `facebook_id` varchar(66) DEFAULT NULL,
  `google_id` varchar(66) DEFAULT NULL,
  `first_name` varchar(66) NOT NULL,
  `last_name` varchar(66) NOT NULL,
  `user_name` varchar(66) NOT NULL,
  `email` varchar(66) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(666) NOT NULL,
  `remember_token` varchar(666) DEFAULT NULL,
  `auth_token` varchar(6666) DEFAULT NULL,
  `phone_number` varchar(66) DEFAULT NULL,
  `address` varchar(666) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `facebook_id`, `google_id`, `first_name`, `last_name`, `user_name`, `email`, `email_verified_at`, `password`, `remember_token`, `auth_token`, `phone_number`, `address`, `gender`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'Thuc', 'Nguyen', 'de4th-zone', 'nguyenthucofficial@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84336077131', 'HoChiMinh City, VietNam', 'male', 'data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDY0IDY0IiB3aWR0aD0iNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnIGlkPSJBdXRob3JpemF0aW9uX21hbmFnZXIiIGRhdGEtbmFtZT0iQXV0aG9yaXphdGlvbiBtYW5hZ2VyIj48cGF0aCBkPSJtMzYuOTIgMzQuNDQtNC45MiAyNi41NmgtMTJsLTQuOTItMjYuNTZjLjE5LS4wNS4zOC0uMTEuNTctLjE1bDEuMzUtLjI5IDQtMSA1IDMgNS0zIDQgMSAxLjM1LjI5Yy4xOS4wNC4zOC4xLjU3LjE1eiIgZmlsbD0iIzliYzlmZiIvPjxwYXRoIGQ9Im0zNS4zODkgMzQuMDg0LTUuMzg5IDI2LjkxNmgybDQuOTItMjYuNTZjLS4xOS0uMDUtLjM4LS4xMS0uNTctLjE1eiIgZmlsbD0iIzhjYjVlNSIvPjxwYXRoIGQ9Im0xNi42MTEgMzQuMDg0IDUuMzg5IDI2LjkxNmgtMmwtNC45Mi0yNi41NmMuMTktLjA1LjM4LS4xMS41Ny0uMTV6IiBmaWxsPSIjOGNiNWU1Ii8+PHBhdGggZD0ibTE1LjA4MSAzNC40MzdhMTUuOTkgMTUuOTkgMCAwIDAgLTEyLjA4MSAxNS40OTh2MTEuMDY1aDE3eiIgZmlsbD0iIzU4NTk1YiIvPjxwYXRoIGQ9Im0zOSA1OXYtMTZhMiAyIDAgMCAxIDItMmgxdi00LjI5YTE1LjgxMyAxNS44MTMgMCAwIDAgLTUuMDgyLTIuMjdsLTQuOTE4IDI2LjU2aDlhMiAyIDAgMCAxIC0yLTJ6IiBmaWxsPSIjNTg1OTViIi8+PHBhdGggZD0ibTMxIDI5LjY1MWE5Ljk4OCA5Ljk4OCAwIDAgMCA1LTguNjUxdi00aC0uMDc4YTQgNCAwIDAgMSAtMy4xMjItMS41bC0yLjgtMy41LTEuNiAyYTggOCAwIDAgMSAtNi4yNDcgM2gtNi4xNTN2NGE5Ljk4OCA5Ljk4OCAwIDAgMCA1IDguNjUxIDkuOTQxIDkuOTQxIDAgMCAwIDEwIDB6IiBmaWxsPSIjZmZiY2FiIi8+PHBhdGggZD0ibTI4LjUwMyAzOS45OTktLjIxNy0yLjE3LTIuMjg2LTEuODI5LTIuMjg2IDEuODI5LS4yMTcgMi4xNyAyLjUwMyAxLjAwMXoiIGZpbGw9IiNjY2E0MDAiLz48cGF0aCBkPSJtMjYgNDEtMi41MDMtMS4wMDEtMS40OTcgMTUuMDAxIDQgNCA0LTQtMS40OTctMTUuMDAxeiIgZmlsbD0iI2ZmY2QwMCIvPjxwYXRoIGQ9Im0zMSAzM3YtMy4zNDlhOS45NDEgOS45NDEgMCAwIDEgLTEwIDB2My4zNDlsNSAzeiIgZmlsbD0iI2ZmOTQ3OCIvPjxwYXRoIGQ9Im0zMSA0MCA0LTYtNC0xLTUgM3oiIGZpbGw9IiMyNDg4ZmYiLz48cGF0aCBkPSJtMjYgMzYtNS0zLTQgMSA0IDZ6IiBmaWxsPSIjMjQ4OGZmIi8+PHBhdGggZD0ibTI4LjQgMTQgMS42LTIgMi44IDMuNWE0IDQgMCAwIDAgMy4xMjIgMS41aDIuMDc4di00YTEwIDEwIDAgMCAwIC0xMC0xMGgtNGExMCAxMCAwIDAgMCAtMTAgMTB2NGg4LjE1NWE4IDggMCAwIDAgNi4yNDUtM3oiIGZpbGw9IiNmZjk4MTEiLz48cmVjdCBmaWxsPSIjZDgwMDI3IiBoZWlnaHQ9IjIwIiByeD0iMiIgd2lkdGg9IjIyIiB4PSIzOSIgeT0iNDEiLz48cGF0aCBkPSJtNDUgNDF2LThhNSA1IDAgMCAxIDEwIDB2OGgzdi04YTggOCAwIDAgMCAtMTYgMHY4eiIgZmlsbD0iI2ZmOTgxMSIvPjxwYXRoIGQ9Im00MC43IDYwLjk3YTEuOTU0IDEuOTU0IDAgMCAwIC4zLjAzaDE4YTIuMDA2IDIuMDA2IDAgMCAwIDItMnYtMTZjMCA5Ljc0Ni05LjAxOSAxNy42NS0yMC4zIDE3Ljk3eiIgZmlsbD0iI2MyMDAyMyIvPjxwYXRoIGQ9Im0xMyA0OGExIDEgMCAwIDEgMSAxdjEyYTAgMCAwIDAgMSAwIDBoLTJhMCAwIDAgMCAxIDAgMHYtMTJhMSAxIDAgMCAxIDEtMXoiIGZpbGw9IiM0MTQwNDIiLz48cGF0aCBkPSJtNTIgNDlhMiAyIDAgMSAwIC0zIDEuNzIzdjYuMjc3YTEgMSAwIDAgMCAyIDB2LTYuMjc3YTEuOTk0IDEuOTk0IDAgMCAwIDEtMS43MjN6IiBmaWxsPSIjZjFmMmYyIi8+PC9nPjwvc3ZnPg==', '2020-10-25 14:25:10', '2021-04-21 08:58:02'),
(2, 1, NULL, NULL, 'Thuc 2', 'Nguyen', 'nguyen-thuc', 'nguyenthuc@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84666999666', 'Mosul City, Iraq', 'female', 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PGc+PHBhdGggZD0ibTI1Ni4wMjUgNDgzLjMzNCAxMDEuNDI5LTI1LjYxNGM1Ny44OTUtNDguMDc0IDk0Ljc3MS0xMjAuNTg2IDk0Ljc3MS0yMDEuNzE5IDAtMTI1LjE0NC04Ny43MTEtMjI5LjgwMS0yMDUuMDEyLTI1NS44NTItMTM3LjMxNiA0LjYzMS0yNDcuMjEzIDExNy40MDctMjQ3LjIxMyAyNTUuODUxIDAgNzEuMTEyIDI5IDEzNS40NDYgNzUuODEyIDE4MS44MzZ6IiBmaWxsPSIjY2JlMmZmIi8+PC9nPjxnPjxwYXRoIGQ9Im00NDYuOTE0IDI1NmMwIDgzLjkxNS00MC4zODEgMTU4LjM5MS0xMDIuNzY1IDIwNS4wNzlsOTIuMDMxLTIzLjI0MWM0Ni44MTUtNDYuMzkgNzUuODItMTEwLjcyNCA3NS44Mi0xODEuODM4IDAtMTQxLjM4NS0xMTQuNjE1LTI1Ni0yNTYtMjU2LTExLjAyNCAwLTIxLjg4Ni42OTgtMzIuNTQzIDIuMDUgMTI2LjAxOSAxNS45ODggMjIzLjQ1NyAxMjMuNTkgMjIzLjQ1NyAyNTMuOTV6IiBmaWxsPSIjYmVkOGZiIi8+PC9nPjxnPjxnPjxnPjxwYXRoIGQ9Im0zMTkuNjIxIDk2Ljk1MmMwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTgxLjU4MmMtMzAuMDkxIDAtNTQuNDg1IDI0LjM5NC01NC40ODUgNTQuNDg1djYwLjQ5M2gxOTIuMjA5di01OS42MzVjMC0xMy4wNzUtMTAuNTk5LTIzLjY3NC0yMy42NzQtMjMuNjc0aC0uNzk4Yy00LjQxNiAwLTcuOTk2LTMuNTc5LTcuOTk2LTcuOTk1eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zMjguNDE1IDEwNC45NDdoLS43OThjLTQuNDE2IDAtNy45OTYtMy41OC03Ljk5Ni03Ljk5NiAwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTguOTQ1djExNC45NzhoNjUuMDg2di01OS42MzVjLjAwMS0xMy4wNzMtMTAuNTk5LTIzLjY3My0yMy42NzMtMjMuNjczeiIgZmlsbD0iIzJiNGQ2NiIvPjxwYXRoIGQ9Im00MjUuMDQ1IDM3Mi4zNTVjLTYuMjU5LTYuMTgyLTE0LjAwMS0xMC45NjMtMjIuNzktMTMuNzQ1bC02OS44OTEtMjIuMTI4LTc2LjM0OC0yLjY4My03Ni4zOCAyLjY4My02OS44OTEgMjIuMTI4Yy0yMy42NDQgNy40ODYtMzkuNzEzIDI5LjQyOC0zOS43MTMgNTQuMjI5djE5LjA5NGM0NC43ODkgNDcuMzI4IDEwNy40NTEgNzcuNTY4IDE3Ny4xODMgNzkuOTIgNzguMTI4LTE3LjM1MyAxNDMuMTI5LTY5LjU3NiAxNzcuODMtMTM5LjQ5OHoiIGZpbGw9IiM0YTgwYWEiLz48cGF0aCBkPSJtNDQxLjk2OCA0MzEuOTMydi0xOS4wOTRjMC0xNy41MzYtOC4wNC0zMy42MzUtMjEuMTA1LTQ0LjIxMy0zNy4xMTEgNzUuNjI2LTExMC40MjIgMTMwLjI2OC0xOTcuMzQ2IDE0MS4zMTcgMTAuNDkyIDEuMzI5IDIxLjE3OCAyLjAzOCAzMi4wMjYgMi4wNTcgMTAuNDIzLS4wMTYgMjAuNzA4LS42MiAzMC44MjQtMS43ODIgNjEuMDMxLTcuMjEyIDExNS40ODUtMzUuODk0IDE1NS42MDEtNzguMjg1eiIgZmlsbD0iIzQwNzA5MyIvPjxwYXRoIGQ9Im0yNjEuNzk2IDUwOC4xNjhjMTUuNDg5LTMwLjc1MSA1NS44MjItMTE4LjA2NyA0NC4zMjEtMTcyLjYwOWwtNTAuMTAxLTE5LjQ5OS01MC4xNDggMTkuNWMtMTEuODU2IDU2LjIyNSAzMS4zNyAxNDcuMjc3IDQ1LjY4MSAxNzUuMjkgMy40NDItLjgyNiA2Ljg1OS0xLjcyMSAxMC4yNDctMi42ODJ6IiBmaWxsPSIjZTRmNmZmIi8+PHBhdGggZD0ibTI4OC4xOTcgNDgzLjc4OS0yMC4zMTQtNzkuOTE3aC0yMy43NjdsLTIwLjI2NCA3OS42OTkgMjUuMDU4IDI3Ljg5N2M2LjM2MS0xLjQ1NyAxMi42MzQtMy4xNDYgMTguODEtNS4wNTd6IiBmaWxsPSIjZTI4MDg2Ii8+PHBhdGggZD0ibTI0OS4zMDIgNTExLjkwNWMyLjA3NS4wNTQgNC4xNTQuMDkxIDYuMjQxLjA5NSAyLjQxNS0uMDA0IDQuODIyLS4wNDYgNy4yMjItLjExM2wxMi45MDctMTQuMjU5Yy0xMC4xNTkgMy41NjQtMjAuNjEgNi41MDYtMzEuMzA5IDguNzc5eiIgZmlsbD0iI2RkNjM2ZSIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Im0yOTguNzc0IDMyOC4xODN2LTQ1LjA2NmgtODUuNTh2NDUuMDY2YzAgMjMuNjMyIDQyLjc5IDQ5LjQ0NiA0Mi43OSA0OS40NDZzNDIuNzktMjUuODE0IDQyLjc5LTQ5LjQ0NnoiIGZpbGw9IiNmZmRkY2UiLz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PHBhdGggZD0ibTM1Mi4wODkgMTgwLjMxOGgtMTYuMzU5Yy05LjA5OCAwLTE2LjQ3My03LjM3NS0xNi40NzMtMTYuNDczdi05LjAxNWMwLTExLjg1MS0xMS41OTUtMjAuMjMtMjIuODQ3LTE2LjUxMS0yNi4yNDMgOC42NzQtNTQuNTc5IDguNjc2LTgwLjgyMy4wMDZsLS4wMzEtLjAxYy0xMS4yNTItMy43MTctMjIuODQ1IDQuNjYyLTIyLjg0NSAxNi41MTJ2OS4wMTljMCA5LjA5OC03LjM3NSAxNi40NzMtMTYuNDczIDE2LjQ3M2gtMTYuMzU4djI2LjkzOGMwIDYuODgzIDUuNTggMTIuNDY0IDEyLjQ2NCAxMi40NjQgMi4xNzIgMCAzLjkzOSAxLjcwMSA0LjA3NiAzLjg2OSAyLjYyOCA0MS42NjggMzcuMjM1IDc0LjY1NCA3OS41NjUgNzQuNjU0IDQyLjMzIDAgNzYuOTM3LTMyLjk4NiA3OS41NjUtNzQuNjU0LjEzNy0yLjE2NyAxLjkwNC0zLjg2OSA0LjA3Ni0zLjg2OSA2Ljg4MyAwIDEyLjQ2NC01LjU4IDEyLjQ2NC0xMi40NjR2LTI2LjkzOXoiIGZpbGw9IiNmZmRkY2UiLz48cGF0aCBkPSJtMzM1LjczIDE4MC4zMThjLTkuMDk4IDAtMTYuNDczLTcuMzc1LTE2LjQ3My0xNi40NzN2LTkuMDE1YzAtMTEuODUxLTExLjU5NS0yMC4yMy0yMi44NDctMTYuNTExLTMuMTA4IDEuMDI3LTYuMjQ3IDEuOTIzLTkuNDA3IDIuNzA3djg4Ljk3MmMtLjQzOCAyOC45NDgtMTYuMyA1NC4xNDItMzkuNzI1IDY3Ljc1OCAyLjg2MS4zMTEgNS43NjMuNDg2IDguNzA2LjQ4NiA0Mi4zMyAwIDc2LjkzNy0zMi45ODYgNzkuNTY1LTc0LjY1NC4xMzctMi4xNjcgMS45MDQtMy44NjkgNC4wNzYtMy44NjkgNi44ODMgMCAxMi40NjQtNS41OCAxMi40NjQtMTIuNDY0di0yNi45MzhoLTE2LjM1OXoiIGZpbGw9IiNmZmNiYmUiLz48L2c+PGcgZmlsbD0iI2Y0ZmJmZiI+PHBhdGggZD0ibTIxMy4xOTQgMzE2LjA2LTMzLjU1OCAyNy4yNjcgMzUuMTkyIDQzLjUxM2M0LjI4MSA0LjE2OCAxMS4wMTkgNC40MjQgMTUuNjA1LjU5NGwyNi40NjUtMjIuMTA3eiIvPjxwYXRoIGQ9Im0yOTguNzkgMzE2LjA2LTQxLjg5MiA0OS4yNjcgMjQuODc0IDIxLjI2OGM0LjU1NyAzLjg5NiAxMS4zMjcgMy43IDE1LjY1MS0uNDUzbDM0Ljk0LTQyLjgxNXoiLz48L2c+PC9nPjxwYXRoIGQ9Im0yMTMuMTk0IDMxNi4wNi00OS4yNTYgMjQuMTk5Yy0zLjc1IDEuODQyLTUuMjU2IDYuNDA0LTMuMzQxIDEwLjExN2w5LjY1IDE4LjcxYzIuNTAxIDQuODQ4IDEuNTc4IDEwLjc1Ni0yLjI4MiAxNC42MS0xLjk4NyAxLjk4My00LjEzOSA0LjEzMS02LjAwNCA1Ljk5My0zLjMzOCAzLjMzMi00LjUzNyA4LjI1NS0zLjA2NyAxMi43MzcgMTEuNjUxIDM1LjUxNyA2Ny43MjUgODkuODI4IDg4Ljk0NiAxMDkuNDc4IDEuNDI3LjAzOCAyLjg1Ny4wNjQgNC4yOS4wOC0xNS4zODktMjkuOTMzLTY5LjkyMi0xNDMuNjU1LTM4LjkzNi0xOTUuOTI0eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zNDQuMDE5IDM4My42OTVjLTMuODYxLTMuODU0LTQuNzgzLTkuNzYyLTIuMjgyLTE0LjYxbDkuNjUtMTguNzFjMS45MTUtMy43MTMuNDA5LTguMjc1LTMuMzQxLTEwLjExN2wtNDkuMjU2LTI0LjE5OGMzMC45NzggNTIuMjU1LTIzLjUxNyAxNjUuOTI5LTM4LjkyMyAxOTUuOSAxLjQ0OC0uMDI1IDIuODkzLS4wNjEgNC4zMzUtLjEwOSAyMS4yNjUtMTkuNjk1IDc3LjI0OC03My45NCA4OC44ODgtMTA5LjQyNCAxLjQ3LTQuNDgyLjI3MS05LjQwNS0zLjA2Ny0xMi43MzctMS44NjUtMS44NjMtNC4wMTctNC4wMTItNi4wMDQtNS45OTV6IiBmaWxsPSIjMzY1ZTdkIi8+PHBhdGggZD0ibTI1Ni44OTggMzY1LjMyNy0yNi4wNiAyMS43NjQgMTMuMjc4IDE2Ljc4MWgyMy43NjdsMTMuMjc5LTE3Ljc3MXoiIGZpbGw9IiNkZDYzNmUiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', '2020-10-25 14:25:10', '2021-04-21 08:59:09'),
(3, 1, NULL, NULL, 'Thuc 3', 'Nguyen', 'thuc-jwt', 'thucjwt@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84999666999', 'Moskva, Russia', 'orther', 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PGc+PHBhdGggZD0ibTI1Ni4wMjUgNDgzLjMzNCAxMDEuNDI5LTI1LjYxNGM1Ny44OTUtNDguMDc0IDk0Ljc3MS0xMjAuNTg2IDk0Ljc3MS0yMDEuNzE5IDAtMTI1LjE0NC04Ny43MTEtMjI5LjgwMS0yMDUuMDEyLTI1NS44NTItMTM3LjMxNiA0LjYzMS0yNDcuMjEzIDExNy40MDctMjQ3LjIxMyAyNTUuODUxIDAgNzEuMTEyIDI5IDEzNS40NDYgNzUuODEyIDE4MS44MzZ6IiBmaWxsPSIjY2JlMmZmIi8+PC9nPjxnPjxwYXRoIGQ9Im00NDYuOTE0IDI1NmMwIDgzLjkxNS00MC4zODEgMTU4LjM5MS0xMDIuNzY1IDIwNS4wNzlsOTIuMDMxLTIzLjI0MWM0Ni44MTUtNDYuMzkgNzUuODItMTEwLjcyNCA3NS44Mi0xODEuODM4IDAtMTQxLjM4NS0xMTQuNjE1LTI1Ni0yNTYtMjU2LTExLjAyNCAwLTIxLjg4Ni42OTgtMzIuNTQzIDIuMDUgMTI2LjAxOSAxNS45ODggMjIzLjQ1NyAxMjMuNTkgMjIzLjQ1NyAyNTMuOTV6IiBmaWxsPSIjYmVkOGZiIi8+PC9nPjxnPjxnPjxnPjxwYXRoIGQ9Im0zMTkuNjIxIDk2Ljk1MmMwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTgxLjU4MmMtMzAuMDkxIDAtNTQuNDg1IDI0LjM5NC01NC40ODUgNTQuNDg1djYwLjQ5M2gxOTIuMjA5di01OS42MzVjMC0xMy4wNzUtMTAuNTk5LTIzLjY3NC0yMy42NzQtMjMuNjc0aC0uNzk4Yy00LjQxNiAwLTcuOTk2LTMuNTc5LTcuOTk2LTcuOTk1eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zMjguNDE1IDEwNC45NDdoLS43OThjLTQuNDE2IDAtNy45OTYtMy41OC03Ljk5Ni03Ljk5NiAwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTguOTQ1djExNC45NzhoNjUuMDg2di01OS42MzVjLjAwMS0xMy4wNzMtMTAuNTk5LTIzLjY3My0yMy42NzMtMjMuNjczeiIgZmlsbD0iIzJiNGQ2NiIvPjxwYXRoIGQ9Im00MjUuMDQ1IDM3Mi4zNTVjLTYuMjU5LTYuMTgyLTE0LjAwMS0xMC45NjMtMjIuNzktMTMuNzQ1bC02OS44OTEtMjIuMTI4LTc2LjM0OC0yLjY4My03Ni4zOCAyLjY4My02OS44OTEgMjIuMTI4Yy0yMy42NDQgNy40ODYtMzkuNzEzIDI5LjQyOC0zOS43MTMgNTQuMjI5djE5LjA5NGM0NC43ODkgNDcuMzI4IDEwNy40NTEgNzcuNTY4IDE3Ny4xODMgNzkuOTIgNzguMTI4LTE3LjM1MyAxNDMuMTI5LTY5LjU3NiAxNzcuODMtMTM5LjQ5OHoiIGZpbGw9IiM0YTgwYWEiLz48cGF0aCBkPSJtNDQxLjk2OCA0MzEuOTMydi0xOS4wOTRjMC0xNy41MzYtOC4wNC0zMy42MzUtMjEuMTA1LTQ0LjIxMy0zNy4xMTEgNzUuNjI2LTExMC40MjIgMTMwLjI2OC0xOTcuMzQ2IDE0MS4zMTcgMTAuNDkyIDEuMzI5IDIxLjE3OCAyLjAzOCAzMi4wMjYgMi4wNTcgMTAuNDIzLS4wMTYgMjAuNzA4LS42MiAzMC44MjQtMS43ODIgNjEuMDMxLTcuMjEyIDExNS40ODUtMzUuODk0IDE1NS42MDEtNzguMjg1eiIgZmlsbD0iIzQwNzA5MyIvPjxwYXRoIGQ9Im0yNjEuNzk2IDUwOC4xNjhjMTUuNDg5LTMwLjc1MSA1NS44MjItMTE4LjA2NyA0NC4zMjEtMTcyLjYwOWwtNTAuMTAxLTE5LjQ5OS01MC4xNDggMTkuNWMtMTEuODU2IDU2LjIyNSAzMS4zNyAxNDcuMjc3IDQ1LjY4MSAxNzUuMjkgMy40NDItLjgyNiA2Ljg1OS0xLjcyMSAxMC4yNDctMi42ODJ6IiBmaWxsPSIjZTRmNmZmIi8+PHBhdGggZD0ibTI4OC4xOTcgNDgzLjc4OS0yMC4zMTQtNzkuOTE3aC0yMy43NjdsLTIwLjI2NCA3OS42OTkgMjUuMDU4IDI3Ljg5N2M2LjM2MS0xLjQ1NyAxMi42MzQtMy4xNDYgMTguODEtNS4wNTd6IiBmaWxsPSIjZTI4MDg2Ii8+PHBhdGggZD0ibTI0OS4zMDIgNTExLjkwNWMyLjA3NS4wNTQgNC4xNTQuMDkxIDYuMjQxLjA5NSAyLjQxNS0uMDA0IDQuODIyLS4wNDYgNy4yMjItLjExM2wxMi45MDctMTQuMjU5Yy0xMC4xNTkgMy41NjQtMjAuNjEgNi41MDYtMzEuMzA5IDguNzc5eiIgZmlsbD0iI2RkNjM2ZSIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Im0yOTguNzc0IDMyOC4xODN2LTQ1LjA2NmgtODUuNTh2NDUuMDY2YzAgMjMuNjMyIDQyLjc5IDQ5LjQ0NiA0Mi43OSA0OS40NDZzNDIuNzktMjUuODE0IDQyLjc5LTQ5LjQ0NnoiIGZpbGw9IiNmZmRkY2UiLz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PHBhdGggZD0ibTM1Mi4wODkgMTgwLjMxOGgtMTYuMzU5Yy05LjA5OCAwLTE2LjQ3My03LjM3NS0xNi40NzMtMTYuNDczdi05LjAxNWMwLTExLjg1MS0xMS41OTUtMjAuMjMtMjIuODQ3LTE2LjUxMS0yNi4yNDMgOC42NzQtNTQuNTc5IDguNjc2LTgwLjgyMy4wMDZsLS4wMzEtLjAxYy0xMS4yNTItMy43MTctMjIuODQ1IDQuNjYyLTIyLjg0NSAxNi41MTJ2OS4wMTljMCA5LjA5OC03LjM3NSAxNi40NzMtMTYuNDczIDE2LjQ3M2gtMTYuMzU4djI2LjkzOGMwIDYuODgzIDUuNTggMTIuNDY0IDEyLjQ2NCAxMi40NjQgMi4xNzIgMCAzLjkzOSAxLjcwMSA0LjA3NiAzLjg2OSAyLjYyOCA0MS42NjggMzcuMjM1IDc0LjY1NCA3OS41NjUgNzQuNjU0IDQyLjMzIDAgNzYuOTM3LTMyLjk4NiA3OS41NjUtNzQuNjU0LjEzNy0yLjE2NyAxLjkwNC0zLjg2OSA0LjA3Ni0zLjg2OSA2Ljg4MyAwIDEyLjQ2NC01LjU4IDEyLjQ2NC0xMi40NjR2LTI2LjkzOXoiIGZpbGw9IiNmZmRkY2UiLz48cGF0aCBkPSJtMzM1LjczIDE4MC4zMThjLTkuMDk4IDAtMTYuNDczLTcuMzc1LTE2LjQ3My0xNi40NzN2LTkuMDE1YzAtMTEuODUxLTExLjU5NS0yMC4yMy0yMi44NDctMTYuNTExLTMuMTA4IDEuMDI3LTYuMjQ3IDEuOTIzLTkuNDA3IDIuNzA3djg4Ljk3MmMtLjQzOCAyOC45NDgtMTYuMyA1NC4xNDItMzkuNzI1IDY3Ljc1OCAyLjg2MS4zMTEgNS43NjMuNDg2IDguNzA2LjQ4NiA0Mi4zMyAwIDc2LjkzNy0zMi45ODYgNzkuNTY1LTc0LjY1NC4xMzctMi4xNjcgMS45MDQtMy44NjkgNC4wNzYtMy44NjkgNi44ODMgMCAxMi40NjQtNS41OCAxMi40NjQtMTIuNDY0di0yNi45MzhoLTE2LjM1OXoiIGZpbGw9IiNmZmNiYmUiLz48L2c+PGcgZmlsbD0iI2Y0ZmJmZiI+PHBhdGggZD0ibTIxMy4xOTQgMzE2LjA2LTMzLjU1OCAyNy4yNjcgMzUuMTkyIDQzLjUxM2M0LjI4MSA0LjE2OCAxMS4wMTkgNC40MjQgMTUuNjA1LjU5NGwyNi40NjUtMjIuMTA3eiIvPjxwYXRoIGQ9Im0yOTguNzkgMzE2LjA2LTQxLjg5MiA0OS4yNjcgMjQuODc0IDIxLjI2OGM0LjU1NyAzLjg5NiAxMS4zMjcgMy43IDE1LjY1MS0uNDUzbDM0Ljk0LTQyLjgxNXoiLz48L2c+PC9nPjxwYXRoIGQ9Im0yMTMuMTk0IDMxNi4wNi00OS4yNTYgMjQuMTk5Yy0zLjc1IDEuODQyLTUuMjU2IDYuNDA0LTMuMzQxIDEwLjExN2w5LjY1IDE4LjcxYzIuNTAxIDQuODQ4IDEuNTc4IDEwLjc1Ni0yLjI4MiAxNC42MS0xLjk4NyAxLjk4My00LjEzOSA0LjEzMS02LjAwNCA1Ljk5My0zLjMzOCAzLjMzMi00LjUzNyA4LjI1NS0zLjA2NyAxMi43MzcgMTEuNjUxIDM1LjUxNyA2Ny43MjUgODkuODI4IDg4Ljk0NiAxMDkuNDc4IDEuNDI3LjAzOCAyLjg1Ny4wNjQgNC4yOS4wOC0xNS4zODktMjkuOTMzLTY5LjkyMi0xNDMuNjU1LTM4LjkzNi0xOTUuOTI0eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zNDQuMDE5IDM4My42OTVjLTMuODYxLTMuODU0LTQuNzgzLTkuNzYyLTIuMjgyLTE0LjYxbDkuNjUtMTguNzFjMS45MTUtMy43MTMuNDA5LTguMjc1LTMuMzQxLTEwLjExN2wtNDkuMjU2LTI0LjE5OGMzMC45NzggNTIuMjU1LTIzLjUxNyAxNjUuOTI5LTM4LjkyMyAxOTUuOSAxLjQ0OC0uMDI1IDIuODkzLS4wNjEgNC4zMzUtLjEwOSAyMS4yNjUtMTkuNjk1IDc3LjI0OC03My45NCA4OC44ODgtMTA5LjQyNCAxLjQ3LTQuNDgyLjI3MS05LjQwNS0zLjA2Ny0xMi43MzctMS44NjUtMS44NjMtNC4wMTctNC4wMTItNi4wMDQtNS45OTV6IiBmaWxsPSIjMzY1ZTdkIi8+PHBhdGggZD0ibTI1Ni44OTggMzY1LjMyNy0yNi4wNiAyMS43NjQgMTMuMjc4IDE2Ljc4MWgyMy43NjdsMTMuMjc5LTE3Ljc3MXoiIGZpbGw9IiNkZDYzNmUiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', '2020-10-25 14:25:10', '2021-04-21 08:59:12'),
(4, 1, NULL, NULL, 'Thuc 4', 'Nguyen', 'd4z-d4z', 'd4zd4z@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84123456789', 'New York City, United States', 'male', 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PGc+PHBhdGggZD0ibTI1Ni4wMjUgNDgzLjMzNCAxMDEuNDI5LTI1LjYxNGM1Ny44OTUtNDguMDc0IDk0Ljc3MS0xMjAuNTg2IDk0Ljc3MS0yMDEuNzE5IDAtMTI1LjE0NC04Ny43MTEtMjI5LjgwMS0yMDUuMDEyLTI1NS44NTItMTM3LjMxNiA0LjYzMS0yNDcuMjEzIDExNy40MDctMjQ3LjIxMyAyNTUuODUxIDAgNzEuMTEyIDI5IDEzNS40NDYgNzUuODEyIDE4MS44MzZ6IiBmaWxsPSIjY2JlMmZmIi8+PC9nPjxnPjxwYXRoIGQ9Im00NDYuOTE0IDI1NmMwIDgzLjkxNS00MC4zODEgMTU4LjM5MS0xMDIuNzY1IDIwNS4wNzlsOTIuMDMxLTIzLjI0MWM0Ni44MTUtNDYuMzkgNzUuODItMTEwLjcyNCA3NS44Mi0xODEuODM4IDAtMTQxLjM4NS0xMTQuNjE1LTI1Ni0yNTYtMjU2LTExLjAyNCAwLTIxLjg4Ni42OTgtMzIuNTQzIDIuMDUgMTI2LjAxOSAxNS45ODggMjIzLjQ1NyAxMjMuNTkgMjIzLjQ1NyAyNTMuOTV6IiBmaWxsPSIjYmVkOGZiIi8+PC9nPjxnPjxnPjxnPjxwYXRoIGQ9Im0zMTkuNjIxIDk2Ljk1MmMwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTgxLjU4MmMtMzAuMDkxIDAtNTQuNDg1IDI0LjM5NC01NC40ODUgNTQuNDg1djYwLjQ5M2gxOTIuMjA5di01OS42MzVjMC0xMy4wNzUtMTAuNTk5LTIzLjY3NC0yMy42NzQtMjMuNjc0aC0uNzk4Yy00LjQxNiAwLTcuOTk2LTMuNTc5LTcuOTk2LTcuOTk1eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zMjguNDE1IDEwNC45NDdoLS43OThjLTQuNDE2IDAtNy45OTYtMy41OC03Ljk5Ni03Ljk5NiAwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTguOTQ1djExNC45NzhoNjUuMDg2di01OS42MzVjLjAwMS0xMy4wNzMtMTAuNTk5LTIzLjY3My0yMy42NzMtMjMuNjczeiIgZmlsbD0iIzJiNGQ2NiIvPjxwYXRoIGQ9Im00MjUuMDQ1IDM3Mi4zNTVjLTYuMjU5LTYuMTgyLTE0LjAwMS0xMC45NjMtMjIuNzktMTMuNzQ1bC02OS44OTEtMjIuMTI4LTc2LjM0OC0yLjY4My03Ni4zOCAyLjY4My02OS44OTEgMjIuMTI4Yy0yMy42NDQgNy40ODYtMzkuNzEzIDI5LjQyOC0zOS43MTMgNTQuMjI5djE5LjA5NGM0NC43ODkgNDcuMzI4IDEwNy40NTEgNzcuNTY4IDE3Ny4xODMgNzkuOTIgNzguMTI4LTE3LjM1MyAxNDMuMTI5LTY5LjU3NiAxNzcuODMtMTM5LjQ5OHoiIGZpbGw9IiM0YTgwYWEiLz48cGF0aCBkPSJtNDQxLjk2OCA0MzEuOTMydi0xOS4wOTRjMC0xNy41MzYtOC4wNC0zMy42MzUtMjEuMTA1LTQ0LjIxMy0zNy4xMTEgNzUuNjI2LTExMC40MjIgMTMwLjI2OC0xOTcuMzQ2IDE0MS4zMTcgMTAuNDkyIDEuMzI5IDIxLjE3OCAyLjAzOCAzMi4wMjYgMi4wNTcgMTAuNDIzLS4wMTYgMjAuNzA4LS42MiAzMC44MjQtMS43ODIgNjEuMDMxLTcuMjEyIDExNS40ODUtMzUuODk0IDE1NS42MDEtNzguMjg1eiIgZmlsbD0iIzQwNzA5MyIvPjxwYXRoIGQ9Im0yNjEuNzk2IDUwOC4xNjhjMTUuNDg5LTMwLjc1MSA1NS44MjItMTE4LjA2NyA0NC4zMjEtMTcyLjYwOWwtNTAuMTAxLTE5LjQ5OS01MC4xNDggMTkuNWMtMTEuODU2IDU2LjIyNSAzMS4zNyAxNDcuMjc3IDQ1LjY4MSAxNzUuMjkgMy40NDItLjgyNiA2Ljg1OS0xLjcyMSAxMC4yNDctMi42ODJ6IiBmaWxsPSIjZTRmNmZmIi8+PHBhdGggZD0ibTI4OC4xOTcgNDgzLjc4OS0yMC4zMTQtNzkuOTE3aC0yMy43NjdsLTIwLjI2NCA3OS42OTkgMjUuMDU4IDI3Ljg5N2M2LjM2MS0xLjQ1NyAxMi42MzQtMy4xNDYgMTguODEtNS4wNTd6IiBmaWxsPSIjZTI4MDg2Ii8+PHBhdGggZD0ibTI0OS4zMDIgNTExLjkwNWMyLjA3NS4wNTQgNC4xNTQuMDkxIDYuMjQxLjA5NSAyLjQxNS0uMDA0IDQuODIyLS4wNDYgNy4yMjItLjExM2wxMi45MDctMTQuMjU5Yy0xMC4xNTkgMy41NjQtMjAuNjEgNi41MDYtMzEuMzA5IDguNzc5eiIgZmlsbD0iI2RkNjM2ZSIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Im0yOTguNzc0IDMyOC4xODN2LTQ1LjA2NmgtODUuNTh2NDUuMDY2YzAgMjMuNjMyIDQyLjc5IDQ5LjQ0NiA0Mi43OSA0OS40NDZzNDIuNzktMjUuODE0IDQyLjc5LTQ5LjQ0NnoiIGZpbGw9IiNmZmRkY2UiLz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PHBhdGggZD0ibTM1Mi4wODkgMTgwLjMxOGgtMTYuMzU5Yy05LjA5OCAwLTE2LjQ3My03LjM3NS0xNi40NzMtMTYuNDczdi05LjAxNWMwLTExLjg1MS0xMS41OTUtMjAuMjMtMjIuODQ3LTE2LjUxMS0yNi4yNDMgOC42NzQtNTQuNTc5IDguNjc2LTgwLjgyMy4wMDZsLS4wMzEtLjAxYy0xMS4yNTItMy43MTctMjIuODQ1IDQuNjYyLTIyLjg0NSAxNi41MTJ2OS4wMTljMCA5LjA5OC03LjM3NSAxNi40NzMtMTYuNDczIDE2LjQ3M2gtMTYuMzU4djI2LjkzOGMwIDYuODgzIDUuNTggMTIuNDY0IDEyLjQ2NCAxMi40NjQgMi4xNzIgMCAzLjkzOSAxLjcwMSA0LjA3NiAzLjg2OSAyLjYyOCA0MS42NjggMzcuMjM1IDc0LjY1NCA3OS41NjUgNzQuNjU0IDQyLjMzIDAgNzYuOTM3LTMyLjk4NiA3OS41NjUtNzQuNjU0LjEzNy0yLjE2NyAxLjkwNC0zLjg2OSA0LjA3Ni0zLjg2OSA2Ljg4MyAwIDEyLjQ2NC01LjU4IDEyLjQ2NC0xMi40NjR2LTI2LjkzOXoiIGZpbGw9IiNmZmRkY2UiLz48cGF0aCBkPSJtMzM1LjczIDE4MC4zMThjLTkuMDk4IDAtMTYuNDczLTcuMzc1LTE2LjQ3My0xNi40NzN2LTkuMDE1YzAtMTEuODUxLTExLjU5NS0yMC4yMy0yMi44NDctMTYuNTExLTMuMTA4IDEuMDI3LTYuMjQ3IDEuOTIzLTkuNDA3IDIuNzA3djg4Ljk3MmMtLjQzOCAyOC45NDgtMTYuMyA1NC4xNDItMzkuNzI1IDY3Ljc1OCAyLjg2MS4zMTEgNS43NjMuNDg2IDguNzA2LjQ4NiA0Mi4zMyAwIDc2LjkzNy0zMi45ODYgNzkuNTY1LTc0LjY1NC4xMzctMi4xNjcgMS45MDQtMy44NjkgNC4wNzYtMy44NjkgNi44ODMgMCAxMi40NjQtNS41OCAxMi40NjQtMTIuNDY0di0yNi45MzhoLTE2LjM1OXoiIGZpbGw9IiNmZmNiYmUiLz48L2c+PGcgZmlsbD0iI2Y0ZmJmZiI+PHBhdGggZD0ibTIxMy4xOTQgMzE2LjA2LTMzLjU1OCAyNy4yNjcgMzUuMTkyIDQzLjUxM2M0LjI4MSA0LjE2OCAxMS4wMTkgNC40MjQgMTUuNjA1LjU5NGwyNi40NjUtMjIuMTA3eiIvPjxwYXRoIGQ9Im0yOTguNzkgMzE2LjA2LTQxLjg5MiA0OS4yNjcgMjQuODc0IDIxLjI2OGM0LjU1NyAzLjg5NiAxMS4zMjcgMy43IDE1LjY1MS0uNDUzbDM0Ljk0LTQyLjgxNXoiLz48L2c+PC9nPjxwYXRoIGQ9Im0yMTMuMTk0IDMxNi4wNi00OS4yNTYgMjQuMTk5Yy0zLjc1IDEuODQyLTUuMjU2IDYuNDA0LTMuMzQxIDEwLjExN2w5LjY1IDE4LjcxYzIuNTAxIDQuODQ4IDEuNTc4IDEwLjc1Ni0yLjI4MiAxNC42MS0xLjk4NyAxLjk4My00LjEzOSA0LjEzMS02LjAwNCA1Ljk5My0zLjMzOCAzLjMzMi00LjUzNyA4LjI1NS0zLjA2NyAxMi43MzcgMTEuNjUxIDM1LjUxNyA2Ny43MjUgODkuODI4IDg4Ljk0NiAxMDkuNDc4IDEuNDI3LjAzOCAyLjg1Ny4wNjQgNC4yOS4wOC0xNS4zODktMjkuOTMzLTY5LjkyMi0xNDMuNjU1LTM4LjkzNi0xOTUuOTI0eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zNDQuMDE5IDM4My42OTVjLTMuODYxLTMuODU0LTQuNzgzLTkuNzYyLTIuMjgyLTE0LjYxbDkuNjUtMTguNzFjMS45MTUtMy43MTMuNDA5LTguMjc1LTMuMzQxLTEwLjExN2wtNDkuMjU2LTI0LjE5OGMzMC45NzggNTIuMjU1LTIzLjUxNyAxNjUuOTI5LTM4LjkyMyAxOTUuOSAxLjQ0OC0uMDI1IDIuODkzLS4wNjEgNC4zMzUtLjEwOSAyMS4yNjUtMTkuNjk1IDc3LjI0OC03My45NCA4OC44ODgtMTA5LjQyNCAxLjQ3LTQuNDgyLjI3MS05LjQwNS0zLjA2Ny0xMi43MzctMS44NjUtMS44NjMtNC4wMTctNC4wMTItNi4wMDQtNS45OTV6IiBmaWxsPSIjMzY1ZTdkIi8+PHBhdGggZD0ibTI1Ni44OTggMzY1LjMyNy0yNi4wNiAyMS43NjQgMTMuMjc4IDE2Ljc4MWgyMy43NjdsMTMuMjc5LTE3Ljc3MXoiIGZpbGw9IiNkZDYzNmUiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', '2020-10-25 14:25:10', '2021-04-21 08:59:14'),
(5, 1, NULL, NULL, 'Thuc 5', 'Nguyen', 'call-back', 'callback@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84987654321', 'TayNinh City, VietNam', 'male', 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PGc+PHBhdGggZD0ibTI1Ni4wMjUgNDgzLjMzNCAxMDEuNDI5LTI1LjYxNGM1Ny44OTUtNDguMDc0IDk0Ljc3MS0xMjAuNTg2IDk0Ljc3MS0yMDEuNzE5IDAtMTI1LjE0NC04Ny43MTEtMjI5LjgwMS0yMDUuMDEyLTI1NS44NTItMTM3LjMxNiA0LjYzMS0yNDcuMjEzIDExNy40MDctMjQ3LjIxMyAyNTUuODUxIDAgNzEuMTEyIDI5IDEzNS40NDYgNzUuODEyIDE4MS44MzZ6IiBmaWxsPSIjY2JlMmZmIi8+PC9nPjxnPjxwYXRoIGQ9Im00NDYuOTE0IDI1NmMwIDgzLjkxNS00MC4zODEgMTU4LjM5MS0xMDIuNzY1IDIwNS4wNzlsOTIuMDMxLTIzLjI0MWM0Ni44MTUtNDYuMzkgNzUuODItMTEwLjcyNCA3NS44Mi0xODEuODM4IDAtMTQxLjM4NS0xMTQuNjE1LTI1Ni0yNTYtMjU2LTExLjAyNCAwLTIxLjg4Ni42OTgtMzIuNTQzIDIuMDUgMTI2LjAxOSAxNS45ODggMjIzLjQ1NyAxMjMuNTkgMjIzLjQ1NyAyNTMuOTV6IiBmaWxsPSIjYmVkOGZiIi8+PC9nPjxnPjxnPjxnPjxwYXRoIGQ9Im0zMTkuNjIxIDk2Ljk1MmMwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTgxLjU4MmMtMzAuMDkxIDAtNTQuNDg1IDI0LjM5NC01NC40ODUgNTQuNDg1djYwLjQ5M2gxOTIuMjA5di01OS42MzVjMC0xMy4wNzUtMTAuNTk5LTIzLjY3NC0yMy42NzQtMjMuNjc0aC0uNzk4Yy00LjQxNiAwLTcuOTk2LTMuNTc5LTcuOTk2LTcuOTk1eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zMjguNDE1IDEwNC45NDdoLS43OThjLTQuNDE2IDAtNy45OTYtMy41OC03Ljk5Ni03Ljk5NiAwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTguOTQ1djExNC45NzhoNjUuMDg2di01OS42MzVjLjAwMS0xMy4wNzMtMTAuNTk5LTIzLjY3My0yMy42NzMtMjMuNjczeiIgZmlsbD0iIzJiNGQ2NiIvPjxwYXRoIGQ9Im00MjUuMDQ1IDM3Mi4zNTVjLTYuMjU5LTYuMTgyLTE0LjAwMS0xMC45NjMtMjIuNzktMTMuNzQ1bC02OS44OTEtMjIuMTI4LTc2LjM0OC0yLjY4My03Ni4zOCAyLjY4My02OS44OTEgMjIuMTI4Yy0yMy42NDQgNy40ODYtMzkuNzEzIDI5LjQyOC0zOS43MTMgNTQuMjI5djE5LjA5NGM0NC43ODkgNDcuMzI4IDEwNy40NTEgNzcuNTY4IDE3Ny4xODMgNzkuOTIgNzguMTI4LTE3LjM1MyAxNDMuMTI5LTY5LjU3NiAxNzcuODMtMTM5LjQ5OHoiIGZpbGw9IiM0YTgwYWEiLz48cGF0aCBkPSJtNDQxLjk2OCA0MzEuOTMydi0xOS4wOTRjMC0xNy41MzYtOC4wNC0zMy42MzUtMjEuMTA1LTQ0LjIxMy0zNy4xMTEgNzUuNjI2LTExMC40MjIgMTMwLjI2OC0xOTcuMzQ2IDE0MS4zMTcgMTAuNDkyIDEuMzI5IDIxLjE3OCAyLjAzOCAzMi4wMjYgMi4wNTcgMTAuNDIzLS4wMTYgMjAuNzA4LS42MiAzMC44MjQtMS43ODIgNjEuMDMxLTcuMjEyIDExNS40ODUtMzUuODk0IDE1NS42MDEtNzguMjg1eiIgZmlsbD0iIzQwNzA5MyIvPjxwYXRoIGQ9Im0yNjEuNzk2IDUwOC4xNjhjMTUuNDg5LTMwLjc1MSA1NS44MjItMTE4LjA2NyA0NC4zMjEtMTcyLjYwOWwtNTAuMTAxLTE5LjQ5OS01MC4xNDggMTkuNWMtMTEuODU2IDU2LjIyNSAzMS4zNyAxNDcuMjc3IDQ1LjY4MSAxNzUuMjkgMy40NDItLjgyNiA2Ljg1OS0xLjcyMSAxMC4yNDctMi42ODJ6IiBmaWxsPSIjZTRmNmZmIi8+PHBhdGggZD0ibTI4OC4xOTcgNDgzLjc4OS0yMC4zMTQtNzkuOTE3aC0yMy43NjdsLTIwLjI2NCA3OS42OTkgMjUuMDU4IDI3Ljg5N2M2LjM2MS0xLjQ1NyAxMi42MzQtMy4xNDYgMTguODEtNS4wNTd6IiBmaWxsPSIjZTI4MDg2Ii8+PHBhdGggZD0ibTI0OS4zMDIgNTExLjkwNWMyLjA3NS4wNTQgNC4xNTQuMDkxIDYuMjQxLjA5NSAyLjQxNS0uMDA0IDQuODIyLS4wNDYgNy4yMjItLjExM2wxMi45MDctMTQuMjU5Yy0xMC4xNTkgMy41NjQtMjAuNjEgNi41MDYtMzEuMzA5IDguNzc5eiIgZmlsbD0iI2RkNjM2ZSIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Im0yOTguNzc0IDMyOC4xODN2LTQ1LjA2NmgtODUuNTh2NDUuMDY2YzAgMjMuNjMyIDQyLjc5IDQ5LjQ0NiA0Mi43OSA0OS40NDZzNDIuNzktMjUuODE0IDQyLjc5LTQ5LjQ0NnoiIGZpbGw9IiNmZmRkY2UiLz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PHBhdGggZD0ibTM1Mi4wODkgMTgwLjMxOGgtMTYuMzU5Yy05LjA5OCAwLTE2LjQ3My03LjM3NS0xNi40NzMtMTYuNDczdi05LjAxNWMwLTExLjg1MS0xMS41OTUtMjAuMjMtMjIuODQ3LTE2LjUxMS0yNi4yNDMgOC42NzQtNTQuNTc5IDguNjc2LTgwLjgyMy4wMDZsLS4wMzEtLjAxYy0xMS4yNTItMy43MTctMjIuODQ1IDQuNjYyLTIyLjg0NSAxNi41MTJ2OS4wMTljMCA5LjA5OC03LjM3NSAxNi40NzMtMTYuNDczIDE2LjQ3M2gtMTYuMzU4djI2LjkzOGMwIDYuODgzIDUuNTggMTIuNDY0IDEyLjQ2NCAxMi40NjQgMi4xNzIgMCAzLjkzOSAxLjcwMSA0LjA3NiAzLjg2OSAyLjYyOCA0MS42NjggMzcuMjM1IDc0LjY1NCA3OS41NjUgNzQuNjU0IDQyLjMzIDAgNzYuOTM3LTMyLjk4NiA3OS41NjUtNzQuNjU0LjEzNy0yLjE2NyAxLjkwNC0zLjg2OSA0LjA3Ni0zLjg2OSA2Ljg4MyAwIDEyLjQ2NC01LjU4IDEyLjQ2NC0xMi40NjR2LTI2LjkzOXoiIGZpbGw9IiNmZmRkY2UiLz48cGF0aCBkPSJtMzM1LjczIDE4MC4zMThjLTkuMDk4IDAtMTYuNDczLTcuMzc1LTE2LjQ3My0xNi40NzN2LTkuMDE1YzAtMTEuODUxLTExLjU5NS0yMC4yMy0yMi44NDctMTYuNTExLTMuMTA4IDEuMDI3LTYuMjQ3IDEuOTIzLTkuNDA3IDIuNzA3djg4Ljk3MmMtLjQzOCAyOC45NDgtMTYuMyA1NC4xNDItMzkuNzI1IDY3Ljc1OCAyLjg2MS4zMTEgNS43NjMuNDg2IDguNzA2LjQ4NiA0Mi4zMyAwIDc2LjkzNy0zMi45ODYgNzkuNTY1LTc0LjY1NC4xMzctMi4xNjcgMS45MDQtMy44NjkgNC4wNzYtMy44NjkgNi44ODMgMCAxMi40NjQtNS41OCAxMi40NjQtMTIuNDY0di0yNi45MzhoLTE2LjM1OXoiIGZpbGw9IiNmZmNiYmUiLz48L2c+PGcgZmlsbD0iI2Y0ZmJmZiI+PHBhdGggZD0ibTIxMy4xOTQgMzE2LjA2LTMzLjU1OCAyNy4yNjcgMzUuMTkyIDQzLjUxM2M0LjI4MSA0LjE2OCAxMS4wMTkgNC40MjQgMTUuNjA1LjU5NGwyNi40NjUtMjIuMTA3eiIvPjxwYXRoIGQ9Im0yOTguNzkgMzE2LjA2LTQxLjg5MiA0OS4yNjcgMjQuODc0IDIxLjI2OGM0LjU1NyAzLjg5NiAxMS4zMjcgMy43IDE1LjY1MS0uNDUzbDM0Ljk0LTQyLjgxNXoiLz48L2c+PC9nPjxwYXRoIGQ9Im0yMTMuMTk0IDMxNi4wNi00OS4yNTYgMjQuMTk5Yy0zLjc1IDEuODQyLTUuMjU2IDYuNDA0LTMuMzQxIDEwLjExN2w5LjY1IDE4LjcxYzIuNTAxIDQuODQ4IDEuNTc4IDEwLjc1Ni0yLjI4MiAxNC42MS0xLjk4NyAxLjk4My00LjEzOSA0LjEzMS02LjAwNCA1Ljk5My0zLjMzOCAzLjMzMi00LjUzNyA4LjI1NS0zLjA2NyAxMi43MzcgMTEuNjUxIDM1LjUxNyA2Ny43MjUgODkuODI4IDg4Ljk0NiAxMDkuNDc4IDEuNDI3LjAzOCAyLjg1Ny4wNjQgNC4yOS4wOC0xNS4zODktMjkuOTMzLTY5LjkyMi0xNDMuNjU1LTM4LjkzNi0xOTUuOTI0eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zNDQuMDE5IDM4My42OTVjLTMuODYxLTMuODU0LTQuNzgzLTkuNzYyLTIuMjgyLTE0LjYxbDkuNjUtMTguNzFjMS45MTUtMy43MTMuNDA5LTguMjc1LTMuMzQxLTEwLjExN2wtNDkuMjU2LTI0LjE5OGMzMC45NzggNTIuMjU1LTIzLjUxNyAxNjUuOTI5LTM4LjkyMyAxOTUuOSAxLjQ0OC0uMDI1IDIuODkzLS4wNjEgNC4zMzUtLjEwOSAyMS4yNjUtMTkuNjk1IDc3LjI0OC03My45NCA4OC44ODgtMTA5LjQyNCAxLjQ3LTQuNDgyLjI3MS05LjQwNS0zLjA2Ny0xMi43MzctMS44NjUtMS44NjMtNC4wMTctNC4wMTItNi4wMDQtNS45OTV6IiBmaWxsPSIjMzY1ZTdkIi8+PHBhdGggZD0ibTI1Ni44OTggMzY1LjMyNy0yNi4wNiAyMS43NjQgMTMuMjc4IDE2Ljc4MWgyMy43NjdsMTMuMjc5LTE3Ljc3MXoiIGZpbGw9IiNkZDYzNmUiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', '2020-10-25 14:25:10', '2021-04-21 08:59:16'),
(6, 1, NULL, NULL, 'Thuc 6', 'Nguyen', 'i-love-me', 'iloveme@gmail.com', NULL, '$2y$10$6VYDB2m9ueOpbSIuMfnunuZ0l4JsvypCWnL1TFfN0jDh66kRjCAke', NULL, NULL, '84666666666', 'HaNoi, VietNam', 'male', 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PGc+PHBhdGggZD0ibTI1Ni4wMjUgNDgzLjMzNCAxMDEuNDI5LTI1LjYxNGM1Ny44OTUtNDguMDc0IDk0Ljc3MS0xMjAuNTg2IDk0Ljc3MS0yMDEuNzE5IDAtMTI1LjE0NC04Ny43MTEtMjI5LjgwMS0yMDUuMDEyLTI1NS44NTItMTM3LjMxNiA0LjYzMS0yNDcuMjEzIDExNy40MDctMjQ3LjIxMyAyNTUuODUxIDAgNzEuMTEyIDI5IDEzNS40NDYgNzUuODEyIDE4MS44MzZ6IiBmaWxsPSIjY2JlMmZmIi8+PC9nPjxnPjxwYXRoIGQ9Im00NDYuOTE0IDI1NmMwIDgzLjkxNS00MC4zODEgMTU4LjM5MS0xMDIuNzY1IDIwNS4wNzlsOTIuMDMxLTIzLjI0MWM0Ni44MTUtNDYuMzkgNzUuODItMTEwLjcyNCA3NS44Mi0xODEuODM4IDAtMTQxLjM4NS0xMTQuNjE1LTI1Ni0yNTYtMjU2LTExLjAyNCAwLTIxLjg4Ni42OTgtMzIuNTQzIDIuMDUgMTI2LjAxOSAxNS45ODggMjIzLjQ1NyAxMjMuNTkgMjIzLjQ1NyAyNTMuOTV6IiBmaWxsPSIjYmVkOGZiIi8+PC9nPjxnPjxnPjxnPjxwYXRoIGQ9Im0zMTkuNjIxIDk2Ljk1MmMwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTgxLjU4MmMtMzAuMDkxIDAtNTQuNDg1IDI0LjM5NC01NC40ODUgNTQuNDg1djYwLjQ5M2gxOTIuMjA5di01OS42MzVjMC0xMy4wNzUtMTAuNTk5LTIzLjY3NC0yMy42NzQtMjMuNjc0aC0uNzk4Yy00LjQxNiAwLTcuOTk2LTMuNTc5LTcuOTk2LTcuOTk1eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zMjguNDE1IDEwNC45NDdoLS43OThjLTQuNDE2IDAtNy45OTYtMy41OC03Ljk5Ni03Ljk5NiAwLTEzLjA3NS0xMC41OTktMjMuNjc0LTIzLjY3NC0yMy42NzRoLTguOTQ1djExNC45NzhoNjUuMDg2di01OS42MzVjLjAwMS0xMy4wNzMtMTAuNTk5LTIzLjY3My0yMy42NzMtMjMuNjczeiIgZmlsbD0iIzJiNGQ2NiIvPjxwYXRoIGQ9Im00MjUuMDQ1IDM3Mi4zNTVjLTYuMjU5LTYuMTgyLTE0LjAwMS0xMC45NjMtMjIuNzktMTMuNzQ1bC02OS44OTEtMjIuMTI4LTc2LjM0OC0yLjY4My03Ni4zOCAyLjY4My02OS44OTEgMjIuMTI4Yy0yMy42NDQgNy40ODYtMzkuNzEzIDI5LjQyOC0zOS43MTMgNTQuMjI5djE5LjA5NGM0NC43ODkgNDcuMzI4IDEwNy40NTEgNzcuNTY4IDE3Ny4xODMgNzkuOTIgNzguMTI4LTE3LjM1MyAxNDMuMTI5LTY5LjU3NiAxNzcuODMtMTM5LjQ5OHoiIGZpbGw9IiM0YTgwYWEiLz48cGF0aCBkPSJtNDQxLjk2OCA0MzEuOTMydi0xOS4wOTRjMC0xNy41MzYtOC4wNC0zMy42MzUtMjEuMTA1LTQ0LjIxMy0zNy4xMTEgNzUuNjI2LTExMC40MjIgMTMwLjI2OC0xOTcuMzQ2IDE0MS4zMTcgMTAuNDkyIDEuMzI5IDIxLjE3OCAyLjAzOCAzMi4wMjYgMi4wNTcgMTAuNDIzLS4wMTYgMjAuNzA4LS42MiAzMC44MjQtMS43ODIgNjEuMDMxLTcuMjEyIDExNS40ODUtMzUuODk0IDE1NS42MDEtNzguMjg1eiIgZmlsbD0iIzQwNzA5MyIvPjxwYXRoIGQ9Im0yNjEuNzk2IDUwOC4xNjhjMTUuNDg5LTMwLjc1MSA1NS44MjItMTE4LjA2NyA0NC4zMjEtMTcyLjYwOWwtNTAuMTAxLTE5LjQ5OS01MC4xNDggMTkuNWMtMTEuODU2IDU2LjIyNSAzMS4zNyAxNDcuMjc3IDQ1LjY4MSAxNzUuMjkgMy40NDItLjgyNiA2Ljg1OS0xLjcyMSAxMC4yNDctMi42ODJ6IiBmaWxsPSIjZTRmNmZmIi8+PHBhdGggZD0ibTI4OC4xOTcgNDgzLjc4OS0yMC4zMTQtNzkuOTE3aC0yMy43NjdsLTIwLjI2NCA3OS42OTkgMjUuMDU4IDI3Ljg5N2M2LjM2MS0xLjQ1NyAxMi42MzQtMy4xNDYgMTguODEtNS4wNTd6IiBmaWxsPSIjZTI4MDg2Ii8+PHBhdGggZD0ibTI0OS4zMDIgNTExLjkwNWMyLjA3NS4wNTQgNC4xNTQuMDkxIDYuMjQxLjA5NSAyLjQxNS0uMDA0IDQuODIyLS4wNDYgNy4yMjItLjExM2wxMi45MDctMTQuMjU5Yy0xMC4xNTkgMy41NjQtMjAuNjEgNi41MDYtMzEuMzA5IDguNzc5eiIgZmlsbD0iI2RkNjM2ZSIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Im0yOTguNzc0IDMyOC4xODN2LTQ1LjA2NmgtODUuNTh2NDUuMDY2YzAgMjMuNjMyIDQyLjc5IDQ5LjQ0NiA0Mi43OSA0OS40NDZzNDIuNzktMjUuODE0IDQyLjc5LTQ5LjQ0NnoiIGZpbGw9IiNmZmRkY2UiLz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PHBhdGggZD0ibTM1Mi4wODkgMTgwLjMxOGgtMTYuMzU5Yy05LjA5OCAwLTE2LjQ3My03LjM3NS0xNi40NzMtMTYuNDczdi05LjAxNWMwLTExLjg1MS0xMS41OTUtMjAuMjMtMjIuODQ3LTE2LjUxMS0yNi4yNDMgOC42NzQtNTQuNTc5IDguNjc2LTgwLjgyMy4wMDZsLS4wMzEtLjAxYy0xMS4yNTItMy43MTctMjIuODQ1IDQuNjYyLTIyLjg0NSAxNi41MTJ2OS4wMTljMCA5LjA5OC03LjM3NSAxNi40NzMtMTYuNDczIDE2LjQ3M2gtMTYuMzU4djI2LjkzOGMwIDYuODgzIDUuNTggMTIuNDY0IDEyLjQ2NCAxMi40NjQgMi4xNzIgMCAzLjkzOSAxLjcwMSA0LjA3NiAzLjg2OSAyLjYyOCA0MS42NjggMzcuMjM1IDc0LjY1NCA3OS41NjUgNzQuNjU0IDQyLjMzIDAgNzYuOTM3LTMyLjk4NiA3OS41NjUtNzQuNjU0LjEzNy0yLjE2NyAxLjkwNC0zLjg2OSA0LjA3Ni0zLjg2OSA2Ljg4MyAwIDEyLjQ2NC01LjU4IDEyLjQ2NC0xMi40NjR2LTI2LjkzOXoiIGZpbGw9IiNmZmRkY2UiLz48cGF0aCBkPSJtMzM1LjczIDE4MC4zMThjLTkuMDk4IDAtMTYuNDczLTcuMzc1LTE2LjQ3My0xNi40NzN2LTkuMDE1YzAtMTEuODUxLTExLjU5NS0yMC4yMy0yMi44NDctMTYuNTExLTMuMTA4IDEuMDI3LTYuMjQ3IDEuOTIzLTkuNDA3IDIuNzA3djg4Ljk3MmMtLjQzOCAyOC45NDgtMTYuMyA1NC4xNDItMzkuNzI1IDY3Ljc1OCAyLjg2MS4zMTEgNS43NjMuNDg2IDguNzA2LjQ4NiA0Mi4zMyAwIDc2LjkzNy0zMi45ODYgNzkuNTY1LTc0LjY1NC4xMzctMi4xNjcgMS45MDQtMy44NjkgNC4wNzYtMy44NjkgNi44ODMgMCAxMi40NjQtNS41OCAxMi40NjQtMTIuNDY0di0yNi45MzhoLTE2LjM1OXoiIGZpbGw9IiNmZmNiYmUiLz48L2c+PGcgZmlsbD0iI2Y0ZmJmZiI+PHBhdGggZD0ibTIxMy4xOTQgMzE2LjA2LTMzLjU1OCAyNy4yNjcgMzUuMTkyIDQzLjUxM2M0LjI4MSA0LjE2OCAxMS4wMTkgNC40MjQgMTUuNjA1LjU5NGwyNi40NjUtMjIuMTA3eiIvPjxwYXRoIGQ9Im0yOTguNzkgMzE2LjA2LTQxLjg5MiA0OS4yNjcgMjQuODc0IDIxLjI2OGM0LjU1NyAzLjg5NiAxMS4zMjcgMy43IDE1LjY1MS0uNDUzbDM0Ljk0LTQyLjgxNXoiLz48L2c+PC9nPjxwYXRoIGQ9Im0yMTMuMTk0IDMxNi4wNi00OS4yNTYgMjQuMTk5Yy0zLjc1IDEuODQyLTUuMjU2IDYuNDA0LTMuMzQxIDEwLjExN2w5LjY1IDE4LjcxYzIuNTAxIDQuODQ4IDEuNTc4IDEwLjc1Ni0yLjI4MiAxNC42MS0xLjk4NyAxLjk4My00LjEzOSA0LjEzMS02LjAwNCA1Ljk5My0zLjMzOCAzLjMzMi00LjUzNyA4LjI1NS0zLjA2NyAxMi43MzcgMTEuNjUxIDM1LjUxNyA2Ny43MjUgODkuODI4IDg4Ljk0NiAxMDkuNDc4IDEuNDI3LjAzOCAyLjg1Ny4wNjQgNC4yOS4wOC0xNS4zODktMjkuOTMzLTY5LjkyMi0xNDMuNjU1LTM4LjkzNi0xOTUuOTI0eiIgZmlsbD0iIzM2NWU3ZCIvPjxwYXRoIGQ9Im0zNDQuMDE5IDM4My42OTVjLTMuODYxLTMuODU0LTQuNzgzLTkuNzYyLTIuMjgyLTE0LjYxbDkuNjUtMTguNzFjMS45MTUtMy43MTMuNDA5LTguMjc1LTMuMzQxLTEwLjExN2wtNDkuMjU2LTI0LjE5OGMzMC45NzggNTIuMjU1LTIzLjUxNyAxNjUuOTI5LTM4LjkyMyAxOTUuOSAxLjQ0OC0uMDI1IDIuODkzLS4wNjEgNC4zMzUtLjEwOSAyMS4yNjUtMTkuNjk1IDc3LjI0OC03My45NCA4OC44ODgtMTA5LjQyNCAxLjQ3LTQuNDgyLjI3MS05LjQwNS0zLjA2Ny0xMi43MzctMS44NjUtMS44NjMtNC4wMTctNC4wMTItNi4wMDQtNS45OTV6IiBmaWxsPSIjMzY1ZTdkIi8+PHBhdGggZD0ibTI1Ni44OTggMzY1LjMyNy0yNi4wNiAyMS43NjQgMTMuMjc4IDE2Ljc4MWgyMy43NjdsMTMuMjc5LTE3Ljc3MXoiIGZpbGw9IiNkZDYzNmUiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', '2021-02-03 02:20:33', '2021-04-21 08:59:19'),
(7, 1, NULL, NULL, 'Thuc 7', 'Nguyen', 'passport', 'passport@gmail.com', NULL, '$2y$10$56JsRNoiBuHj6l4jv1IY4OTz9LnOw7KjoyFnrAqfPyJdlGQiqTbzy', NULL, NULL, '03967857857', 'HaNoi City', 'male', NULL, '2021-04-22 15:33:41', '2021-04-22 15:33:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_category_slug` (`slug`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comment_post_id` (`post_id`),
  ADD KEY `idx_comment_parent_id` (`parent_id`),
  ADD KEY `fk_comment_user` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorite_post`
--
ALTER TABLE `favorite_post`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `idx_p_u_user_id` (`user_id`),
  ADD KEY `idx_p_u_post_id` (`post_id`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`user_id`,`following_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_following_id` (`following_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_permission_slug` (`slug`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_post_slug` (`slug`),
  ADD KEY `idx_post_user_id` (`user_id`),
  ADD KEY `idx_post_parent_id` (`parent_id`),
  ADD KEY `fk_post_category` (`category_id`);

--
-- Indexes for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `idx_p_t_post_id` (`post_id`),
  ADD KEY `idx_p_t_tag_id` (`tag_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_role_slug` (`slug`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `idx_r_p_role_id` (`role_id`),
  ADD KEY `idx_r_p_permission_id` (`permission_id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_tag_slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_name_unique` (`user_name`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `idx_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_parent` FOREIGN KEY (`parent_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `favorite_post`
--
ALTER TABLE `favorite_post`
  ADD CONSTRAINT `fk_p_u_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_p_u_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `fk_u_following_id` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_u_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_post_parent` FOREIGN KEY (`parent_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `fk_p_t_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_p_t_tag` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `fk_r_p_permission` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  ADD CONSTRAINT `fk_r_p_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
