-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 06, 2023 at 04:02 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sport`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE `avatars` (
  `id` int(32) NOT NULL,
  `image` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userprograms`
--

CREATE TABLE `userprograms` (
  `id` int(11) NOT NULL,
  `program` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`program`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userprograms`
--

INSERT INTO `userprograms` (`id`, `program`) VALUES
(1, '[[\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/4/60\",\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/3/30\"],[],[\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/4/60\",\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/3/30\"],[],[\"press/2/2/30\",\"press/2/3/30\",\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/4/60\",\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/3/30\"],[],[\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/4/60\",\"chest_and_shoulders/1/2/30\",\"chest_and_shoulders/1/3/30\"]]'),
(2, '[[\"press/1/3/30\",\"press/1/5/30\",\"press/1/3/30\",\"press/1/3/30\",\"press/1/2/30\"],[\"press/1/3/30\",\"press/1/5/30\",\"press/1/3/30\",\"press/1/3/30\",\"press/1/2/30\"],[],[],[\"press/1/3/30\",\"press/1/5/30\",\"press/1/3/30\",\"press/1/3/30\",\"press/1/2/30\"],[],[]]'),
(3, '[[],[],[\"press/1/4/30\",\"press/1/3/30\",\"press/1/2/30\"],[\"press/1/4/30\",\"press/1/3/30\",\"press/1/2/30\"],[\"press/1/4/30\",\"press/1/3/30\",\"press/1/2/30\"],[],[]]'),
(4, '[[\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\"],[\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\"],[],[],[\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\",\"press/1/2/30\"],[],[]]'),
(5, '[[\"arms/1/2/30\",\"arms/1/4/30\",\"arms/1/4/30\"],[],[\"arms/1/2/30\",\"arms/1/4/30\",\"arms/1/4/30\"],[],[],[\"arms/1/2/30\",\"arms/1/4/30\",\"arms/1/4/30\"],[\"arms/1/2/30\",\"arms/1/4/30\",\"arms/1/4/30\"]]'),
(6, '[[],[\"press/1/2/30\",\"press/1/3/30\",\"press/1/4/30\",\"press/1/5/30\",\"press/1/6/30\"],[],[\"press/1/2/30\",\"press/1/3/30\",\"press/1/4/30\",\"press/1/5/30\",\"press/1/6/30\",\"press/1/2/30\",\"press/1/3/30\",\"press/1/4/30\",\"press/1/5/30\",\"press/1/6/30\",\"legs/2/6/30\",\"legs/1/2/30\",\"chest_and_shoulders/1/3/30\"],[],[\"press/1/2/30\",\"press/1/3/30\",\"press/1/4/30\",\"press/1/5/30\",\"press/1/6/30\"],[]]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `thirdname` varchar(32) DEFAULT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `avatar_id` int(32) UNSIGNED DEFAULT NULL,
  `weight` float(6,3) UNSIGNED NOT NULL,
  `height` float(6,3) UNSIGNED NOT NULL,
  `sex` varchar(16) NOT NULL,
  `date_of_birth` date NOT NULL,
  `program` int(11) UNSIGNED NOT NULL,
  `program_duration` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `surname`, `thirdname`, `email`, `password`, `avatar_id`, `weight`, `height`, `sex`, `date_of_birth`, `program`, `program_duration`) VALUES
(1, 'test1', 'testsurname', 'te', 'testthirdname', 'sdafasd@dsafsdf', '123', NULL, 0.000, 0.000, 'man', '1998-08-12', 6, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userprograms`
--
ALTER TABLE `userprograms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avatars`
--
ALTER TABLE `avatars`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userprograms`
--
ALTER TABLE `userprograms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
