-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admin_auth_assignment`;
CREATE TABLE `admin_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `admin_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `admin_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admin_auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admin_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('普通用户',	1,	1573548433),
('普通用户',	2,	1573549054),
('普通用户',	3,	1573607925),
('管理员',	1,	1573530560);

DROP TABLE IF EXISTS `admin_auth_item`;
CREATE TABLE `admin_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `admin_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `admin_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admin_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/*',	2,	NULL,	NULL,	NULL,	1557484761,	1557484761),
('/log',	2,	NULL,	NULL,	NULL,	1557977925,	1557977925),
('/log/*',	2,	NULL,	NULL,	NULL,	1557977920,	1557977920),
('/log/default',	2,	NULL,	NULL,	NULL,	1557977973,	1557977973),
('/log/default/*',	2,	NULL,	NULL,	NULL,	1557977965,	1557977965),
('/log/log-email-send-code',	2,	NULL,	NULL,	NULL,	1573550285,	1573550285),
('/log/log-email-send-code/*',	2,	NULL,	NULL,	NULL,	1573550272,	1573550272),
('/rbac',	2,	NULL,	NULL,	NULL,	1557486324,	1557486324),
('/rbac/*',	2,	NULL,	NULL,	NULL,	1557486315,	1557486315),
('/rbac/assignment',	2,	NULL,	NULL,	NULL,	1557719120,	1557719120),
('/rbac/assignment/*',	2,	NULL,	NULL,	NULL,	1557719110,	1557719110),
('/rbac/menu',	2,	NULL,	NULL,	NULL,	1557719183,	1557719183),
('/rbac/menu/*',	2,	NULL,	NULL,	NULL,	1557719177,	1557719177),
('/rbac/permission',	2,	NULL,	NULL,	NULL,	1557719212,	1557719212),
('/rbac/permission/*',	2,	NULL,	NULL,	NULL,	1557719209,	1557719209),
('/rbac/role',	2,	NULL,	NULL,	NULL,	1557719133,	1557719133),
('/rbac/role/*',	2,	NULL,	NULL,	NULL,	1557719129,	1557719129),
('/rbac/route',	2,	NULL,	NULL,	NULL,	1557719162,	1557719162),
('/rbac/route/*',	2,	NULL,	NULL,	NULL,	1557719158,	1557719158),
('/rbac/rule',	2,	NULL,	NULL,	NULL,	1557719224,	1557719224),
('/rbac/rule/*',	2,	NULL,	NULL,	NULL,	1557719220,	1557719220),
('/ucenter',	2,	NULL,	NULL,	NULL,	1573174904,	1573174904),
('/ucenter/*',	2,	NULL,	NULL,	NULL,	1573174900,	1573174900),
('/ucenter/user-file',	2,	NULL,	NULL,	NULL,	1573174923,	1573174923),
('/ucenter/user-file/*',	2,	NULL,	NULL,	NULL,	1573174908,	1573174908),
('/user/default',	2,	NULL,	NULL,	NULL,	1557718778,	1557718778),
('/user/default/*',	2,	NULL,	NULL,	NULL,	1557718770,	1557718770),
('普通用户',	1,	NULL,	NULL,	NULL,	1573177792,	1573177792),
('用户个人文件管理',	2,	'',	NULL,	'',	1573175748,	1573175748),
('管理员',	1,	NULL,	NULL,	NULL,	1560000000,	1560000000);

DROP TABLE IF EXISTS `admin_auth_item_child`;
CREATE TABLE `admin_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `admin_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `admin_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admin_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `admin_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admin_auth_item_child` (`parent`, `child`) VALUES
('管理员',	'/*'),
('用户个人文件管理',	'/ucenter/user-file'),
('用户个人文件管理',	'/ucenter/user-file/*'),
('普通用户',	'用户个人文件管理'),
('管理员',	'用户个人文件管理');

DROP TABLE IF EXISTS `admin_auth_rule`;
CREATE TABLE `admin_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admin_auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('用户文件管理',	'O:40:\"admin\\modules\\ucenter\\rules\\UserFileRule\":3:{s:4:\"name\";s:18:\"用户文件管理\";s:9:\"createdAt\";i:1573175590;s:9:\"updatedAt\";i:1573175590;}',	1573175590,	1573175590);

DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `route` (`route`),
  CONSTRAINT `admin_menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `admin_menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `admin_menu_ibfk_3` FOREIGN KEY (`route`) REFERENCES `admin_auth_item` (`name`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `admin_menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES
