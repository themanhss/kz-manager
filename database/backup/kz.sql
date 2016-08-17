/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : kz

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-08-17 02:47:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blocks
-- ----------------------------
DROP TABLE IF EXISTS `blocks`;
CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT '0',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `list_li` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail_a` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delete_item` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of blocks
-- ----------------------------
INSERT INTO `blocks` VALUES ('1', 'Kênh 14 - Đời Sống', '0', 'http://kenh14.vn/doi-song.chn', 'sd', 'sd', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null);
INSERT INTO `blocks` VALUES ('2', 'Thanh niên - Giới Trẻ', '1', 'http://thanhnien.vn/gioi-tre/song-yeu-an-choi/', 'df', 'df', null, null, null, '0000-00-00 00:00:00', '2016-07-02 16:14:34', '2016-07-02 16:14:34');
INSERT INTO `blocks` VALUES ('3', 'Kênh 14 - 2 Teck ', '1', 'http://kenh14.vn/2-tek.chn', '.kcnwn', '.kcnwn-thumb', '.knd-title', '.knd-content', '', '2016-07-02 15:02:10', '2016-07-02 16:23:42', null);
INSERT INTO `blocks` VALUES ('4', '#hnbmg - Tin Giày converse 4', '1', 'http://hnbmg.com/c/tin-tuc/converse', '.title-wrapper', '.title-wrapper a', 'h1.title span', '.content', '.leaderboard-wrapper', '2016-07-03 06:11:19', '2016-07-03 06:11:19', null);
INSERT INTO `blocks` VALUES ('5', '#hnbmg - Tin Giày Adidas', '1', 'http://hnbmg.com/c/tin-tuc/adidas', '.title-wrapper', '.title-wrapper a', 'h1.title span', '.content', '.leaderboard-wrapper', '2016-07-03 06:11:19', '2016-07-03 14:52:47', null);

-- ----------------------------
-- Table structure for blogs
-- ----------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `database` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of blogs
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
INSERT INTO `blogspots` VALUES ('89', 'http://muagiaynikehcm.blogspot.sg/', '5962644739751344743', '10', '', null, '2016-08-09 17:37:59', '2016-08-09 17:37:59', null);
INSERT INTO `blogspots` VALUES ('90', 'http://muagiaadidasvn.blogspot.sg/', '264224868183260920', '10', '', null, '2016-08-09 17:38:13', '2016-08-09 17:38:13', null);
INSERT INTO `blogspots` VALUES ('91', 'http://giayconversevn09.blogspot.com/', '1030337920144303457', '10', '', null, '2016-08-09 17:41:18', '2016-08-09 17:41:18', null);
INSERT INTO `blogspots` VALUES ('92', 'http://giayvansdephanoi.blogspot.com/', '8460745376018775486', '10', '', null, '2016-08-09 17:41:38', '2016-08-09 17:41:38', null);
INSERT INTO `blogspots` VALUES ('93', 'http://giaythethaodep2017.blogspot.com/', '8166991692257617183', '13', '', null, '2016-08-09 17:42:13', '2016-08-09 17:42:13', null);
INSERT INTO `blogspots` VALUES ('94', 'http://giayvaimuahe.blogspot.com/', '9203550589475736881', '13', '', null, '2016-08-09 17:42:20', '2016-08-09 17:42:20', null);
INSERT INTO `blogspots` VALUES ('95', 'http://giaydanamgiare2017.blogspot.com/', '107711694543641447', '13', '', null, '2016-08-09 17:42:29', '2016-08-09 17:42:29', null);
INSERT INTO `blogspots` VALUES ('96', 'http://donugiare.blogspot.com/', '2498008965565138483', '13', '', null, '2016-08-09 17:42:38', '2016-08-09 17:42:38', null);
INSERT INTO `blogspots` VALUES ('97', 'http://muagiaynikethethaovn.blogspot.com/', '3649647946192843075', '14', '', null, '2016-08-09 17:43:08', '2016-08-09 17:43:08', null);
INSERT INTO `blogspots` VALUES ('98', 'http://giayvanschinhhang01.blogspot.com/', '1071290832491912992', '14', '', null, '2016-08-09 17:43:15', '2016-08-09 17:43:15', null);
INSERT INTO `blogspots` VALUES ('99', 'http://muagiayconversedep.blogspot.com/', '9036394393697951359', '14', '', null, '2016-08-09 17:43:23', '2016-08-09 17:43:23', null);
INSERT INTO `blogspots` VALUES ('100', 'http://giayadidasgiarevn.blogspot.com/', '6062908676698079028', '14', '', null, '2016-08-09 17:43:30', '2016-08-09 17:43:30', null);

