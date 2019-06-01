-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2019 at 03:04 AM
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
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `date` date NOT NULL,
  `teacherid` int(11) NOT NULL,
  `scheduled` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`date`, `teacherid`, `scheduled`) VALUES
('2019-06-02', 8, 1),
('2019-06-02', 7, 1),
('2019-06-02', 32, 1),
('2019-06-02', 33, 0),
('2019-06-02', 6, 0),
('2019-06-02', 3, 0),
('2019-06-09', 8, 1),
('2019-06-09', 7, 1),
('2019-06-09', 32, 1),
('2019-06-09', 33, 1),
('2019-06-09', 6, 1),
('2019-06-09', 3, 0),
('2019-07-07', 8, 1),
('2019-07-07', 7, 1),
('2019-07-07', 32, 0),
('2019-07-07', 33, 1),
('2019-07-07', 6, 0),
('2019-07-07', 3, 1),
('2019-06-30', 8, 1),
('2019-06-30', 7, 0),
('2019-06-30', 32, 1),
('2019-06-30', 33, 0),
('2019-06-30', 6, 1),
('2019-06-30', 3, 1),
('2019-07-21', 8, 1),
('2019-07-21', 7, 1),
('2019-07-21', 32, 0),
('2019-07-21', 33, 1),
('2019-07-21', 6, 0),
('2019-07-21', 3, 1),
('2019-06-16', 8, 1),
('2019-06-16', 7, 0),
('2019-06-16', 32, 0),
('2019-06-16', 33, 1),
('2019-06-16', 6, 1),
('2019-06-16', 3, 0),
('2019-06-23', 8, 1),
('2019-06-23', 7, 1),
('2019-06-23', 32, 0),
('2019-06-23', 33, 0),
('2019-06-23', 6, 1),
('2019-06-23', 3, 1),
('2019-07-14', 8, 0),
('2019-07-14', 7, 1),
('2019-07-14', 32, 0),
('2019-07-14', 33, 1),
('2019-07-14', 6, 1),
('2019-07-14', 3, 0),
('2019-07-28', 8, 0),
('2019-07-28', 7, 1),
('2019-07-28', 32, 0),
('2019-07-28', 33, 1),
('2019-07-28', 6, 1),
('2019-07-28', 3, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
