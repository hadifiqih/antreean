/*
 Navicat Premium Dump SQL

 Source Server         : antree
 Source Server Type    : MySQL
 Source Server Version : 101110 (10.11.10-MariaDB)
 Source Host           : srv1415.hstgr.io:3306
 Source Schema         : u449407362_antree

 Target Server Type    : MySQL
 Target Server Version : 101110 (10.11.10-MariaDB)
 File Encoding         : 65001

 Date: 15/03/2025 09:29:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `infoPelanggan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `instansi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `frekuensi_order` int NULL DEFAULT 0,
  `count_followUp` bigint NULL DEFAULT 0,
  `sales_id` bigint NULL DEFAULT NULL,
  `provinsi_id` bigint NULL DEFAULT NULL,
  `kota_id` bigint NULL DEFAULT NULL,
  `last_follow_up` timestamp NULL DEFAULT NULL,
  `status_follow_up` enum('pending','done','ignored') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` int NOT NULL DEFAULT 1,
  `next_follow_up` timestamp NULL DEFAULT NULL,
  `reason_for_follow_up` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_order_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11668 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
