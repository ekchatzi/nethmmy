-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 26, 2012 at 12:51 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ethmmy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'announcement id',
  `poster` int(10) unsigned NOT NULL COMMENT 'user that posted announcement',
  `class` int(10) unsigned NOT NULL COMMENT 'class the announcement is about',
  `post_time` int(10) unsigned NOT NULL COMMENT 'unix time the announcement was posted',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT 'unix time the announcement was last updated',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'announcement header title',
  `text` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'body of annoument',
  `is_urgent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'flag that tells if announcement is urgent',
  PRIMARY KEY (`id`),
  KEY `poster` (`poster`,`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'class id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'class title',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'class description',
  `semesters` tinytext CHARACTER SET ascii NOT NULL COMMENT 'comma seperated list of semesters the class is avanaible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='classes table' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `class_associations`
--

CREATE TABLE IF NOT EXISTS `class_associations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'class association id',
  `user` int(11) NOT NULL COMMENT 'associated user id',
  `type` int(10) unsigned NOT NULL COMMENT 'association type',
  `class` int(11) NOT NULL COMMENT 'associated class id',
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `class_association_types`
--

CREATE TABLE IF NOT EXISTS `class_association_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'association type id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'association title',
  `priority` int(10) unsigned NOT NULL COMMENT 'how important is for the class',
  `permissions` tinytext CHARACTER SET ascii NOT NULL COMMENT 'access on classes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'file id',
  `name` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'filename',
  `full_path` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'full file path',
  `folder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id of parent_folder',
  `uploader` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user id of uploader',
  `upload_time` int(10) unsigned NOT NULL COMMENT 'upload unix time',
  `download_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'times the file was downloaded',
  PRIMARY KEY (`id`),
  KEY `folder` (`folder`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `file_folders`
--

CREATE TABLE IF NOT EXISTS `file_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'file folder id',
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'folder name',
  `class` int(11) NOT NULL COMMENT 'id of associated class',
  `public` int(11) NOT NULL COMMENT 'public flag',
  PRIMARY KEY (`id`),
  KEY `class` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE IF NOT EXISTS `labs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'lab id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'lab title',
  `class` int(10) unsigned NOT NULL COMMENT 'class of lab',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'about the lab',
  `folder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'file folder for lab',
  `creation_time` int(11) unsigned NOT NULL COMMENT 'creation unix timestamp',
  `update_time` int(10) unsigned NOT NULL COMMENT 'unix time lab was last updated',
  `register_expire` int(10) unsigned NOT NULL COMMENT 'unix time that registration expires',
  `upload_expire` int(10) unsigned NOT NULL COMMENT 'unix time that file uploading expires',
  `upload_limit` int(11) unsigned NOT NULL DEFAULT '1' COMMENT 'How many files can a team upload',
  `team_limit` int(10) unsigned NOT NULL COMMENT 'how many lab teams can exist ',
  `users_per_team_limit` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'how many users can be on the same team',
  `can_free_join` int(11) NOT NULL DEFAULT '1' COMMENT 'boolean value that tells if users can join any team or they have to join the first avainable',
  `can_make_new_teams` int(11) NOT NULL DEFAULT '1' COMMENT 'boolean value that tells if users can make new teams',
  `can_lock_teams` int(11) NOT NULL DEFAULT '1' COMMENT 'boolean value that tells if users can lock their teams',
  `last_no` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'last team no',
  PRIMARY KEY (`id`),
  KEY `class` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_teams`
--

CREATE TABLE IF NOT EXISTS `lab_teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'lab team id',
  `lab` int(10) unsigned NOT NULL COMMENT 'lab team associated lab id',
  `students` text CHARACTER SET ascii NOT NULL COMMENT 'comma seperated list of students',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'team title',
  `creation_time` int(11) NOT NULL COMMENT 'unix time the team was created',
  `update_time` int(11) NOT NULL COMMENT 'unix time the team was updated',
  `files` text CHARACTER SET ascii NOT NULL COMMENT 'comma seperated list of uploaded files',
  `is_locked` int(11) NOT NULL COMMENT 'flag that is 1 if team is locked, ie noone new can join',
  PRIMARY KEY (`id`),
  KEY `lab` (`lab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE IF NOT EXISTS `titles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'title id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'title text',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'title description',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'student id',
  `username` varchar(32) CHARACTER SET latin1 NOT NULL COMMENT 'student username',
  `password` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'hash of salted password',
  `first_name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'user first name',
  `last_name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'user last name',
  `aem` int(10) unsigned DEFAULT NULL COMMENT 'Arithmos eidikou mitrwou',
  `user_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User type code',
  `title` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'title id for titles table',
  `email` tinytext COLLATE utf8_unicode_ci COMMENT 'user email adress',
  `telephone` varchar(20) CHARACTER SET ascii DEFAULT NULL COMMENT 'telephone number',
  `website` tinytext COLLATE utf8_unicode_ci COMMENT 'user website url',
  `bio` text COLLATE utf8_unicode_ci COMMENT 'user biography',
  `registration_time` int(11) NOT NULL COMMENT 'unix timestamp of registration',
  `semester` int(11) DEFAULT NULL COMMENT 'semester of studies at registration',
  `last_login` int(11) DEFAULT NULL COMMENT 'unix timestamp of last login',
  `salt` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'random salt for security',
  `login_token` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'hash for login authentication',
  `is_active` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'active account flag',
  `last_remote_adress` varchar(39) COLLATE utf8_unicode_ci NOT NULL COMMENT 'last login ip adress , sufficient length for IPv6',
  `is_email_validated` int(11) NOT NULL DEFAULT '0' COMMENT 'flag for email validation',
  `semester_update_time` int(11) NOT NULL COMMENT 'unix timestamp of the last time the semester field was updated by the user',
  `classes` text COLLATE utf8_unicode_ci COMMENT 'comma seperated list of watched classes',
  `email_urgent` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'flag that tells if user wants to be notified by email for urgent messages',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `aem` (`aem`),
  KEY `login_token` (`login_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users table' AUTO_INCREMENT=10 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
