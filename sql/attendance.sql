-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 09, 2019 at 01:29 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yvainilo_grc`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `date` date NOT NULL,
  `sid` int(255) NOT NULL,
  `present` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`date`, `sid`, `present`) VALUES
('2019-05-08', 1, 1),
('2019-05-08', 2, 1),
('2019-05-08', 3, 1),
('2019-05-08', 4, 1),
('2019-05-08', 5, 1),
('2019-05-08', 6, 0),
('2019-05-08', 7, 0),
('2019-05-08', 10, 1),
('2019-05-09', 1, 1),
('2019-05-09', 2, 1),
('2019-05-09', 3, 1),
('2019-05-09', 4, 1),
('2019-05-09', 5, 0),
('2019-05-09', 6, 0),
('2019-05-09', 7, 0),
('2019-05-09', 10, 0),
('2019-05-09', 11, 0),
('2019-05-09', 12, 0),
('2019-05-08', 11, 1),
('2019-05-08', 12, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
