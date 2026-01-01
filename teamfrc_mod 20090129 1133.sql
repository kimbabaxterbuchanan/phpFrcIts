-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.27-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema teamfrc
--

CREATE DATABASE IF NOT EXISTS teamfrc;
USE teamfrc;

--
-- Definition of table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `companyNum` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `displayname` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

--
-- Definition of table `companyprofile`
--

DROP TABLE IF EXISTS `companyprofile`;
CREATE TABLE `companyprofile` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `companyId` int(10) unsigned NOT NULL,
  `street` varchar(45) NOT NULL,
  `mailStop` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `zipCode` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `fax` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companyprofile`
--


--
-- Definition of table `email`
--

DROP TABLE IF EXISTS `email`;
CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `seq_num` varchar(45) NOT NULL,
  `role` varchar(45) NOT NULL,
  `mailHost` varchar(120) NOT NULL,
  `isHTML` varchar(45) NOT NULL,
  `smtpAuth` varchar(45) NOT NULL,
  `Username` varchar(45) default NULL,
  `Password` varchar(45) default NULL,
  `toMail` varchar(2048) NOT NULL,
  `toName` varchar(45) NOT NULL,
  `fromMail` varchar(120) NOT NULL,
  `fromName` varchar(45) NOT NULL,
  `replyMail` varchar(45) NOT NULL,
  `replyName` varchar(120) NOT NULL,
  `ccMail` varchar(2048) NOT NULL,
  `ccName` varchar(2048) NOT NULL,
  `subject` varchar(2024) NOT NULL,
  `body` varchar(2048) NOT NULL,
  `altBody` varchar(2048) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email`
--

/*!40000 ALTER TABLE `email` DISABLE KEYS */;
/*!40000 ALTER TABLE `email` ENABLE KEYS */;


--
-- Definition of table `isawarded`
--

DROP TABLE IF EXISTS `isawarded`;
CREATE TABLE `isawarded` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `awarded` varchar(45) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `isawarded`
--


--
-- Definition of table `library`
--

DROP TABLE IF EXISTS `library`;
CREATE TABLE `library` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(45) NOT NULL,
  `isDirectory` varchar(45) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `notes` varchar(2048) default NULL,
  `userId` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `library`
--

/*!40000 ALTER TABLE `library` DISABLE KEYS */;
/*!40000 ALTER TABLE `library` ENABLE KEYS */;


--
-- Definition of table `libraryreference`
--

DROP TABLE IF EXISTS `libraryreference`;
CREATE TABLE `libraryreference` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libId` int(10) unsigned NOT NULL,
  `propId` int(10) unsigned NOT NULL,
  `rfqId` int(10) unsigned NOT NULL,
  `awardId` int(10) unsigned NOT NULL,
  `directory` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `size` varchar(45) NOT NULL,
  `posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `libraryreference`
--

/*!40000 ALTER TABLE `libraryreference` DISABLE KEYS */;
/*!40000 ALTER TABLE `libraryreference` ENABLE KEYS */;


