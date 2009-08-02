-- phpMyAdmin SQL Dump
-- version 3.2.0.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2009 at 01:42 AM
-- Server version: 5.0.83
-- PHP Version: 5.2.10-1ubuntu1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `tourney`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `scoringtype` varchar(128) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`, `description`, `scoringtype`) VALUES
(2, 'Super Puzzle Fighter (Arcade)', 'The best puzzle game of all time', 'Model_VictoryCondition_HighestScore'),
(4, 'Street Fighter 2 (Arcade)', 'The greatest fighter of all time', 'Model_VictoryCondition_HighestScore');

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE IF NOT EXISTS `match` (
  `id` int(11) NOT NULL auto_increment,
  `tourneyid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `scheduletime` int(11) NOT NULL,
  `playtime` int(11) NOT NULL,
  `data` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `match`
--

INSERT INTO `match` (`id`, `tourneyid`, `gameid`, `scheduletime`, `playtime`, `data`) VALUES
(1, 1, 4, 0, 0, 'matchtype:tree;'),
(2, 1, 4, 0, 0, 'matchtype:tree;'),
(3, 1, 4, 0, 0, 'matchtype:tree;left:1;right:2;'),
(4, 1, 4, 0, 0, 'matchtype:tree;'),
(5, 1, 4, 0, 0, 'matchtype:tree;'),
(6, 1, 4, 0, 0, 'matchtype:tree;left:4;right:5;'),
(7, 1, 4, 0, 0, 'matchtype:tree;left:3;right:6;root:true;');

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(11) NOT NULL auto_increment,
  `matchid` int(11) NOT NULL,
  `participantid` varchar(128) NOT NULL,
  `type` varchar(128) NOT NULL,
  `score` float NOT NULL,
  `result` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `data` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `participant`
--

INSERT INTO `participant` (`id`, `matchid`, `participantid`, `type`, `score`, `result`, `draw`, `data`) VALUES
(1, 1, 'test5', 'Model_User', 1, 2, 0, ''),
(2, 1, 'beefsack', 'Model_User', 2, 1, 0, ''),
(3, 2, 'test6', 'Model_User', 5, 1, 0, ''),
(4, 2, 'test4', 'Model_User', 3, 2, 0, ''),
(5, 3, 'beefsack', 'Model_User', 3, 1, 0, 'source:1;sourcetype:winner;'),
(6, 3, 'test6', 'Model_User', 2, 2, 0, 'source:2;sourcetype:winner;'),
(7, 4, 'test1', 'Model_User', 7, 2, 0, ''),
(8, 4, 'test2', 'Model_User', 55, 1, 0, ''),
(9, 5, 'test3', 'Model_User', 1, 2, 0, ''),
(10, 5, 'baconheist', 'Model_User', 4, 1, 0, ''),
(11, 6, 'test2', 'Model_User', 4, 2, 0, 'source:4;sourcetype:winner;'),
(12, 6, 'baconheist', 'Model_User', 7, 1, 0, 'source:5;sourcetype:winner;'),
(13, 7, 'beefsack', 'Model_User', 2, 1, 0, 'source:3;sourcetype:winner;'),
(14, 7, 'baconheist', 'Model_User', 1, 2, 0, 'source:6;sourcetype:winner;');

-- --------------------------------------------------------

--
-- Table structure for table `tourney`
--

CREATE TABLE IF NOT EXISTS `tourney` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `data` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tourney`
--

INSERT INTO `tourney` (`id`, `type`, `name`, `data`) VALUES
(1, 'Model_Type_SingleElimination', 'fdsafdsa', 'gameid:4;matchuptype:Model_MatchupType_Random;');

-- --------------------------------------------------------

--
-- Table structure for table `tourneyadmin`
--

CREATE TABLE IF NOT EXISTS `tourneyadmin` (
  `tourneyid` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY  (`tourneyid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tourneyadmin`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`name`, `password`) VALUES
('test1', ''),
('test2', ''),
('test3', ''),
('test4', ''),
('test5', ''),
('test6', ''),
('test7', ''),
('test8', ''),
('test9', ''),
('test10', ''),
('beefsack', ''),
('baconheist', '');
