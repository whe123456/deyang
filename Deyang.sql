/*
SQLyog Ultimate v11.27 (32 bit)
MySQL - 5.5.13-log : Database - Deyang_Occupation
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`Deyang_Occupation` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `Deyang_Occupation`;

/*Table structure for table `saoma_list` */

DROP TABLE IF EXISTS `saoma_list`;

CREATE TABLE `saoma_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qj_id` int(11) NOT NULL COMMENT '请假表id',
  `create_ts` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `saoma_list` */

/*Table structure for table `sidebar_icon` */

DROP TABLE IF EXISTS `sidebar_icon`;

CREATE TABLE `sidebar_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT NULL COMMENT '字体图标，参照饿了么ui',
  `index` varchar(100) NOT NULL COMMENT '页面导航',
  `title` varchar(100) DEFAULT NULL COMMENT '页面名称',
  `parent` varchar(11) DEFAULT NULL COMMENT '父级',
  `order` varchar(10) NOT NULL DEFAULT '0' COMMENT '排序设置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sidebar_icon` */

insert  into `sidebar_icon`(`id`,`icon`,`index`,`title`,`parent`,`order`) values (1,'el-icon-setting','1','业务管理','0','0'),(3,NULL,'white','白名单管理','1','0'),(4,NULL,'userkq','出勤统计','1','0'),(5,NULL,'getqjjl','请假统计','1','0'),(6,'el-icon-date','2','系统管理','0','0'),(7,NULL,'mangeruser','系统管理','2','0'),(8,NULL,'role','角色管理','2','0'),(9,NULL,'menu','菜单管理','2','0');

/*Table structure for table `wxid_b` */

DROP TABLE IF EXISTS `wxid_b`;

CREATE TABLE `wxid_b` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信id',
  `wxnc` varchar(100) DEFAULT NULL COMMENT '微信昵称',
  `create_ts` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `wxid_b` */

/*Table structure for table `zjzz_dhbmd` */

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
  `bj_mc` varchar(50) NOT NULL COMMENT '班级名称',
  `js_bh` varchar(50) NOT NULL COMMENT '教室编号',
  `grade` varchar(50) NOT NULL COMMENT '年级名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_dhbmd` */

/*Table structure for table `zjzz_js` */

DROP TABLE IF EXISTS `zjzz_js`;

CREATE TABLE `zjzz_js` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js_bm` varchar(50) NOT NULL COMMENT '教师编码',
  `xm` varchar(500) NOT NULL COMMENT '教师名称',
  `dl_mm` varchar(50) NOT NULL COMMENT '后台登陆密码',
  `sjhm` varchar(18) NOT NULL COMMENT '手机号',
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信ID',
  `zc_ts` datetime DEFAULT NULL COMMENT '注册时间',
  `sf_zc` int(1) DEFAULT '0' COMMENT '0未注册，1已注册',
  `js_id` int(11) DEFAULT '0' COMMENT '0为未指定角色，其余对应角色表',
  `bjbm` varchar(50) DEFAULT NULL COMMENT '学号',
  `bj_mc` varchar(50) DEFAULT NULL COMMENT '班级名称',
  `js_bh` varchar(50) DEFAULT NULL COMMENT '教室编号',
  `grade` varchar(50) DEFAULT NULL COMMENT '年级名称',
  PRIMARY KEY (`js_bm`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_js` */

insert  into `zjzz_js`(`id`,`js_bm`,`xm`,`dl_mm`,`sjhm`,`wxid`,`zc_ts`,`sf_zc`,`js_id`,`bjbm`,`bj_mc`,`js_bh`,`grade`) values (1,'001','老王','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(2,'002','老2','202cb962ac59075b964b07152d234b70','18812345678','222','2018-04-16 13:49:27',1,1,NULL,NULL,NULL,NULL),(3,'003','老3','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(4,'004','老4','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(5,'005','老5','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(6,'006','老6','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(7,'007','老7','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(8,'008','老8','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(9,'009','老9','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(10,'010','老10','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132',NULL,0,1,NULL,NULL,NULL,NULL),(11,'011','老11','e10adc3949ba59abbe56e057f20f883e','18812345678','','2018-04-06 17:24:26',0,4,NULL,NULL,NULL,NULL);

/*Table structure for table `zjzz_juese` */

DROP TABLE IF EXISTS `zjzz_juese`;

CREATE TABLE `zjzz_juese` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `create_ts` datetime NOT NULL COMMENT '创建时间',
  `qx_list` varchar(100) NOT NULL COMMENT '左侧菜单栏权限列表',
  `ms` varchar(100) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_juese` */

insert  into `zjzz_juese`(`id`,`name`,`create_ts`,`qx_list`,`ms`) values (1,'普通老师','2018-04-06 14:24:41','userkq,getqjjl','普通老师'),(4,'学生处','2018-04-06 16:51:15','white,userkq,getqjjl,mangeruser,role,menu','学生处');

/*Table structure for table `zjzz_kq` */

DROP TABLE IF EXISTS `zjzz_kq`;

CREATE TABLE `zjzz_kq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xs_id` int(11) NOT NULL COMMENT '学生id',
  `kq_lx` int(1) NOT NULL DEFAULT '0' COMMENT '0为日常考勤，1为周末考勤',
  `create_ts` datetime NOT NULL COMMENT '考勤时间',
  `gps` varchar(100) DEFAULT NULL COMMENT 'gps',
  `kq_dz` varchar(500) DEFAULT NULL COMMENT '考勤地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_kq` */

/*Table structure for table `zjzz_qj` */

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
  `jdc_teacher` varchar(50) DEFAULT NULL COMMENT '教导处审核教师id',
  `jdc_ty` int(11) DEFAULT '0' COMMENT '是否同意：0等待审核-1不同意1同意',
  `jdc_yj` text COMMENT '审核意见',
  `ec_sj` datetime DEFAULT NULL COMMENT '二次审核时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_qj` */

/*Table structure for table `zjzz_sm` */

DROP TABLE IF EXISTS `zjzz_sm`;

CREATE TABLE `zjzz_sm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_nr` text COMMENT '扫码内容',
  `create_ts` datetime NOT NULL COMMENT '扫码时间',
  `qj_id` int(11) DEFAULT NULL COMMENT '请假id',
  `kq_id` int(11) DEFAULT NULL COMMENT '考勤id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_sm` */

/*Table structure for table `zjzz_xs` */

DROP TABLE IF EXISTS `zjzz_xs`;

CREATE TABLE `zjzz_xs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dhbmd_id` int(11) NOT NULL COMMENT '白名单ID',
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信ID',
  `create_ts` datetime NOT NULL COMMENT '创建时间',
  `ewm_url` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dhbmd` (`dhbmd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_xs` */

/*Table structure for table `zjzz_yzm` */

DROP TABLE IF EXISTS `zjzz_yzm`;

CREATE TABLE `zjzz_yzm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yzm` varchar(10) DEFAULT NULL,
  `sjhm` varchar(20) DEFAULT NULL,
  `create_ts` datetime DEFAULT NULL,
  `is_use` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_yzm` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
