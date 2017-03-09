-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2017 at 07:54 AM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `appt_booker`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointments`
--

CREATE TABLE IF NOT EXISTS `Appointments` (
  `appID` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`appID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Bookings`
--

CREATE TABLE IF NOT EXISTS `Bookings` (
  `empNo` int(11) NOT NULL,
  `appID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`appID`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BusinessOwner`
--

CREATE TABLE IF NOT EXISTS `BusinessOwner` (
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(8) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  `busName` varchar(100) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CanWork`
--

CREATE TABLE IF NOT EXISTS `CanWork` (
  `empID` int(11) NOT NULL,
  `appID` int(11) NOT NULL,
  `canWork` tinyint(1) NOT NULL,
  PRIMARY KEY (`empID`,`appID`),
  KEY `appID` (`appID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

CREATE TABLE IF NOT EXISTS `Customers` (
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(8) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Employees`
--

CREATE TABLE IF NOT EXISTS `Employees` (
  `empID` int(11) NOT NULL AUTO_INCREMENT,
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  PRIMARY KEY (`empID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `Employees`
--

INSERT INTO `Employees` (`empID`, `fName`, `lName`) VALUES
(2, 'Albert', 'Albee'),
(3, 'Bert', 'Beatle'),
(4, 'Candice', 'Chalmers');

-- --------------------------------------------------------

--
-- Table structure for table `Employs`
--

CREATE TABLE IF NOT EXISTS `Employs` (
  `email` varchar(100) NOT NULL,
  `empID` int(11) NOT NULL,
  PRIMARY KEY (`empID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CanWork`
--
ALTER TABLE `CanWork`
  ADD CONSTRAINT `CanWork_ibfk_2` FOREIGN KEY (`appID`) REFERENCES `Appointments` (`appID`),
  ADD CONSTRAINT `CanWork_ibfk_1` FOREIGN KEY (`empID`) REFERENCES `Employees` (`empID`);

--
-- Constraints for table `Employs`
--
ALTER TABLE `Employs`
  ADD CONSTRAINT `Employs_ibfk_2` FOREIGN KEY (`empID`) REFERENCES `Employees` (`empID`),
  ADD CONSTRAINT `Employs_ibfk_1` FOREIGN KEY (`email`) REFERENCES `BusinessOwner` (`email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
