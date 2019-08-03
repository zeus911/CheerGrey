/*
Navicat MySQL Data Transfer

Source Server         : myspacex_erp
Source Server Version : 50718
Source Host           : rm-bp1x9890955n6un11fo.mysql.rds.aliyuncs.com:3306
Source Database       : db_myspacex_cheerdevops

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2019-08-03 23:26:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_code_branch_repository
-- ----------------------------
DROP TABLE IF EXISTS `t_code_branch_repository`;
CREATE TABLE `t_code_branch_repository` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联任务ID',
  `repository_id` int(11) NOT NULL DEFAULT '0' COMMENT '代码仓库关联ID',
  `group_code` varchar(100) NOT NULL DEFAULT '' COMMENT '仓库分组code',
  `group_name` varchar(150) NOT NULL DEFAULT '' COMMENT '仓库分组名称',
  `group_url` varchar(300) NOT NULL DEFAULT '' COMMENT '仓库分组URL',
  `repository_code` varchar(100) NOT NULL DEFAULT '' COMMENT '仓库code',
  `repository_name` varchar(150) NOT NULL DEFAULT '' COMMENT '仓库名称',
  `repository_url` varchar(300) NOT NULL DEFAULT '' COMMENT '仓库URL',
  `feature_code` varchar(100) NOT NULL DEFAULT '' COMMENT '分支code',
  `feature_url` varchar(300) NOT NULL DEFAULT '' COMMENT '分支URL',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `update_ip` varchar(30) NOT NULL,
  `client_last_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '客户端上次处理时间',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `task_id_index` (`task_id`) USING BTREE,
  KEY `state_index` (`state`) USING BTREE,
  KEY `client_last_time_index` (`client_last_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_code_branch_task
-- ----------------------------
DROP TABLE IF EXISTS `t_code_branch_task`;
CREATE TABLE `t_code_branch_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品线ID',
  `department_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品部门ID',
  `task_type` varchar(50) NOT NULL DEFAULT 'feature_task' COMMENT '任务类型:feature_task特性分支,fix_task修复分支',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '任务名称',
  `story_id` int(11) NOT NULL COMMENT '关联需求ID',
  `story_code` varchar(100) NOT NULL COMMENT '需求代码',
  `story_url` varchar(300) NOT NULL COMMENT '需求页面地址',
  `rd_uid` int(11) NOT NULL COMMENT '开发用户ID',
  `rd_username` varchar(100) NOT NULL COMMENT '开发用户名',
  `rd_realname` varchar(100) NOT NULL COMMENT '开发真实姓名',
  `qa_uid` int(11) NOT NULL COMMENT '测试用户ID',
  `qa_username` varchar(100) NOT NULL COMMENT '测试用户名',
  `qa_realname` varchar(100) NOT NULL COMMENT '测试用户真实姓名',
  `create_time` datetime NOT NULL DEFAULT '1990-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '1990-01-01 00:00:00' COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL DEFAULT '127.0.0.1' COMMENT '最后操作IP',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '任务状态',
  PRIMARY KEY (`id`),
  KEY `product_id_index` (`product_id`) USING BTREE,
  KEY `department_id_index` (`department_id`) USING BTREE,
  KEY `state_index` (`state`) USING BTREE,
  KEY `rd_uid_index` (`rd_uid`) USING BTREE,
  KEY `qa_uid_index` (`qa_uid`) USING BTREE,
  KEY `story_id_index` (`story_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='分支任务表';

-- ----------------------------
-- Table structure for t_code_integration_branch
-- ----------------------------
DROP TABLE IF EXISTS `t_code_integration_branch`;
CREATE TABLE `t_code_integration_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `integration_task_id` int(11) NOT NULL COMMENT '集成任务ID',
  `branch_task_id` int(11) NOT NULL COMMENT '分支任务ID',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `integration_task_id_index` (`integration_task_id`) USING BTREE,
  KEY `branch_task_id_index` (`branch_task_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_code_integration_task
-- ----------------------------
DROP TABLE IF EXISTS `t_code_integration_task`;
CREATE TABLE `t_code_integration_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `title` varchar(200) NOT NULL COMMENT '任务名称',
  `product_id` int(11) NOT NULL COMMENT '产品线ID',
  `department_id` int(11) NOT NULL COMMENT '产品组ID',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` datetime NOT NULL COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `department_id_index` (`department_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='代码集成';

-- ----------------------------
-- Table structure for t_code_repository
-- ----------------------------
DROP TABLE IF EXISTS `t_code_repository`;
CREATE TABLE `t_code_repository` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `group_uuid` varchar(100) NOT NULL DEFAULT '' COMMENT '分组系统ID',
  `group_code` varchar(100) NOT NULL DEFAULT '' COMMENT '分组代码',
  `group_name` varchar(150) NOT NULL DEFAULT '' COMMENT '分组名称',
  `group_url` varchar(300) NOT NULL COMMENT '分组地址',
  `repository_uuid` varchar(100) NOT NULL COMMENT '仓库系统ID',
  `repository_code` varchar(100) NOT NULL DEFAULT '' COMMENT '仓库代码',
  `repository_name` varchar(150) NOT NULL DEFAULT '' COMMENT '仓库名称',
  `repository_url` varchar(300) NOT NULL COMMENT '仓库地址',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL COMMENT '最后更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `state_index` (`state`) USING BTREE,
  KEY `group_uuid_index` (`group_uuid`) USING BTREE,
  KEY `repository_uuid_index` (`repository_uuid`) USING BTREE,
  KEY `repository_code_index` (`repository_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=546 DEFAULT CHARSET=utf8 COMMENT='代码仓库';

-- ----------------------------
-- Table structure for t_code_story
-- ----------------------------
DROP TABLE IF EXISTS `t_code_story`;
CREATE TABLE `t_code_story` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品线ID',
  `department_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品组ID',
  `project_code` varchar(100) NOT NULL DEFAULT '' COMMENT '项目code',
  `project_name` varchar(150) NOT NULL DEFAULT '' COMMENT '项目名称',
  `story_code` varchar(100) NOT NULL COMMENT '需求code',
  `story_name` varchar(150) NOT NULL COMMENT '需求名称',
  `story_url` varchar(300) NOT NULL COMMENT '需求地址',
  `recv_username` varchar(100) NOT NULL DEFAULT '' COMMENT '负责人用户名',
  `send_username` varchar(100) NOT NULL DEFAULT '' COMMENT '创建者用户名',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `update_ip` varchar(30) NOT NULL,
  `state` int(11) NOT NULL COMMENT '需求状态',
  PRIMARY KEY (`id`),
  KEY `state_index` (`state`) USING BTREE,
  KEY `project_code_index` (`project_code`) USING BTREE,
  KEY `story_code_index` (`story_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1121 DEFAULT CHARSET=utf8 COMMENT='需求表';

-- ----------------------------
-- Table structure for t_grey_backend
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_backend`;
CREATE TABLE `t_grey_backend` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `config_id` int(11) NOT NULL DEFAULT '0' COMMENT '配置服务器ID',
  `title` varchar(100) NOT NULL COMMENT '标题信息',
  `backend_name` varchar(100) NOT NULL COMMENT '后端名称',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `update_ip` varchar(30) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `config_id_index` (`config_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='灰度后端信息';

-- ----------------------------
-- Table structure for t_grey_backend_node
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_backend_node`;
CREATE TABLE `t_grey_backend_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `config_id` int(11) NOT NULL COMMENT '配置ID',
  `backend_id` int(11) NOT NULL COMMENT '后端信息ID',
  `server_ip` varchar(200) NOT NULL COMMENT '服务器IP',
  `server_port` int(11) NOT NULL DEFAULT '80' COMMENT '服务器端口',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `config_id_index` (`config_id`) USING BTREE,
  KEY `backend_id_index` (`backend_id`) USING BTREE,
  KEY `state_index` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='灰度后端节点';

-- ----------------------------
-- Table structure for t_grey_config_server
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_config_server`;
CREATE TABLE `t_grey_config_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `config_name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `server_host` varchar(100) NOT NULL DEFAULT '' COMMENT '服务器地址',
  `server_port` int(11) NOT NULL DEFAULT '6309' COMMENT '服务器端口',
  `server_db` int(11) NOT NULL DEFAULT '0' COMMENT '服务器DB号',
  `server_password` varchar(100) NOT NULL DEFAULT '' COMMENT '服务器密码',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL COMMENT '更新IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_grey_rule_list
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_rule_list`;
CREATE TABLE `t_grey_rule_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `config_id` int(11) NOT NULL DEFAULT '0' COMMENT '配置服务器ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点ID',
  `match_object` varchar(100) NOT NULL DEFAULT 's_tenant_code' COMMENT '匹配对象',
  `match_opp` varchar(50) NOT NULL DEFAULT 'eq' COMMENT '匹配操作符',
  `match_value` varchar(200) NOT NULL DEFAULT '' COMMENT '匹配值',
  `mode` varchar(30) NOT NULL DEFAULT 'none' COMMENT '模式',
  `backend_id` int(11) NOT NULL DEFAULT '0' COMMENT '后端节点ID',
  `order_no` int(11) NOT NULL DEFAULT '0' COMMENT '优先级,越大越优先',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `site_id_idex` (`site_id`) USING BTREE,
  KEY `tenant_code_idex` (`match_value`) USING BTREE,
  KEY `mode_idex` (`mode`) USING BTREE,
  KEY `state_idex` (`state`) USING BTREE,
  KEY `config_id_index` (`config_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_grey_rule_site
-- ----------------------------
DROP TABLE IF EXISTS `t_grey_rule_site`;
CREATE TABLE `t_grey_rule_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `config_id` int(11) NOT NULL DEFAULT '0' COMMENT '对应配置服务器ID',
  `rule_site_name` varchar(100) NOT NULL COMMENT '站点规则名称',
  `http_host` varchar(250) NOT NULL COMMENT '站点host规则',
  `http_host_type` varchar(50) NOT NULL DEFAULT 'string' COMMENT '站点host规则类别,string,regex',
  `mode` varchar(30) NOT NULL DEFAULT 'none' COMMENT '全局模式',
  `prod_backend_id` int(11) NOT NULL DEFAULT '0' COMMENT '全量线上节点ID',
  `grey_backend_id` int(11) NOT NULL DEFAULT '0' COMMENT '全量灰度节点ID',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_ip` varchar(50) NOT NULL COMMENT '更新IP',
  `update_cache_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '上次更新到缓存时间',
  `state` int(11) NOT NULL COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `config_id_index` (`config_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_user_department
-- ----------------------------
DROP TABLE IF EXISTS `t_user_department`;
CREATE TABLE `t_user_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `keyword` varchar(100) NOT NULL COMMENT '匹配关键词',
  `level_code` varchar(50) NOT NULL COMMENT '级别code,product/department',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '上次更新时间',
  `update_ip` varchar(255) NOT NULL DEFAULT '127.0.0.1' COMMENT '更新IP',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for t_user_info
-- ----------------------------
DROP TABLE IF EXISTS `t_user_info`;
CREATE TABLE `t_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `user_password` varchar(50) NOT NULL COMMENT '登录密码',
  `user_pass_salt` varchar(50) NOT NULL COMMENT '登录密码盐',
  `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(100) NOT NULL DEFAULT '' COMMENT '联系手机号码',
  `email` varchar(100) NOT NULL COMMENT '邮箱地址',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品线ID',
  `department_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品组ID',
  `role_group_code` varchar(50) NOT NULL DEFAULT '' COMMENT '角色分组code',
  `role_group_level` int(11) NOT NULL COMMENT '角色分组级别',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '最后更新时间',
  `update_ip` varchar(50) NOT NULL COMMENT '最后更新ip',
  `state` int(11) NOT NULL COMMENT '账号状态',
  PRIMARY KEY (`id`),
  KEY `user_name_index` (`user_name`) USING BTREE,
  KEY `product_id_index` (`product_id`) USING BTREE,
  KEY `department_id_index` (`department_id`) USING BTREE,
  KEY `role_group_code_index` (`role_group_code`) USING BTREE,
  KEY `role_group_level_index` (`role_group_level`) USING BTREE,
  KEY `state_index` (`state`) USING BTREE,
  KEY `email_index` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;
