SET FOREIGN_KEY_CHECKS=0;

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
