DROP TABLE IF EXISTS `t_grey_config_server`;
DROP TABLE IF EXISTS `t_grey_backend`;
DROP TABLE IF EXISTS `t_grey_backend_node`;
DROP TABLE IF EXISTS `t_grey_rule_site`;
DROP TABLE IF EXISTS `t_grey_rule_list`;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='灰度后端信息';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='灰度后端节点';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

