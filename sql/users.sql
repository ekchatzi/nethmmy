-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 02:57 PM
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
  `registration_semester` int(11) DEFAULT '1' COMMENT 'semester of studies at registration',
  `last_login` int(11) DEFAULT NULL COMMENT 'unix timestamp of last login',
  `salt` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'random salt for security',
  `login_token` varchar(64) CHARACTER SET ascii NOT NULL COMMENT 'hash for login authentication',
  `is_active` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'active account flag',
  `last_remote_adress` varchar(39) COLLATE utf8_unicode_ci NOT NULL COMMENT 'last login ip adress , sufficient length for IPv6',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `aem` (`aem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='users table' AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