(1,	'RBAC',	NULL,	'/rbac',	NULL,	NULL),
(2,	'用户列表',	1,	'/user/default',	1,	NULL),
(3,	'权限分配',	1,	'/rbac/assignment',	4,	NULL),
(4,	'角色列表',	1,	'/rbac/role',	3,	NULL),
(5,	'路由列表',	1,	'/rbac/route',	2,	NULL),
(6,	'菜单列表',	1,	'/rbac/menu',	5,	NULL),
(7,	'规则列表',	1,	'/rbac/rule',	6,	NULL),
(8,	'权限列表',	1,	'/rbac/permission',	7,	NULL),
(9,	'日志记录',	NULL,	'/log',	NULL,	NULL),
(10,	'系统错误日志',	9,	'/log/default',	1,	NULL),
(11,	'用户中心',	NULL,	'/ucenter',	NULL,	NULL),
(12,	'用户文件管理',	11,	'/ucenter/user-file',	NULL,	NULL),
(13,	'验证码发送记录',	9,	'/log/log-email-send-code',	NULL,	NULL);

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


DROP TABLE IF EXISTS `log_email_send_code`;
CREATE TABLE `log_email_send_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `type` int(11) NOT NULL COMMENT '类型',
  `from` varchar(150) NOT NULL COMMENT '发件邮箱',
  `to` varchar(150) NOT NULL COMMENT '目标邮箱',
  `subject` varchar(150) NOT NULL COMMENT '主题',
  `code` char(32) NOT NULL COMMENT '验证码',
  `params` text NOT NULL COMMENT '其他参数',
  `status` int(11) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `log_email_send_code_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮箱发送验证码日志';


DROP TABLE IF EXISTS `log_quene_yii_task`;
CREATE TABLE `log_quene_yii_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` int(11) unsigned NOT NULL,
  `result_code` int(11) NOT NULL,
  `result_msg` text NOT NULL,
  `done_quene_yii_task_ids` text COMMENT '本次完成的所有任务',
  `failed_quene_yii_task_ids` text COMMENT '本次失败的所以任务',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='yii任务队列执行记录';


DROP TABLE IF EXISTS `log_user_login`;
CREATE TABLE `log_user_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `from_ip` varchar(120) DEFAULT NULL COMMENT '登录ip',
  `from_app` varchar(20) DEFAULT NULL COMMENT '从哪个应用登录',
  `is_login` int(11) NOT NULL COMMENT '是否登录成功',
  `params` text COMMENT '请求参数',
  `param_username` varchar(150) DEFAULT NULL,
  `param_email` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `log_user_login_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录日志';


DROP TABLE IF EXISTS `quene_yii_task`;
CREATE TABLE `quene_yii_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `run_at` int(11) unsigned DEFAULT NULL COMMENT '执行时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态0:默认进入队列',
  `route` varchar(100) NOT NULL COMMENT '任务路由',
  `params` text COMMENT '参数',
  `error_msg` text COMMENT '运行失败信息',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `quene_yii_task_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='yii任务队列';


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `email` varchar(150) NOT NULL COMMENT '邮箱',
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
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

INSERT INTO `user` (`id`, `username`, `email`, `password`, `pwd_back`, `status`, `created_at`, `token`, `key`, `auth_key`, `amount`, `frozen`, `deposit`) VALUES
(1,	'wodrow',	'1173957281@qq.com',	'e10adc3949ba59abbe56e057f20f883e',	'MDAwMDAwMDAwMK6LrquHe7pl',	10,	1573530553,	'Ongq7aaNF7L1jlTanWv7iTObNXbD9z6l',	'WzU37lsBYYBtQZGwjI7vThu2vhhfv49j',	'17UPoxB0pUxdcfm_OeGwuel2qNOG4Xld',	0.00,	0.00,	0.00),
(2,	'test11',	'test11@test.test',	'e10adc3949ba59abbe56e057f20f883e',	'MDAwMDAwMDAwMK6LrquHe7pl',	10,	1573549054,	'qWwIpPjd9rf6SDvPfWW36KoKJA8U34CC',	'baxoTs6UJN0cvHeJmBAEjuFEUf-BdgDs',	'TYeGYmthieFikkgjcCkcgYG5sKGa2IPB',	0.00,	0.00,	0.00),
(3,	'test12',	'test12@test.test',	'e10adc3949ba59abbe56e057f20f883e',	'MDAwMDAwMDAwMK6LrquHe7pl',	10,	1573607925,	'ScY0F2dhPBgFXXpN3K4MHsJQxdeQPfxT',	'E0qiobiC-IhjEuymc0qPFyTSxDsYaV9p',	'UVc4R6WyzstXhtjt8LeR7FgrDqfPjC9p',	0.00,	0.00,	0.00);

DROP TABLE IF EXISTS `user_file`;
CREATE TABLE `user_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(260) NOT NULL,
  `extension` varchar(40) NOT NULL COMMENT '扩展名',
  `mime_type` varchar(50) DEFAULT NULL COMMENT '文件类型',
  `relation_path` varchar(200) NOT NULL COMMENT '相对路径',
  `yii_alias_uploads_path` varchar(200) NOT NULL COMMENT '上传地址',
  `yii_alias_uploads_root` varchar(200) NOT NULL COMMENT '上传路径',
  `size` bigint(20) unsigned DEFAULT NULL COMMENT '文件大小',
  `created_by` int(11) unsigned DEFAULT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `user_file_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_file_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户文件(上传)';


-- 2019-11-13 01:48:48