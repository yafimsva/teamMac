-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2019 at 06:21 PM
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
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacherid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `classid` int(11) NOT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacherid`, `name`, `username`, `password`, `classid`, `endDate`) VALUES
(3, 'Yafim Vainilovich', 'yafimsva', '123', 1, '2019-08-08'),
(6, 'Sasha Ankuda', 'sasha', '123', 2, '0000-00-00'),
(7, 'Angela', 'agie', '123', 4, NULL),
(8, 'Alina Kirilko', 'alina', '123', 3, '0000-00-00'),
(31, 'Koval', 'koval1', '123', 3, '2019-05-28'),
(32, 'KOVAL2', 'koval2', '123', 4, NULL),
(29, 'koval30', 'koval30', '123', 3, NULL),
(33, 'Koval4', 'koval4', '123', 2, '2019-05-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacherid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacherid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
