/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : tpcms

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 13/08/2019 10:51:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tp_admin
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin`;
CREATE TABLE `tp_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_admin
-- ----------------------------
INSERT INTO `tp_admin` VALUES (1, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', NULL, 0, 1565663373);
INSERT INTO `tp_admin` VALUES (2, 'test', '7c4a8d09ca3762af61e59520943dc26494f8941b', NULL, 1564930804, 1564930804);

-- ----------------------------
-- Table structure for tp_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role`;
CREATE TABLE `tp_admin_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `role_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_admin_role
-- ----------------------------
INSERT INTO `tp_admin_role` VALUES (1, 1, 1, 0, NULL);
INSERT INTO `tp_admin_role` VALUES (2, 2, 2, 1564930804, 1564930804);

-- ----------------------------
-- Table structure for tp_article
-- ----------------------------
DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `category_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '栏目ID',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tp_category
-- ----------------------------
DROP TABLE IF EXISTS `tp_category`;
CREATE TABLE `tp_category`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型:1列表,2单页,3链接',
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级ID',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '栏目表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tp_config
-- ----------------------------
DROP TABLE IF EXISTS `tp_config`;
CREATE TABLE `tp_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型:1单行文本,2多行文本,3单选按钮,4复选框,5下拉框',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '配置值',
  `values` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '值',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_config
-- ----------------------------
INSERT INTO `tp_config` VALUES (1, '是否开启站点?', 'website', 3, '开启,关闭', '开启', 1, 1564928283, 1564931215);
INSERT INTO `tp_config` VALUES (2, '网站标题', 'title', 1, NULL, '内容管理系统', 2, 1564928323, 1564931215);
INSERT INTO `tp_config` VALUES (3, '网站描述', 'desc', 2, NULL, '该网站处于开发阶段', 3, 1564928386, 1564931215);
INSERT INTO `tp_config` VALUES (4, '优惠时间', 'time', 4, '1,2,3,4,5,6,7', '7', 4, 1564928918, 1564931215);
INSERT INTO `tp_config` VALUES (5, '你最喜欢的框架是什么？', 'framework', 5, 'ThinkPHP,Laravel,Yii,Ci', 'ThinkPHP', 5, 1564929023, 1564931215);

-- ----------------------------
-- Table structure for tp_permissions
-- ----------------------------
DROP TABLE IF EXISTS `tp_permissions`;
CREATE TABLE `tp_permissions`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '路径',
  `menu` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '菜单:0不是,1是',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级ID',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_permissions
-- ----------------------------
INSERT INTO `tp_permissions` VALUES (1, '用户管理', 'fa-users', NULL, 1, 2, 0, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (2, '用户列表', 'fa-circle-o', 'admin/admin/index', 1, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (3, '用户添加', NULL, 'admin/admin/create', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (4, '用户编辑', NULL, 'admin/admin/update', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (5, '用户删除', NULL, 'admin/admin/delete', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (6, '角色列表', 'fa-circle-o', 'admin/role/index', 1, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (7, '角色添加', NULL, 'admin/role/create', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (8, '角色编辑', NULL, 'admin/role/update', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (9, '角色删除', NULL, 'admin/role/delete', 0, 0, 1, 0, 1565664417);
INSERT INTO `tp_permissions` VALUES (10, '权限列表', 'fa-circle-o', 'admin/permissions/index', 1, 0, 1, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (11, '权限添加', NULL, 'admin/permissions/create', 0, 0, 1, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (12, '权限编辑', NULL, 'admin/permissions/update', 0, 0, 1, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (13, '权限删除', NULL, 'admin/permissions/delete', 0, 0, 1, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (14, '系统管理', 'fa-gears', NULL, 1, 3, 0, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (15, '配置列表', 'fa-circle-o', 'admin/config/index', 1, 0, 14, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (16, '配置添加', NULL, 'admin/config/create', 0, 0, 14, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (17, '配置编辑', NULL, 'admin/config/update', 0, 0, 14, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (18, '配置删除', NULL, 'admin/config/delete', 0, 0, 14, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (19, '配置管理', 'fa-circle-o', 'admin/config/save', 1, 0, 14, 0, 1565664431);
INSERT INTO `tp_permissions` VALUES (20, '测试路由', 'fa-circle-o', NULL, 1, 4, 0, 1564930746, 1565664431);
INSERT INTO `tp_permissions` VALUES (21, '栏目管理', 'fa-align-justify', '', 1, 0, 0, 1565663586, 1565664417);
INSERT INTO `tp_permissions` VALUES (22, '栏目列表', 'fa-circle-o', 'admin/category/index', 1, 0, 21, 1565663636, 1565664417);
INSERT INTO `tp_permissions` VALUES (23, '栏目添加', '', 'admin/admin/create', 0, 0, 21, 1565664213, 1565664417);
INSERT INTO `tp_permissions` VALUES (24, '栏目编辑', '', 'admin/admin/update', 0, 0, 21, 1565664236, 1565664417);
INSERT INTO `tp_permissions` VALUES (25, '栏目删除', '', 'admin/admin/delete', 0, 0, 21, 1565664255, 1565664417);
INSERT INTO `tp_permissions` VALUES (26, '文章管理', 'fa-file-text', '', 1, 1, 0, 1565664387, 1565664488);
INSERT INTO `tp_permissions` VALUES (27, '文章列表', 'fa-circle-o', 'admin/article/index', 1, 0, 26, 1565664477, 1565664477);
INSERT INTO `tp_permissions` VALUES (28, '文章添加', '', 'admin/article/create', 0, 0, 26, 1565664519, 1565664519);
INSERT INTO `tp_permissions` VALUES (29, '文章编辑', '', 'admin/article/update', 0, 0, 26, 1565664542, 1565664542);
INSERT INTO `tp_permissions` VALUES (30, '文章删除', '', 'admin/article/delete', 0, 0, 26, 1565664559, 1565664559);

-- ----------------------------
-- Table structure for tp_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态:0禁用,1正常',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_role
-- ----------------------------
INSERT INTO `tp_role` VALUES (1, 'admin', 1, 0, NULL);
INSERT INTO `tp_role` VALUES (2, 'test', 1, 1564930777, 1564931158);
INSERT INTO `tp_role` VALUES (3, 'test2', 0, 1564930786, 1564931166);

-- ----------------------------
-- Table structure for tp_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `tp_role_permissions`;
CREATE TABLE `tp_role_permissions`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
  `permissions_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限ID',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 109 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_role_permissions
-- ----------------------------
INSERT INTO `tp_role_permissions` VALUES (67, 3, 1, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (68, 3, 2, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (69, 3, 3, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (70, 3, 4, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (71, 3, 5, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (72, 3, 6, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (73, 3, 7, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (74, 3, 8, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (75, 3, 9, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (76, 3, 10, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (77, 3, 11, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (78, 3, 12, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (79, 3, 13, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (80, 3, 14, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (81, 3, 15, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (82, 3, 16, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (83, 3, 17, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (84, 3, 18, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (85, 3, 19, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (86, 3, 20, 1564931166, 1564931166);
INSERT INTO `tp_role_permissions` VALUES (87, 2, 1, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (88, 2, 2, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (89, 2, 3, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (90, 2, 4, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (91, 2, 5, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (92, 2, 6, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (93, 2, 7, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (94, 2, 8, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (95, 2, 9, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (96, 2, 10, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (97, 2, 11, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (98, 2, 12, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (99, 2, 13, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (100, 2, 14, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (101, 2, 15, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (102, 2, 16, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (103, 2, 17, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (104, 2, 18, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (105, 2, 19, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (106, 2, 20, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (107, 2, 21, 1565664036, 1565664036);
INSERT INTO `tp_role_permissions` VALUES (108, 2, 22, 1565664036, 1565664036);

SET FOREIGN_KEY_CHECKS = 1;
