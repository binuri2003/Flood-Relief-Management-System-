-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 07:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flood_relief_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affected_person`
--

CREATE TABLE `affected_person` (
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relief_request`
--

CREATE TABLE `relief_request` (
  `RequestID` int(11) NOT NULL,
  `AffectedUserID` int(11) DEFAULT NULL,
  `AdminUserID` int(11) DEFAULT NULL,
  `ReliefType` varchar(50) DEFAULT NULL,
  `District` varchar(50) DEFAULT NULL,
  `DivisionalSecretariat` varchar(50) DEFAULT NULL,
  `GNDivision` varchar(50) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `NoOfFamilyMembers` int(11) DEFAULT NULL,
  `ContactName` varchar(50) DEFAULT NULL,
  `SeverityLevel` varchar(20) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_contact`
--

CREATE TABLE `request_contact` (
  `RequestID` int(11) NOT NULL,
  `ContactNumber` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Role` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contact`
--

CREATE TABLE `user_contact` (
  `UserID` int(11) NOT NULL,
  `ContactNumber` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `affected_person`
--
ALTER TABLE `affected_person`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `relief_request`
--
ALTER TABLE `relief_request`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `AffectedUserID` (`AffectedUserID`),
  ADD KEY `AdminUserID` (`AdminUserID`);

--
-- Indexes for table `request_contact`
--
ALTER TABLE `request_contact`
  ADD PRIMARY KEY (`RequestID`,`ContactNumber`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user_contact`
--
ALTER TABLE `user_contact`
  ADD PRIMARY KEY (`UserID`,`ContactNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `relief_request`
--
ALTER TABLE `relief_request`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `affected_person`
--
ALTER TABLE `affected_person`
  ADD CONSTRAINT `affected_person_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `relief_request`
--
ALTER TABLE `relief_request`
  ADD CONSTRAINT `relief_request_ibfk_1` FOREIGN KEY (`AffectedUserID`) REFERENCES `affected_person` (`UserID`),
  ADD CONSTRAINT `relief_request_ibfk_2` FOREIGN KEY (`AdminUserID`) REFERENCES `admin` (`UserID`);

--
-- Constraints for table `request_contact`
--
ALTER TABLE `request_contact`
  ADD CONSTRAINT `request_contact_ibfk_1` FOREIGN KEY (`RequestID`) REFERENCES `relief_request` (`RequestID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_contact`
--
ALTER TABLE `user_contact`
  ADD CONSTRAINT `user_contact_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
