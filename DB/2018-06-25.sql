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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



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




DROP TABLE IF EXISTS `fa_orders`;
CREATE TABLE `fa_orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `product_id` int(10) NOT NULL DEFAULT 0 COMMENT '产品ID',
  `wallet` varchar(255) NOT NULL DEFAULT "" COMMENT '钱包地址',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '订单状态 0-等待付款 1-已付款未发货 2-订单完成 ',
  `content` text COMMENT '订单备注',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `order_code` varchar(255) NOT NULL DEFAULT "" COMMENT '订单号',
  PRIMARY KEY (`id`),
  INDEX(product_id),
  INDEX(status)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


/*
    群机器人设置相关表
    rule is_delete_link 删除包含链接的消息 value 0 - 关闭 1 - 开启
    rule is_clear_all_time 开启禁言时长  0 - 关闭 300 - 5分钟 根据更新时间 updated_at 进行相关功能
            time() < updated_at + is_clear_all_time 代表在禁言期
    rule is_clear_all_news  是否开启全体禁言模式
*/

DROP TABLE IF EXISTS `group_bot_config`;
CREATE TABLE `group_bot_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule` varchar(255) NOT NULL COMMENT '配置规则名称',
  `value` int(10) NOT NULL DEFAULT 0 COMMENT '配置规则设置数据',
  `data` text COMMENT '额外配置数据-例如关键词。',
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `chat_bot_id` int(10) NOT NULL DEFAULT 0  COMMENT 'chat_bot_id',
  `chat_id` varchar(14) NOT NULL DEFAULT "" COMMENT '群ID',
  PRIMARY KEY (`id`),
  INDEX(chat_bot_id),
  INDEX(chat_id),
  INDEX(rule)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE chat_push ADD is_pin tinyint(2) NOT NULL DEFAULT 0 COMMENT "是否置顶";
ALTER TABLE chat_push ADD push_type tinyint(2) NOT NULL DEFAULT 0 COMMENT "0 实时 1 定时";
ALTER TABLE chat_push ADD push_task int(10) NOT NULL DEFAULT 0 COMMENT "推送定时";
