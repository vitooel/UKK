-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 06:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','on_process','completed') DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `priority` enum('biasa','segera') DEFAULT 'biasa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task`, `description`, `status`, `due_date`, `completed_at`, `user_id`, `start_date`, `priority`) VALUES
(5, 'nulis', 'anjay mabart', 'on_process', NULL, NULL, 0, NULL, 'biasa'),
(11, 'sholat zhuhur', 'baru mau sholat awokawkoawok', 'on_process', NULL, NULL, 6, NULL, 'biasa'),
(12, 'anjayayaywyaw', 'mkn bro', 'completed', NULL, NULL, 7, NULL, 'biasa'),
(30, 'sadada', 'asasdas', 'completed', '2025-02-08', NULL, 8, NULL, 'biasa'),
(38, 'kamar mandi', 'mandi besar', 'completed', '2025-02-19', '2025-02-17', 5, '2025-02-18 00:00:00', 'biasa'),
(39, 'ngising', 'cebok', '', '2025-02-18', '0000-00-00', 5, '2025-02-17 00:00:00', 'biasa'),
(40, 'sholat zhuhur', 'mau sholat 5 waktu', 'completed', '2025-02-21', '2025-02-19', 11, '2025-02-19 00:00:00', 'segera'),
(41, 'makan malem', 'dfzszcxz', '', '2025-02-20', '0000-00-00', 11, '2025-02-19 00:00:00', 'biasa'),
(42, 'sholat zhuhur', 'sadada', 'pending', '2025-03-01', NULL, 11, '2025-02-19 00:00:00', 'segera'),
(43, 'sholat zhuhur', 'sholat trus ngaji', 'completed', '2025-02-22', '2025-02-19', 12, '2025-02-19 00:00:00', 'segera'),
(44, 'asdasdad', 'assdadadasa', '', '2025-03-01', '0000-00-00', 12, '2025-02-19 00:00:00', 'biasa'),
(45, 'makan malem', 'asssaaa', 'pending', '2025-02-21', NULL, 12, '2025-02-19 00:00:00', 'segera'),
(46, 'projek sekolah', 'bikin sepatu', 'completed', '2025-02-22', '2025-02-20', 1, '2025-02-21 00:00:00', 'segera'),
(48, 'makan siang', '10 waktu', 'pending', '2025-04-16', NULL, 11, '2025-04-14 00:00:00', 'biasa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(5, 'vito', 'pizzhhh@gmail.com', '$2y$10$WIZ9XMsfoSQmOEr8hsx2E.iDyrz6aNQ.AD5HxDdZ/X7SQUFvsRJR6'),
(6, 'yuliati', 'lintangnata4@gmail.com', '$2y$10$TuTc0azRKHBC1w1TAgqyqeHHJYgVQd5nA8R2fNqDNxjl5QvxFa2xK'),
(7, 'iyo', 'infokan@gmail.com', '$2y$10$lXafi.KhKuTUUhcFm312ve7ZvdXd7gpatDbA9hQ7CW81LIAC7TjCi'),
(8, 'vito', 'anjay@gmail.com', '$2y$10$Wx6gHbz5akLHVe4naaBfGOYSBt9gtlgb3QcGSEdFpqvZaUo13I9wC'),
(9, 'vitobirahi', 'vitobirahi@gmail.com', '$2y$10$y9GKTPGV7wftsvndBcGkr.CRi6c1LynlRZnHD0Tu/xplMYgQoI/4m'),
(11, 'vito tampan', 'hujanbliang@srv.jagoan.xyz', '$2y$10$klmu4a0EazuOFh8mWMzMsex5t19z5pZbufgnv9maXcqUwVq7RBDT.'),
(12, 'umi', 'amelianew136@gmail.com', '$2y$10$xPmneeN027zE1zU6AdKGrO4xxuEsMu9fAQQkrvnsik.NP1V0Id9By');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
