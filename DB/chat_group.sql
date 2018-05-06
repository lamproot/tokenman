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

 Date: 05/05/2018 17:15:13 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_group`
-- ----------------------------
DROP TABLE IF EXISTS `chat_group`;
CREATE TABLE `chat_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '群名称',
  `is_del` tinyint(2) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `chat_bot_id` int(10) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0' COMMENT '0 - 机器人未激活   1 - 机器人已激活  ',
  `admin_id` int(10) DEFAULT '0',
  `chat_id` varchar(14) DEFAULT NULL,
  `remark` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `chat_group`
-- ----------------------------
BEGIN;
INSERT INTO `chat_group` VALUES ('1', '小熊测试群11', '0', '1525505903', '1525509066', '2', '1', '5', '-1001249040089', '大叔大婶大', '大苏打111111');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
