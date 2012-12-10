-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 28, 2007 at 07:23 AM
-- Server version: 4.0.15
-- PHP Version: 4.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `saps-gigs`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `answer`
-- 

CREATE TABLE `answer` (
  `answer_event` smallint(6) NOT NULL default '0',
  `answer_person` smallint(6) NOT NULL default '0',
  `answer_answer` tinyint(4) NOT NULL default '0',
  `answer_comments` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `answer`
-- 

INSERT INTO `answer` (`answer_event`, `answer_person`, `answer_answer`, `answer_comments`) VALUES 
(1, 1, 1, 'delicious!'),
(1, 2, 1, 'yay!'),
(5, 5, 1, 'can''t wait!'),
(1, 5, 0, ''),
(5, 1, 1, 'yum'),
(1, 3, 1, 'yum'),
(6, 3, 1, ''),
(5, 3, 0, 'maybe - I''ll let you know.'),
(5, 2, 3, 'I''ll know more soon'),
(7, 1, 2, 'woo! I love shakespeare. no really abcdefghijklmnopqrstuvwxyz'),
(7, 10, 1, ''),
(1, 10, 1, ''),
(5, 10, 1, ''),
(8, 10, 3, ''),
(7, 3, 1, ''),
(8, 3, 1, ''),
(8, 1, 4, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `event`
-- 

CREATE TABLE `event` (
  `event_id` smallint(6) NOT NULL auto_increment,
  `event_name` varchar(255) NOT NULL default '',
  `event_date` date NOT NULL default '0000-00-00',
  `event_call` varchar(25) default NULL,
  `event_details` varchar(255) default NULL,
  `event_confirmed` tinyint(4) NOT NULL default '0',
  `event_contact` tinyint(4) default NULL,
  UNIQUE KEY `event_id` (`event_id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `event`
-- 

INSERT INTO `event` (`event_id`, `event_name`, `event_date`, `event_call`, `event_details`, `event_confirmed`, `event_contact`) VALUES 
(1, 'Poppy''s Party', '2007-08-05', '4:00 or so', 'Playing at Poppy''s birthday party.\r\n\r\nShould be fun! Always is!\r\n\r\nyahoo!\r\n', 1, 3),
(7, 'Shakespeare Day', '2007-07-28', '', 'This is a batch of details and boy are my arms tired.\r\n\r\nla la la\r\n\r\nno, really!', 0, 3),
(5, 'Pig Pickin''', '2007-08-06', '6:00', 'This is on the street in front of Redbone''s - same place the bike thing was.', 1, 3),
(6, 'test old gig', '2007-01-01', '', '', 0, 3),
(8, 'Kennebunkport Protest', '2007-08-25', '', '\r\n', 1, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `person`
-- 

CREATE TABLE `person` (
  `person_id` tinyint(4) NOT NULL auto_increment,
  `person_name` varchar(255) NOT NULL default '',
  `person_phone` varchar(25) default NULL,
  `person_email` varchar(255) default NULL,
  `person_instrument` mediumint(9) default '0',
  UNIQUE KEY `person_id` (`person_id`),
  KEY `person_instrument` (`person_instrument`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;

-- 
-- Dumping data for table `person`
-- 

INSERT INTO `person` (`person_id`, `person_name`, `person_phone`, `person_email`, `person_instrument`) VALUES 
(1, 'Aaron', '617-864-3252', 'aoppenheimer@gmail.com', 60),
(2, 'Kevin', '123', 'kemon_lemon@gmail.com', 30),
(3, 'Maury', '', '', 10),
(5, 'Trudi', '', '', 50),
(6, 'John F.', '', '', 10),
(7, 'John B.', '', '', 30),
(8, 'Hepdog', '', '', 20),
(9, 'Michele', '', '', 20),
(10, 'Jim', '', '', 40),
(11, 'Kathleen', '', '', 20),
(12, 'Frank', '', '', 20),
(13, 'Bob', '', '', 30),
(14, 'Lydia', '', '', 70),
(15, 'Jamie', '', '', 10),
(16, 'Helen', '', '', 10),
(17, 'Rob', '', '', 999),
(18, 'Reebee', '', '', 50);
