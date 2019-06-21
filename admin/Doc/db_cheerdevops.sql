/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50721
Source Host           : localhost:3306
Source Database       : db_cheerdevops

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2019-06-21 11:51:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_grey_rule_list
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_rule_list`;
CREATE TABLE `t_grey_rule_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点ID',
  `tenant_code` varchar(100) NOT NULL DEFAULT '' COMMENT '租户代码',
  `mode` varchar(30) NOT NULL DEFAULT 'none' COMMENT '模式',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `site_id_idex` (`site_id`) USING BTREE,
  KEY `tenant_code_idex` (`tenant_code`) USING BTREE,
  KEY `mode_idex` (`mode`) USING BTREE,
  KEY `state_idex` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_grey_rule_list
-- ----------------------------
INSERT INTO `t_grey_rule_list` VALUES ('1', '1', 'mysoft', 'grey', '2019-06-21 11:25:13', '2019-06-21 11:31:02', '127.0.0.1', '1');

-- ----------------------------
-- Table structure for t_grey_rule_site
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_rule_site`;
CREATE TABLE `t_grey_rule_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `rule_site_name` varchar(100) NOT NULL COMMENT '站点规则名称',
  `http_host` varchar(250) NOT NULL COMMENT '站点host规则',
  `http_host_type` varchar(50) NOT NULL DEFAULT 'string' COMMENT '站点host规则类别,string,regex',
  `mode` varchar(30) NOT NULL DEFAULT 'none' COMMENT '全局模式',
  `grey_node` varchar(100) NOT NULL DEFAULT '@backend_grey_none' COMMENT '灰度节点名称',
  `prod_node` varchar(100) NOT NULL DEFAULT '@backend_prod_none' COMMENT '全量节点名称',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(50) NOT NULL COMMENT '更新IP',
  `update_cache_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '上次更新到缓存时间',
  `state` int(11) NOT NULL COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_grey_rule_site
-- ----------------------------
INSERT INTO `t_grey_rule_site` VALUES ('1', '企鹅狗的测试站', 'cheergo.myysq.com.cn', 'string', 'prod', '@backend_grey_none', '@backend_prod_none', '2018-03-06 17:59:35', '2019-06-21 10:09:27', '127.0.0.1', '2019-06-21 10:09:27', '1');

-- ----------------------------
-- Table structure for t_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_system_menu`;
CREATE TABLE `t_system_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `menu_level` int(11) NOT NULL DEFAULT '0' COMMENT '菜单层级',
  `menu_pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级菜单ID',
  `menu_title` varchar(50) NOT NULL COMMENT '菜单标题',
  `menu_full_title` varchar(100) NOT NULL COMMENT '菜单全称',
  `menu_icon` text NOT NULL COMMENT '菜单图标地址',
  `menu_url` text NOT NULL COMMENT '菜单链接',
  `menu_order` int(11) NOT NULL DEFAULT '0' COMMENT '菜单排序',
  `state` int(11) NOT NULL COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_system_menu
-- ----------------------------
INSERT INTO `t_system_menu` VALUES ('1', '0', '0', '灰度系统', '灰度系统管理', '/static/skin/default/nav/pus.png', '#', '0', '1');
INSERT INTO `t_system_menu` VALUES ('2', '1', '1', '规则', '规则管理', '#', '#', '0', '1');
INSERT INTO `t_system_menu` VALUES ('4', '0', '0', '系统', '系统管理', '/static/skin/default/nav/sys.png', '#', '0', '1');
INSERT INTO `t_system_menu` VALUES ('5', '1', '4', '站点', '站点管理', '#', '#', '0', '1');
INSERT INTO `t_system_menu` VALUES ('6', '2', '5', '管理菜单', '管理菜单', '#', '/system/menu.html', '0', '1');
INSERT INTO `t_system_menu` VALUES ('10', '2', '2', '应用站点', '应用站点', '#', '/grey/site_list.html', '0', '1');
INSERT INTO `t_system_menu` VALUES ('11', '2', '2', '租户规则', '租户规则', '#', '/grey/rule_list.html', '1', '1');

-- ----------------------------
-- Table structure for t_user_info
-- ----------------------------
DROP TABLE IF EXISTS `t_user_info`;
CREATE TABLE `t_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `user_password` varchar(50) NOT NULL COMMENT '登录密码',
  `user_pass_salt` varchar(50) NOT NULL COMMENT '登录密码盐',
  `user_type` int(11) NOT NULL COMMENT '用户类别',
  `state` int(11) NOT NULL COMMENT '账号状态',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '最后更新时间',
  `update_ip` varchar(50) NOT NULL COMMENT '最后更新ip',
  PRIMARY KEY (`id`),
  KEY `user_name_index` (`user_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user_info
-- ----------------------------
INSERT INTO `t_user_info` VALUES ('1', 'cheergo', 'a66abb5684c45962d887564f08346e8d', '123456', '1', '1', '2018-01-23 23:17:48', '2018-01-23 23:17:52', '127.0.0.1');
