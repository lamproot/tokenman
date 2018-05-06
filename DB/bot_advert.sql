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

 Date: 04/30/2018 18:46:48 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `bot_currency`
-- ----------------------------
DROP TABLE IF EXISTS `bot_currency`;
CREATE TABLE `bot_currency` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_bot_id` int(10) NOT NULL,
  `is_del` tinyint(2) NOT NULL DEFAULT '0',
  `opreate_uid` int(10) NOT NULL DEFAULT '0',
  `opreate_username` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `exchange` varchar(255) DEFAULT '' COMMENT '交易所名称',
  `currency` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;



SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `bot_twitter`
-- ----------------------------
DROP TABLE IF EXISTS `bot_twitter`;
CREATE TABLE `bot_twitter` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_bot_id` int(10) NOT NULL,
  `is_del` tinyint(2) NOT NULL DEFAULT '0',
  `opreate_uid` int(10) NOT NULL DEFAULT '0',
  `opreate_username` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE fa_admin ADD license VARCHAR(255) NOT NULL DEFAULT "" COMMENT "营业执照";
ALTER TABLE fa_admin ADD account VARCHAR(255) NOT NULL DEFAULT "" COMMENT "对公账户信息";



