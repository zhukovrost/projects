-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 06, 2023 at 10:17 AM
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
-- Database: `english`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`question`)),
  `theme` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `theme`) VALUES
(1, '[\"Test question type 1\",4,1,[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\"],\"radio\",null]', 'Present Perfect'),
(2, '[\"Test question type 2\",4,[1,3],[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\"],\"checkbox\",null]', 'Present Perfect Continous'),
(3, '[\"Test question type 3\",\"2\",-1,[\"test answer 1\",\"test answer 2\"],\"definite\",null]', 'Present Simple'),
(4, '[\"Test question type 4\",null,null,null,\"definite_mc\"]', 'Past Simple'),
(5, '[\"Test question 5\",\"2\",1,[\"test answer 1\",\"te\"],\"radio\",null]', 'Present Simple'),
(6, '[\"test question 6\",null,null,null,\"definite_mc\"]', ''),
(7, '[\"test question 8\",\"6\",[3,5],[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\",\"test answer 5\",\"test answer 6\"],\"checkbox\",null]', 'Present Simple'),
(8, '[\"ывфаоыфоадлвфвлыоваыффы\",4,[0,1],[\"выафыав\",\"авыфаыв\",\"выфаыф\",\"авыфаыфа\"],\"checkbox\",null]', 'Present Perfect');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `test` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`test`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `name`, `test`) VALUES
(1, 'Test name 1', '[[\"Test question type 1\",4,1,[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\"],\"radio\",null],[\"Test question type 2\",4,[1,3],[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\"],\"checkbox\",null],[\"Test question type 3\",\"2\",-1,[\"test answer 1\",\"test answer 2\"],\"definite\",null],[\"Test question type 4\",null,null,null,\"definite_mc\"]]'),
(2, 'Test name 2', '[[\"Test question 5\",\"2\",1,[\"test answer 1\",\"te\"],\"radio\",null],[\"test question 6\",null,null,null,\"definite_mc\"],[\"test question 8\",\"6\",[3,5],[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\",\"test answer 5\",\"test answer 6\"],\"checkbox\",null]]'),
(3, 'test name 3', '[[\"Test question type 2\",4,[1,3],[\"test answer 1\",\"test answer 2\",\"test answer 3\",\"test answer 4\"],\"checkbox\",null],[\"test question 6\",null,null,null,\"definite_mc\"],[\"Test question type 3\",\"2\",-1,[\"test answer 1\",\"test answer 2\"],\"definite\",null]]'),
(4, 'ыяапаваыпкааыпвпыв', '[[\"Test question type 4\",null,null,null,\"definite_mc\"],[\"ывфаоыфоадлвфвлыоваыффы\",4,[0,1],[\"выафыав\",\"авыфаыв\",\"выфаыф\",\"авыфаыфа\"],\"checkbox\",null]]');

-- --------------------------------------------------------

--
-- Table structure for table `test_images`
--

CREATE TABLE `test_images` (
  `id` int(11) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `theme` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`theme`) VALUES
('Present Perfect'),
('Present Simple'),
('Past Perfect'),
('Past Simple'),
('Present Continous'),
('Past Continous'),
('Present Perfect Continous');

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
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_images`
--
ALTER TABLE `test_images`
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
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `test_images`
--
ALTER TABLE `test_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
