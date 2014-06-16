-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 16, 2014 at 10:28 AM
-- Server version: 5.1.66
-- PHP Version: 5.3.3-7+squeeze16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `epigalerie`
--

-- --------------------------------------------------------

--
-- Table structure for table `adm_rights`
--

CREATE TABLE IF NOT EXISTS `adm_rights` (
  `adm_login` varchar(8) NOT NULL,
  `adm_rights` int(11) NOT NULL,
  PRIMARY KEY (`adm_login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adm_rights`
--

INSERT INTO `adm_rights` (`adm_login`, `adm_rights`) VALUES
('assens_b', 42),
('mombul_s', 42);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'fdf'),
(2, 'wolf3d'),
(3, 'rt');

-- --------------------------------------------------------

--
-- Table structure for table `pics`
--

CREATE TABLE IF NOT EXISTS `pics` (
  `pics_login` varchar(8) NOT NULL,
  `pics_promo` int(11) NOT NULL,
  `pics_ville` varchar(20) NOT NULL,
  `pics_valid` tinyint(4) NOT NULL,
  `pics_src` text NOT NULL,
  `pics_cat` int(11) NOT NULL,
  PRIMARY KEY (`pics_login`,`pics_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pics`
--


-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `vote_pic_login` varchar(8) NOT NULL,
  `vote_pic_cat` int(11) NOT NULL,
  `vote_user` varchar(8) NOT NULL,
  `vote_rank` int(11) NOT NULL,
  PRIMARY KEY (`vote_pic_login`,`vote_pic_cat`,`vote_user`,`vote_rank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes`
--

