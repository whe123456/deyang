/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : deyang_occupation

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-04-15 18:45:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sidebar_icon`
-- ----------------------------
DROP TABLE IF EXISTS `sidebar_icon`;
CREATE TABLE `sidebar_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT NULL COMMENT '字体图标，参照饿了么ui',
  `index` varchar(100) NOT NULL COMMENT '页面导航',
  `title` varchar(100) DEFAULT NULL COMMENT '页面名称',
  `parent` varchar(11) DEFAULT NULL COMMENT '父级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of sidebar_icon
-- ----------------------------
INSERT INTO `sidebar_icon` VALUES ('1', 'el-icon-setting', '1', '业务管理', '0');
INSERT INTO `sidebar_icon` VALUES ('2', null, 'class', '班级管理', '1');
INSERT INTO `sidebar_icon` VALUES ('3', null, 'white', '白名单管理', '1');
INSERT INTO `sidebar_icon` VALUES ('4', null, 'userkq', '出勤统计', '1');
INSERT INTO `sidebar_icon` VALUES ('5', null, 'getqjjl', '请假统计', '1');
INSERT INTO `sidebar_icon` VALUES ('6', 'el-icon-date', '2', '系统管理', '0');
INSERT INTO `sidebar_icon` VALUES ('7', null, 'mangeruser', '系统管理', '2');
INSERT INTO `sidebar_icon` VALUES ('8', null, 'role', '角色管理', '2');
INSERT INTO `sidebar_icon` VALUES ('9', null, 'menu', '菜单管理', '2');

-- ----------------------------
-- Table structure for `zjzz_bj`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_bj`;
CREATE TABLE `zjzz_bj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bj_bm` varchar(50) NOT NULL COMMENT '班级编码',
  `bj_mc` varchar(500) NOT NULL COMMENT '班级名称',
  `js_bh` varchar(500) DEFAULT NULL COMMENT '教室编号',
  `js_bm` varchar(50) NOT NULL COMMENT '教师编码',
  `bz` varchar(50) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`bj_bm`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_bj
-- ----------------------------
INSERT INTO `zjzz_bj` VALUES ('1', '001', '中江1', '001', '001', '随意');
INSERT INTO `zjzz_bj` VALUES ('2', '002', '中江2', '002', '002', '随意');
INSERT INTO `zjzz_bj` VALUES ('3', '003', '中江3', '003', '003', null);
INSERT INTO `zjzz_bj` VALUES ('4', '004', '中江4', '004', '004', '随意');
INSERT INTO `zjzz_bj` VALUES ('5', '005', '中江5', '005', '005', null);
INSERT INTO `zjzz_bj` VALUES ('6', '006', '中江6', '006', '006', null);
INSERT INTO `zjzz_bj` VALUES ('7', '007', '中江7', '007', '007', null);
INSERT INTO `zjzz_bj` VALUES ('8', '008', '中江8', '008', '008', '随意');
INSERT INTO `zjzz_bj` VALUES ('9', '009', '中江9', '009', '009', null);
INSERT INTO `zjzz_bj` VALUES ('10', '010', '中江10', '010', '010', null);
INSERT INTO `zjzz_bj` VALUES ('11', '011', '中江11', '011', '011', null);
INSERT INTO `zjzz_bj` VALUES ('12', '012', '0556', '012', '007', 'www');

-- ----------------------------
-- Table structure for `zjzz_dhbmd`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_dhbmd`;
CREATE TABLE `zjzz_dhbmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sjhm` varchar(11) NOT NULL COMMENT '手机号',
  `xm` varchar(100) NOT NULL COMMENT '姓名',
  `cj_time` datetime DEFAULT NULL COMMENT '创建时间',
  `yz_ts` datetime DEFAULT NULL COMMENT '验证时间',
  `sf_yz` int(11) NOT NULL COMMENT '0未验证1已验证',
  `yzm` varchar(50) DEFAULT NULL COMMENT '验证码',
  `yzsj` datetime DEFAULT NULL COMMENT '验证时间,每个月更新一次',
  `bjbm` varchar(50) NOT NULL COMMENT '班级编码',
  `xh` varchar(50) NOT NULL COMMENT '学号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_dhbmd
-- ----------------------------
INSERT INTO `zjzz_dhbmd` VALUES ('1', '12345612345', '我去我去1', '2018-04-03 16:12:31', null, '0', '', '0000-00-00 00:00:00', '001', '002');
INSERT INTO `zjzz_dhbmd` VALUES ('7', '18888888888', '测试哥', '2018-04-03 17:59:43', '2018-04-14 21:07:32', '1', '248480', '2018-04-14 21:07:32', '002', '200');

