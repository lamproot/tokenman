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

 Date: 04/23/2018 11:35:26 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `fa_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_group`;
CREATE TABLE `fa_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text NOT NULL COMMENT '规则ID',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='分组表';

-- ----------------------------
--  Records of `fa_auth_group`
-- ----------------------------
BEGIN;
INSERT INTO `fa_auth_group` VALUES ('1', '0', 'Admin group', '*', '1490883540', '149088354', 'normal'), ('2', '1', '二级管理组(TokenManBoss权限)', '1,8,9,10,11,13,14,15,16,17,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,2,5', '1490883540', '1522314284', 'normal'), ('3', '2', '三级管理组(TokenMan运营管理员权限--TokenMan运营管理人员)', '1,8,13,29,30,31,32,33,34,66,67,68,69,70,71,72,85,2', '1490883540', '1522314468', 'normal'), ('4', '1', '二级管理组(TokenMan客户管理员权限VIP )', '1,8,9,10,13,14,15,16,17,24,29,30,31,32,33,34,40,41,42,43,44,45,46,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,2,5,7', '1490883540', '1524314923', 'normal'), ('5', '2', '三级管理组(TokenMan运营管理员权限--TokenMan销售管理人员)', '1,8,13,29,30,31,32,33,34,85,2', '1490883540', '1522314459', 'normal'), ('6', '1', '二级管理组(TokenMan超级客户管理员权限SVIP)', '1,8,13,14,15,16,17,29,30,31,32,33,34,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,2', '1522314848', '1522314848', 'normal'), ('7', '4', '三级管理组(TokenMan客户管理员权限VIP--客户运营管理人员)', '1,8,13,14,15,16,17,29,30,31,32,33,34,2', '1522315001', '1522315001', 'normal'), ('8', '6', '三级管理组(TokenMan超级客户管理员权限SVIP--客户运营管理人员)', '1,8,13,14,15,16,17,29,30,31,32,33,34,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,2', '1522315032', '1522315044', 'normal');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
