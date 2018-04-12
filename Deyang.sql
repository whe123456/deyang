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

/*Table structure for table `sidebar_icon` */

DROP TABLE IF EXISTS `sidebar_icon`;

CREATE TABLE `sidebar_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT NULL COMMENT '字体图标，参照饿了么ui',
  `index` varchar(100) NOT NULL COMMENT '页面导航',
  `title` varchar(100) DEFAULT NULL COMMENT '页面名称',
  `parent` varchar(11) DEFAULT NULL COMMENT '父级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sidebar_icon` */

insert  into `sidebar_icon`(`id`,`icon`,`index`,`title`,`parent`) values (1,'el-icon-setting','1','业务管理','0'),(2,NULL,'class','班级管理','1'),(3,NULL,'white','白名单管理','1'),(4,NULL,'userkq','出勤统计','1'),(5,NULL,'getqjjl','请假统计','1'),(6,'el-icon-date','2','系统管理','0'),(7,NULL,'mangeruser','系统管理','2'),(8,NULL,'role','角色管理','2'),(9,NULL,'menu','菜单管理','2');

/*Table structure for table `zjzz_bj` */

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

/*Data for the table `zjzz_bj` */

insert  into `zjzz_bj`(`id`,`bj_bm`,`bj_mc`,`js_bh`,`js_bm`,`bz`) values (1,'001','中江1','001','001','随意'),(2,'002','中江2','002','002','随意'),(3,'003','中江3','003','003',NULL),(4,'004','中江4','004','004','随意'),(5,'005','中江5','005','005',NULL),(6,'006','中江6','006','006',NULL),(7,'007','中江7','007','007',NULL),(8,'008','中江8','008','008','随意'),(9,'009','中江9','009','009',NULL),(10,'010','中江10','010','010',NULL),(11,'011','中江11','011','011',NULL),(12,'012','0556','012','007','www');

/*Table structure for table `zjzz_dhbmd` */

DROP TABLE IF EXISTS `zjzz_dhbmd`;

CREATE TABLE `zjzz_dhbmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sjhm` varchar(11) NOT NULL COMMENT '手机号',
  `xm` varchar(100) NOT NULL COMMENT '姓名',
  `cj_time` datetime DEFAULT NULL COMMENT '创建时间',
  `sf_yz` int(11) NOT NULL COMMENT '0未验证1已验证',
  `yzm` varchar(50) DEFAULT NULL COMMENT '验证码',
  `yzsj` datetime DEFAULT NULL COMMENT '验证时间',
  `bjbm` varchar(50) NOT NULL COMMENT '班级编码',
  `xh` varchar(50) NOT NULL COMMENT '学号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_dhbmd` */

insert  into `zjzz_dhbmd`(`id`,`sjhm`,`xm`,`cj_time`,`sf_yz`,`yzm`,`yzsj`,`bjbm`,`xh`) values (1,'12345612345','我去我去1','2018-04-03 16:12:31',0,'','0000-00-00 00:00:00','001','002'),(7,'12345678900','测试哥','2018-04-03 17:59:43',0,'','0000-00-00 00:00:00','002','200');

/*Table structure for table `zjzz_js` */

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

/*Data for the table `zjzz_js` */

insert  into `zjzz_js`(`id`,`js_bm`,`xm`,`dl_mm`,`sjhm`,`wxid`,`wxnc`,`zc_ts`,`ewm_url`,`sf_zc`,`js_id`) values (1,'001','老王','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(2,'002','老2','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(3,'003','老3','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(4,'004','老4','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(5,'005','老5','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(6,'006','老6','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(7,'007','老7','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(8,'008','老8','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(9,'009','老9','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(10,'010','老10','e10adc3949ba59abbe56e057f20f883e','18812345678','16151132231331213213213123132132132132','啧啧',NULL,NULL,0,1),(11,'011','老11','e10adc3949ba59abbe56e057f20f883e','18812345678','','','2018-04-06 17:24:26','',0,4);

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

insert  into `zjzz_juese`(`id`,`name`,`create_ts`,`qx_list`,`ms`) values (1,'普通老师','2018-04-06 14:24:41','class,userkq,getqjjl','www'),(4,'测试','2018-04-06 16:51:15','mangeruser,role,menu,class,white,userkq,getqjjl','测试哥');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_kq` */

insert  into `zjzz_kq`(`id`,`xs_id`,`kq_lx`,`create_ts`,`gps`,`kq_dz`) values (1,200,0,'2018-04-05 17:07:40','101,90','学校门口'),(2,200,1,'2018-04-05 17:23:49','101,90','学校门口');

/*Table structure for table `zjzz_qj` */

DROP TABLE IF EXISTS `zjzz_qj`;

CREATE TABLE `zjzz_qj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xs_id` int(11) NOT NULL COMMENT '学生id',
  `qj_sj` varchar(100) NOT NULL COMMENT '请假时间 Y-m-d至Y-m-d',
  `qj_yy` varchar(200) NOT NULL COMMENT '请假原因',
  `js_bm` varchar(50) NOT NULL COMMENT '教师id',
  `sf_ty` int(11) NOT NULL DEFAULT '0' COMMENT '是否同意：0等待审核-1不同意1同意',
  `sh_yj` text COMMENT '审核意见',
  `ewm_url` text COMMENT '二维码url',
  `create_ts` datetime NOT NULL COMMENT '申请时间',
  `sh_sj` datetime DEFAULT NULL COMMENT '审核时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_qj` */

insert  into `zjzz_qj`(`id`,`xs_id`,`qj_sj`,`qj_yy`,`js_bm`,`sf_ty`,`sh_yj`,`ewm_url`,`create_ts`,`sh_sj`) values (1,200,'2018-04-05 17:07:40-2018-04-05 17:07:40','试试看','001',1,'啧啧啧','https://cn.vuejs.org/images/logo.png','2018-04-06 10:32:03','2018-04-06 11:32:06');

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
  `xh` varchar(50) DEFAULT NULL COMMENT '学号',
  `dhbmd_id` int(11) NOT NULL COMMENT '白名单ID',
  `wxid` varchar(100) DEFAULT NULL COMMENT '微信ID',
  `wxnc` varchar(50) DEFAULT NULL COMMENT '昵称',
  `create_ts` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dhbmd` (`dhbmd_id`),
  UNIQUE KEY `学号` (`xh`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Data for the table `zjzz_xs` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
