-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 03:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cems_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'volunteer',
  `created_at` datetime DEFAULT current_timestamp(),
  `assignments` text DEFAULT NULL,
  `metrics` text DEFAULT NULL,
  `training` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`, `assignments`, `metrics`, `training`) VALUES
(1, 'Carl David Binghay', '0@gmail.com', '$2y$10$VPOTnoIhxRIcLVq1gf0N8.o2tvDf5PNQ5vBA0zbqy.BtxFToMl10W', 'admin', '2025-11-27 19:54:27', NULL, NULL, NULL),
(12, 'Binghay Carl David ', NULL, '', 'Volunteer', '2025-11-27 21:14:10', 'Program A, Project X', 'On-time completion: 95%', 'CPR, Leadership Training'),
(13, 'Carl David Binghay ', 'carldavidbinghay@admin.com', '$2y$10$jj7G9yrc45tiLFQltRMP1.oc76pqf0xmfIvf7a8A.YFh8cQRLL.4y', 'Admin', '2025-11-27 21:15:49', 'Project Manager', 'On-time completion: 95%', 'CPR, Leadership Training'),
(14, 'Carl David Binghay ', NULL, '', 'Staff', '2025-11-27 21:53:13', 'Project Manager', 'On-time completion: 95%', 'CPR, Leadership Training'),
(15, 'Qwerty', 'qwerty@gmail.com', '$2y$10$7bRZyvy5sEua8yAy89JkieZCTHwi7l5WGZ8.FK4Bt/tiO5RL1HYxa', 'Staff', '2025-11-27 21:54:23', 'Project Manager', 'On-time completion: 95%', 'CPR, Leadership Training'),
(16, 'asdfg', '5@gmail.com', '$2y$10$PPvp9i94fKVtbpSNXxMdVuqwQDqhKAhUT99TsggGC3ywUaXrpaafW', 'Staff', '2025-11-27 21:55:57', 'Project Manager', 'On-time completion: 95%', 'CPR, Leadership Training');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