--
-- Definition of table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) default '0',
  `careerId` int(11) default '0',
  `emailId` varchar(45) default NULL,
  `status` varchar(45) NOT NULL,
  `toMail` varchar(2048) NOT NULL,
  `replyMail` varchar(2048) NOT NULL,
  `ccMail` varchar(2048) default NULL,
  `message` varchar(2048) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `FK60142F1B9211AFB2` (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;


--
-- Definition of table `primecontacts`
--

DROP TABLE IF EXISTS `primecontacts`;
CREATE TABLE `primecontacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) collate latin1_general_ci NOT NULL,
  `poctype` varchar(45) collate latin1_general_ci NOT NULL,
  `sortinfo` varchar(45) collate latin1_general_ci NOT NULL,
  `companyId` int(10) unsigned NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `primecontacts`
--

/*!40000 ALTER TABLE `primecontacts` DISABLE KEYS */;
INSERT INTO `primecontacts` (`id`,`title`,`poctype`,`sortinfo`,`companyId`,`userId`,`create_dt`,`modified_dt`) VALUES
 (1,'','All','',1,3,'2008-10-25 08:56:24','2008-10-25 08:56:24'),
 (2,'Proposal Coordinator','All','2',1,2,'2008-10-25 08:56:24','2008-10-25 08:56:24'),
 (4,'Assistance Needed or Report Trouble','All','3',1,23,'2008-10-25 08:56:24','2008-10-25 08:56:24');
/*!40000 ALTER TABLE `primecontacts` ENABLE KEYS */;


--
-- Definition of table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
CREATE TABLE `proposals` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `docNumber` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `value` varchar(255) default NULL,
  `performancePeriod` varchar(255) default NULL,
  `received_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `questionDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `draftDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `finalDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `bid` varchar(45) default NULL,
  `results` varchar(45) default NULL,
  `notes` varchar(2048) default NULL,
  `teamName` varchar(45) NOT NULL,
  `userId` varchar(45) default NULL,
  `posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `companyId` int(10) unsigned default '0',
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `proposals`
--


--
-- Definition of table `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `id` int(11) NOT NULL auto_increment,
  `reportname` varchar(255) NOT NULL,
  `reportdescription` varchar(2048) NOT NULL,
  `reporttitle` varchar(255) NOT NULL,
  `selected_tables` varchar(255) default NULL,
  `reportsql` varchar(4096) NOT NULL,
  `displaytype` varchar(255) character set latin1 collate latin1_bin default NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--


--
-- Definition of table `toawards`
--

DROP TABLE IF EXISTS `toawards`;
CREATE TABLE `toawards` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `docNumber` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `value` varchar(255) default NULL,
  `performancePeriod` varchar(255) default NULL,
  `received_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `questionDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `draftDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `finalDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `bid` varchar(45) default NULL,
  `results` varchar(45) default NULL,
  `notes` varchar(2048) default NULL,
  `teamName` varchar(45) NOT NULL,
  `userId` varchar(45) default NULL,
  `posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `companyId` int(10) unsigned default '0',
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `toawards`
--

/*!40000 ALTER TABLE `toawards` DISABLE KEYS */;
/*!40000 ALTER TABLE `toawards` ENABLE KEYS */;


--
-- Definition of table `torfqs`
--

DROP TABLE IF EXISTS `torfqs`;
CREATE TABLE `torfqs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `docNumber` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `value` varchar(255) default NULL,
  `performancePeriod` varchar(255) default NULL,
  `received_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `questionDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `draftDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `finalDue_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `bid` varchar(45) default NULL,
  `results` varchar(45) default NULL,
  `notes` varchar(2048) default NULL,
  `teamName` varchar(45) NOT NULL,
  `userId` varchar(45) default NULL,
  `posted` datetime NOT NULL,
  `companyId` int(10) unsigned default '0',
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `torfqs`
--

/*!40000 ALTER TABLE `torfqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `torfqs` ENABLE KEYS */;


--
-- Definition of table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userNum` varchar(45) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `passwd` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `teamManager` varchar(45) NOT NULL,
  `webAdmin` varchar(45) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`,`userNum`,`firstname`,`lastname`,`passwd`,`login`,`teamManager`,`webAdmin`,`teamName`,`create_dt`,`modified_dt`) VALUES 
 (1,'9999999999','Admin','Admin','18c6d818ae35a3e8279b5330eda01498','admin','yes','yes','web','0000-00-00 00:00:00','2008-11-10 12:59:31'),


--
-- Definition of table `userprofile`
--

DROP TABLE IF EXISTS `userprofile`;
CREATE TABLE `userprofile` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userId` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `pocType` varchar(45) NOT NULL,
  `companyId` varchar(45) NOT NULL,
  `primecontact` varchar(45) default NULL,
  `create_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userprofile`
--

/*!40000 ALTER TABLE `userprofile` DISABLE KEYS */;
INSERT INTO `userprofile` (`id`,`userId`,`phone`,`email`,`pocType`,`companyId`,`primecontact`,`create_dt`,`modified_dt`) VALUES
 (1,'1','931-808-9961','kbuchanan@gmail.com','99','1','','0000-00-00 00:00:00','2008-11-03 13:15:21'),



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
