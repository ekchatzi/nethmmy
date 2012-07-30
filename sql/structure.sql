-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 30, 2012 at 02:24 PM
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
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'class id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'class title',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'class description',
  `semesters` tinytext CHARACTER SET ascii NOT NULL COMMENT 'comma seperated list of semesters the class is avanaible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='classes table' AUTO_INCREMENT=5 ;

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
  KEY `class` (`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `class_association_types`
--

CREATE TABLE IF NOT EXISTS `class_association_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'association type id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'association title',
  `priority` int(10) unsigned NOT NULL COMMENT 'how important is for the class',
  `permissions` varchar(20) CHARACTER SET ascii NOT NULL COMMENT 'access on classes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE IF NOT EXISTS `titles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'title id',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'title text',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'title description',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `aem` (`aem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users table' AUTO_INCREMENT=6 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
