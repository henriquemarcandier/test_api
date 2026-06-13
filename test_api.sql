/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : test_api

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-06-13 15:32:42
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of payment_requests
-- ----------------------------
INSERT INTO `payment_requests` VALUES ('1', '7', '5000.00', 'BRL', '848.33', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:36:00', 'approved', '7', '2026-06-13 17:36:00', '2026-06-15 17:00:58', null, '2026-06-13 17:00:58', '2026-06-13 17:36:00');
INSERT INTO `payment_requests` VALUES ('2', '1', '2000.00', 'EUR', '2000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:38:43', 'rejected', '7', '2026-06-13 17:38:43', '2026-06-15 17:11:30', null, '2026-06-13 17:11:30', '2026-06-13 17:38:43');
INSERT INTO `payment_requests` VALUES ('3', '4', '8000.00', 'BRL', '1187.67', '5.893900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:53:56', 'pending', null, null, '2026-06-15 17:26:30', null, '2026-05-11 17:26:30', '2026-06-13 17:53:56');
INSERT INTO `payment_requests` VALUES ('4', '5', '6000.00', 'USD', '4321.89', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 14:54:57', 'pending', null, null, '2026-06-15 17:54:27', null, '2026-06-13 17:54:27', '2026-06-13 17:54:57');
INSERT INTO `payment_requests` VALUES ('5', '9', '4000.00', 'EUR', '4000.00', '1.000000', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 00:00:01', 'pending', null, null, '2026-06-15 18:07:40', null, '2026-06-13 18:07:40', '2026-06-13 18:07:40');
INSERT INTO `payment_requests` VALUES ('6', '3', '5000.00', 'USD', '4321.89', '1.156900', 'https://v6.exchangerate-api.com/v6/ba0f7ae2e285c305b038a4fd/latest/EUR', '2026-06-13 00:00:01', 'pending', null, null, '2026-06-15 18:18:06', null, '2026-06-13 18:18:06', '2026-06-13 18:18:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
INSERT INTO `personal_access_tokens` VALUES ('1', 'App\\Models\\User', '2', 'auth-token', '382b39050a7871d2f265a60ff6b2c679ddd1aea8b003fd7adacb644b15e892e2', '[\"*\"]', null, null, '2026-06-13 16:20:34', '2026-06-13 16:20:34');
INSERT INTO `personal_access_tokens` VALUES ('2', 'App\\Models\\User', '8', 'auth-token', 'e6aa84f97361e45bdaac0c230bfe3ec0783698eda8a46c24a52d2d1dbe72c1df', '[\"*\"]', null, null, '2026-06-13 16:21:27', '2026-06-13 16:21:27');
INSERT INTO `personal_access_tokens` VALUES ('3', 'App\\Models\\User', '8', 'auth-token', '13f79f8d93e3ba65e612c500727a0c4cef1980b89eca6170a0e1d56714421404', '[\"*\"]', '2026-06-13 16:48:47', null, '2026-06-13 16:22:36', '2026-06-13 16:48:47');
INSERT INTO `personal_access_tokens` VALUES ('4', 'App\\Models\\User', '7', 'auth-token', 'f80194e815d7085e14d4dc2b7733878b32f5906bfb4e681069de7647332c1372', '[\"*\"]', '2026-06-13 17:38:43', null, '2026-06-13 16:23:09', '2026-06-13 17:38:43');
INSERT INTO `personal_access_tokens` VALUES ('5', 'App\\Models\\User', '8', 'auth-token', '16b3cabac059ec9659299a54d12dfffb386a26f8d3b3dae02e31c433c0cbbc7d', '[\"*\"]', null, null, '2026-06-13 17:09:10', '2026-06-13 17:09:10');
INSERT INTO `personal_access_tokens` VALUES ('6', 'App\\Models\\User', '1', 'auth-token', '9f1476b5b7a7bfd74f42e79f32a4c8dada88e5fc1b508a32b0e6c6c405d41e42', '[\"*\"]', '2026-06-13 17:11:28', null, '2026-06-13 17:10:59', '2026-06-13 17:11:28');
INSERT INTO `personal_access_tokens` VALUES ('7', 'App\\Models\\User', '4', 'auth-token', 'cbb58a462db0bfccfee49c974f1079228c9ad76301b206f1c791cc2b43f91bbc', '[\"*\"]', '2026-06-13 17:26:29', null, '2026-06-13 17:26:03', '2026-06-13 17:26:29');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'João Silva', 'joao@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'EFqwZb2lkS', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Brasil', 'BRL');
INSERT INTO `users` VALUES ('2', 'John Smith', 'john@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'c9Uo8jlZHrb77ux5QenIUUAHs4qyVgWPuVi8cWC2HUcLXG3oI7zUhXDMHA78', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'EUA', 'USD');
INSERT INTO `users` VALUES ('3', 'Pierre Dubois', 'pierre@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'rwXwh3Rins', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'França', 'EUR');
INSERT INTO `users` VALUES ('4', 'Akira Tanaka', 'akira@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'vbEbvjTN8i', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Japão', 'JPY');
INSERT INTO `users` VALUES ('5', 'Carlos Garcia', 'carlos@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'Fo1V0y3DiL', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'México', 'MXN');
INSERT INTO `users` VALUES ('6', 'Sarah Johnson', 'sarah@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'DdlmAChgI0', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'employee', 'Reino Unido', 'GBP');
INSERT INTO `users` VALUES ('7', 'Finance Team', 'finance@empresa.com', '2026-06-13 16:15:24', '$2y$12$vW9CCNFMOSA1YJEDpbSwAuSL8p1dGHsY.tONyy83dQm06zFkIUld6', 'mRoeKYEDrpKB6hS3XlHblzlroz72a19OIJATsv0a2Lf9lQdS848mn3AXOaDw', '2026-06-13 16:15:24', '2026-06-13 16:15:24', 'finance', 'França', 'EUR');
INSERT INTO `users` VALUES ('8', 'John', 'john@test.com', null, '$2y$12$/.QCKDNOR8Aij8mV3A9Ip.klb0jR56lqEG4qbwSokx1X.e5VqM17G', null, '2026-06-13 16:21:27', '2026-06-13 16:21:27', 'employee', null, null);
INSERT INTO `users` VALUES ('9', 'Teste', 'teste@teste.com', null, '$2y$12$9oeL5yeq/FZZAleVAp0LjumHoUijgnRNDebu5i3BmkyBK/EkO3Mi.', null, '2026-06-13 18:03:49', '2026-06-13 18:03:49', 'employee', 'Brazil', 'EUR');
