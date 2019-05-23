-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2019 at 08:55 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `morganfk`
--
CREATE DATABASE IF NOT EXISTS `morganfk` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `morganfk`;

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `animal_id` int(11) NOT NULL,
  `animalName` varchar(64) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`animal_id`, `animalName`) VALUES
(50, 'Deer'),
(51, 'Gray Squirrel'),
(52, 'Snowman'),
(53, 'Wild Boar'),
(54, 'Black Bear');

-- --------------------------------------------------------

--
-- Table structure for table `hunts`
--

CREATE TABLE `hunts` (
  `hunt_id` int(11) NOT NULL,
  `huntDate` date NOT NULL,
  `isSuccess` varchar(10) COLLATE utf8_bin NOT NULL,
  `animal_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `hunts`
--

INSERT INTO `hunts` (`hunt_id`, `huntDate`, `isSuccess`, `animal_id`, `location_id`, `user_id`) VALUES
(50, '2019-05-09', 'true', 50, 50, 50),
(51, '2019-05-10', 'true', 51, 51, 50),
(52, '2019-05-02', 'true', 52, 52, 50),
(53, '2019-05-12', 'true', 53, 53, 51),
(54, '2019-04-26', 'true', 50, 54, 51),
(55, '2019-05-27', 'true', 51, 55, 52),
(56, '2019-01-17', 'true', 50, 56, 52),
(57, '2018-09-08', 'true', 53, 57, 52),
(58, '2019-05-22', 'true', 54, 50, 53),
(59, '2019-05-12', 'true', 54, 58, 53);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `locationName` varchar(64) COLLATE utf8_bin NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `locationName`, `longitude`, `latitude`) VALUES
(50, 'Eau Claire, WI, USA', -91.4984941, 44.811349),
(51, 'Green Bay, WI, USA', -88.0132958, 44.5133188),
(52, 'Minneapolis, MN, USA', -93.2650108, 44.977753),
(53, 'Indiantown, FL 34956, USA', -80.4856083, 27.0272756),
(54, 'Madison, WI, USA', -89.4012302, 43.0730517),
(55, 'La Crosse, WI, USA', -91.2519017, 43.8137751),
(56, 'St Paul, MN, USA', -93.0899578, 44.9537029),
(57, 'Sebring, FL, USA', -81.440907, 27.495592),
(58, 'Wausau, WI, USA', -89.6301221, 44.9591352);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `password` varchar(256) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(50, 'Foster', '$2y$10$QEJCDR6aH4nb3BE8BiGYqO8vhYCvcTHYkCH7uOlnhDTt30Yq5Gl7O'),
(51, 'Billy', '$2y$10$a28R25B2eglmG.5ym59Fm.2tD0ZQZhD4A9py5njo3L1gsGashOd.u'),
(52, 'Bobby', '$2y$10$.P/YUATpqAj5sn4g2ajHEe1hYwdv8VCXxKJBvUioJdVeBMByIB0Jm'),
(53, 'Joe', '$2y$10$DsctdugW0CrqR3GyT/BR2.MMVdbwuJ3dky5ncAT.j1NPRpBku1Cp.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`animal_id`);

--
-- Indexes for table `hunts`
--
ALTER TABLE `hunts`
  ADD PRIMARY KEY (`hunt_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `hunts`
--
ALTER TABLE `hunts`
  MODIFY `hunt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
