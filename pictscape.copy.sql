-- MySQL dump 10.11
--
-- Host: localhost    Database: pictscape
-- ------------------------------------------------------
-- Server version	5.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catg_img_info`
--

DROP TABLE IF EXISTS `catg_img_info`;
CREATE TABLE `catg_img_info` (
  `catg_info_id` int(11) NOT NULL auto_increment,
  `catg_master_id` int(11) NOT NULL,
  `sub_catg_name_id` int(11) NOT NULL,
  `img_mng_id` int(11) NOT NULL,
  PRIMARY KEY  (`catg_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catg_img_info`
--

LOCK TABLES `catg_img_info` WRITE;
/*!40000 ALTER TABLE `catg_img_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `catg_img_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catg_info`
--

DROP TABLE IF EXISTS `catg_info`;
CREATE TABLE `catg_info` (
  `catg_info_id` int(11) NOT NULL auto_increment,
  `catg_master_id` int(11) NOT NULL,
  `sub_catg_name_id` int(11) NOT NULL,
  PRIMARY KEY  (`catg_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catg_info`
--

LOCK TABLES `catg_info` WRITE;
/*!40000 ALTER TABLE `catg_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `catg_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catg_master`
--

DROP TABLE IF EXISTS `catg_master`;
CREATE TABLE `catg_master` (
  `catg_master_id` int(11) NOT NULL auto_increment,
  `catg_name` text NOT NULL,
  `del_flag` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`catg_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catg_master`
--

LOCK TABLES `catg_master` WRITE;
/*!40000 ALTER TABLE `catg_master` DISABLE KEYS */;
INSERT INTO `catg_master` VALUES (1,'未登録','N'),(2,'NEWS','N');
/*!40000 ALTER TABLE `catg_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `img_master`
--

DROP TABLE IF EXISTS `img_master`;
CREATE TABLE `img_master` (
  `img_mng_id` int(11) NOT NULL auto_increment,
  `img_org_name` text NOT NULL,
  `img_alt_name` text NOT NULL,
  `insert_date` datetime default NULL,
  `del_flag` char(1) NOT NULL default 'N',
  `app_flag` char(1) NOT NULL default 'Y',
  `img_DateTimeOriginal` datetime default NULL,
  `img_save_name` text,
  PRIMARY KEY  (`img_mng_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `img_master`
--

LOCK TABLES `img_master` WRITE;
/*!40000 ALTER TABLE `img_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `img_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_master`
--

DROP TABLE IF EXISTS `setting_master`;
CREATE TABLE `setting_master` (
  `setting_master_id` int(11) NOT NULL auto_increment,
  `setting_img_size` int(11) NOT NULL,
  `setting_thum_size` int(11) NOT NULL,
  `setting_jpg_quality` int(11) NOT NULL,
  `setting_app_path` text,
  `setting_upload_path` text,
  PRIMARY KEY  (`setting_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_master`
--

LOCK TABLES `setting_master` WRITE;
/*!40000 ALTER TABLE `setting_master` DISABLE KEYS */;
INSERT INTO `setting_master` VALUES (1,600,150,80,'/home/angeltale/public_html/','/home/angeltale/public_html/pictscape/upload/');
/*!40000 ALTER TABLE `setting_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_catg_name`
--

DROP TABLE IF EXISTS `sub_catg_name`;
CREATE TABLE `sub_catg_name` (
  `sub_catg_name_id` int(11) NOT NULL auto_increment,
  `sub_catg_name` text NOT NULL,
  `del_flag` char(1) NOT NULL,
  PRIMARY KEY  (`sub_catg_name_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_catg_name`
--

LOCK TABLES `sub_catg_name` WRITE;
/*!40000 ALTER TABLE `sub_catg_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `sub_catg_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thum_info`
--

DROP TABLE IF EXISTS `thum_info`;
CREATE TABLE `thum_info` (
  `thum_info_id` int(11) NOT NULL auto_increment,
  `img_name` text,
  `thum_name` text,
  PRIMARY KEY  (`thum_info_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thum_info`
--

LOCK TABLES `thum_info` WRITE;
/*!40000 ALTER TABLE `thum_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `thum_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_auth`
--

DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE `user_auth` (
  `user_auth_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(255) NOT NULL,
  `passwd` text NOT NULL,
  `del_flag` char(1) NOT NULL default 'N',
  `insert_date` date default NULL,
  PRIMARY KEY  (`user_auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_auth`
--

LOCK TABLES `user_auth` WRITE;
/*!40000 ALTER TABLE `user_auth` DISABLE KEYS */;
INSERT INTO `user_auth` VALUES (1,'admin','admin123','N',NULL);
/*!40000 ALTER TABLE `user_auth` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-09-09  0:33:34
