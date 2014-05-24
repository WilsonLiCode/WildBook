-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2014 at 10:34 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wildbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `aname` varchar(50) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`aid`, `aname`) VALUES
(1, 'Snowboarding'),
(2, 'Surfing'),
(3, 'Basketball');

-- --------------------------------------------------------

--
-- Table structure for table `diarycomments`
--

CREATE TABLE IF NOT EXISTS `diarycomments` (
  `dcid` int(11) NOT NULL AUTO_INCREMENT,
  `deid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `body` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dcid`),
  KEY `deid` (`deid`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `diarycomments`
--

INSERT INTO `diarycomments` (`dcid`, `deid`, `username`, `body`, `timestamp`) VALUES
(1, 1, 'sprayerftw', 'LOL I agree!', '2014-04-14 00:34:34'),
(2, 1, 'xxsonz', '@sprayerftw I know right, god I am so bad!', '2014-04-14 00:35:14'),
(3, 2, 'randomimba', 'hehe', '2014-04-14 00:50:50'),
(4, 2, 'adeezeyfresh', 'Keepo', '2014-04-14 00:50:50'),
(5, 2, 'xxsonz', 'wtf you got more comments than me', '2014-04-14 00:51:16'),
(6, 2, 'sprayerftw', 'of course, i''m better', '2014-04-14 00:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `diaryentry`
--

CREATE TABLE IF NOT EXISTS `diaryentry` (
  `deid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` varchar(500) NOT NULL,
  `mid` int(11) DEFAULT NULL,
  `lid` int(11) DEFAULT NULL,
  `privacy` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`deid`),
  KEY `username` (`username`),
  KEY `lid` (`lid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `diaryentry`
--

INSERT INTO `diaryentry` (`deid`, `username`, `title`, `body`, `mid`, `lid`, `privacy`, `timestamp`) VALUES
(1, 'xxsonz', 'Story of My Life', 'gasdfagsdfasdg', NULL, 1, 'Public', '2014-05-10 22:11:11'),
(2, 'sprayerftw', 'asgsadf', 'Kappa', NULL, NULL, 'FOF', '2014-05-10 23:53:22'),
(14, 'sprayerftw', 'pic', 'pic', 8, 1, 'Public', '2014-05-10 22:27:14'),
(15, 'randomimba', 'vid', 'vid', 10, NULL, 'FOF', '2014-05-11 03:23:47'),
(16, 'randomimba', 'pub', 'pub', NULL, NULL, 'Public', '2014-05-11 17:23:32'),
(17, 'randomimba', 'priv', 'priv', NULL, NULL, 'Private', '2014-05-11 17:23:42'),
(18, 'randomimba', 'homie', 'homie', NULL, NULL, 'Friends', '2014-05-11 17:23:53');

-- --------------------------------------------------------

--
-- Table structure for table `friendship`
--

CREATE TABLE IF NOT EXISTS `friendship` (
  `username` varchar(50) NOT NULL,
  `friend` varchar(50) NOT NULL,
  `privacy` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`,`friend`),
  KEY `friend` (`friend`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friendship`
--

INSERT INTO `friendship` (`username`, `friend`, `privacy`, `timestamp`) VALUES
('adeezeyfresh', 'sprayerftw', 'Public', '2014-04-01 14:23:21'),
('mystic', 'sprayerftw', 'Public', '2014-05-10 02:55:48'),
('randomimba', 'sprayerftw', 'Public', '2014-04-03 15:25:28'),
('sprayerftw', 'adeezeyfresh', 'Public', '2014-04-01 14:23:21'),
('sprayerftw', 'randomimba', 'Public', '2014-04-03 15:25:28'),
('sprayerftw', 'xxsonz', 'Public', '2014-04-14 00:33:13'),
('xxsonz', 'sprayerftw', 'Public', '2014-04-14 00:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `likeactivity`
--

CREATE TABLE IF NOT EXISTS `likeactivity` (
  `username` varchar(50) NOT NULL,
  `aid` int(11) NOT NULL,
  PRIMARY KEY (`username`,`aid`),
  KEY `username` (`username`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likeactivity`
--

INSERT INTO `likeactivity` (`username`, `aid`) VALUES
('sprayerftw', 1),
('xxsonz', 2),
('sprayerftw', 3);

-- --------------------------------------------------------

--
-- Table structure for table `likediary`
--

CREATE TABLE IF NOT EXISTS `likediary` (
  `deid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`deid`,`username`),
  KEY `deid` (`deid`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likediary`
--

INSERT INTO `likediary` (`deid`, `username`) VALUES
(1, 'sprayerftw'),
(1, 'xxsonz');

-- --------------------------------------------------------

--
-- Table structure for table `likelocation`
--

CREATE TABLE IF NOT EXISTS `likelocation` (
  `username` varchar(50) NOT NULL,
  `aid` int(11) NOT NULL,
  `lid` int(11) NOT NULL,
  PRIMARY KEY (`username`,`aid`,`lid`),
  KEY `username` (`username`),
  KEY `aid` (`aid`),
  KEY `lid` (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likelocation`
--

INSERT INTO `likelocation` (`username`, `aid`, `lid`) VALUES
('sprayerftw', 1, 1),
('adeezeyfresh', 2, 2),
('sprayerftw', 3, 3),
('sprayerftw', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `lname` varchar(50) NOT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`lid`, `lname`, `longitude`, `latitude`) VALUES
(1, 'Brooklyn', 43.1579464, 44.161654657),
(2, 'Log Angeles', 34.08, 118.15),
(3, 'Testing Place', 123.189, 25.487),
(4, 'Poly', -73.986706, 40.694011);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `meid` int(11) NOT NULL AUTO_INCREMENT,
  `fromuser` varchar(50) NOT NULL,
  `touser` varchar(50) NOT NULL,
  `body` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`meid`),
  KEY `fromuser` (`fromuser`),
  KEY `touser` (`touser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`meid`, `fromuser`, `touser`, `body`, `timestamp`) VALUES
(1, 'sprayerftw', 'sprayerftw', 'hi', '2014-05-12 22:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `multimedia`
--

CREATE TABLE IF NOT EXISTS `multimedia` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `filetype` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `privacy` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `multimedia`
--

INSERT INTO `multimedia` (`mid`, `filetype`, `username`, `privacy`, `timestamp`) VALUES
(8, 'image/gif', 'sprayerftw', 'Public', '2014-05-10 22:27:14'),
(9, 'image/jpeg', 'sprayerftw', 'Public', '2014-05-10 22:51:26'),
(10, 'video/mp4', 'randomimba', 'Public', '2014-05-11 03:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `username` varchar(50) NOT NULL,
  `bio` varchar(5000) NOT NULL,
  `privacy` varchar(50) NOT NULL,
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`username`, `bio`, `privacy`) VALUES
('xxsonz', 'test', 'Public'),
('mystic', 'Cali boy... what else?', 'Public'),
('adeezeyfresh', 'what up bro', 'FOF'),
('randomimba', 'haha miami sucks!', 'Public'),
('sprayerftw', 'Born in 1994, grew up in Brooklyn. Went to middle school at Dyker Heights, and Leon M. Goldstein High School. Currently attending NYU-Poly.', 'Public'),
('testuser', 'test', 'Public');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dob` date NOT NULL,
  `lastaccessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `fname`, `lname`, `city`, `dob`, `lastaccessed`) VALUES
('adeezeyfresh', 'andy', 'Andy', 'Liang', 'Brooklyn', '1994-03-18', '2014-05-11 18:04:05'),
('dyrus', 'marcus', 'Marcus', 'Hill', 'la', '1990-06-25', '2014-05-06 20:24:38'),
('mystic', 'daniel', 'Daniel', 'Geng', 'Mountain View', '1994-02-08', '2014-05-11 19:49:32'),
('powpanda', 'allen', 'Allen', 'Cao', 'Brooklyn', '1994-05-26', '2014-05-06 20:24:38'),
('randomimba', 'kenny', 'Kenny', 'Tan', 'Brooklyn', '1994-05-26', '2014-05-11 19:49:10'),
('sprayerftw', 'sui', 'Sui', 'Zhen', 'Brooklyn', '1994-03-15', '2014-05-12 22:15:40'),
('testuser', 'test', 'test', 'user', 'test island', '2014-01-01', '2014-05-12 01:50:56'),
('xxsonz', 'wilson', 'Wilson', 'Li', 'Queens', '1994-11-11', '2014-05-12 22:29:15');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diarycomments`
--
ALTER TABLE `diarycomments`
  ADD CONSTRAINT `diarycomments_ibfk_1` FOREIGN KEY (`deid`) REFERENCES `diaryentry` (`deid`),
  ADD CONSTRAINT `diarycomments_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Constraints for table `diaryentry`
--
ALTER TABLE `diaryentry`
  ADD CONSTRAINT `diaryentry_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `diaryentry_ibfk_2` FOREIGN KEY (`lid`) REFERENCES `location` (`lid`),
  ADD CONSTRAINT `diaryentry_ibfk_3` FOREIGN KEY (`mid`) REFERENCES `multimedia` (`mid`);

--
-- Constraints for table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`friend`) REFERENCES `user` (`username`);

--
-- Constraints for table `likeactivity`
--
ALTER TABLE `likeactivity`
  ADD CONSTRAINT `likeactivity_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `likeactivity_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `activity` (`aid`);

--
-- Constraints for table `likediary`
--
ALTER TABLE `likediary`
  ADD CONSTRAINT `likediary_ibfk_1` FOREIGN KEY (`deid`) REFERENCES `diaryentry` (`deid`),
  ADD CONSTRAINT `likediary_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Constraints for table `likelocation`
--
ALTER TABLE `likelocation`
  ADD CONSTRAINT `likelocation_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `likelocation_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `activity` (`aid`),
  ADD CONSTRAINT `likelocation_ibfk_3` FOREIGN KEY (`lid`) REFERENCES `location` (`lid`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`fromuser`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`touser`) REFERENCES `user` (`username`);

--
-- Constraints for table `multimedia`
--
ALTER TABLE `multimedia`
  ADD CONSTRAINT `multimedia_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
