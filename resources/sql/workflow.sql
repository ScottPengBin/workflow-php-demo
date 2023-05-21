/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : workflow

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 21/05/2023 16:53:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for workflow_job
-- ----------------------------
DROP TABLE IF EXISTS `workflow_job`;
CREATE TABLE `workflow_job`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_id` int(10) UNSIGNED NOT NULL COMMENT '工作流id',
  `workflow_node_id` int(10) UNSIGNED NOT NULL COMMENT '工作流节点id',
  `before_data` json NULL COMMENT '之前数据',
  `after_data` json NULL COMMENT '之后数据',
  `do_action` tinyint(3) UNSIGNED NOT NULL COMMENT '1 新增 2修改 3删除',
  `module_id` int(10) UNSIGNED NULL DEFAULT 0,
  `module_type` int(11) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 审批中 2审批完成 3审批拒绝 4取消 ',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '工作流-任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of workflow_job
-- ----------------------------

-- ----------------------------
-- Table structure for workflow_job_log
-- ----------------------------
DROP TABLE IF EXISTS `workflow_job_log`;
CREATE TABLE `workflow_job_log`  (
  `id` int(11) NOT NULL,
  `workflow_id` int(11) NULL DEFAULT NULL,
  `workflow_node_id` int(11) NULL DEFAULT NULL,
  `workflow_node_type` int(255) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '任务日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of workflow_job_log
-- ----------------------------

-- ----------------------------
-- Table structure for workflow_main_setting
-- ----------------------------
DROP TABLE IF EXISTS `workflow_main_setting`;
CREATE TABLE `workflow_main_setting`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `trigger_task` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '触发task',
  `done_task` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '完成task',
  `filed_task` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '失败task',
  `workflow_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '名称',
  `workflow_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `workflow_start_condition` json NULL COMMENT '启动条件',
  `workflow_status` tinyint(1) NULL DEFAULT NULL COMMENT '1 未发布 2已发布 3 草稿',
  `tigger_action` tinyint(1) NULL DEFAULT NULL COMMENT '1 新增 2更新 3删除',
  `module_type` int(11) NOT NULL COMMENT '所属模块类型',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tigger_task`(`trigger_task`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '工作流-主-设置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of workflow_main_setting
-- ----------------------------
INSERT INTO `workflow_main_setting` VALUES (1, 'App\\Http\\Service\\Workflow\\ProjectService@createProject', 'App\\Http\\Service\\Workflow\\ProjectService@createProject', 'App\\Http\\Service\\Workflow\\ProjectService@CancleCreateProject', '创建项目流程', '这个一个创建项目流程demo', NULL, 2, 1, 10, '2023-05-20 01:00:24', '2023-05-20 01:00:27');

-- ----------------------------
-- Table structure for workflow_node_setting
-- ----------------------------
DROP TABLE IF EXISTS `workflow_node_setting`;
CREATE TABLE `workflow_node_setting`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `node_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '节点标题',
  `node_task` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务',
  `workflow_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '主设置表id',
  `next_node_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '下一个节点id',
  `node_priority` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '优先级 1 2 3',
  `node_type` tinyint(1) UNSIGNED NULL DEFAULT NULL COMMENT '节点类型： 1 条件 2审批 3抄送 4触发节点',
  `node_ext` json NULL COMMENT '节点扩展信息',
  `is_done` tinyint(1) UNSIGNED NULL DEFAULT 2 COMMENT '是否结束节点 1是 2否',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '工作流-节点-设置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of workflow_node_setting
-- ----------------------------
INSERT INTO `workflow_node_setting` VALUES (1, '创建项目流程', 'App\\Http\\Service\\Workflow\\ProjectService@createProject', 1, 2, 1, 4, NULL, 2, '2023-05-20 09:06:28', '2023-05-20 09:06:30');
INSERT INTO `workflow_node_setting` VALUES (2, '审批1', 'App\\Http\\Service\\Workflow\\ProjectService@approval', 1, 3, 1, 2, '[\"主管\"]', 2, NULL, NULL);
INSERT INTO `workflow_node_setting` VALUES (3, '审批2', 'App\\Http\\Service\\Workflow\\ProjectService@approval', 1, 0, 1, 2, '[\"总经理\"]', 1, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
