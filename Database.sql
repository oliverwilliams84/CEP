-- phpMyAdmin SQL Dump
-- version 4.0.10.17
-- https://www.phpmyadmin.net
--
-- Host: mysql.dur.ac.uk
-- Generation Time: Nov 07, 2016 at 04:11 PM
-- Server version: 5.1.39-community-log
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Xdnfn65_Database`
--

-- ------------------------*NOTES*-------------------------

-- Need to setup relationships between entries but it's best to do this once the structure fully takes shape

-- Setup automatic pruning of old entries using maintenance scripts, possibly configurable through an admin account

-- Autonum for userID and foodID

-- Change text sizes in itemTable. Category is smaller than description, no idea how to do that in this file.

-- Made manual edits to this and it might not work, can always roll back

-- --------------------------------------------------------

--
-- Table structure for table `itemTable`
--

CREATE TABLE IF NOT EXISTS `itemTable` (
  `foodid` int(11) NOT NULL,
  `expirydate` date NOT NULL,
  `category` text NOT NULL,
  `userid` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `location` text NOT NULL,
  `amount` int(11) NOT NULL,
  `weight` FLOAT NOT NULL, 
  `image` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `hidden` tinyint(1) NOT NULL,  
  PRIMARY KEY (`foodid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messageTable`
--

CREATE TABLE IF NOT EXISTS `messageTable` (
  `messageid` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` TIMESTAMP NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `userMessageTable`
-- FK to messageTable
--

CREATE TABLE IF NOT EXISTS `userMessageTable` (
  `messageid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requestMessageTable`
-- FK to messageTable
--

CREATE TABLE IF NOT EXISTS `requestMessageTable` (
  `messageid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `requestid` int(11) NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

/* If this seems like overkill, let me explain.
 
Storing seen for each message can be a huge amount of data and is very liable to data corruption.
It may seem like storing this new table will take up a lot of space but it shouldn't be too large.

In return we get to just check the user's lastseen against the time of the message and don't get any database oddities.
Lookup times checking integers against integers is crazy fast. You could easily search 1 million entries in less than 50ms,
so receiving the entries form the chatTable won't be ap roblem

We can prune the database and archieve/delete old messages and chatTable entries using automatic scripts 
that arn't hard to setup, especially with this config, that will keep the DB size reasonable.

We can also prune all messages related to requests that are deleted instantly, and ones that are completed after a set time

*/

-- --------------------------------------------------------

--
-- Table structure for table `chatTable`
--

CREATE TABLE IF NOT EXISTS `chatTable` (
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `user1seen` TIMESTAMP,
  `user2seen` TIMESTAMP,
  PRIMARY KEY (`user1`,`user2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requestTable`
--

CREATE TABLE IF NOT EXISTS `requestTable` (
  `requestid` int(11) NOT NULL,
  `requester` int(11) NOT NULL,
  `foodid` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL,  
  PRIMARY KEY (`requestid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userTable`
--

CREATE TABLE IF NOT EXISTS `userTable` (
  `userid` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `picture` text,
  `email` text,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `authTable`
-- Holds authentication tokens so a user may stay logged in
-- We ask for username and password once and then exclusively use auth tokens
-- This means users can never spoof the userID being sent to request data and must always use their auth token
-- Stops problems such as a coder not realising and accepting any userID in say a message request function
-- That lets someone read anyone's messages. It can happen anytime and not having to validate input on every little thing
-- Is real nice
--

CREATE TABLE IF NOT EXISTS `authTable` (
  `userid` int(11) NOT NULL,
  `authToken` text NOT NULL,
  `expirDate` DATE NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
