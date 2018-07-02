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
--  Table structure for `group_user`
-- ----------------------------
DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_bot_id` int(10) NOT NULL,
  `chat_id` varchar(14) NOT NULL DEFAULT '',
  `type` tinyint(3) NOT NULL DEFAULT 1 COMMENT '1 入群 2 退群',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `from_id` varchar(255) DEFAULT '' COMMENT '用户ID',
  `first_name` varchar(255) DEFAULT '' COMMENT '',
  `last_name` varchar(255) DEFAULT '' COMMENT '',
  `from_username` varchar(255) DEFAULT '' COMMENT '',
  PRIMARY KEY (`id`),
  INDEX(chat_bot_id),
  INDEX(chat_id),
  INDEX(type)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- ----------------------------
--  Table structure for `news_total`
-- ----------------------------
DROP TABLE IF EXISTS `news_total`;
CREATE TABLE `news_total` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_bot_id` int(10) NOT NULL,
  `chat_id` varchar(14) DEFAULT '',
  `total` int(10) NOT NULL COMMENT '消息数据',
  PRIMARY KEY (`id`),
  INDEX(chat_bot_id),
  INDEX(chat_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE group_activity ADD share_msg VARCHAR(300) NOT NULL DEFAULT "" COMMENT "活动分享文案";
ALTER TABLE group_activity ADD total_rate int(11) NOT NULL DEFAULT 0 COMMENT "活动奖励实时消耗";


ALTER TABLE illega_log ADD chat_id varchar(14) NOT NULL DEFAULT "" COMMENT "群ID";
ALTER TABLE chat_bot ADD started_at int(10) NOT NULL DEFAULT 0 COMMENT "机器人开始时间";
ALTER TABLE chat_bot ADD stoped_at int(10) NOT NULL DEFAULT 0 COMMENT "机器人结束时间";



ALTER TABLE group_activity ADD en_activity_end_text text NOT NULL DEFAULT "" COMMENT "活动结束英文文案";
