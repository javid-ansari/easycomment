/*
SQLyog Ultimate - MySQL GUI v8.2 
MySQL - 5.6.16 : Database - ecomments
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ecomments` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ecomments`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` varchar(15) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `content_id` varchar(255) DEFAULT NULL,
  `content_ref` varchar(255) DEFAULT NULL,
  `domainaccess` varchar(255) DEFAULT NULL,
  `spoiler` int(1) NOT NULL DEFAULT '0',
  `approve` int(2) NOT NULL DEFAULT '0',
  `u_name` varchar(100) DEFAULT NULL,
  `u_email` varchar(100) DEFAULT NULL,
  `u_ip` varchar(100) DEFAULT NULL,
  `out_id` int(11) DEFAULT NULL,
  `out_name` varchar(100) DEFAULT NULL,
  `out_link` varchar(250) DEFAULT NULL,
  `out_icon` varchar(250) DEFAULT NULL,
  `site` varchar(55) DEFAULT 'ITP',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `content_id` (`content_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;

/*Table structure for table `flags` */

DROP TABLE IF EXISTS `flags`;

CREATE TABLE `flags` (
  `who` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL,
  `date` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `likes` */

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL,
  `likestype` varchar(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` text,
  `typelike` varchar(15) NOT NULL,
  `domainaccess` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `content_id` (`content_id`),
  KEY `likesitip` (`likestype`),
  KEY `typelike` (`typelike`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` text,
  `pagetitle` varchar(255) NOT NULL,
  `text` text,
  `content_type` varchar(20) NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `age` varchar(15) DEFAULT NULL,
  `registerdate` varchar(15) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `icon` text,
  `city` varchar(50) DEFAULT NULL,
  `last_date` varchar(15) DEFAULT NULL,
  `usertype` int(11) DEFAULT NULL,
  `ipno` varchar(50) DEFAULT NULL,
  `ban` int(11) DEFAULT NULL,
  `social` varchar(250) DEFAULT NULL,
  `socialtype` varchar(15) DEFAULT NULL,
  `seoslug` varchar(250) DEFAULT NULL,
  `about` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
