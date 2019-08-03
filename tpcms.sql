# Host: localhost  (Version: 5.5.53)
# Date: 2019-08-03 21:23:32
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "tp_admin"
#

DROP TABLE IF EXISTS `tp_admin`;
CREATE TABLE `tp_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(40) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

#
# Data for table "tp_admin"
#

INSERT INTO `tp_admin` VALUES (1,'admin','7c4a8d09ca3762af61e59520943dc26494f8941b',NULL,0,NULL),(2,'test','7c4a8d09ca3762af61e59520943dc26494f8941b',NULL,0,NULL);

#
# Structure for table "tp_admin_role"
#

DROP TABLE IF EXISTS `tp_admin_role`;
CREATE TABLE `tp_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色关联表';

#
# Data for table "tp_admin_role"
#

INSERT INTO `tp_admin_role` VALUES (1,1,1,0,NULL),(2,2,2,0,NULL);

#
# Structure for table "tp_article"
#

DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章表';

#
# Data for table "tp_article"
#


#
# Structure for table "tp_category"
#

DROP TABLE IF EXISTS `tp_category`;
CREATE TABLE `tp_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1列表,2单页,3链接',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='栏目表';

#
# Data for table "tp_category"
#


#
# Structure for table "tp_config"
#

DROP TABLE IF EXISTS `tp_config`;
CREATE TABLE `tp_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '变量名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1单行文本,2多行文本,3单选按钮,4复选框,5下拉框',
  `value` varchar(255) DEFAULT NULL COMMENT '配置值',
  `values` varchar(255) DEFAULT NULL COMMENT '值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配置表';

#
# Data for table "tp_config"
#


#
# Structure for table "tp_permissions"
#

DROP TABLE IF EXISTS `tp_permissions`;
CREATE TABLE `tp_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `path` varchar(255) DEFAULT NULL COMMENT '路径',
  `menu` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '菜单:0不是,1是',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='权限表';

#
# Data for table "tp_permissions"
#

INSERT INTO `tp_permissions` VALUES (1,'用户管理','fa-users',NULL,1,0,0,0,NULL),(2,'用户列表','fa-circle-o','admin/admin/index',1,0,1,0,NULL),(3,'用户添加',NULL,'admin/admin/create',0,0,1,0,NULL),(4,'用户编辑',NULL,'admin/admin/update',0,0,1,0,NULL),(5,'用户删除',NULL,'admin/admin/delete',0,0,1,0,NULL),(6,'角色列表','fa-circle-o','admin/role/index',1,0,1,0,NULL),(7,'角色添加',NULL,'admin/role/create',0,0,1,0,NULL),(8,'角色编辑',NULL,'admin/role/update',0,0,1,0,NULL),(9,'角色删除',NULL,'admin/role/delete',0,0,1,0,NULL),(10,'权限列表','fa-circle-o','admin/permissions/index',1,0,1,0,NULL),(11,'权限添加',NULL,'admin/permissions/create',0,0,1,0,NULL),(12,'权限编辑',NULL,'admin/permissions/update',0,0,1,0,NULL),(13,'权限删除',NULL,'admin/permissions/delete',0,0,1,0,NULL),(14,'系统管理','fa-gears',NULL,1,0,0,0,NULL),(15,'配置列表','fa-circle-o','admin/config/index',1,0,14,0,NULL),(16,'配置添加',NULL,'admin/config/create',0,0,14,0,NULL),(17,'配置编辑',NULL,'admin/config/update',0,0,14,0,NULL),(18,'配置删除',NULL,'admin/config/delete',0,0,14,0,NULL),(19,'配置管理','fa-circle-o','admin/config/save',1,0,14,0,NULL);

#
# Structure for table "tp_role"
#

DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

#
# Data for table "tp_role"
#

INSERT INTO `tp_role` VALUES (1,'admin',1,0,NULL),(2,'test',1,0,NULL);

#
# Structure for table "tp_role_permissions"
#

DROP TABLE IF EXISTS `tp_role_permissions`;
CREATE TABLE `tp_role_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `permissions_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关联表';

#
# Data for table "tp_role_permissions"
#

INSERT INTO `tp_role_permissions` VALUES (1,2,1,0,NULL),(2,2,2,0,NULL),(3,2,3,0,NULL),(4,2,4,0,NULL),(5,2,5,0,NULL),(6,2,6,0,NULL),(7,2,7,0,NULL),(8,2,8,0,NULL),(9,2,9,0,NULL),(10,2,10,0,NULL),(11,2,11,0,NULL),(12,2,12,0,NULL),(13,2,13,0,NULL),(14,2,14,0,NULL),(15,2,15,0,NULL),(16,2,16,0,NULL),(17,2,17,0,NULL),(18,2,18,0,NULL);
