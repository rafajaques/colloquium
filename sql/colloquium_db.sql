-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2013 at 02:12 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `colloquium_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `conference`
--

CREATE TABLE `conference` (
  `conference_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_name` varchar(150) DEFAULT NULL,
  `description` text,
  `registration_fee` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `gmt` varchar(5) DEFAULT NULL,
  `show_running` tinyint(1) unsigned DEFAULT '0',
  `active` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`conference_id`),
  KEY `conference_author_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conference`
--


-- --------------------------------------------------------

--
-- Table structure for table `conference_attendee`
--

CREATE TABLE `conference_attendee` (
  `conference_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `register` datetime DEFAULT NULL,
  `accreditation` datetime DEFAULT NULL,
  `disclose_email` tinyint(1) unsigned DEFAULT NULL,
  KEY `atend_conf_fk_idx` (`conference_id`),
  KEY `atend_user_fk_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conference_attendee`
--


-- --------------------------------------------------------

--
-- Table structure for table `conference_schedule`
--

CREATE TABLE `conference_schedule` (
  `conference_id` int(11) unsigned NOT NULL,
  `starting_time` time DEFAULT NULL,
  `first_day` date DEFAULT NULL,
  `last_day` date DEFAULT NULL,
  `cfp_opened` datetime DEFAULT NULL,
  `cfp_closed` datetime DEFAULT NULL,
  `registration_opened` datetime NOT NULL,
  `registration_closed` datetime NOT NULL,
  KEY `schedule_idx` (`conference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conference_schedule`
--


-- --------------------------------------------------------

--
-- Table structure for table `conference_staff`
--

CREATE TABLE `conference_staff` (
  `conference_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  KEY `staff_conf_fk_idx` (`conference_id`),
  KEY `staff_user_fk_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conference_staff`
--


-- --------------------------------------------------------

--
-- Table structure for table `conference_track`
--

CREATE TABLE `conference_track` (
  `track_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(11) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`track_id`),
  KEY `track_conf_fk_idx` (`conference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conference_track`
--


-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE `submission` (
  `submission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(10) unsigned NOT NULL,
  `track_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text,
  `minicurriculo` text,
  `duration` tinyint(1) unsigned DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `accepted` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`submission_id`),
  KEY `subs_conf_fk_idx` (`conference_id`),
  KEY `subs_track_fk` (`track_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `submission`
--


-- --------------------------------------------------------

--
-- Table structure for table `submission_user`
--

CREATE TABLE `submission_user` (
  `submission_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status` enum('confirmed','pending') NOT NULL,
  `main` tinyint(1) DEFAULT '0',
  KEY `conf_user_fk_idx` (`submission_id`),
  KEY `user_submis_fk_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `submission_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `recovery_hash` varchar(150) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `role_id` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `parent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` VALUES('admin', 0, 'staff');
INSERT INTO `user_role` VALUES('guest', 1, NULL);
INSERT INTO `user_role` VALUES('staff', 0, 'user');
INSERT INTO `user_role` VALUES('user', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_linker`
--

CREATE TABLE `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_fk_idx` (`role_id`),
  KEY `user_role_fk_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role_linker`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `conference`
--
ALTER TABLE `conference`
  ADD CONSTRAINT `conference_author` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conference_attendee`
--
ALTER TABLE `conference_attendee`
  ADD CONSTRAINT `atend_conf_fk` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`conference_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atend_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conference_schedule`
--
ALTER TABLE `conference_schedule`
  ADD CONSTRAINT `schedule_fk` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`conference_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conference_staff`
--
ALTER TABLE `conference_staff`
  ADD CONSTRAINT `staff_conf_fk` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`conference_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `staff_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conference_track`
--
ALTER TABLE `conference_track`
  ADD CONSTRAINT `track_conf_fk` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`conference_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `subs_conf_fk` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`conference_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `subs_track_fk` FOREIGN KEY (`track_id`) REFERENCES `conference_track` (`track_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `submission_user`
--
ALTER TABLE `submission_user`
  ADD CONSTRAINT `submission_fk` FOREIGN KEY (`submission_id`) REFERENCES `submission` (`submission_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_submis_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_role_linker`
--
ALTER TABLE `user_role_linker`
  ADD CONSTRAINT `role_user_fk` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_role_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
