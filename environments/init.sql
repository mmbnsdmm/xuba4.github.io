-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

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


-- 2019-10-17 05:54:45