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

 Date: 04/27/2018 19:35:08 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `fa_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_rule`;
CREATE TABLE `fa_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点表';

-- ----------------------------
--  Records of `fa_auth_rule`
-- ----------------------------
BEGIN;
INSERT INTO `fa_auth_rule` VALUES ('1', 'file', '0', 'dashboard', 'dashboard', 'fa fa-dashboard', 'Dashboard', 'Dashboard', '1', '1497429920', '1523936133', '94', 'normal'), ('2', 'file', '0', 'userset', 'Userset', 'fa fa-cogs', '用户设置', 'Userset', '1', '1497429920', '1523935927', '56', 'normal'), ('3', 'file', '0', 'category', '分类管理', 'fa fa-list', '', 'Category tips', '1', '1497429920', '1524555969', '11', 'normal'), ('4', 'file', '0', 'addon', 'Addon', 'fa fa-rocket', '', 'Addon tips', '1', '1502035509', '1502035509', '53', 'normal'), ('5', 'file', '0', 'auth', 'Auth', 'fa fa-group', '', '', '1', '1497429920', '1497430092', '55', 'normal'), ('6', 'file', '2', 'general/config', 'Config', 'fa fa-cog', '', 'Config tips', '1', '1497429920', '1497430683', '32', 'normal'), ('7', 'file', '2', 'general/attachment', 'Attachment', 'fa fa-file-image-o', '', 'Attachment tips', '1', '1497429920', '1497430699', '39', 'normal'), ('8', 'file', '2', 'general/profile', 'Profile', 'fa fa-user', '', '', '1', '1497429920', '1497429920', '45', 'normal'), ('9', 'file', '5', 'auth/admin', 'Auth admin', 'fa fa-user', '', 'Admin tips', '1', '1497429920', '1523936822', '12', 'normal'), ('10', 'file', '5', 'auth/adminlog', 'Admin log', 'fa fa-list-alt', '', 'Admin log tips', '1', '1497429920', '1497430307', '17', 'normal'), ('11', 'file', '5', 'auth/group', 'Group', 'fa fa-group', '', 'Group tips', '1', '1497429920', '1497429920', '21', 'normal'), ('12', 'file', '5', 'auth/rule', 'Rule', 'fa fa-bars', '', 'Rule tips', '1', '1497429920', '1497430581', '26', 'normal'), ('13', 'file', '1', 'dashboard/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '6', 'normal'), ('14', 'file', '1', 'dashboard/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '7', 'normal'), ('15', 'file', '1', 'dashboard/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '9', 'normal'), ('16', 'file', '1', 'dashboard/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '8', 'normal'), ('17', 'file', '1', 'dashboard/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '10', 'normal'), ('18', 'file', '6', 'general/config/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '40', 'normal'), ('19', 'file', '6', 'general/config/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '41', 'normal'), ('20', 'file', '6', 'general/config/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '42', 'normal'), ('21', 'file', '6', 'general/config/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '43', 'normal'), ('22', 'file', '6', 'general/config/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '44', 'normal'), ('23', 'file', '7', 'general/attachment/index', 'View', 'fa fa-circle-o', '', 'Attachment tips', '0', '1497429920', '1497429920', '33', 'normal'), ('24', 'file', '7', 'general/attachment/select', 'Select attachment', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '34', 'normal'), ('25', 'file', '7', 'general/attachment/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '35', 'normal'), ('26', 'file', '7', 'general/attachment/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '36', 'normal'), ('27', 'file', '7', 'general/attachment/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '37', 'normal'), ('28', 'file', '7', 'general/attachment/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '38', 'normal'), ('29', 'file', '8', 'general/profile/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '46', 'normal'), ('30', 'file', '8', 'general/profile/update', 'Update profile', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '47', 'normal'), ('31', 'file', '8', 'general/profile/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '48', 'normal'), ('32', 'file', '8', 'general/profile/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '49', 'normal'), ('33', 'file', '8', 'general/profile/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '50', 'normal'), ('34', 'file', '8', 'general/profile/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '51', 'normal'), ('35', 'file', '3', 'category/index', 'View', 'fa fa-circle-o', '', 'Category tips', '0', '1497429920', '1497429920', '1', 'normal'), ('36', 'file', '3', 'category/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '2', 'normal'), ('37', 'file', '3', 'category/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '3', 'normal'), ('38', 'file', '3', 'category/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '4', 'normal'), ('39', 'file', '3', 'category/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '5', 'normal'), ('40', 'file', '9', 'auth/admin/index', 'View', 'fa fa-circle-o', '', 'Admin tips', '0', '1497429920', '1497429920', '13', 'normal'), ('41', 'file', '9', 'auth/admin/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '14', 'normal'), ('42', 'file', '9', 'auth/admin/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '15', 'normal'), ('43', 'file', '9', 'auth/admin/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '16', 'normal'), ('44', 'file', '10', 'auth/adminlog/index', 'View', 'fa fa-circle-o', '', 'Admin log tips', '0', '1497429920', '1497429920', '18', 'normal'), ('45', 'file', '10', 'auth/adminlog/detail', 'Detail', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '19', 'normal'), ('46', 'file', '10', 'auth/adminlog/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '20', 'normal'), ('47', 'file', '11', 'auth/group/index', 'View', 'fa fa-circle-o', '', 'Group tips', '0', '1497429920', '1497429920', '22', 'normal'), ('48', 'file', '11', 'auth/group/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '23', 'normal'), ('49', 'file', '11', 'auth/group/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '24', 'normal'), ('50', 'file', '11', 'auth/group/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '25', 'normal'), ('51', 'file', '12', 'auth/rule/index', 'View', 'fa fa-circle-o', '', 'Rule tips', '0', '1497429920', '1497429920', '27', 'normal'), ('52', 'file', '12', 'auth/rule/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '28', 'normal'), ('53', 'file', '12', 'auth/rule/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '29', 'normal'), ('54', 'file', '12', 'auth/rule/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '30', 'normal'), ('55', 'file', '4', 'addon/index', 'View', 'fa fa-circle-o', '', 'Addon tips', '0', '1502035509', '1502035509', '92', 'normal'), ('56', 'file', '4', 'addon/add', 'Add', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '91', 'normal'), ('57', 'file', '4', 'addon/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '90', 'normal'), ('58', 'file', '4', 'addon/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '89', 'normal'), ('59', 'file', '4', 'addon/local', 'Local install', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '88', 'normal'), ('60', 'file', '4', 'addon/state', 'Update state', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '87', 'normal'), ('61', 'file', '4', 'addon/install', 'Install', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '86', 'normal'), ('62', 'file', '4', 'addon/uninstall', 'Uninstall', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '85', 'normal'), ('63', 'file', '4', 'addon/config', 'Setting', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '84', 'normal'), ('64', 'file', '4', 'addon/refresh', 'Refresh', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '83', 'normal'), ('65', 'file', '4', 'addon/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '82', 'normal'), ('66', 'file', '0', 'user', 'User', 'fa fa-list', '', '', '1', '1516374729', '1516374729', '52', 'normal'), ('67', 'file', '66', 'user/user', 'User', 'fa fa-user', '', '', '1', '1516374729', '1516374729', '80', 'normal'), ('68', 'file', '67', 'user/user/index', 'View', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '79', 'normal'), ('69', 'file', '67', 'user/user/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '78', 'normal'), ('70', 'file', '67', 'user/user/add', 'Add', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '77', 'normal'), ('71', 'file', '67', 'user/user/del', 'Del', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '76', 'normal'), ('72', 'file', '67', 'user/user/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '75', 'normal'), ('73', 'file', '66', 'user/group', 'User group', 'fa fa-users', '', '', '1', '1516374729', '1516374729', '74', 'normal'), ('74', 'file', '73', 'user/group/add', 'Add', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '73', 'normal'), ('75', 'file', '73', 'user/group/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '72', 'normal'), ('76', 'file', '73', 'user/group/index', 'View', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '71', 'normal'), ('77', 'file', '73', 'user/group/del', 'Del', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '70', 'normal'), ('78', 'file', '73', 'user/group/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '69', 'normal'), ('79', 'file', '66', 'user/rule', 'User rule', 'fa fa-circle-o', '', '', '1', '1516374729', '1516374729', '68', 'normal'), ('80', 'file', '79', 'user/rule/index', 'View', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '67', 'normal'), ('81', 'file', '79', 'user/rule/del', 'Del', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '66', 'normal'), ('82', 'file', '79', 'user/rule/add', 'Add', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '65', 'normal'), ('83', 'file', '79', 'user/rule/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '64', 'normal'), ('84', 'file', '79', 'user/rule/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1516374729', '1516374729', '63', 'normal'), ('85', 'file', '0', 'order/index', 'Order', 'fa fa-rmb', 'Order', 'Order', '1', '1522312871', '1523936893', '31', 'hidden'), ('86', 'file', '2', 'userinfo', 'Userset', 'fa fa-user', 'userinfo/index', 'userinfo', '1', '1523862467', '1523936790', '61', 'hidden'), ('87', 'file', '2', 'authentication', 'Authentication', 'fa fa-address-card-o', 'authentication', '', '1', '1523862673', '1523936806', '60', 'normal'), ('88', 'file', '0', 'security/center', 'Security center', 'fa fa-shield', '', '', '1', '1523862825', '1523936923', '54', 'normal'), ('89', 'file', '88', 'password/reset/index', '密码重置', 'fa fa-circle-o', '', '', '1', '1523862869', '1524542160', '58', 'normal'), ('90', 'file', '88', 'password/recovery', '密码找回', 'fa fa-circle-o', 'password/recovery', '', '1', '1523862957', '1524552131', '57', 'hidden'), ('91', 'file', '0', 'message/manage', 'Message manage', 'fa fa-commenting', '', '消息管理', '1', '1523863175', '1523936224', '93', 'normal'), ('92', 'file', '0', 'activity_manage', 'Activity manage', 'fa fa-american-sign-language-interpreting', '', '', '1', '1523863314', '1523936617', '81', 'normal'), ('93', 'file', '0', 'antispam/index', 'Antispam index', 'fa fa-user-secret', '', '', '1', '1523863352', '1523936666', '62', 'normal'), ('94', 'file', '0', 'advert/manage', '广告管理', 'fa fa-bullhorn', '', '', '1', '1523863442', '1524313168', '59', 'normal'), ('95', 'file', '91', 'keyword/manage/index', 'Keyword manage', 'fa fa-circle-o', 'keyword', '', '1', '1523864170', '1524062728', '0', 'normal'), ('96', 'file', '91', 'keyword/message/index', '消息管理', 'fa fa-circle-o', 'message', '', '1', '1523864196', '1524230931', '0', 'normal'), ('97', 'file', '91', '/keyword/message/add', '发布消息', 'fa fa-circle-o', '', '', '1', '1523864227', '1524232056', '0', 'hidden'), ('98', 'file', '92', 'activity/manage/index', '活动管理', 'fa fa-circle-o', '', '', '1', '1523864266', '1524237437', '0', 'normal'), ('99', 'file', '92', 'activity/manage/publish', '发布活动', 'fa fa-circle-o', 'activity/publish', '', '1', '1523864311', '1524799226', '0', 'normal'), ('100', 'file', '93', 'antispam/manage/index', 'Antispam manage', 'fa fa-circle-o', '', '', '1', '1523864381', '1524309789', '0', 'normal'), ('101', 'file', '93', 'antispam/white/index', '白名单权限', 'fa fa-circle-o', '', '', '1', '1523864437', '1524310234', '0', 'normal'), ('102', 'file', '94', 'advert/set', 'Advert set', 'fa fa-circle-o', '', '', '1', '1523864627', '1523936767', '0', 'normal'), ('103', 'file', '0', 'guide/guide', '帮助中心', 'fa fa-folder-open-o', '', '', '1', '1523873503', '1524562452', '0', 'normal'), ('104', 'file', '103', 'guide/guide/detail', '新手指导详情', 'fa fa-circle-o', '', '', '0', '1523873532', '1524555446', '0', 'normal'), ('105', 'file', '0', 'chatbot/manage', '机器人管理', 'fa fa-android', 'chat_bot', '', '1', '1523937070', '1524824277', '0', 'normal'), ('106', 'file', '0', 'guide/manage', '帮助中心管理', 'fa fa-circle-o', '', '', '1', '1523938418', '1524562167', '0', 'normal'), ('107', 'file', '95', 'keyword/manage/edit', '编辑', 'fa fa-circle-o', '', '关键字回复编辑页面与接口权限', '0', '1524063027', '1524063041', '0', 'normal'), ('108', 'file', '95', 'keyword/manage/add', 'Add', 'fa fa-circle-o', '', '关键字回复新增页面与接口', '0', '1524063095', '1524063095', '0', 'normal'), ('109', 'file', '95', 'keyword/manage/del', 'Del', 'fa fa-circle-o', '', '关键字回复删除接口', '0', '1524063122', '1524063122', '0', 'normal'), ('110', 'file', '96', 'keyword/message/add', 'Message Add', 'fa fa-circle-o', '', '', '0', '1524231856', '1524231856', '0', 'normal'), ('111', 'file', '96', 'keyword/message/detail', 'Message Detail', 'fa fa-circle-o', '', '', '0', '1524231877', '1524232305', '0', 'normal'), ('112', 'file', '98', 'activity/manage/edit', 'Activity Add', 'fa fa-circle-o', '', '', '0', '1524298552', '1524298552', '0', 'normal'), ('113', 'file', '98', 'activity/manage/add', 'Activity Add', 'fa fa-circle-o', '', '', '0', '1524298581', '1524298581', '0', 'normal'), ('114', 'file', '93', 'antispam/illegalog/index', '屏蔽数据管理', 'fa fa-circle-o', '', '', '1', '1524305654', '1524312600', '0', 'normal'), ('115', 'file', '100', 'antispam/manage/add', 'Add', 'fa fa-circle-o', '', '', '0', '1524309214', '1524309214', '0', 'normal'), ('116', 'file', '100', 'antispam/manage/del', 'Del', 'fa fa-circle-o', '', '', '0', '1524309236', '1524309236', '0', 'normal'), ('117', 'file', '100', 'antispam/manage/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1524309259', '1524309259', '0', 'normal'), ('118', 'file', '101', 'antispam/white/add', 'Add', 'fa fa-circle-o', '', '', '0', '1524310279', '1524310279', '0', 'normal'), ('119', 'file', '101', 'antispam/white/detail', 'Detail', 'fa fa-circle-o', '', '', '0', '1524310304', '1524310304', '0', 'normal'), ('120', 'file', '101', 'antispam/white/del', 'Del', 'fa fa-circle-o', '', '', '0', '1524310336', '1524310336', '0', 'normal'), ('121', 'file', '114', 'antispam/illegalog/detail', 'Detail', 'fa fa-circle-o', '', '', '0', '1524313039', '1524313039', '0', 'normal'), ('122', 'file', '98', 'activity/codemanage/index', 'Code Manage', 'fa fa-circle-o', '', '', '0', '1524314833', '1524314833', '0', 'normal'), ('123', 'file', '98', 'activity/codemanage/user', 'Code Manage User', 'fa fa-circle-o', '', '', '0', '1524314874', '1524314874', '0', 'normal'), ('124', 'file', '105', 'chatbot/manage/index', 'Chatbot Manage Index', 'fa fa-circle-o', '', '', '0', '1524538291', '1524538291', '0', 'normal'), ('125', 'file', '105', 'chatbot/manage/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1524538312', '1524538312', '0', 'normal'), ('126', 'file', '106', 'guide/manage/index', 'Manage', 'fa fa-circle-o', '', '', '0', '1524554347', '1524555483', '0', 'normal'), ('127', 'file', '106', 'guide/manage/add', '添加', 'fa fa-circle-o', '', '', '0', '1524554370', '1524555493', '0', 'normal'), ('128', 'file', '106', 'guide/manage/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524554389', '1524555506', '0', 'normal'), ('129', 'file', '106', 'guide/manage/del', '删除', 'fa fa-circle-o', '', '', '0', '1524554409', '1524555516', '0', 'normal'), ('130', 'file', '103', 'guide/guide/index', '新手指导', 'fa fa-circle-o', '', '', '1', '1524562491', '1524562606', '0', 'normal'), ('131', 'file', '98', 'activity/articlemanage/index', 'Activity Articlemanage Index', 'fa fa-circle-o', '', '', '0', '1524797619', '1524797619', '0', 'normal'), ('132', 'file', '98', 'activity/articlemanage/detail', 'Detail', 'fa fa-circle-o', '', '', '0', '1524797915', '1524797915', '0', 'normal'), ('133', 'file', '105', 'chatbot/advert/index', 'Chatbot Advert Index', 'fa fa-circle-o', '', '机器人广告管理', '1', '1524818350', '1524818350', '0', 'normal'), ('134', 'file', '133', 'chatbot/advert/add', 'Add', 'fa fa-circle-o', '', '', '0', '1524819286', '1524819286', '0', 'normal'), ('135', 'file', '133', 'chatbot/advert/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1524819302', '1524819302', '0', 'normal'), ('136', 'file', '133', 'chatbot/advert/del', 'Del', 'fa fa-circle-o', '', '', '0', '1524819315', '1524819315', '0', 'normal');
COMMIT;

