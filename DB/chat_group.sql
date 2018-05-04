/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50627
 Source Host           : localhost
 Source Database       : telegram

 Target Server Type    : MySQL
 Target Server Version : 50627
 File Encoding         : utf-8

 Date: 05/04/2018 20:09:21 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_group`
-- ----------------------------
DROP TABLE IF EXISTS `chat_group`;
CREATE TABLE `chat_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '群名称',
  `is_del` tinyint(2) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `chat_bot_id` int(10) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0' COMMENT '0 - 机器人未激活   1 - 机器人已激活  ',
  `admin_id` int(10) DEFAULT '0',
  `chat_id` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `chat_group`
-- ----------------------------
BEGIN;
INSERT INTO `chat_group` VALUES ('1', '小熊测试群', '0', null, null, '0', '0', '0', null);
COMMIT;

