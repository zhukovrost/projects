-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 15, 2023 at 07:49 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

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
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `new_id` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  `date` int(11) NOT NULL,
  `personal` tinyint(1) NOT NULL DEFAULT 0,
  `additional_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `new_id`, `user`, `date`, `personal`, `additional_info`) VALUES
(1, 4, 'zhukovrost', 1677148290, 1, NULL),
(2, 2, 'zhukovrost', 1677148384, 0, NULL),
(3, 1, 'zhukovrost', 1678015430, 0, NULL),
(4, 3, 'zhukovrost', 1678017718, 0, NULL),
(5, 2, 'zhukovrost', 1678023801, 0, NULL),
(6, 4, 'test1', 1678023936, 1, NULL),
(7, 0, 'zhukovrost', 1678024468, 1, 'test1'),
(8, 3, 'zhukovrost', 1678025759, 0, NULL);

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
(1, '[[\"press/1/2/30\",\"legs/1/2/30\",\"arms/1/2/30\"],[],[\"press/1/2/30\",\"legs/1/2/30\",\"arms/1/2/30\"],[],[\"press/1/2/30\",\"legs/1/2/30\",\"arms/1/2/30\"],[],[]]'),
(2, '[[\"press/6/4/30\",\"press/2/3/30\",\"press/1/2/30\"],[],[\"arms/1/2/30\",\"arms/1/2/30\",\"arms/1/5/30\",\"arms/1/5/30\"],[],[\"back/1/2/30\",\"back/1/3/30\",\"back/1/4/30\"],[\"back/1/2/30\",\"back/1/3/30\",\"back/1/4/30\"],[\"press/6/4/30\",\"press/2/3/30\",\"press/1/2/30\"]]');

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
  `sex` varchar(1) NOT NULL,
  `date_of_birth` int(11) NOT NULL,
  `program` int(11) UNSIGNED NOT NULL,
  `program_duration` int(10) UNSIGNED DEFAULT NULL,
  `calendar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `start_program` int(11) DEFAULT NULL,
  `completed_program` tinyint(1) DEFAULT NULL,
  `subscriptions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `surname`, `thirdname`, `email`, `password`, `avatar_id`, `weight`, `height`, `sex`, `date_of_birth`, `program`, `program_duration`, `calendar`, `start_program`, `completed_program`, `subscriptions`) VALUES
(1, 'zhukovrost', 'Ростислав', 'Жуков', 'Сергеевич', 'rosf.zhukov@gmail.com', 'ZHU2006', NULL, 0.000, 0.000, 'm', 1141074000, 0, 0, '[]', 0, 0, '[]'),
(2, 'test1', 'User_name', 'User_surname', 'User_thirdname', 'aaaaaaa@aaaaaaaaaa', '123', NULL, 0.000, 0.000, 'm', 952981200, 0, NULL, NULL, NULL, NULL, '[\"zhukovrost\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
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
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `userprograms`
--
ALTER TABLE `userprograms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
