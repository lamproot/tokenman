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
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE fa_admin ADD license VARCHAR(255) NOT NULL DEFAULT "" COMMENT "营业执照";
ALTER TABLE fa_admin ADD account VARCHAR(255) NOT NULL DEFAULT "" COMMENT "对公账户信息";

ALTER TABLE chat_group ADD account VARCHAR(255) NOT NULL DEFAULT "" COMMENT "对公账户信息";


2018-05-08 23:27:54新增
ALTER TABLE drafters ADD code VARCHAR(255) NOT NULL DEFAULT "" COMMENT "code";
ALTER TABLE drafters ADD eth VARCHAR(255) NOT NULL DEFAULT "" COMMENT "eth";
ALTER TABLE drafters ADD is_del tinyint(2) NOT NULL DEFAULT 0 COMMENT "is_del";

ALTER TABLE group_activity ADD theme_id int(10) NOT NULL DEFAULT 1 COMMENT "主题ID";
ALTER TABLE group_activity ADD tokenman_url VARCHAR(255) NOT NULL DEFAULT "" COMMENT "tokenman_url";
ALTER TABLE group_activity ADD model_logo VARCHAR(255) NOT NULL DEFAULT "" COMMENT "model_logo";

ALTER TABLE group_activity ADD step1 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "step1";
ALTER TABLE group_activity ADD step2 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "step2";
ALTER TABLE group_activity ADD step3 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "step3";
ALTER TABLE group_activity ADD step4 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "step4";

ALTER TABLE group_activity ADD en_step1 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "en_step1";
ALTER TABLE group_activity ADD en_step2 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "en_step2";
ALTER TABLE group_activity ADD en_step3 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "en_step3";
ALTER TABLE group_activity ADD en_step4 VARCHAR(255) NOT NULL DEFAULT "" COMMENT "en_step4";


ALTER TABLE group_activity ADD twitter VARCHAR(255) NOT NULL DEFAULT "" COMMENT "twitter";

ALTER TABLE group_activity ADD facebook VARCHAR(255) NOT NULL DEFAULT "" COMMENT "facebook";
ALTER TABLE group_activity ADD telegram VARCHAR(255) NOT NULL DEFAULT "" COMMENT "telegram";
ALTER TABLE group_activity ADD web VARCHAR(255) NOT NULL DEFAULT "" COMMENT "web";
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

 Date: 05/09/2018 17:51:32 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `activity_theme`
-- ----------------------------
DROP TABLE IF EXISTS `activity_theme`;
CREATE TABLE `activity_theme` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL COMMENT '主题名称',
  `bglogo` varchar(250) NOT NULL COMMENT 'code页背景图',
  `dashboard` varchar(250) NOT NULL COMMENT 'dashboard 背景图',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '修改时间',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `activity_theme`
-- ----------------------------
BEGIN;
INSERT INTO `activity_theme` VALUES ('1', '科技感主题', 'http://18.188.112.65:8090/Uploads/images/command/5af0249d30628.png', 'http://18.188.112.65:8090/Uploads/images/command/5af0249d14ed6.png', '0', '0', '0'), ('2', '糖果色主题', 'http://18.188.112.65:8090/Uploads/images/command/5af0249d344b7.png', 'http://18.188.112.65:8090/Uploads/images/command/5af0249d23fdb.png', '0', '0', '0'), ('3', 'aaaa', 'http://localhost:8890/Uploads/images/activity/5af2b0a508249.png', 'http://localhost:8890/Uploads/images/activity/5af2b0a508958.png', '1525854095', '1525854432', '1'), ('4', '', 'http://localhost:8890/Uploads/images/activity/5af2b0e9ed895.png', 'http://localhost:8890/Uploads/images/activity/5af2b0e9ede4e.png', '1525854441', '1525854441', '0');
COMMIT;

-- ----------------------------
--  Table structure for `bot_twitter`
-- ----------------------------
DROP TABLE IF EXISTS `bot_twitter_log`;
CREATE TABLE `bot_twitter_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_bot_id` int(10) NOT NULL,
  `twitter` varchar(255) DEFAULT '',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL DEFAULT '',
  `twitter_id` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
