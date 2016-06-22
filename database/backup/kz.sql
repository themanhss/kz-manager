/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : kz

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-06-23 01:27:31
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of blogspots
-- ----------------------------
INSERT INTO `blogspots` VALUES ('5', 'http://giaynikenugiare.blogspot.com/', '5032988436021182927', '1', 'ss', null, '2016-06-19 09:00:34', '2016-06-19 09:00:34', null);
INSERT INTO `blogspots` VALUES ('6', 'http://giaynikenamgiare.blogspot.com/', '4268275848201360644', '1', '', null, '2016-06-19 09:02:32', '2016-06-19 09:02:32', null);
INSERT INTO `blogspots` VALUES ('7', 'http://conversehanoi.blogspot.com/', '1647313005791780948', '1', 'd', null, '2016-06-19 09:06:03', '2016-06-19 09:06:03', null);
INSERT INTO `blogspots` VALUES ('8', 'http://giaynikenuhanoi.blogspot.com/', '1259944265994133177', '1', '', null, '2016-06-19 09:32:03', '2016-06-19 09:32:03', null);
INSERT INTO `blogspots` VALUES ('9', 'http://giaynikenamhanoi.blogspot.com/', '226835874716525883', '1', '', null, '2016-06-19 09:32:22', '2016-06-19 09:32:22', null);
INSERT INTO `blogspots` VALUES ('10', 'http://giayvansgiarehanoi.blogspot.com/', '4786102494371211784', '1', '', null, '2016-06-19 09:32:36', '2016-06-19 09:32:36', null);
INSERT INTO `blogspots` VALUES ('11', 'http://giayvansgiare2015.blogspot.com/', '8944680025495986995', '6', '', null, '2016-06-20 17:58:09', '2016-06-20 17:58:09', null);
INSERT INTO `blogspots` VALUES ('12', 'http://giayconversegiare2015.blogspot.com/', '4554678448998355695', '6', '', null, '2016-06-20 18:00:40', '2016-06-20 18:00:40', null);
INSERT INTO `blogspots` VALUES ('13', 'http://shopgiayconversehcm.blogspot.com/', '5426467113046141448', '6', '', null, '2016-06-20 18:00:59', '2016-06-20 18:00:59', null);
INSERT INTO `blogspots` VALUES ('14', 'http://shopgiayvansnam.blogspot.com/', '6781092427198814269', '6', '', null, '2016-06-20 18:01:12', '2016-06-20 18:01:12', null);
INSERT INTO `blogspots` VALUES ('15', 'http://giayadidasnudanang.blogspot.com/', '297340918127475195', '6', '', null, '2016-06-20 18:01:27', '2016-06-20 18:01:27', null);
INSERT INTO `blogspots` VALUES ('16', 'http://giayvansdepgiare.blogspot.com/', '2726218970918934815', '6', '', null, '2016-06-20 18:01:42', '2016-06-20 18:01:42', null);
INSERT INTO `blogspots` VALUES ('17', 'http://giaynikedepgiare.blogspot.com/', '3236665221729947846', '6', '', null, '2016-06-20 18:01:57', '2016-06-20 18:01:57', null);
INSERT INTO `blogspots` VALUES ('18', 'http://giaynikenamdanang.blogspot.com/', '2503100807518864072', '6', '', null, '2016-06-20 18:02:38', '2016-06-20 18:02:38', null);
INSERT INTO `blogspots` VALUES ('19', 'http://giayadidasdanang.blogspot.com/', '5472107550338934535', '6', '', null, '2016-06-20 18:03:03', '2016-06-20 18:03:03', null);
INSERT INTO `blogspots` VALUES ('20', 'http://giaynikedanang.blogspot.com/', '1776880128415821304', '6', '', null, '2016-06-20 18:03:33', '2016-06-20 18:03:33', null);
INSERT INTO `blogspots` VALUES ('21', 'http://giayvans09.blogspot.com/', '8673623829431657071', '3', '', null, '2016-06-22 14:32:46', '2016-06-22 14:32:46', null);
INSERT INTO `blogspots` VALUES ('22', 'http://giaydep168.blogspot.com/', '7698213181861181124', '3', '', null, '2016-06-22 14:32:59', '2016-06-22 14:32:59', null);
INSERT INTO `blogspots` VALUES ('23', 'http://giayconversechinhhanghanoi.blogspot.com/', '8152104606926776814', '3', '', null, '2016-06-22 14:33:13', '2016-06-22 14:33:13', null);
INSERT INTO `blogspots` VALUES ('24', 'http://giayconverse09.blogspot.com/', '7883607175462882626', '3', '', null, '2016-06-22 14:33:34', '2016-06-22 14:33:34', null);
INSERT INTO `blogspots` VALUES ('25', 'http://giayconversenamfake.blogspot.com/', '5042650916123789623', '7', '', null, '2016-06-22 17:31:26', '2016-06-22 17:31:26', null);
INSERT INTO `blogspots` VALUES ('26', 'http://muagiayconversexin.blogspot.com/', '7205692332389622899', '7', '', null, '2016-06-22 17:31:39', '2016-06-22 17:31:39', null);
INSERT INTO `blogspots` VALUES ('27', 'http://giayvansgiarehcm.blogspot.com/', '124032878743480614', '7', '', null, '2016-06-22 17:31:52', '2016-06-22 17:31:52', null);
INSERT INTO `blogspots` VALUES ('28', 'http://muaigiayconversegiarehcm.blogspot.com/', '3635201020940396186', '7', '', null, '2016-06-22 17:32:06', '2016-06-22 17:32:06', null);
INSERT INTO `blogspots` VALUES ('29', 'http://giaythethaonikedep.blogspot.com/', '3991256257370937715', '7', '', null, '2016-06-22 17:32:17', '2016-06-22 17:32:17', null);
INSERT INTO `blogspots` VALUES ('30', 'http://giayadidassaigon.blogspot.com/', '7947971003306093802', '7', '', null, '2016-06-22 17:32:29', '2016-06-22 17:32:29', null);
INSERT INTO `blogspots` VALUES ('31', 'http://giaythethaohcmdep.blogspot.com/', '2628219257522756917', '2', '', null, '2016-06-22 17:58:19', '2016-06-22 17:58:19', null);
INSERT INTO `blogspots` VALUES ('32', 'http://muagiaydanamhanoi.blogspot.com/', '1453479819697901384', '2', '', null, '2016-06-22 17:58:31', '2016-06-22 17:58:31', null);
INSERT INTO `blogspots` VALUES ('33', 'http://giayadidasvietnam.blogspot.com/', '8161367872515118684', '2', '', null, '2016-06-22 17:58:42', '2016-06-22 17:58:42', null);
INSERT INTO `blogspots` VALUES ('34', 'http://giaynikevietnam.blogspot.com/', '6069742626868037281', '2', '', null, '2016-06-22 17:58:56', '2016-06-22 17:58:56', null);
INSERT INTO `blogspots` VALUES ('35', 'http://giaythethaonamgiarehanoi.blogspot.com/', '7819378731473306790', '2', '', null, '2016-06-22 17:59:07', '2016-06-22 17:59:07', null);
INSERT INTO `blogspots` VALUES ('36', 'http://giaythethaonuhanoi.blogspot.com/', '8429192421028759281', '2', '', null, '2016-06-22 17:59:18', '2016-06-22 17:59:18', null);
INSERT INTO `blogspots` VALUES ('37', 'http://giaydanamdephanoi.blogspot.com/', '7266842757526566566', '2', '', null, '2016-06-22 17:59:33', '2016-06-22 17:59:33', null);
INSERT INTO `blogspots` VALUES ('38', 'http://giaythethaohcmgiare.blogspot.com/', '1980742087941044484', '2', '', null, '2016-06-22 17:59:44', '2016-06-22 17:59:44', null);
INSERT INTO `blogspots` VALUES ('39', 'http://muagiaynuthethao.blogspot.com/', '8968782926571872882', '2', '', null, '2016-06-22 17:59:53', '2016-06-22 17:59:53', null);
INSERT INTO `blogspots` VALUES ('40', 'http://muagiaythethaonu.blogspot.com/', '3884930604754247252', '8', '', null, '2016-06-22 18:01:54', '2016-06-22 18:01:54', null);
INSERT INTO `blogspots` VALUES ('41', 'http://muagiaynikenamgiare.blogspot.com/', '3498542253329916057', '8', '', null, '2016-06-22 18:02:16', '2016-06-22 18:02:16', null);
INSERT INTO `blogspots` VALUES ('42', 'http://muagiaynikegiare.blogspot.com/', '4747852879234446996', '8', '', null, '2016-06-22 18:02:29', '2016-06-22 18:02:29', null);
INSERT INTO `blogspots` VALUES ('43', 'http://giaynikenuhaiphong.blogspot.com/', '2272084779286045544', '8', '', null, '2016-06-22 18:02:41', '2016-06-22 18:02:41', null);
INSERT INTO `blogspots` VALUES ('44', 'http://muagiayadidashaiphong.blogspot.com/', '1233058104874769011', '8', '', null, '2016-06-22 18:02:51', '2016-06-22 18:02:51', null);
INSERT INTO `blogspots` VALUES ('45', 'http://muagiayconversechuck2.blogspot.com/', '1374421160339451328', '8', '', null, '2016-06-22 18:03:01', '2016-06-22 18:03:01', null);
INSERT INTO `blogspots` VALUES ('46', 'http://giaynikeairmaxhanoi.blogspot.com/', '182851062016816779', '9', '', null, '2016-06-22 18:04:17', '2016-06-22 18:04:17', null);
INSERT INTO `blogspots` VALUES ('47', 'http://giayvansdepdanang.blogspot.com/', '5495678271787021053', '9', '', null, '2016-06-22 18:04:30', '2016-06-22 18:04:30', null);
INSERT INTO `blogspots` VALUES ('48', 'http://giaythethaogiarehanoi.blogspot.com/', '7613387423505240200', '9', '', null, '2016-06-22 18:04:42', '2016-06-22 18:04:42', null);
INSERT INTO `blogspots` VALUES ('49', 'http://giayvansoldskoolgiare.blogspot.com/', '6393055528706367516', '9', '', null, '2016-06-22 18:04:52', '2016-06-22 18:04:52', null);
INSERT INTO `blogspots` VALUES ('50', 'http://giaythethaonamdephn.blogspot.com/', '7585504196372675444', '9', '', null, '2016-06-22 18:05:05', '2016-06-22 18:05:05', null);
INSERT INTO `blogspots` VALUES ('51', 'http://giayvansthethao.blogspot.com/', '159495637480291971', '9', '', null, '2016-06-22 18:05:18', '2016-06-22 18:05:18', null);
INSERT INTO `blogspots` VALUES ('52', 'http://giayconversethethao.blogspot.com/', '716075650314227497', '9', '', null, '2016-06-22 18:05:29', '2016-06-22 18:05:29', null);
INSERT INTO `blogspots` VALUES ('53', 'http://giayadidasthethao.blogspot.com/', '7818535819153385235', '9', '', null, '2016-06-22 18:05:45', '2016-06-22 18:05:45', null);
INSERT INTO `blogspots` VALUES ('54', 'http://giayconversechucktaylor.blogspot.com/', '3288442383364815924', '9', '', null, '2016-06-22 18:05:55', '2016-06-22 18:05:55', null);
INSERT INTO `blogspots` VALUES ('55', 'http://giaynikegiarehcm.blogspot.com/', '1402421364361913198', '10', '', null, '2016-06-22 18:07:57', '2016-06-22 18:07:57', null);
INSERT INTO `blogspots` VALUES ('56', 'http://giayvansoldskoolgiarehcm.blogspot.com/', '1253749479903090594', '10', '', null, '2016-06-22 18:08:08', '2016-06-22 18:08:08', null);
INSERT INTO `blogspots` VALUES ('57', 'http://giayvansclassicgiare.blogspot.com/', '7387783879127269522', '10', '', null, '2016-06-22 18:08:20', '2016-06-22 18:08:20', null);
INSERT INTO `blogspots` VALUES ('58', 'http://giayadidassaigongiare.blogspot.com/', '2341566937028922171', '10', '', null, '2016-06-22 18:08:29', '2016-06-22 18:08:29', null);
INSERT INTO `blogspots` VALUES ('59', 'http://giayconversecantho.blogspot.com/', '5885288698031031308', '10', '', null, '2016-06-22 18:08:40', '2016-06-22 18:08:40', null);
INSERT INTO `blogspots` VALUES ('60', 'http://giaythethaodanang.blogspot.com/', '418274769822004108', '10', '', null, '2016-06-22 18:08:49', '2016-06-22 18:08:49', null);
INSERT INTO `blogspots` VALUES ('61', 'http://giayadidascaugiay.blogspot.com/', '681496905909845300', '11', '', null, '2016-06-22 18:10:47', '2016-06-22 18:10:47', null);
INSERT INTO `blogspots` VALUES ('62', 'http://giayconversecaugiay.blogspot.com/', '2374002180121249406', '11', '', null, '2016-06-22 18:10:57', '2016-06-22 18:10:57', null);
INSERT INTO `blogspots` VALUES ('63', 'http://giaynumuahe.blogspot.com/', '1549181772254687542', '11', '', null, '2016-06-22 18:11:08', '2016-06-22 18:11:08', null);
INSERT INTO `blogspots` VALUES ('64', 'http://giaynammuadong.blogspot.com/', '6489497659614308039', '11', '', null, '2016-06-22 18:11:19', '2016-06-22 18:11:19', null);
INSERT INTO `blogspots` VALUES ('65', 'http://giaynikegiarevietnam.blogspot.com/', '8161846397192679003', '11', '', null, '2016-06-22 18:11:28', '2016-06-22 18:11:28', null);
INSERT INTO `blogspots` VALUES ('66', 'http://giayadidasgiarevietnam.blogspot.com/', '8153183336531919514', '11', '', null, '2016-06-22 18:11:38', '2016-06-22 18:11:38', null);
INSERT INTO `blogspots` VALUES ('67', 'http://giayconversegiarevietnam.blogspot.com/', '7819456098728414495', '11', '', null, '2016-06-22 18:11:49', '2016-06-22 18:11:49', null);
INSERT INTO `blogspots` VALUES ('68', 'http://giaythethaomuadong.blogspot.com/', '166750719590652677', '11', '', null, '2016-06-22 18:11:59', '2016-06-22 18:11:59', null);
INSERT INTO `blogspots` VALUES ('69', 'http://giaythethaonhatrang.blogspot.com/', '7768528357412173499', '12', '', null, '2016-06-22 18:13:50', '2016-06-22 18:13:50', null);
INSERT INTO `blogspots` VALUES ('70', 'http://giayconversechuck2giare.blogspot.com/', '6080525981504007550', '12', '', null, '2016-06-22 18:14:01', '2016-06-22 18:14:01', null);
INSERT INTO `blogspots` VALUES ('71', 'http://giayvansclassicdepgiare.blogspot.com/', '7427613140264299342', '12', '', null, '2016-06-22 18:14:10', '2016-06-22 18:14:10', null);
INSERT INTO `blogspots` VALUES ('72', 'http://giaythethaoadidasgiare.blogspot.com/', '1354342447080381312', '12', '', null, '2016-06-22 18:14:19', '2016-06-22 18:14:19', null);
INSERT INTO `blogspots` VALUES ('73', 'http://giaythethaovans.blogspot.com/', '4341521208018432540', '12', '', null, '2016-06-22 18:14:30', '2016-06-22 18:14:30', null);
INSERT INTO `blogspots` VALUES ('74', 'http://giaythethaoconverse.blogspot.com/', '4702015093240584781', '12', '', null, '2016-06-22 18:14:39', '2016-06-22 18:14:39', null);
INSERT INTO `blogspots` VALUES ('75', 'http://giaydepgiarenhatrang.blogspot.com/', '6852122448849601752', '12', '', null, '2016-06-22 18:14:48', '2016-06-22 18:14:48', null);
INSERT INTO `blogspots` VALUES ('76', 'http://muagiaygiareodau.blogspot.com/', '3088560607741773754', '12', '', null, '2016-06-22 18:14:57', '2016-06-22 18:14:57', null);
INSERT INTO `blogspots` VALUES ('77', 'http://muagiaychuck2giare.blogspot.com/', '807455386531882998', '12', '', null, '2016-06-22 18:15:07', '2016-06-22 18:15:07', null);
INSERT INTO `blogspots` VALUES ('78', 'http://giaynikenamgiarehanoi.blogspot.com/', '7204235344934155205', '13', '', null, '2016-06-22 18:17:14', '2016-06-22 18:17:14', null);
INSERT INTO `blogspots` VALUES ('79', 'http://giayvansoldskool.blogspot.com/', '678683399319960812', '13', '', null, '2016-06-22 18:17:23', '2016-06-22 18:17:23', null);
INSERT INTO `blogspots` VALUES ('80', 'http://giayconversechucktaylor2.blogspot.com/', '778816600648990133', '13', '', null, '2016-06-22 18:17:33', '2016-06-22 18:17:33', null);
INSERT INTO `blogspots` VALUES ('81', 'http://giaythethaoadidashcm.blogspot.com/', '6110928390881087504', '13', '', null, '2016-06-22 18:17:43', '2016-06-22 18:17:43', null);
INSERT INTO `blogspots` VALUES ('82', 'http://giayconverseclassichanoi.blogspot.com/', '8172029259342580206', '13', '', null, '2016-06-22 18:17:53', '2016-06-22 18:17:53', null);
INSERT INTO `blogspots` VALUES ('83', 'http://giayconversechuck2hcm.blogspot.com/', '4232899932425637264', '13', '', null, '2016-06-22 18:18:03', '2016-06-22 18:18:03', null);
INSERT INTO `blogspots` VALUES ('84', 'http://giaydagiarehanoi.blogspot.com/', '6124574403957904133', '14', '', null, '2016-06-22 18:19:43', '2016-06-22 18:19:43', null);
INSERT INTO `blogspots` VALUES ('85', 'http://giaythethaonamdepgiare.blogspot.com/', '7542783248340478408', '14', '', null, '2016-06-22 18:19:53', '2016-06-22 18:19:53', null);
INSERT INTO `blogspots` VALUES ('86', 'http://giaydepgiarehanoi.blogspot.com/', '4928501464423677811', '14', '', null, '2016-06-22 18:20:05', '2016-06-22 18:20:05', null);
INSERT INTO `blogspots` VALUES ('87', 'http://giayluoithethao.blogspot.com/', '1985440465480241011', '14', '', null, '2016-06-22 18:20:15', '2016-06-22 18:20:15', null);
INSERT INTO `blogspots` VALUES ('88', 'http://giayluoigiarehcm.blogspot.com/', '5385845689403641427', '14', '', null, '2016-06-22 18:20:25', '2016-06-22 18:20:25', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of gmails
-- ----------------------------
INSERT INTO `gmails` VALUES ('1', 'themanhss@gmail.com', null, '29972.json', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null);
INSERT INTO `gmails` VALUES ('2', 'iag.dev2016@gmail.com', '', '', null, null, null, '0000-00-00 00:00:00', '2016-06-20 17:19:53', null);
INSERT INTO `gmails` VALUES ('3', 'theman2311@gmail.com', '968332556', '57592.json', null, null, null, '2016-06-19 03:59:21', '2016-06-22 14:27:50', null);
INSERT INTO `gmails` VALUES ('6', 'vinhbao.love09@gmail.com', '', '19669.json', null, null, null, '2016-06-20 17:57:03', '2016-06-20 17:57:03', null);
INSERT INTO `gmails` VALUES ('7', 'kiza.vn@gmail.com', '', '', null, null, null, '2016-06-22 17:28:09', '2016-06-22 17:28:09', null);
INSERT INTO `gmails` VALUES ('8', 'daokimdung201192@gmail.com', '', '', null, null, null, '2016-06-22 18:01:12', '2016-06-22 18:01:12', null);
INSERT INTO `gmails` VALUES ('9', 'haiphong.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:03:41', '2016-06-22 18:03:41', null);
INSERT INTO `gmails` VALUES ('10', 'hangnguyen.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:07:24', '2016-06-22 18:07:24', null);
INSERT INTO `gmails` VALUES ('11', 'huonggiang.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:09:40', '2016-06-22 18:09:40', null);
INSERT INTO `gmails` VALUES ('12', 'vinhan.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:12:43', '2016-06-22 18:12:43', null);
INSERT INTO `gmails` VALUES ('13', 'langson.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:15:50', '2016-06-22 18:15:50', null);
INSERT INTO `gmails` VALUES ('14', 'manhbk.love09@gmail.com', '', '', null, null, null, '2016-06-22 18:19:01', '2016-06-22 18:19:01', null);

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
