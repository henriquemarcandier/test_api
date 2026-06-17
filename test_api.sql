/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : test_api

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-06-17 13:35:54
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cache`
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for `cache_locks`
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for `failed_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for `job_batches`
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for `jobs`
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('4', '2026_06_13_160712_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` VALUES ('5', '2026_06_13_170000_add_role_country_currency_to_users', '1');
INSERT INTO `migrations` VALUES ('6', '2026_06_13_170001_create_payment_requests_table', '1');

-- ----------------------------
-- Table structure for `password_reset_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `payment_requests`
-- ----------------------------
DROP TABLE IF EXISTS `payment_requests`;
CREATE TABLE `payment_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount_local` decimal(15,2) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `amount_eur` decimal(15,2) NOT NULL,
  `exchange_rate` decimal(15,6) NOT NULL,
  `exchange_rate_source` varchar(255) NOT NULL,
  `exchange_rate_fetched_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_requests_user_id_foreign` (`user_id`),
  KEY `payment_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `payment_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payment_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of payment_requests
-- ----------------------------
INSERT INTO `payment_requests` VALUES ('1', '7', '5000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:36:00', 'approved', '7', '2026-06-13 17:36:00', '2026-06-15 17:00:58', null, '2026-06-13 17:00:58', '2026-06-13 17:36:00');
INSERT INTO `payment_requests` VALUES ('2', '1', '2000.00', 'EUR', '2000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:38:43', 'rejected', '7', '2026-06-13 17:38:43', '2026-06-15 17:11:30', null, '2026-06-13 17:11:30', '2026-06-13 17:38:43');
INSERT INTO `payment_requests` VALUES ('3', '4', '8000.00', 'BRL', '1187.67', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-14 01:46:40', 'expired', null, null, '2026-06-15 17:26:30', null, '2026-05-11 17:26:30', '2026-06-14 04:46:39');
INSERT INTO `payment_requests` VALUES ('4', '5', '6000.00', 'USD', '4321.89', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', '7', '2026-06-13 19:39:53', '2026-06-15 17:54:27', null, '2026-06-13 17:54:27', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('5', '9', '4000.00', 'EUR', '4000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 18:07:40', null, '2026-06-13 18:07:40', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('6', '3', '5000.00', 'USD', '4321.89', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 18:18:06', null, '2026-06-13 18:18:06', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('7', '7', '7000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', '7', '2026-06-14 03:59:14', '2026-06-15 18:58:10', null, '2026-06-13 18:58:10', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('8', '14', '3000.00', 'BRL', '509.00', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:11:04', null, '2026-06-13 19:11:04', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('9', '13', '9000.00', 'USD', '7779.41', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-14 01:54:55', 'rejected', '7', '2026-06-14 04:54:55', '2026-06-15 19:11:40', null, '2026-06-13 19:11:40', '2026-06-14 04:54:55');
INSERT INTO `payment_requests` VALUES ('10', '12', '10000.00', 'BRL', '1696.67', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:12:02', null, '2026-06-13 19:12:02', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('11', '2', '11000.00', 'EUR', '11000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:12:29', null, '2026-06-13 19:12:29', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('12', '15', '12000.00', 'USD', '10372.55', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:13:04', null, '2026-06-13 19:13:04', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('13', '11', '13000.00', 'BRL', '2205.67', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-14 01:52:30', 'approved', '7', '2026-06-14 04:52:30', '2026-06-15 19:13:32', null, '2026-06-13 19:13:32', '2026-06-14 04:52:30');
INSERT INTO `payment_requests` VALUES ('14', '6', '14000.00', 'EUR', '14000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-14 01:54:42', 'approved', '7', '2026-06-14 04:54:42', '2026-06-15 19:14:02', null, '2026-06-13 19:14:02', '2026-06-14 04:54:42');
INSERT INTO `payment_requests` VALUES ('15', '16', '15000.00', 'USD', '12965.68', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:14:25', null, '2026-06-13 19:14:25', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('16', '10', '16000.00', 'EUR', '16000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:14:51', null, '2026-06-13 19:14:51', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('17', '4', '7000.00', 'BRL', '1187.67', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', '7', '2026-06-14 00:24:20', '2026-06-15 19:38:50', null, '2026-06-13 19:38:50', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('18', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', '7', '2026-06-14 00:28:41', '2026-06-15 19:58:03', null, '2026-06-13 19:58:03', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('19', '7', '5000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 19:58:24', null, '2026-06-13 19:58:24', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('20', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 23:57:07', null, '2026-06-13 23:57:07', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('21', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 23:57:28', null, '2026-06-13 23:57:28', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('22', '7', '5000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 23:57:30', null, '2026-06-13 23:57:30', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('23', '7', '5000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-15 23:58:20', null, '2026-06-13 23:58:20', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('24', '7', '5000.00', 'BRL', '847.82', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:55:58', null, '2026-06-14 01:55:58', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('25', '7', '5000.00', 'BRL', '847.82', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:56:06', null, '2026-06-14 01:56:06', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('26', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:56:19', null, '2026-06-14 01:56:19', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('27', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:56:24', null, '2026-06-14 01:56:24', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('28', '7', '17000.00', 'EUR', '17000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:56:28', null, '2026-06-14 01:56:28', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('29', '4', '7000.00', 'BRL', '1186.94', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:57:57', null, '2026-06-14 01:57:57', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('30', '4', '7000.00', 'BRL', '1186.94', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:58:07', null, '2026-06-14 01:58:07', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('31', '4', '7000.00', 'BRL', '1186.94', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 01:58:12', null, '2026-06-14 01:58:12', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('32', '7', '5000.00', 'BRL', '847.82', '5.897500', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 13:34:28', 'expired', null, null, '2026-06-16 04:54:09', null, '2026-06-14 04:54:09', '2026-06-17 16:34:28');
INSERT INTO `payment_requests` VALUES ('33', '7', '5000.00', 'BRL', '850.48', '5.879000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-17 00:00:01', 'pending', null, null, '2026-06-19 16:26:22', null, '2026-06-17 16:26:22', '2026-06-17 16:26:22');

-- ----------------------------
-- Table structure for `personal_access_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
INSERT INTO `personal_access_tokens` VALUES ('1', 'App\\Models\\User', '2', 'auth-token', '382b39050a7871d2f265a60ff6b2c679ddd1aea8b003fd7adacb644b15e892e2', '[\"*\"]', null, null, '2026-06-13 16:20:34', '2026-06-13 16:20:34');
INSERT INTO `personal_access_tokens` VALUES ('2', 'App\\Models\\User', '8', 'auth-token', 'e6aa84f97361e45bdaac0c230bfe3ec0783698eda8a46c24a52d2d1dbe72c1df', '[\"*\"]', null, null, '2026-06-13 16:21:27', '2026-06-13 16:21:27');
INSERT INTO `personal_access_tokens` VALUES ('3', 'App\\Models\\User', '8', 'auth-token', '13f79f8d93e3ba65e612c500727a0c4cef1980b89eca6170a0e1d56714421404', '[\"*\"]', '2026-06-13 16:48:47', null, '2026-06-13 16:22:36', '2026-06-13 16:48:47');
INSERT INTO `personal_access_tokens` VALUES ('4', 'App\\Models\\User', '7', 'auth-token', 'f80194e815d7085e14d4dc2b7733878b32f5906bfb4e681069de7647332c1372', '[\"*\"]', '2026-06-14 03:59:13', null, '2026-06-13 16:23:09', '2026-06-14 03:59:13');
INSERT INTO `personal_access_tokens` VALUES ('5', 'App\\Models\\User', '8', 'auth-token', '16b3cabac059ec9659299a54d12dfffb386a26f8d3b3dae02e31c433c0cbbc7d', '[\"*\"]', null, null, '2026-06-13 17:09:10', '2026-06-13 17:09:10');
INSERT INTO `personal_access_tokens` VALUES ('6', 'App\\Models\\User', '1', 'auth-token', '9f1476b5b7a7bfd74f42e79f32a4c8dada88e5fc1b508a32b0e6c6c405d41e42', '[\"*\"]', '2026-06-13 17:11:28', null, '2026-06-13 17:10:59', '2026-06-13 17:11:28');
INSERT INTO `personal_access_tokens` VALUES ('7', 'App\\Models\\User', '4', 'auth-token', 'cbb58a462db0bfccfee49c974f1079228c9ad76301b206f1c791cc2b43f91bbc', '[\"*\"]', '2026-06-14 01:58:12', null, '2026-06-13 17:26:03', '2026-06-14 01:58:12');
INSERT INTO `personal_access_tokens` VALUES ('8', 'App\\Models\\User', '7', 'auth-token', 'bc2bed520df4dedb680655f13abee6572dec1cff43f71169cf9d3b6acddb9a2f', '[\"*\"]', '2026-06-14 01:56:28', null, '2026-06-13 18:52:27', '2026-06-14 01:56:28');
INSERT INTO `personal_access_tokens` VALUES ('9', 'App\\Models\\User', '8', 'auth-token', 'bafe711bbd7b42d353f7a25df0d696a463020735ad93686d1466507fbfb78d5a', '[\"*\"]', null, null, '2026-06-13 18:54:27', '2026-06-13 18:54:27');
INSERT INTO `personal_access_tokens` VALUES ('10', 'App\\Models\\User', '1', 'auth-token', '0bf1d98c1090d3b8bf5846f70f9975bc3cac95855e8438a6eb3bd5c27311b3f7', '[\"*\"]', null, null, '2026-06-13 19:37:30', '2026-06-13 19:37:30');
INSERT INTO `personal_access_tokens` VALUES ('11', 'App\\Models\\User', '1', 'auth-token', '5850da25b5c8f00495198bfe0930959c1cd50b539b7dc5471bb6aed125030728', '[\"*\"]', null, null, '2026-06-13 19:38:39', '2026-06-13 19:38:39');
INSERT INTO `personal_access_tokens` VALUES ('12', 'App\\Models\\User', '4', 'auth-token', 'b3211682b08ae5135be3e825e2a129cc23ef3e4f961075853066ba80628b1ded', '[\"*\"]', null, null, '2026-06-13 19:39:09', '2026-06-13 19:39:09');
INSERT INTO `personal_access_tokens` VALUES ('13', 'App\\Models\\User', '17', 'auth-token', '48974eb4d151dc87397e00da784b651ef93f8f4484d00f5814ffe4cac4b56258', '[\"*\"]', null, null, '2026-06-14 00:00:00', '2026-06-14 00:00:00');
INSERT INTO `personal_access_tokens` VALUES ('14', 'App\\Models\\User', '7', 'auth-token', '7edacba276cd8b047cc0c08662b18ad6620c71d00d2778b1021db72b7871b881', '[\"*\"]', null, null, '2026-06-14 04:28:17', '2026-06-14 04:28:17');
INSERT INTO `personal_access_tokens` VALUES ('17', 'App\\Models\\User', '7', 'auth-token', '7e90f2fbcd3fcd3481a57373ed139f807c7451a7e6cd7b870504da64801f666b', '[\"*\"]', '2026-06-17 16:26:22', null, '2026-06-17 16:25:45', '2026-06-17 16:26:22');

-- ----------------------------
-- Table structure for `sessions`
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'employee',
  `country` varchar(255) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'João Silva', 'joao@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', '8z5sHmjSr9ibmmxPm1Larn69C51Q2UlGpwwc9KriTcE0caxyAROyPQbW0Rtk', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Brasil', 'BRL');
INSERT INTO `users` VALUES ('2', 'John Smith', 'john@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 's0aQ3nXkhEU4VNYE6GGSNQwhD4WnsRJVuAKyrCpflePHphxyl7azw7XQI9ML', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'EUA', 'USD');
INSERT INTO `users` VALUES ('3', 'Pierre Dubois', 'pierre@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'rwXwh3Rins', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'França', 'EUR');
INSERT INTO `users` VALUES ('4', 'Akira Tanaka', 'akira@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'vbEbvjTN8i', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Japão', 'JPY');
INSERT INTO `users` VALUES ('5', 'Carlos Garcia', 'carlos@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'Fo1V0y3DiL', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'México', 'MXN');
INSERT INTO `users` VALUES ('6', 'Sarah Johnson', 'sarah@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'DdlmAChgI0', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Reino Unido', 'GBP');
INSERT INTO `users` VALUES ('7', 'Finance Team', 'finance@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'A4Bi6Gttw2NHlE6WDJT3A3Mp1jfCsZoUvdg5tPbp0kv8vh4eI2U2qMhOreQ6', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'finance', 'França', 'EUR');
INSERT INTO `users` VALUES ('8', 'John', 'john@test.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 16:21:27', '2026-06-13 19:03:44', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('9', 'Teste', 'teste@teste.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 18:03:49', '2026-06-13 18:03:49', 'employee', 'Brazil', 'EUR');
INSERT INTO `users` VALUES ('10', 'Talita', 'talita@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:03:05', '2026-06-13 19:03:05', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('11', 'Monica', 'monica@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:04:21', '2026-06-13 19:04:21', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('12', 'Henrique Marcandier', 'henrique@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:04:54', '2026-06-13 19:04:54', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('13', 'Douglas', 'douglas@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:05:34', '2026-06-13 19:05:34', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('14', 'Airton Senna', 'airton@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:06:13', '2026-06-13 19:06:13', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('15', 'Luciano Huck', 'lucianohuck@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:06:46', '2026-06-13 19:06:46', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('16', 'Tabata', 'tabata@empresa.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-13 19:07:17', '2026-06-13 19:07:17', 'employee', 'Brazil', 'BRL');
INSERT INTO `users` VALUES ('17', 'Teste testando', 'testando@test.com', null, '$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', null, '2026-06-14 00:00:00', '2026-06-14 00:00:31', 'employee', 'Brazil', 'BRL');
