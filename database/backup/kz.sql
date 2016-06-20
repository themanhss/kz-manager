/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : kz

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-06-21 00:46:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blogspots
-- ----------------------------
DROP TABLE IF EXISTS `blogspots`;
CREATE TABLE `blogspots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `blog_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gmail_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `start_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of blogspots
-- ----------------------------
INSERT INTO `blogspots` VALUES ('3', 'http://giaythethaonamgiarehanoi.blogspot.com/', null, '2', null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null);
INSERT INTO `blogspots` VALUES ('4', 'http://giaythethaonuhanoi.blogspot.com/', null, '2', null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null);
INSERT INTO `blogspots` VALUES ('5', 'http://giaynikenugiare.blogspot.com/', '5032988436021182927', '1', 'ss', null, '2016-06-19 09:00:34', '2016-06-19 09:00:34', null);
INSERT INTO `blogspots` VALUES ('6', 'http://giaynikenamgiare.blogspot.com/', '4268275848201360644', '1', '', null, '2016-06-19 09:02:32', '2016-06-19 09:02:32', null);
INSERT INTO `blogspots` VALUES ('7', 'http://conversehanoi.blogspot.com/', '1647313005791780948', '1', 'd', null, '2016-06-19 09:06:03', '2016-06-19 09:06:03', null);
INSERT INTO `blogspots` VALUES ('8', 'http://giaynikenuhanoi.blogspot.com/', '1259944265994133177', '1', '', null, '2016-06-19 09:32:03', '2016-06-19 09:32:03', null);
INSERT INTO `blogspots` VALUES ('9', 'http://giaynikenamhanoi.blogspot.com/', '226835874716525883', '1', '', null, '2016-06-19 09:32:22', '2016-06-19 09:32:22', null);
INSERT INTO `blogspots` VALUES ('10', 'http://giayvansgiarehanoi.blogspot.com/', '4786102494371211784', '1', '', null, '2016-06-19 09:32:36', '2016-06-19 09:32:36', null);

-- ----------------------------
-- Table structure for gmails
-- ----------------------------
DROP TABLE IF EXISTS `gmails`;
CREATE TABLE `gmails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gmail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_key` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_backup` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_at` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of gmails
-- ----------------------------
INSERT INTO `gmails` VALUES ('1', 'themanhss@gmail.com', null, '29972.json', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null);
INSERT INTO `gmails` VALUES ('2', 'iag.dev2016@gmail.com', '', '', null, null, null, '0000-00-00 00:00:00', '2016-06-20 17:19:53', null);
INSERT INTO `gmails` VALUES ('3', 'theman2311@gmail.com', '968332556', '29972.json', null, null, null, '2016-06-19 03:59:21', '2016-06-19 03:59:21', null);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('2016_06_17_085853_create_gmails_table', '2');
INSERT INTO `migrations` VALUES ('2016_06_17_090534_create_blogspots_table', '2');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT '0',
  `isAdmin` tinyint(4) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Henry', 'Tran', 'themanhss@gmail.com', '$2y$10$jfOXeW9aqtuP4QA0gzn1re6GEoTCsokcrNAmFISBx677PG1m4kx5q', '', '1', '1', 'D3lM24muUb4K5b4uM2LP4GipGXqGainL58kDHb7Z6FL8Vb9naNoWVgICvENM', '2016-03-17 17:07:27', '2016-03-17 10:38:23', null);
