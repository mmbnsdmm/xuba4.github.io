/*
Navicat MySQL Data Transfer
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` double DEFAULT NULL,
  `prefix` text COLLATE utf8_unicode_ci,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_log_level` (`level`),
  KEY `idx_log_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='应用log';

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `password` char(32) NOT NULL COMMENT '密码',
  `pwd_back` varchar(150) NOT NULL COMMENT '只用于获取密码明文',
  `status` int(11) NOT NULL DEFAULT '10' COMMENT '状态10:正常',
  `created_at` int(11) unsigned NOT NULL COMMENT '注册时间',
  `token` char(32) NOT NULL COMMENT '登录令牌',
  `key` char(32) NOT NULL COMMENT '登录秘钥',
  `auth_key` char(32) NOT NULL COMMENT 'session认证秘钥',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `frozen` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '冻结资金',
  `deposit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '保证金',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- Records of user
-- ----------------------------