-- ----------------------------
-- Table structure for `zjzz_js`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_js`;
CREATE TABLE `zjzz_js` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js_bm` varchar(50) NOT NULL COMMENT '教师编码',
  `xm` varchar(500) NOT NULL COMMENT '教师名称',
  `dl_mm` varchar(50) NOT NULL COMMENT '后台登陆密码',
  `sjhm` varchar(18) NOT NULL COMMENT '手机号',
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信ID',
  `wxnc` varchar(50) DEFAULT NULL COMMENT '微信昵称',
  `zc_ts` datetime DEFAULT NULL COMMENT '注册时间',
  `ewm_url` text COMMENT '二维码地址',
  `sf_zc` int(1) DEFAULT '0' COMMENT '0未注册，1已注册',
  `js_id` int(11) DEFAULT '0' COMMENT '0为未指定角色，其余对应角色表',
  PRIMARY KEY (`js_bm`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_js
-- ----------------------------
INSERT INTO `zjzz_js` VALUES ('1', '001', '老王', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('2', '002', '老2', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('3', '003', '老3', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('4', '004', '老4', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('5', '005', '老5', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('6', '006', '老6', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('7', '007', '老7', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('8', '008', '老8', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('9', '009', '老9', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('10', '010', '老10', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '16151132231331213213213123132132132132', '啧啧', null, null, '0', '1');
INSERT INTO `zjzz_js` VALUES ('11', '011', '老11', 'e10adc3949ba59abbe56e057f20f883e', '18812345678', '', '', '2018-04-06 17:24:26', '', '0', '4');

-- ----------------------------
-- Table structure for `zjzz_juese`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_juese`;
CREATE TABLE `zjzz_juese` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `create_ts` datetime NOT NULL COMMENT '创建时间',
  `qx_list` varchar(100) NOT NULL COMMENT '左侧菜单栏权限列表',
  `ms` varchar(100) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_juese
-- ----------------------------
INSERT INTO `zjzz_juese` VALUES ('1', '普通老师', '2018-04-06 14:24:41', 'class,userkq,getqjjl', 'www');
INSERT INTO `zjzz_juese` VALUES ('4', '测试', '2018-04-06 16:51:15', 'mangeruser,role,menu,class,white,userkq,getqjjl', '测试哥');

-- ----------------------------
-- Table structure for `zjzz_kq`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_kq`;
CREATE TABLE `zjzz_kq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xs_id` int(11) NOT NULL COMMENT '学生id',
  `kq_lx` int(1) NOT NULL DEFAULT '0' COMMENT '0为日常考勤，1为周末考勤',
  `create_ts` datetime NOT NULL COMMENT '考勤时间',
  `gps` varchar(100) DEFAULT NULL COMMENT 'gps',
  `kq_dz` varchar(500) DEFAULT NULL COMMENT '考勤地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_kq
-- ----------------------------
INSERT INTO `zjzz_kq` VALUES ('1', '200', '0', '2018-04-05 17:07:40', '101,90', '学校门口');
INSERT INTO `zjzz_kq` VALUES ('2', '200', '1', '2018-04-05 17:23:49', '101,90', '学校门口');
INSERT INTO `zjzz_kq` VALUES ('8', '200', '1', '2018-04-15 10:59:54', '11', '11');
INSERT INTO `zjzz_kq` VALUES ('7', '200', '0', '2018-04-15 10:57:17', '11', '11');
INSERT INTO `zjzz_kq` VALUES ('9', '200', '1', '2018-04-14 12:56:38', '11', '11');

-- ----------------------------
-- Table structure for `zjzz_qj`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_qj`;
CREATE TABLE `zjzz_qj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xs_id` int(11) NOT NULL COMMENT '学生id',
  `qj_sj` varchar(100) NOT NULL COMMENT '请假时间 Y-m-d至Y-m-d',
  `qj_yy` varchar(200) NOT NULL COMMENT '请假原因',
  `qj_nr` varchar(200) DEFAULT NULL,
  `js_bm` varchar(50) NOT NULL COMMENT '教师id',
  `sf_ty` int(11) NOT NULL DEFAULT '0' COMMENT '是否同意：0等待审核-1不同意1同意',
  `sh_yj` text COMMENT '审核意见',
  `ewm_url` text COMMENT '二维码url',
  `create_ts` datetime NOT NULL COMMENT '申请时间',
  `sh_sj` datetime DEFAULT NULL COMMENT '审核时间',
  `sq_img` text COMMENT '申请图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_qj
-- ----------------------------
INSERT INTO `zjzz_qj` VALUES ('1', '200', '2018-04-05 17:07:40-2018-04-05 17:07:40', '试试看', null, '001', '1', '啧啧啧', 'https://cn.vuejs.org/images/logo.png', '2018-04-06 10:32:03', '2018-04-06 11:32:06', null);
INSERT INTO `zjzz_qj` VALUES ('2', '200', '2018-04-15 14:25-2018-04-21 14:25', '12121', '我去我去·', '002', '0', '', '', '2018-04-15 15:21:55', '2018-04-15 15:21:55', null);
INSERT INTO `zjzz_qj` VALUES ('3', '200', '2018-04-15 14:25-2018-04-21 14:25', '12121', '我去我去·', '002', '0', '', '', '2018-04-15 15:22:09', '2018-04-15 15:22:09', null);
INSERT INTO `zjzz_qj` VALUES ('4', '200', '2018-04-15 15:23-2018-04-20 15:23', '121', '2121', '002', '0', '', '', '2018-04-15 15:23:53', '2018-04-15 15:23:53', null);
INSERT INTO `zjzz_qj` VALUES ('5', '200', '2018-04-15 15:24-2018-04-30 15:24', '12', '212', '002', '0', '', '', '2018-04-15 15:24:42', '2018-04-15 15:24:42', null);
INSERT INTO `zjzz_qj` VALUES ('6', '200', '2018-04-15 18:41-2018-04-28 18:42', '1212', '这是标题', '002', '0', '', '', '2018-04-15 18:42:06', '2018-04-15 18:42:06', 'Array');
INSERT INTO `zjzz_qj` VALUES ('7', '200', '2018-04-15 18:41-2018-04-28 18:42', '1212', '这是标题', '002', '0', '', '', '2018-04-15 18:42:27', '2018-04-15 18:42:27', '[{\"url\":\"http:\\/\\/127.0.0.1:8180\\/api\\/upload\\/img\\/201804151841557147.png\"},{\"url\":\"http:\\/\\/127.0.0.1:8180\\/api\\/upload\\/img\\/20180415184157693.png\"},{\"url\":\"http:\\/\\/127.0.0.1:8180\\/api\\/upload\\/img\\/201804151841588216.png\"}]');

-- ----------------------------
-- Table structure for `zjzz_sm`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_sm`;
CREATE TABLE `zjzz_sm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_nr` text COMMENT '扫码内容',
  `create_ts` datetime NOT NULL COMMENT '扫码时间',
  `qj_id` int(11) DEFAULT NULL COMMENT '请假id',
  `kq_id` int(11) DEFAULT NULL COMMENT '考勤id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_sm
-- ----------------------------

-- ----------------------------
-- Table structure for `zjzz_xs`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_xs`;
CREATE TABLE `zjzz_xs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dhbmd_id` int(11) NOT NULL COMMENT '白名单ID',
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信ID',
  `wxnc` varchar(50) DEFAULT NULL COMMENT '昵称',
  `create_ts` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dhbmd` (`dhbmd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_xs
-- ----------------------------
INSERT INTO `zjzz_xs` VALUES ('1', '3', '', '', '2018-04-14 21:00:45');
INSERT INTO `zjzz_xs` VALUES ('2', '5', '', '', '2018-04-14 21:03:54');
INSERT INTO `zjzz_xs` VALUES ('3', '7', '111', '', '2018-04-14 21:06:45');
INSERT INTO `zjzz_xs` VALUES ('4', '8', '', '', '2018-04-14 21:07:32');

-- ----------------------------
-- Table structure for `zjzz_yzm`
-- ----------------------------
DROP TABLE IF EXISTS `zjzz_yzm`;
CREATE TABLE `zjzz_yzm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yzm` varchar(10) DEFAULT NULL,
  `sjhm` varchar(20) DEFAULT NULL,
  `create_ts` datetime DEFAULT NULL,
  `is_use` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of zjzz_yzm
-- ----------------------------
INSERT INTO `zjzz_yzm` VALUES ('3', '391235', '18888888888', '2018-04-14 21:00:38', '1');
INSERT INTO `zjzz_yzm` VALUES ('4', '208892', '18888888888', '2018-04-14 21:02:17', '1');
INSERT INTO `zjzz_yzm` VALUES ('5', '317932', '18888888888', '2018-04-14 21:03:45', '1');
INSERT INTO `zjzz_yzm` VALUES ('6', '032165', '18888888888', '2018-04-14 21:04:33', '1');
INSERT INTO `zjzz_yzm` VALUES ('7', '137600', '18888888888', '2018-04-14 21:06:34', '1');
INSERT INTO `zjzz_yzm` VALUES ('8', '248480', '18888888888', '2018-04-14 21:07:24', '1');
