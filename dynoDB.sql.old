-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 14, 2023 at 02:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dynoDB`
--
CREATE DATABASE IF NOT EXISTS `dynoDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dynoDB`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `delete_data`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_data` (IN `id` INT)   DELETE FROM data WHERE data.id=id$$

DROP PROCEDURE IF EXISTS `get_all_data`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_data` ()  READS SQL DATA FOR i IN (SELECT version_id as v FROM data GROUP BY version_id)
DO
  set @cols = (SELECT GROUP_CONCAT( CONCAT('COLUMN_GET(value, "' ,field, '" as ', type, ' ) AS ', field) SEPARATOR ', ') from fields INNER JOIN version_fields on i.v=version_fields.version_id AND fields.id=version_fields.field_id);
  
  EXECUTE IMMEDIATE CONCAT("SELECT id, version_id as version, ", @cols, " from data where version_id=", i.v ,";\n");
END FOR$$

DROP PROCEDURE IF EXISTS `get_data`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_data` (IN `id` INT)   FOR i IN (SELECT version_id as v FROM data WHERE data.id=id)
DO
  set @cols = (SELECT GROUP_CONCAT( CONCAT('COLUMN_GET(value, "' ,field, '" as ', type, ' ) AS ', field) SEPARATOR ', ') from fields INNER JOIN version_fields on i.v=version_fields.version_id AND fields.id=version_fields.field_id);
  
  EXECUTE IMMEDIATE CONCAT("SELECT id, version_id as version, ", @cols, " from data where id=", id ,";\n");
END FOR$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `value` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `field` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

DROP TABLE IF EXISTS `versions`;
CREATE TABLE `versions` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `version_fields`
--

DROP TABLE IF EXISTS `version_fields`;
CREATE TABLE `version_fields` (
  `id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vid` (`version_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `version_fields`
--
ALTER TABLE `version_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_index` (`field_id`),
  ADD KEY `version_index` (`version_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `version_fields`
--
ALTER TABLE `version_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `ver_con` FOREIGN KEY (`version_id`) REFERENCES `versions` (`id`);

--
-- Constraints for table `version_fields`
--
ALTER TABLE `version_fields`
  ADD CONSTRAINT `field_con` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`),
  ADD CONSTRAINT `version_con` FOREIGN KEY (`version_id`) REFERENCES `versions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
