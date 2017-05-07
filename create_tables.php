<?php

$CLEAN = "DROP TABLE IF EXISTS `AppType`, `Bookings`, `BusinessOwner`, `CanWork`, `Customers`, `Employees`, `TimeSlot`, `haveSkill`, `Business`, `hours`;";

$APPTYPE = "CREATE TABLE `AppType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appType` varchar(20) NOT NULL,
  `appDesc` varchar(200) NOT NULL,
  `appDuration` int(11) NOT NULL,
  PRIMARY KEY (`appType`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;";

$BOOKINGS = "CREATE TABLE `Bookings` (
  `empID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateTime` datetime NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `appType` varchar(20) NOT NULL,
  PRIMARY KEY (`email`,`dateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$BUSINESSOWNER = "CREATE TABLE `BusinessOwner` (
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(8) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$CANWORK = "CREATE TABLE `CanWork` (
  `empID` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  PRIMARY KEY (`empID`,`dateTime`),
  KEY `dateTime` (`dateTime`),
  CONSTRAINT `CanWork_ibfk_1` FOREIGN KEY (`empID`) REFERENCES `Employees` (`empID`),
  CONSTRAINT `CanWork_ibfk_2` FOREIGN KEY (`dateTime`) REFERENCES `TimeSlot` (`dateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$CUSTOMERS = "CREATE TABLE `Customers` (
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(8) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `address` varchar(200) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$EMPLOYEES = "CREATE TABLE `Employees` (
  `empID` int(11) NOT NULL AUTO_INCREMENT,
  `fName` varchar(40) NOT NULL,
  `lName` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`empID`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;";

$TIMESLOTS = "CREATE TABLE `TimeSlot` (
  `dateTime` datetime NOT NULL,
  PRIMARY KEY (`dateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$HAVESKILL = "CREATE TABLE `haveSkill` (
  `empID` int(11) DEFAULT NULL,
  `typeId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$BUSINESS = "CREATE TABLE `Business` (
  `businessName` varchar(40) NOT NULL,
  `businessDesc` varchar(200) NOT NULL,
  PRIMARY KEY (`businessName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$HOURS = "CREATE TABLE `Hours` (
  `day` int(11) NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL,
  PRIMARY KEY (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