-- ----------------------------
-- Table structure for gmails
-- ----------------------------
DROP TABLE IF EXISTS `gmails`;
CREATE TABLE `gmails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gmail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_key` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pw` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `email_backup` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of gmails
-- ----------------------------
INSERT INTO `gmails` VALUES ('1', 'themanhss@gmail.com', null, '29972.json', 'themanh2311', '1', null, '2016-08-17 01:15:20', null, '0000-00-00 00:00:00', '2016-08-17 01:15:20', null);
INSERT INTO `gmails` VALUES ('2', 'iag.dev2016@gmail.com', null, '62692.json', 'themanh2311', '1', null, '2016-08-17 01:16:01', null, '0000-00-00 00:00:00', '2016-08-17 01:16:01', null);
INSERT INTO `gmails` VALUES ('3', 'theman2311@gmail.com', null, '57592.json', '', '1', null, '2016-07-12 17:04:36', null, '2016-06-19 03:59:21', '2016-07-12 17:04:36', '2016-06-22 18:15:50');
INSERT INTO `gmails` VALUES ('6', 'vinhbao.love09@gmail.com', null, '19669.json', 'themanh2311', '1', null, '2016-08-17 01:16:46', null, '2016-06-20 17:57:03', '2016-08-17 01:16:46', null);
INSERT INTO `gmails` VALUES ('7', 'kiza.vn@gmail.com', null, '52631.json', '', '1', null, '2016-07-12 17:15:05', null, '2016-06-22 17:28:09', '2016-07-12 17:15:05', '2016-06-22 18:15:50');
INSERT INTO `gmails` VALUES ('8', 'daokimdung201192@gmail.com', '', '60940.json', 'manhyeudung', '1', null, '2016-08-17 01:17:19', null, '2016-06-22 18:01:12', '2016-08-17 01:17:19', null);
INSERT INTO `gmails` VALUES ('9', 'haiphong.love09@gmail.com', '', '85207.json', 'themanh2311', '1', null, '2016-08-17 01:17:58', null, '2016-06-22 18:03:41', '2016-08-17 01:17:58', null);
INSERT INTO `gmails` VALUES ('10', 'hangnguyen.love09@gmail.com', '', '18684.json', 'themanh2311', '1', null, '2016-08-17 01:18:41', null, '2016-06-22 18:07:24', '2016-08-17 01:18:41', null);
INSERT INTO `gmails` VALUES ('11', 'huonggiang.love09@gmail.com', '', '56222.json', 'themanh2311', '1', null, '2016-08-17 01:19:25', null, '2016-06-22 18:09:40', '2016-08-17 01:19:25', null);
INSERT INTO `gmails` VALUES ('12', 'vinhan.love09@gmail.com', '', '34236.json', 'themanh2311', '1', null, '2016-08-17 01:20:14', null, '2016-06-22 18:12:43', '2016-08-17 01:20:14', null);
INSERT INTO `gmails` VALUES ('13', 'langson.love09@gmail.com', '', '23600.json', 'themanh2311', '1', null, '2016-08-17 01:21:00', null, '2016-06-22 18:15:50', '2016-08-17 01:21:00', null);
INSERT INTO `gmails` VALUES ('14', 'manhbk.love09@gmail.com', '', '64558.json', 'themanh2311', '1', null, '2016-08-17 01:21:46', null, '2016-06-22 18:19:01', '2016-08-17 01:21:46', null);
INSERT INTO `gmails` VALUES ('15', 'caugiay.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:14:50', '2016-08-15 17:16:14', null);
INSERT INTO `gmails` VALUES ('16', 'badinh.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:16:46', '2016-08-15 17:16:46', null);
INSERT INTO `gmails` VALUES ('17', 'thanhtri.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:17:09', '2016-08-15 17:17:09', null);
INSERT INTO `gmails` VALUES ('18', ' tayho.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:17:42', '2016-08-15 17:17:42', null);
INSERT INTO `gmails` VALUES ('19', 'tuliem.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:18:03', '2016-08-15 17:18:03', null);
INSERT INTO `gmails` VALUES ('20', 'longbien.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:18:24', '2016-08-15 17:18:24', null);
INSERT INTO `gmails` VALUES ('21', 'gialam.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:18:52', '2016-08-15 17:18:52', null);
INSERT INTO `gmails` VALUES ('22', 'hoaiduc.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:19:06', '2016-08-15 17:19:06', null);
INSERT INTO `gmails` VALUES ('23', 'gialam.love08@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:19:28', '2016-08-15 17:19:28', null);
INSERT INTO `gmails` VALUES ('24', 'badinh.love08@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:19:42', '2016-08-15 17:19:42', null);
INSERT INTO `gmails` VALUES ('25', 'hoaithu.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:19:59', '2016-08-15 17:19:59', null);
INSERT INTO `gmails` VALUES ('26', 'thuhue.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:20:11', '2016-08-15 17:20:11', null);
INSERT INTO `gmails` VALUES ('27', 'lequyen.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:20:23', '2016-08-15 17:20:23', null);
INSERT INTO `gmails` VALUES ('28', 'hongnhung.love09@gmail.com', '', '', 'themanh2311', '0', null, null, null, '2016-08-15 17:20:34', '2016-08-15 17:20:34', null);
INSERT INTO `gmails` VALUES ('29', 'trangdao.love09@gmail.com', '', '', 'themanh2311', '2', null, null, null, '2016-08-15 17:20:46', '2016-08-15 17:20:46', null);

-- ----------------------------
-- Table structure for links
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=956 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of links
-- ----------------------------
INSERT INTO `links` VALUES ('821', 'http://giaynikenugiare.blogspot.com/2016/08/hoa-si-phat-minh-my-giay-converse-nam.html', 'Họa sĩ phát minh Mỹ giày converse nam bình luận', null, '2016-08-16 13:19:06', '2016-08-16 13:19:06', null);
INSERT INTO `links` VALUES ('822', 'http://giaynikenamgiare.blogspot.com/2016/08/tinh-cam-hong-kong-bac-si-tieu-chi.html', 'Tình cảm Hồng Kong bác sĩ tiêu chí Trung Quốc', null, '2016-08-16 13:19:08', '2016-08-16 13:19:08', null);
INSERT INTO `links` VALUES ('823', 'http://conversehanoi.blogspot.com/2016/08/nike-gia-re-phat-minh-nha-nuoc-trang.html', 'Nike giá rẻ phát minh nhà nước trang phục adidas nam hcm', null, '2016-08-16 13:19:11', '2016-08-16 13:19:11', null);
INSERT INTO `links` VALUES ('824', 'http://giaynikenuhanoi.blogspot.com/2016/08/trang-phuc-hoa-mi-new-balance-nu-ieu-le.html', 'Trang phục họa mi new balance nữ điều lệ adidas nam hà nội', null, '2016-08-16 13:19:14', '2016-08-16 13:19:14', null);
INSERT INTO `links` VALUES ('825', 'http://giaynikenamhanoi.blogspot.com/2016/08/nike-nam-sen-hong-ban-giay-hoa-binh.html', 'Nike nam sen hồng bán giày hòa bình giày da', null, '2016-08-16 13:19:17', '2016-08-16 13:19:17', null);
INSERT INTO `links` VALUES ('826', 'http://giayvansgiarehanoi.blogspot.com/2016/08/lan-gio-so-luong-giay-nike-chinh-hang.html', 'Làn gió số lượng giày nike chính hãng vans nhân tướng', null, '2016-08-16 13:19:19', '2016-08-16 13:19:19', null);
INSERT INTO `links` VALUES ('827', 'http://giaythethaohcmdep.blogspot.com/2016/08/converse-ho-chi-minh-doanh-nghiep-giay.html', 'Converse Hồ Chí Minh doanh nghiệp giày vans nhận định converse màu xanh phân tích', null, '2016-08-16 13:19:46', '2016-08-16 13:19:46', null);
INSERT INTO `links` VALUES ('828', 'http://muagiaydanamhanoi.blogspot.com/2016/08/giay-ep-hoa-quyen-ai-loan-vans-gia-re.html', 'Giày đẹp hòa quyện Đài Loan vans giá rẻ vans nhập khẩu', null, '2016-08-16 13:19:49', '2016-08-16 13:19:49', null);
INSERT INTO `links` VALUES ('829', 'http://giayadidasvietnam.blogspot.com/2016/08/gan-bo-converse-hue-giay-adidas-bao-chi.html', 'Gắn bó converse Huế giày adidas báo chí số lượng', null, '2016-08-16 13:19:51', '2016-08-16 13:19:51', null);
INSERT INTO `links` VALUES ('830', 'http://giaynikevietnam.blogspot.com/2016/08/new-balance-converse-ho-chi-minh-phat.html', 'New balance converse Hồ Chí Minh phát minh hàng hiệu converse nam', null, '2016-08-16 13:19:54', '2016-08-16 13:19:54', null);
INSERT INTO `links` VALUES ('831', 'http://giaythethaonamgiarehanoi.blogspot.com/2016/08/giay-chinh-hang-duyen-dang-adidas-nha.html', 'Giày chính hãng duyên dáng adidas Nha Trang đáng kể', null, '2016-08-16 13:19:57', '2016-08-16 13:19:57', null);
INSERT INTO `links` VALUES ('832', 'http://giaythethaonuhanoi.blogspot.com/2016/08/chung-khoan-doanh-nghiep-giay-vans-nam.html', 'Chứng khoán doanh nghiệp giày vans nam giải quyết bảo hiểm', null, '2016-08-16 13:19:59', '2016-08-16 13:19:59', null);
INSERT INTO `links` VALUES ('833', 'http://giaydanamdephanoi.blogspot.com/2016/08/giay-nhap-khau-converse-giam-gia-xanh.html', 'Giày nhập khẩu converse giảm giá xanh navy giày đẹp giày vans fake', null, '2016-08-16 13:20:02', '2016-08-16 13:20:02', null);
INSERT INTO `links` VALUES ('834', 'http://giaythethaohcmgiare.blogspot.com/2016/08/vans-vnxk-cong-khang-khit-giay-nam-ang.html', 'Vans vnxk công an khăng khít giày nam đáng kể', null, '2016-08-16 13:20:05', '2016-08-16 13:20:05', null);
INSERT INTO `links` VALUES ('835', 'http://muagiaynuthethao.blogspot.com/2016/08/nike-nam-nhan-inh-dep-converse-giay-ep.html', 'Nike nam nhận định dép converse giày đẹp học sĩ', null, '2016-08-16 13:20:07', '2016-08-16 13:20:07', null);
INSERT INTO `links` VALUES ('836', 'http://giayvansgiare2015.blogspot.com/2016/08/co-phan-hien-converse-fake-converse-ha.html', 'Cổ phần thể hiện converse fake converse Hà Nội converse màu xanh', null, '2016-08-16 13:20:26', '2016-08-16 13:20:26', null);
INSERT INTO `links` VALUES ('837', 'http://giayconversegiare2015.blogspot.com/2016/08/converse-classic-duyen-dang-hoa-mi-xanh.html', 'Converse classic duyên dáng họa mi xanh navy converse vnxk', null, '2016-08-16 13:20:30', '2016-08-16 13:20:30', null);
INSERT INTO `links` VALUES ('838', 'http://shopgiayconversehcm.blogspot.com/2016/08/hien-nay-sua-oi-gia-re-co-cao-binh-chon.html', 'Hiện nay sửa đổi giá rẻ cổ cao bình chọn', null, '2016-08-16 13:20:33', '2016-08-16 13:20:33', null);
INSERT INTO `links` VALUES ('839', 'http://shopgiayvansnam.blogspot.com/2016/08/adidas-re-adidas-nu-adidas-nu-hcm-vans.html', 'Adidas rẻ adidas nữ adidas nữ hcm vans nhập khẩu converse giảm giá', null, '2016-08-16 13:20:36', '2016-08-16 13:20:36', null);
INSERT INTO `links` VALUES ('840', 'http://giayadidasnudanang.blogspot.com/2016/08/cao-co-binh-thuan-adidas-ha-noi-vans-ep.html', 'Cao cổ Bình Thuận adidas Hà Nội vans đẹp sửa đổi', null, '2016-08-16 13:20:39', '2016-08-16 13:20:39', null);
INSERT INTO `links` VALUES ('841', 'http://giayvansdepgiare.blogspot.com/2016/08/thuc-thi-giay-nike-nu-vans-nhap-khau.html', 'Thực thi giày nike nữ vans nhập khẩu bác học hàng vnxk', null, '2016-08-16 13:20:42', '2016-08-16 13:20:42', null);
INSERT INTO `links` VALUES ('842', 'http://giaynikedepgiare.blogspot.com/2016/08/giay-adidas-vnxk-cap-giay-co-thap.html', 'Giày adidas vnxk cặp giầy cổ thấp adidas fake nhân cách', null, '2016-08-16 13:20:44', '2016-08-16 13:20:44', null);
INSERT INTO `links` VALUES ('843', 'http://giaynikenamdanang.blogspot.com/2016/08/adidas-vnxk-mua-new-balance-converse.html', 'Adidas vnxk mua new balance converse Cần Thơ khoe sắc tự chọn vans', null, '2016-08-16 13:20:47', '2016-08-16 13:20:47', null);
INSERT INTO `links` VALUES ('844', 'http://giayadidasdanang.blogspot.com/2016/08/adidas-gia-re-ban-giay-nike-converse.html', 'Adidas giá rẻ bán giày nike converse vnxk thời trang xanh đỏ', null, '2016-08-16 13:20:51', '2016-08-16 13:20:51', null);
INSERT INTO `links` VALUES ('845', 'http://giaynikedanang.blogspot.com/2016/08/converse-nam-hong-kong-giay-nike-sua-oi.html', 'Converse nam Hồng Kong giày nike sửa đổi vans Huế', null, '2016-08-16 13:20:53', '2016-08-16 13:20:53', null);
INSERT INTO `links` VALUES ('846', 'http://muagiaythethaonu.blogspot.com/2016/08/converse-giam-gia-giay-converse-nu.html', 'Converse giảm giá giày converse nữ adidas nam phân tích giày nike nữ HCM', null, '2016-08-16 13:21:12', '2016-08-16 13:21:12', null);
INSERT INTO `links` VALUES ('847', 'http://muagiaynikenamgiare.blogspot.com/2016/08/hoa-si-mua-vans-vans-gia-re-phu-tho.html', 'Họa sĩ mua vans vans giá rẻ Phú Thọ giày converse rẻ', null, '2016-08-16 13:21:15', '2016-08-16 13:21:15', null);
INSERT INTO `links` VALUES ('848', 'http://muagiaynikegiare.blogspot.com/2016/08/giay-nike-chinh-hang-mua-nike-converse.html', 'Giày nike chính hãng mua nike converse màu xanh nhân sự nghệ thuật', null, '2016-08-16 13:21:17', '2016-08-16 13:21:17', null);
INSERT INTO `links` VALUES ('849', 'http://giaynikenuhaiphong.blogspot.com/2016/08/ban-converse-vnxk-giay-vans-chinh-hang.html', 'Bán converse vnxk giày vans chính hãng converse giá rẻ báo chí toàn bộ', null, '2016-08-16 13:21:20', '2016-08-16 13:21:20', null);
INSERT INTO `links` VALUES ('850', 'http://muagiayadidashaiphong.blogspot.com/2016/08/giay-chinh-hang-chiem-nguong-ca-si.html', 'Giày chính hãng chiêm ngưỡng ca sĩ trang phục xanh đỏ', null, '2016-08-16 13:21:23', '2016-08-16 13:21:23', null);
INSERT INTO `links` VALUES ('851', 'http://muagiayconversechuck2.blogspot.com/2016/08/tinh-cam-hong-kong-bac-si-tieu-chi.html', 'Tình cảm Hồng Kong bác sĩ tiêu chí Trung Quốc', null, '2016-08-16 13:21:25', '2016-08-16 13:21:25', null);
INSERT INTO `links` VALUES ('852', 'http://giaynikeairmaxhanoi.blogspot.com/2016/08/the-hien-adidas-new-balance-adidas-gia.html', 'Thể hiện adidas new balance adidas giá rẻ hiệp hội', null, '2016-08-16 13:21:47', '2016-08-16 13:21:47', null);
INSERT INTO `links` VALUES ('853', 'http://giayvansdepdanang.blogspot.com/2016/08/trang-phuc-hoa-mi-new-balance-nu-ieu-le.html', 'Trang phục họa mi new balance nữ điều lệ adidas nam hà nội', null, '2016-08-16 13:21:49', '2016-08-16 13:21:49', null);
INSERT INTO `links` VALUES ('854', 'http://giaythethaogiarehanoi.blogspot.com/2016/08/nike-nam-sen-hong-ban-giay-hoa-binh.html', 'Nike nam sen hồng bán giày hòa bình giày da', null, '2016-08-16 13:21:52', '2016-08-16 13:21:52', null);
INSERT INTO `links` VALUES ('855', 'http://giayvansoldskoolgiare.blogspot.com/2016/08/lan-gio-so-luong-giay-nike-chinh-hang.html', 'Làn gió số lượng giày nike chính hãng vans nhân tướng', null, '2016-08-16 13:21:54', '2016-08-16 13:21:54', null);
INSERT INTO `links` VALUES ('856', 'http://giaythethaonamdephn.blogspot.com/2016/08/vans-chinh-hang-bac-si-vnxk-giam-gia.html', 'Vans chính hãng bác sĩ vnxk giảm giá cao su học sĩ', null, '2016-08-16 13:21:57', '2016-08-16 13:21:57', null);
INSERT INTO `links` VALUES ('857', 'http://giayvansthethao.blogspot.com/2016/08/giay-ep-hoa-quyen-ai-loan-vans-gia-re.html', 'Giày đẹp hòa quyện Đài Loan vans giá rẻ vans nhập khẩu', null, '2016-08-16 13:22:00', '2016-08-16 13:22:00', null);
INSERT INTO `links` VALUES ('858', 'http://giayconversethethao.blogspot.com/2016/08/gan-bo-converse-hue-giay-adidas-bao-chi.html', 'Gắn bó converse Huế giày adidas báo chí số lượng', null, '2016-08-16 13:22:03', '2016-08-16 13:22:03', null);
INSERT INTO `links` VALUES ('859', 'http://giayadidasthethao.blogspot.com/2016/08/new-balance-converse-ho-chi-minh-phat.html', 'New balance converse Hồ Chí Minh phát minh hàng hiệu converse nam', null, '2016-08-16 13:22:06', '2016-08-16 13:22:06', null);
INSERT INTO `links` VALUES ('860', 'http://giayconversechucktaylor.blogspot.com/2016/08/giay-chinh-hang-duyen-dang-adidas-nha.html', 'Giày chính hãng duyên dáng adidas Nha Trang đáng kể', null, '2016-08-16 13:22:09', '2016-08-16 13:22:09', null);
INSERT INTO `links` VALUES ('861', 'http://giaynikegiarehcm.blogspot.com/2016/08/giay-adidas-hop-phap-giay-nike-luoi-chi.html', 'Giày adidas hợp pháp giày nike lười chi tiết gia tăng', null, '2016-08-16 13:22:36', '2016-08-16 13:22:36', null);
INSERT INTO `links` VALUES ('862', 'http://giayvansoldskoolgiarehcm.blogspot.com/2016/08/giay-nhap-khau-converse-giam-gia-xanh.html', 'Giày nhập khẩu converse giảm giá xanh navy giày đẹp giày vans fake', null, '2016-08-16 13:22:39', '2016-08-16 13:22:39', null);
INSERT INTO `links` VALUES ('863', 'http://giayvansclassicgiare.blogspot.com/2016/08/vans-vnxk-cong-khang-khit-giay-nam-ang.html', 'Vans vnxk công an khăng khít giày nam đáng kể', null, '2016-08-16 13:22:42', '2016-08-16 13:22:42', null);
INSERT INTO `links` VALUES ('864', 'http://giayadidassaigongiare.blogspot.com/2016/08/nike-nam-nhan-inh-dep-converse-giay-ep.html', 'Nike nam nhận định dép converse giày đẹp học sĩ', null, '2016-08-16 13:22:45', '2016-08-16 13:22:45', null);
INSERT INTO `links` VALUES ('865', 'http://giayconversecantho.blogspot.com/2016/08/phu-tho-new-balance-nghe-nhan-adidas.html', 'Phú Thọ new balance nghệ nhân adidas fake converse màu ghi', null, '2016-08-16 13:22:47', '2016-08-16 13:22:47', null);
INSERT INTO `links` VALUES ('866', 'http://giaythethaodanang.blogspot.com/2016/08/converse-classic-duyen-dang-hoa-mi-xanh.html', 'Converse classic duyên dáng họa mi xanh navy converse vnxk', null, '2016-08-16 13:22:50', '2016-08-16 13:22:50', null);
INSERT INTO `links` VALUES ('867', 'http://muagiaynikehcm.blogspot.com/2016/08/hien-nay-sua-oi-gia-re-co-cao-binh-chon.html', 'Hiện nay sửa đổi giá rẻ cổ cao bình chọn', null, '2016-08-16 13:22:53', '2016-08-16 13:22:53', null);
INSERT INTO `links` VALUES ('868', 'http://muagiaadidasvn.blogspot.com/2016/08/adidas-re-adidas-nu-adidas-nu-hcm-vans.html', 'Adidas rẻ adidas nữ adidas nữ hcm vans nhập khẩu converse giảm giá', null, '2016-08-16 13:22:58', '2016-08-16 13:22:58', null);
INSERT INTO `links` VALUES ('869', 'http://giayconversevn09.blogspot.com/2016/08/cao-co-binh-thuan-adidas-ha-noi-vans-ep.html', 'Cao cổ Bình Thuận adidas Hà Nội vans đẹp sửa đổi', null, '2016-08-16 13:23:01', '2016-08-16 13:23:01', null);
INSERT INTO `links` VALUES ('870', 'http://giaynikenugiare.blogspot.com/2016/08/tri-thuc-mua-giay-lan-gio-converse-da.html', 'Trí thức mua giày làn gió converse da Khánh Hòa', null, '2016-08-17 01:15:09', '2016-08-17 01:15:09', null);
INSERT INTO `links` VALUES ('871', 'http://giaynikenamgiare.blogspot.com/2016/08/adidas-vnxk-mua-new-balance-converse.html', 'Adidas vnxk mua new balance converse Cần Thơ khoe sắc tự chọn vans', null, '2016-08-17 01:15:12', '2016-08-17 01:15:12', null);
INSERT INTO `links` VALUES ('872', 'http://conversehanoi.blogspot.com/2016/08/adidas-gia-re-ban-giay-nike-converse.html', 'Adidas giá rẻ bán giày nike converse vnxk thời trang xanh đỏ', null, '2016-08-17 01:15:13', '2016-08-17 01:15:13', null);
INSERT INTO `links` VALUES ('873', 'http://giaynikenuhanoi.blogspot.com/2016/08/converse-nam-hong-kong-giay-nike-sua-oi.html', 'Converse nam Hồng Kong giày nike sửa đổi vans Huế', null, '2016-08-17 01:15:16', '2016-08-17 01:15:16', null);
INSERT INTO `links` VALUES ('874', 'http://giaynikenamhanoi.blogspot.com/2016/08/thoi-trang-giay-converse-nam-giay.html', 'Thời trang giày converse nam giày converse rẻ nghệ thuật giải pháp', null, '2016-08-17 01:15:18', '2016-08-17 01:15:18', null);
INSERT INTO `links` VALUES ('875', 'http://giayvansgiarehanoi.blogspot.com/2016/08/hoa-si-mua-vans-vans-gia-re-phu-tho.html', 'Họa sĩ mua vans vans giá rẻ Phú Thọ giày converse rẻ', null, '2016-08-17 01:15:20', '2016-08-17 01:15:20', null);
INSERT INTO `links` VALUES ('876', 'http://giaythethaohcmdep.blogspot.com/2016/08/ban-giay-vans-mua-nike-xanh-navy-vans.html', 'Bán giày vans mua nike xanh navy vans lười giày converse Đà Nẵng', null, '2016-08-17 01:15:41', '2016-08-17 01:15:41', null);
INSERT INTO `links` VALUES ('877', 'http://muagiaydanamhanoi.blogspot.com/2016/08/ban-converse-vnxk-giay-vans-chinh-hang.html', 'Bán converse vnxk giày vans chính hãng converse giá rẻ báo chí toàn bộ', null, '2016-08-17 01:15:44', '2016-08-17 01:15:44', null);
INSERT INTO `links` VALUES ('878', 'http://giayadidasvietnam.blogspot.com/2016/08/giay-chinh-hang-chiem-nguong-ca-si.html', 'Giày chính hãng chiêm ngưỡng ca sĩ trang phục xanh đỏ', null, '2016-08-17 01:15:46', '2016-08-17 01:15:46', null);
INSERT INTO `links` VALUES ('879', 'http://giaynikevietnam.blogspot.com/2016/08/tinh-cam-hong-kong-bac-si-tieu-chi.html', 'Tình cảm Hồng Kong bác sĩ tiêu chí Trung Quốc', null, '2016-08-17 01:15:48', '2016-08-17 01:15:48', null);
INSERT INTO `links` VALUES ('880', 'http://giaythethaonamgiarehanoi.blogspot.com/2016/08/nike-gia-re-phat-minh-nha-nuoc-trang.html', 'Nike giá rẻ phát minh nhà nước trang phục adidas nam hcm', null, '2016-08-17 01:15:51', '2016-08-17 01:15:51', null);
INSERT INTO `links` VALUES ('881', 'http://giaythethaonuhanoi.blogspot.com/2016/08/trang-phuc-hoa-mi-new-balance-nu-ieu-le.html', 'Trang phục họa mi new balance nữ điều lệ adidas nam hà nội', null, '2016-08-17 01:15:53', '2016-08-17 01:15:53', null);
INSERT INTO `links` VALUES ('882', 'http://giaydanamdephanoi.blogspot.com/2016/08/nike-nam-sen-hong-ban-giay-hoa-binh.html', 'Nike nam sen hồng bán giày hòa bình giày da', null, '2016-08-17 01:15:55', '2016-08-17 01:15:55', null);
INSERT INTO `links` VALUES ('883', 'http://giaythethaohcmgiare.blogspot.com/2016/08/lan-gio-so-luong-giay-nike-chinh-hang.html', 'Làn gió số lượng giày nike chính hãng vans nhân tướng', null, '2016-08-17 01:15:58', '2016-08-17 01:15:58', null);
INSERT INTO `links` VALUES ('884', 'http://muagiaynuthethao.blogspot.com/2016/08/vans-chinh-hang-bac-si-vnxk-giam-gia.html', 'Vans chính hãng bác sĩ vnxk giảm giá cao su học sĩ', null, '2016-08-17 01:16:01', '2016-08-17 01:16:01', null);
INSERT INTO `links` VALUES ('885', 'http://giayvansgiare2015.blogspot.com/2016/08/thuot-tha-cong-nghe-nhan-nhan-su.html', 'Thướt tha công an nghệ nhân nhân sự converse Hải Phòng', null, '2016-08-17 01:16:20', '2016-08-17 01:16:20', null);
INSERT INTO `links` VALUES ('886', 'http://giayconversegiare2015.blogspot.com/2016/08/gan-bo-converse-hue-giay-adidas-bao-chi.html', 'Gắn bó converse Huế giày adidas báo chí số lượng', null, '2016-08-17 01:16:23', '2016-08-17 01:16:23', null);
INSERT INTO `links` VALUES ('887', 'http://shopgiayconversehcm.blogspot.com/2016/08/new-balance-converse-ho-chi-minh-phat.html', 'New balance converse Hồ Chí Minh phát minh hàng hiệu converse nam', null, '2016-08-17 01:16:27', '2016-08-17 01:16:27', null);
INSERT INTO `links` VALUES ('888', 'http://shopgiayvansnam.blogspot.com/2016/08/giay-chinh-hang-duyen-dang-adidas-nha.html', 'Giày chính hãng duyên dáng adidas Nha Trang đáng kể', null, '2016-08-17 01:16:31', '2016-08-17 01:16:31', null);
INSERT INTO `links` VALUES ('889', 'http://giayadidasnudanang.blogspot.com/2016/08/chung-khoan-doanh-nghiep-giay-vans-nam.html', 'Chứng khoán doanh nghiệp giày vans nam giải quyết bảo hiểm', null, '2016-08-17 01:16:33', '2016-08-17 01:16:33', null);
INSERT INTO `links` VALUES ('890', 'http://giayvansdepgiare.blogspot.com/2016/08/giay-nhap-khau-converse-giam-gia-xanh.html', 'Giày nhập khẩu converse giảm giá xanh navy giày đẹp giày vans fake', null, '2016-08-17 01:16:35', '2016-08-17 01:16:35', null);
INSERT INTO `links` VALUES ('891', 'http://giaynikedepgiare.blogspot.com/2016/08/vans-vnxk-cong-khang-khit-giay-nam-ang.html', 'Vans vnxk công an khăng khít giày nam đáng kể', null, '2016-08-17 01:16:37', '2016-08-17 01:16:37', null);
INSERT INTO `links` VALUES ('892', 'http://giaynikenamdanang.blogspot.com/2016/08/nike-nam-nhan-inh-dep-converse-giay-ep.html', 'Nike nam nhận định dép converse giày đẹp học sĩ', null, '2016-08-17 01:16:40', '2016-08-17 01:16:40', null);
INSERT INTO `links` VALUES ('893', 'http://giayadidasdanang.blogspot.com/2016/08/phu-tho-new-balance-nghe-nhan-adidas.html', 'Phú Thọ new balance nghệ nhân adidas fake converse màu ghi', null, '2016-08-17 01:16:43', '2016-08-17 01:16:43', null);
INSERT INTO `links` VALUES ('894', 'http://giaynikedanang.blogspot.com/2016/08/converse-classic-duyen-dang-hoa-mi-xanh.html', 'Converse classic duyên dáng họa mi xanh navy converse vnxk', null, '2016-08-17 01:16:46', '2016-08-17 01:16:46', null);
INSERT INTO `links` VALUES ('895', 'http://muagiaythethaonu.blogspot.com/2016/08/mua-adidas-converse-chinh-hang-nghe.html', 'Mua adidas converse chính hãng nghệ nhân chính thức giày đẹp', null, '2016-08-17 01:17:06', '2016-08-17 01:17:06', null);
INSERT INTO `links` VALUES ('896', 'http://muagiaynikenamgiare.blogspot.com/2016/08/adidas-re-adidas-nu-adidas-nu-hcm-vans.html', 'Adidas rẻ adidas nữ adidas nữ hcm vans nhập khẩu converse giảm giá', null, '2016-08-17 01:17:08', '2016-08-17 01:17:08', null);
INSERT INTO `links` VALUES ('897', 'http://muagiaynikegiare.blogspot.com/2016/08/cao-co-binh-thuan-adidas-ha-noi-vans-ep.html', 'Cao cổ Bình Thuận adidas Hà Nội vans đẹp sửa đổi', null, '2016-08-17 01:17:11', '2016-08-17 01:17:11', null);
INSERT INTO `links` VALUES ('898', 'http://giaynikenuhaiphong.blogspot.com/2016/08/thuc-thi-giay-nike-nu-vans-nhap-khau.html', 'Thực thi giày nike nữ vans nhập khẩu bác học hàng vnxk', null, '2016-08-17 01:17:13', '2016-08-17 01:17:13', null);
INSERT INTO `links` VALUES ('899', 'http://muagiayadidashaiphong.blogspot.com/2016/08/giay-adidas-vnxk-cap-giay-co-thap.html', 'Giày adidas vnxk cặp giầy cổ thấp adidas fake nhân cách', null, '2016-08-17 01:17:15', '2016-08-17 01:17:15', null);
INSERT INTO `links` VALUES ('900', 'http://muagiayconversechuck2.blogspot.com/2016/08/adidas-vnxk-mua-new-balance-converse.html', 'Adidas vnxk mua new balance converse Cần Thơ khoe sắc tự chọn vans', null, '2016-08-17 01:17:19', '2016-08-17 01:17:19', null);
INSERT INTO `links` VALUES ('901', 'http://giaynikeairmaxhanoi.blogspot.com/2016/08/thap-co-ca-mau-hoa-mi-y-inh-mua-giay-ca.html', 'Thấp cổ Cà Mau họa mi ý định mua giày ca sĩ', null, '2016-08-17 01:17:39', '2016-08-17 01:17:39', null);
INSERT INTO `links` VALUES ('902', 'http://giayvansdepdanang.blogspot.com/2016/08/converse-nam-hong-kong-giay-nike-sua-oi.html', 'Converse nam Hồng Kong giày nike sửa đổi vans Huế', null, '2016-08-17 01:17:41', '2016-08-17 01:17:41', null);
INSERT INTO `links` VALUES ('903', 'http://giaythethaogiarehanoi.blogspot.com/2016/08/thoi-trang-giay-converse-nam-giay.html', 'Thời trang giày converse nam giày converse rẻ nghệ thuật giải pháp', null, '2016-08-17 01:17:43', '2016-08-17 01:17:43', null);
INSERT INTO `links` VALUES ('904', 'http://giayvansoldskoolgiare.blogspot.com/2016/08/hoa-si-mua-vans-vans-gia-re-phu-tho.html', 'Họa sĩ mua vans vans giá rẻ Phú Thọ giày converse rẻ', null, '2016-08-17 01:17:45', '2016-08-17 01:17:45', null);
INSERT INTO `links` VALUES ('905', 'http://giaythethaonamdephn.blogspot.com/2016/08/giay-nike-chinh-hang-mua-nike-converse.html', 'Giày nike chính hãng mua nike converse màu xanh nhân sự nghệ thuật', null, '2016-08-17 01:17:47', '2016-08-17 01:17:47', null);
INSERT INTO `links` VALUES ('906', 'http://giayvansthethao.blogspot.com/2016/08/ban-converse-vnxk-giay-vans-chinh-hang.html', 'Bán converse vnxk giày vans chính hãng converse giá rẻ báo chí toàn bộ', null, '2016-08-17 01:17:49', '2016-08-17 01:17:49', null);
INSERT INTO `links` VALUES ('907', 'http://giayconversethethao.blogspot.com/2016/08/giay-chinh-hang-chiem-nguong-ca-si.html', 'Giày chính hãng chiêm ngưỡng ca sĩ trang phục xanh đỏ', null, '2016-08-17 01:17:53', '2016-08-17 01:17:53', null);
INSERT INTO `links` VALUES ('908', 'http://giayadidasthethao.blogspot.com/2016/08/tinh-cam-hong-kong-bac-si-tieu-chi.html', 'Tình cảm Hồng Kong bác sĩ tiêu chí Trung Quốc', null, '2016-08-17 01:17:55', '2016-08-17 01:17:55', null);
INSERT INTO `links` VALUES ('909', 'http://giayconversechucktaylor.blogspot.com/2016/08/nike-gia-re-phat-minh-nha-nuoc-trang.html', 'Nike giá rẻ phát minh nhà nước trang phục adidas nam hcm', null, '2016-08-17 01:17:58', '2016-08-17 01:17:58', null);
INSERT INTO `links` VALUES ('910', 'http://giaynikegiarehcm.blogspot.com/2016/08/giay-vans-chinh-hang-gia-tang-hang-hieu.html', 'Giày vans chính hãng gia tăng hàng hiệu sửa đổi adidas nam', null, '2016-08-17 01:18:20', '2016-08-17 01:18:20', null);
INSERT INTO `links` VALUES ('911', 'http://giayvansoldskoolgiarehcm.blogspot.com/2016/08/mua-adidas-vans-giam-gia-mua-vans-giay.html', 'Mua adidas vans giảm giá mua vans giày adidas adidas giảm giá', null, '2016-08-17 01:18:22', '2016-08-17 01:18:22', null);
INSERT INTO `links` VALUES ('912', 'http://giayvansclassicgiare.blogspot.com/2016/08/hop-phap-giay-converse-ep-nhan-cach.html', 'Hợp pháp giày converse đẹp nhân cách Nghệ An giày converse rẻ', null, '2016-08-17 01:18:25', '2016-08-17 01:18:25', null);
INSERT INTO `links` VALUES ('913', 'http://giayadidassaigongiare.blogspot.com/2016/08/vans-chinh-hang-bac-si-vnxk-giam-gia.html', 'Vans chính hãng bác sĩ vnxk giảm giá cao su học sĩ', null, '2016-08-17 01:18:27', '2016-08-17 01:18:27', null);
INSERT INTO `links` VALUES ('914', 'http://giayconversecantho.blogspot.com/2016/08/giay-ep-hoa-quyen-ai-loan-vans-gia-re.html', 'Giày đẹp hòa quyện Đài Loan vans giá rẻ vans nhập khẩu', null, '2016-08-17 01:18:29', '2016-08-17 01:18:29', null);
INSERT INTO `links` VALUES ('915', 'http://giaythethaodanang.blogspot.com/2016/08/gan-bo-converse-hue-giay-adidas-bao-chi.html', 'Gắn bó converse Huế giày adidas báo chí số lượng', null, '2016-08-17 01:18:32', '2016-08-17 01:18:32', null);
INSERT INTO `links` VALUES ('916', 'http://muagiaynikehcm.blogspot.com/2016/08/new-balance-converse-ho-chi-minh-phat.html', 'New balance converse Hồ Chí Minh phát minh hàng hiệu converse nam', null, '2016-08-17 01:18:34', '2016-08-17 01:18:34', null);
INSERT INTO `links` VALUES ('917', 'http://muagiaadidasvn.blogspot.com/2016/08/giay-chinh-hang-duyen-dang-adidas-nha.html', 'Giày chính hãng duyên dáng adidas Nha Trang đáng kể', null, '2016-08-17 01:18:36', '2016-08-17 01:18:36', null);
INSERT INTO `links` VALUES ('918', 'http://giayconversevn09.blogspot.com/2016/08/chung-khoan-doanh-nghiep-giay-vans-nam.html', 'Chứng khoán doanh nghiệp giày vans nam giải quyết bảo hiểm', null, '2016-08-17 01:18:38', '2016-08-17 01:18:38', null);
INSERT INTO `links` VALUES ('919', 'http://giayvansdephanoi.blogspot.com/2016/08/giay-nhap-khau-converse-giam-gia-xanh.html', 'Giày nhập khẩu converse giảm giá xanh navy giày đẹp giày vans fake', null, '2016-08-17 01:18:41', '2016-08-17 01:18:41', null);
INSERT INTO `links` VALUES ('920', 'http://giayadidascaugiay.blogspot.com/2016/08/giay-vans-luoi-adidas-giam-gia-diu-dang.html', 'Giày vans lười adidas giảm giá dịu dàng Hồng Kong trang phục', null, '2016-08-17 01:19:07', '2016-08-17 01:19:07', null);
INSERT INTO `links` VALUES ('921', 'http://giayconversecaugiay.blogspot.com/2016/08/nike-nam-nhan-inh-dep-converse-giay-ep.html', 'Nike nam nhận định dép converse giày đẹp học sĩ', null, '2016-08-17 01:19:09', '2016-08-17 01:19:09', null);
INSERT INTO `links` VALUES ('922', 'http://giaynumuahe.blogspot.com/2016/08/phu-tho-new-balance-nghe-nhan-adidas.html', 'Phú Thọ new balance nghệ nhân adidas fake converse màu ghi', null, '2016-08-17 01:19:12', '2016-08-17 01:19:12', null);
INSERT INTO `links` VALUES ('923', 'http://giaynammuadong.blogspot.com/2016/08/converse-classic-duyen-dang-hoa-mi-xanh.html', 'Converse classic duyên dáng họa mi xanh navy converse vnxk', null, '2016-08-17 01:19:15', '2016-08-17 01:19:15', null);
INSERT INTO `links` VALUES ('924', 'http://giaynikegiarevietnam.blogspot.com/2016/08/hien-nay-sua-oi-gia-re-co-cao-binh-chon.html', 'Hiện nay sửa đổi giá rẻ cổ cao bình chọn', null, '2016-08-17 01:19:18', '2016-08-17 01:19:18', null);
INSERT INTO `links` VALUES ('925', 'http://giayadidasgiarevietnam.blogspot.com/2016/08/adidas-re-adidas-nu-adidas-nu-hcm-vans.html', 'Adidas rẻ adidas nữ adidas nữ hcm vans nhập khẩu converse giảm giá', null, '2016-08-17 01:19:21', '2016-08-17 01:19:21', null);
INSERT INTO `links` VALUES ('926', 'http://giayconversegiarevietnam.blogspot.com/2016/08/cao-co-binh-thuan-adidas-ha-noi-vans-ep.html', 'Cao cổ Bình Thuận adidas Hà Nội vans đẹp sửa đổi', null, '2016-08-17 01:19:23', '2016-08-17 01:19:23', null);
INSERT INTO `links` VALUES ('927', 'http://giaythethaomuadong.blogspot.com/2016/08/thuc-thi-giay-nike-nu-vans-nhap-khau.html', 'Thực thi giày nike nữ vans nhập khẩu bác học hàng vnxk', null, '2016-08-17 01:19:25', '2016-08-17 01:19:25', null);
INSERT INTO `links` VALUES ('928', 'http://giaythethaonhatrang.blogspot.com/2016/08/giay-ep-bac-si-ca-mau-tieu-chi-khoe-manh.html', 'Giày đẹp bác sĩ Cà Mau tiêu chí khỏe mạnh', null, '2016-08-17 01:19:52', '2016-08-17 01:19:52', null);
INSERT INTO `links` VALUES ('929', 'http://giayconversechuck2giare.blogspot.com/2016/08/adidas-vnxk-mua-new-balance-converse.html', 'Adidas vnxk mua new balance converse Cần Thơ khoe sắc tự chọn vans', null, '2016-08-17 01:19:55', '2016-08-17 01:19:55', null);
INSERT INTO `links` VALUES ('930', 'http://giayvansclassicdepgiare.blogspot.com/2016/08/adidas-gia-re-ban-giay-nike-converse.html', 'Adidas giá rẻ bán giày nike converse vnxk thời trang xanh đỏ', null, '2016-08-17 01:19:57', '2016-08-17 01:19:57', null);
INSERT INTO `links` VALUES ('931', 'http://giaythethaoadidasgiare.blogspot.com/2016/08/converse-nam-hong-kong-giay-nike-sua-oi.html', 'Converse nam Hồng Kong giày nike sửa đổi vans Huế', null, '2016-08-17 01:20:00', '2016-08-17 01:20:00', null);
INSERT INTO `links` VALUES ('932', 'http://giaythethaovans.blogspot.com/2016/08/thoi-trang-giay-converse-nam-giay.html', 'Thời trang giày converse nam giày converse rẻ nghệ thuật giải pháp', null, '2016-08-17 01:20:03', '2016-08-17 01:20:03', null);
INSERT INTO `links` VALUES ('933', 'http://giaythethaoconverse.blogspot.com/2016/08/hoa-si-mua-vans-vans-gia-re-phu-tho.html', 'Họa sĩ mua vans vans giá rẻ Phú Thọ giày converse rẻ', null, '2016-08-17 01:20:05', '2016-08-17 01:20:05', null);
INSERT INTO `links` VALUES ('934', 'http://giaydepgiarenhatrang.blogspot.com/2016/08/giay-nike-chinh-hang-mua-nike-converse.html', 'Giày nike chính hãng mua nike converse màu xanh nhân sự nghệ thuật', null, '2016-08-17 01:20:08', '2016-08-17 01:20:08', null);
INSERT INTO `links` VALUES ('935', 'http://muagiaygiareodau.blogspot.com/2016/08/ban-converse-vnxk-giay-vans-chinh-hang.html', 'Bán converse vnxk giày vans chính hãng converse giá rẻ báo chí toàn bộ', null, '2016-08-17 01:20:11', '2016-08-17 01:20:11', null);
INSERT INTO `links` VALUES ('936', 'http://muagiaychuck2giare.blogspot.com/2016/08/giay-chinh-hang-chiem-nguong-ca-si.html', 'Giày chính hãng chiêm ngưỡng ca sĩ trang phục xanh đỏ', null, '2016-08-17 01:20:14', '2016-08-17 01:20:14', null);
INSERT INTO `links` VALUES ('937', 'http://giaynikenamgiarehanoi.blogspot.com/2016/08/converse-can-tho-adidas-ha-noi-vans.html', 'Converse Cần Thơ adidas Hà Nội vans lười khuyến mại convese cổ thấp', null, '2016-08-17 01:20:40', '2016-08-17 01:20:40', null);
INSERT INTO `links` VALUES ('938', 'http://giayvansoldskool.blogspot.com/2016/08/converse-nha-trang-vans-giam-gia-ay.html', 'Converse Nha Trang vans giảm giá đẩy nhanh adidas nam mềm mại', null, '2016-08-17 01:20:42', '2016-08-17 01:20:42', null);
INSERT INTO `links` VALUES ('939', 'http://giayconversechucktaylor2.blogspot.com/2016/08/niem-yet-bac-si-cap-giay-thoi-trang-nha.html', 'Niêm yết bác sĩ cặp giầy thời trang Nha Trang', null, '2016-08-17 01:20:44', '2016-08-17 01:20:44', null);
INSERT INTO `links` VALUES ('940', 'http://giaythethaoadidashcm.blogspot.com/2016/08/mua-adidas-vans-giam-gia-mua-vans-giay.html', 'Mua adidas vans giảm giá mua vans giày adidas adidas giảm giá', null, '2016-08-17 01:20:47', '2016-08-17 01:20:47', null);
INSERT INTO `links` VALUES ('941', 'http://giayconverseclassichanoi.blogspot.com/2016/08/hop-phap-giay-converse-ep-nhan-cach.html', 'Hợp pháp giày converse đẹp nhân cách Nghệ An giày converse rẻ', null, '2016-08-17 01:20:50', '2016-08-17 01:20:50', null);
INSERT INTO `links` VALUES ('942', 'http://giayconversechuck2hcm.blogspot.com/2016/08/vans-chinh-hang-bac-si-vnxk-giam-gia.html', 'Vans chính hãng bác sĩ vnxk giảm giá cao su học sĩ', null, '2016-08-17 01:20:52', '2016-08-17 01:20:52', null);
INSERT INTO `links` VALUES ('943', 'http://giaythethaodep2017.blogspot.com/2016/08/giay-ep-hoa-quyen-ai-loan-vans-gia-re.html', 'Giày đẹp hòa quyện Đài Loan vans giá rẻ vans nhập khẩu', null, '2016-08-17 01:20:54', '2016-08-17 01:20:54', null);
INSERT INTO `links` VALUES ('944', 'http://giayvaimuahe.blogspot.com/2016/08/gan-bo-converse-hue-giay-adidas-bao-chi.html', 'Gắn bó converse Huế giày adidas báo chí số lượng', null, '2016-08-17 01:20:56', '2016-08-17 01:20:56', null);
INSERT INTO `links` VALUES ('945', 'http://giaydanamgiare2017.blogspot.com/2016/08/new-balance-converse-ho-chi-minh-phat.html', 'New balance converse Hồ Chí Minh phát minh hàng hiệu converse nam', null, '2016-08-17 01:20:58', '2016-08-17 01:20:58', null);
INSERT INTO `links` VALUES ('946', 'http://donugiare.blogspot.com/2016/08/giay-chinh-hang-duyen-dang-adidas-nha.html', 'Giày chính hãng duyên dáng adidas Nha Trang đáng kể', null, '2016-08-17 01:21:00', '2016-08-17 01:21:00', null);
INSERT INTO `links` VALUES ('947', 'http://giaydagiarehanoi.blogspot.com/2016/08/giay-adidas-hop-phap-giay-nike-luoi-chi.html', 'Giày adidas hợp pháp giày nike lười chi tiết gia tăng', null, '2016-08-17 01:21:27', '2016-08-17 01:21:27', null);
INSERT INTO `links` VALUES ('948', 'http://giaythethaonamdepgiare.blogspot.com/2016/08/giay-nhap-khau-converse-giam-gia-xanh.html', 'Giày nhập khẩu converse giảm giá xanh navy giày đẹp giày vans fake', null, '2016-08-17 01:21:29', '2016-08-17 01:21:29', null);
INSERT INTO `links` VALUES ('949', 'http://giaydepgiarehanoi.blogspot.com/2016/08/vans-vnxk-cong-khang-khit-giay-nam-ang.html', 'Vans vnxk công an khăng khít giày nam đáng kể', null, '2016-08-17 01:21:32', '2016-08-17 01:21:32', null);
INSERT INTO `links` VALUES ('950', 'http://giayluoithethao.blogspot.com/2016/08/nike-nam-nhan-inh-dep-converse-giay-ep.html', 'Nike nam nhận định dép converse giày đẹp học sĩ', null, '2016-08-17 01:21:34', '2016-08-17 01:21:34', null);
INSERT INTO `links` VALUES ('951', 'http://giayluoigiarehcm.blogspot.com/2016/08/phu-tho-new-balance-nghe-nhan-adidas.html', 'Phú Thọ new balance nghệ nhân adidas fake converse màu ghi', null, '2016-08-17 01:21:37', '2016-08-17 01:21:37', null);
INSERT INTO `links` VALUES ('952', 'http://muagiaynikethethaovn.blogspot.com/2016/08/converse-classic-duyen-dang-hoa-mi-xanh.html', 'Converse classic duyên dáng họa mi xanh navy converse vnxk', null, '2016-08-17 01:21:39', '2016-08-17 01:21:39', null);
INSERT INTO `links` VALUES ('953', 'http://giayvanschinhhang01.blogspot.com/2016/08/hien-nay-sua-oi-gia-re-co-cao-binh-chon.html', 'Hiện nay sửa đổi giá rẻ cổ cao bình chọn', null, '2016-08-17 01:21:41', '2016-08-17 01:21:41', null);
INSERT INTO `links` VALUES ('954', 'http://muagiayconversedep.blogspot.com/2016/08/adidas-re-adidas-nu-adidas-nu-hcm-vans.html', 'Adidas rẻ adidas nữ adidas nữ hcm vans nhập khẩu converse giảm giá', null, '2016-08-17 01:21:44', '2016-08-17 01:21:44', null);
INSERT INTO `links` VALUES ('955', 'http://giayadidasgiarevn.blogspot.com/2016/08/cao-co-binh-thuan-adidas-ha-noi-vans-ep.html', 'Cao cổ Bình Thuận adidas Hà Nội vans đẹp sửa đổi', null, '2016-08-17 01:21:46', '2016-08-17 01:21:46', null);

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
INSERT INTO `migrations` VALUES ('2016_07_02_111150_create_blogs_table', '3');
INSERT INTO `migrations` VALUES ('2016_07_02_115425_create_blocks_table', '3');
INSERT INTO `migrations` VALUES ('2016_07_28_091505_create_links_table', '4');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Nguyen ', 'Manh', '1', '$2y$10$jfOXeW9aqtuP4QA0gzn1re6GEoTCsokcrNAmFISBx677PG1m4kx5q', '', '1', '1', 'D3lM24muUb4K5b4uM2LP4GipGXqGainL58kDHb7Z6FL8Vb9naNoWVgICvENM', '2016-03-17 17:07:27', '2016-03-17 10:38:23', null);
INSERT INTO `users` VALUES ('2', '1', '1', 'adf@gmail.com', '$2y$10$CoZB3GO9GA1CE0rVGa59wOtUyS6k6J1R.iQ1BzpHplBPH38nAd6SS', '', '1', '1', null, '2016-07-02 16:05:06', '2016-07-02 00:00:00', null);
