/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50621
 Source Host           : localhost
 Source Database       : telegram

 Target Server Type    : MySQL
 Target Server Version : 50621
 File Encoding         : utf-8

 Date: 04/23/2018 11:16:09 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `antispam_word`
-- ----------------------------
DROP TABLE IF EXISTS `antispam_word`;
CREATE TABLE `antispam_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL COMMENT '反垃圾关键词',
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `chat_bot_id` int(10) NOT NULL,
  `opreate_uid` int(11) NOT NULL,
  `is_del` tinyint(2) DEFAULT '0',
  `opreate_username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `antispam_word`
-- ----------------------------
BEGIN;
INSERT INTO `antispam_word` VALUES ('1', '阿斯达1111', '12121', '1524309862', '2', '2', '1', null), ('2', '啊实打实', '1524309525', '1524309525', '2', '5', '0', 'admin32'), ('3', '大萨达', '1524309867', '1524309867', '2', '5', '0', 'admin32'), ('4', '防守打法', '1524309873', '1524309873', '2', '5', '0', 'admin32'), ('5', '12312', '1524309877', '1524309877', '2', '5', '0', 'admin32'), ('6', '12312', '1524309878', '1524309878', '2', '5', '0', 'admin32'), ('7', '3', '1524309880', '1524309880', '2', '5', '0', 'admin32'), ('8', 'dasd', '1524309884', '1524309884', '2', '5', '0', 'admin32'), ('9', 'dasd', '1524309886', '1524309886', '2', '5', '0', 'admin32'), ('10', '大萨达', '1524309888', '1524309888', '2', '5', '0', 'admin32'), ('11', '13231', '1524309890', '1524309890', '2', '5', '0', 'admin32');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
