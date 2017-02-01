/*
SQLyog Ultimate - MySQL GUI v8.2 
MySQL - 5.6.16 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `users` (
	`id` double ,
	`user_id` varchar (150),
	`password` varchar (150),
	`email` varchar (150),
	`age` varchar (45),
	`registerdate` varchar (45),
	`gender` varchar (150),
	`name` varchar (150),
	`surname` varchar (150),
	`icon` blob ,
	`city` varchar (150),
	`last_date` varchar (45),
	`usertype` double ,
	`ipno` varchar (150),
	`ban` double ,
	`social` varchar (750),
	`socialtype` varchar (45),
	`seoslug` varchar (750),
	`about` varchar (765)
); 
insert into `users` (`id`, `user_id`, `password`, `email`, `age`, `registerdate`, `gender`, `name`, `surname`, `icon`, `city`, `last_date`, `usertype`, `ipno`, `ban`, `social`, `socialtype`, `seoslug`, `about`) values('1','admin','6d650aa053bfead73e59a9d93d3ef0f0',NULL,NULL,'20170122132132',NULL,NULL,NULL,NULL,NULL,'20170131083054','1','192.168.18.90','0',NULL,NULL,'admin',NULL);
insert into `users` (`id`, `user_id`, `password`, `email`, `age`, `registerdate`, `gender`, `name`, `surname`, `icon`, `city`, `last_date`, `usertype`, `ipno`, `ban`, `social`, `socialtype`, `seoslug`, `about`) values('2','javid.ansari','e10adc3949ba59abbe56e057f20f883e','javid.ansari@itp.com','','20170122072937','','','','','','20170122072937','0','::1','0','','','javidansari',NULL);
insert into `users` (`id`, `user_id`, `password`, `email`, `age`, `registerdate`, `gender`, `name`, `surname`, `icon`, `city`, `last_date`, `usertype`, `ipno`, `ban`, `social`, `socialtype`, `seoslug`, `about`) values('3','javid-ansari','e10adc3949ba59abbe56e057f20f883e','javid.ansari123@gmail.com','','20170124022016','','','','','','20170124022016','5','192.168.18.90','0','','','javid-ansari',NULL);
