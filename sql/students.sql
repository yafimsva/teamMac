-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 09, 2019 at 01:27 PM
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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `sid` int(11) NOT NULL,
  `first` varchar(50) NOT NULL,
  `last` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `parents_email` varchar(50) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sid`, `first`, `last`, `dob`, `parents_email`, `classid`) VALUES
(1, 'YafimCH', 'Vainilovich', '2019-01-01', 'yafimsva@gmail.com', 1),
(2, 'David', 'koval', '2000-01-01', 'david@gmail.com', 2),
(3, 'Brandon', 'Scar', '2019-05-08', 'brandon@gmail.com', 3),
(4, 'Nic', 'Alex', '2019-05-01', 'nic@gmail.com', 4),
(5, 'Alex', 'Bykovich', '2019-05-12', 'alexb@gmail.com', 5),
(7, 'Peter', 'Pan', '1999-03-22', 'pte@gmail.com', 2),
(11, 'check', 'check', '9999-03-22', 'check@gmail.com', 5),
(13, 'Someone', 'Unknown', '2014-03-11', 'unknwomn@gmail.com', 4),
(14, 'Arthur ', 'Bek', '1999-03-22', 'new@gmail.com', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
