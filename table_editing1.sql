-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 09:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `table_editing1`
--

-- --------------------------------------------------------

--
-- Table structure for table `edit`
--

CREATE TABLE `edit` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `status` enum('user','admin','','') NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text DEFAULT NULL,
  `date_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edit`
--

INSERT INTO `edit` (`id`, `name`, `status`, `email`, `password`, `date_create`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$TB8t0Po/EXIh912759HWhu6Qb01F6wxTV6yd2GV2clf9Eed.Mz43m', '2025-05-28 05:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `work_history`
--

CREATE TABLE `work_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL,
  `old_date` date NOT NULL,
  `old_detail` varchar(255) NOT NULL,
  `old_start` time NOT NULL,
  `old_end` time NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_table`
--

CREATE TABLE `work_table` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `work_date` date NOT NULL,
  `detail` varchar(255) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `edit`
--
ALTER TABLE `edit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_history`
--
ALTER TABLE `work_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_table`
--
ALTER TABLE `work_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edit`
--
ALTER TABLE `edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `work_history`
--
ALTER TABLE `work_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_table`
--
ALTER TABLE `work_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `work_table`
--
ALTER TABLE `work_table`
  ADD CONSTRAINT `fk_edit_id` FOREIGN KEY (`user_id`) REFERENCES `edit` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `edit` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
