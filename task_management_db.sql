-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 06:02 AM
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
-- Database: `task_management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `time_in`, `time_out`, `created_at`) VALUES
(1, 5, '2025-12-18 23:50:06', '2025-12-19 00:42:18', '2025-12-18 15:50:06'),
(2, 6, '2025-12-19 00:35:06', NULL, '2025-12-18 16:35:06'),
(3, 2, '2025-12-19 00:37:49', '2025-12-19 00:42:24', '2025-12-18 16:37:49'),
(4, 2, '2025-12-19 00:45:21', '2025-12-19 00:46:34', '2025-12-18 16:45:21'),
(5, 5, '2025-12-19 01:03:43', '2025-12-19 01:17:09', '2025-12-18 17:03:43'),
(6, 5, '2025-12-19 01:28:20', '2025-12-19 01:42:10', '2025-12-18 17:28:20'),
(7, 7, '2025-12-19 12:32:09', '2025-12-19 12:40:12', '2025-12-19 04:32:09'),
(8, 7, '2025-12-19 12:40:21', '2025-12-19 12:57:27', '2025-12-19 04:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipient` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `is_read` tinyint(1) DEFAULT 0,
  `task_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `recipient`, `type`, `date`, `is_read`, `task_id`) VALUES
(1, '\'task 2\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2025-12-17', 1, NULL),
(2, '\'task 3\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2025-12-17', 0, NULL),
(3, '\'heyyy\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2025-12-17', 0, NULL),
(4, '\'buhata ni des\' has been assigned to you. Please review and start working on it', 4, 'New Task Assigned', '2025-12-17', 1, NULL),
(5, '\'new task\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-17', 1, NULL),
(6, '\'opaw\' has been assigned to you. Please review and start working on it', 6, 'New Task Assigned', '2025-12-17', 0, NULL),
(7, '\'new task\' has been approved and marked as completed. Comment: good', 5, 'Task Completed', '2025-12-17', 1, NULL),
(8, '\'1\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-17', 1, NULL),
(9, '\'task 3\' has been approved and marked as completed. ', 2, 'Task Completed', '2025-12-17', 0, NULL),
(10, '\'task 2\' has been approved and marked as completed. ', 2, 'Task Completed', '2025-12-17', 0, NULL),
(11, '\'1\' has been updated. Comment: kulang sya', 5, 'Task Updated', '2025-12-17', 1, NULL),
(12, '\'1\' has been approved and marked as completed. Comment: yown pogi', 5, 'Task Completed', '2025-12-17', 1, NULL),
(13, '\'2\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-17', 1, NULL),
(14, '\'2\' has a new submission from roel descartin. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-17', 1, NULL),
(15, '\'2\' has been approved and marked as completed. Comment: sheyt curse helmet', 5, 'Task Completed', '2025-12-17', 1, NULL),
(16, '\'3\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-17', 1, NULL),
(17, '\'3\' has a new submission from roel descartin. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-17', 1, NULL),
(18, '\'3\' submission was rejected. Please review and resubmit.', 5, 'Task Rejected', '2025-12-17', 1, NULL),
(19, '\'3\' has a new submission from roel descartin. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-17', 1, NULL),
(20, '\'3\' has been approved and marked as completed. Comment: goods', 5, 'Task Completed', '2025-12-17', 1, NULL),
(21, '\'5\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-17', 1, NULL),
(22, '\'5\' has a new submission from roel descartin. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-17', 1, NULL),
(23, '\'5\' has been approved and marked as completed. Comment: goods', 5, 'Task Completed', '2025-12-17', 1, NULL),
(24, '\'bag o\' has been assigned to you. Please review and start working on it', 5, 'New Task Assigned', '2025-12-18', 1, NULL),
(25, '\'bag o\' has a new submission from roel descartin. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-18', 1, NULL),
(26, '\'bag o\' has been approved and marked as completed. Comment: pakyu', 5, 'Task Completed', '2025-12-18', 0, NULL),
(27, '\'capstone\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2025-12-19', 1, NULL),
(28, '\'capstone\' has a new submission from Sherwin Españo. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-19', 1, NULL),
(29, '\'capstone\' has been approved and marked as completed. Comment: goods sya', 7, 'Task Completed', '2025-12-19', 1, NULL),
(30, '\'capstone2\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2025-12-19', 1, NULL),
(31, '\'c3\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2025-12-19', 1, 15),
(32, '\'c3\' has a new submission from Sherwin Españo. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-19', 1, 15),
(33, '\'capstone2\' has a new submission from Sherwin Españo. Please review the uploaded file.', 1, 'Task Submitted', '2025-12-19', 1, 14),
(34, '\'c3\' has been approved and marked as completed. ', 7, 'Task Completed', '2025-12-19', 0, 15),
(35, '\'capstone2\' has been approved and marked as completed. ', 7, 'Task Completed', '2025-12-19', 0, 14);

-- --------------------------------------------------------

--
-- Table structure for table `screenshots`
--

CREATE TABLE `screenshots` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attendance_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `taken_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screenshots`
--

INSERT INTO `screenshots` (`id`, `user_id`, `attendance_id`, `image_path`, `taken_at`) VALUES
(1, 2, 3, 'screenshots/2_1766075910.png', '2025-12-19 00:38:30'),
(2, 2, 3, 'screenshots/2_1766075941.png', '2025-12-19 00:39:01'),
(3, 5, 1, 'screenshots/5_1766076115.png', '2025-12-19 00:41:55'),
(4, 2, 3, 'screenshots/2_1766076122.png', '2025-12-19 00:42:02'),
(6, 2, 4, 'screenshots/2_4.png', '2025-12-19 00:46:26'),
(21, 5, 5, 'screenshots/5_5.png', '2025-12-19 01:16:48'),
(64, 5, 6, 'screenshots/5_6.png', '2025-12-19 01:41:45'),
(78, 7, 7, 'screenshots/7_7.png', '2025-12-19 12:39:51'),
(86, 7, 8, 'screenshots/7_8.png', '2025-12-19 12:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` enum('pending','in_progress','completed','rejected') DEFAULT 'pending',
  `submission_file` varchar(255) DEFAULT NULL,
  `template_file` varchar(255) DEFAULT NULL,
  `review_comment` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `status`, `submission_file`, `template_file`, `review_comment`, `reviewed_by`, `reviewed_at`, `created_at`, `due_date`) VALUES
(1, 'Task 1', 'description', 2, 'completed', NULL, NULL, NULL, NULL, NULL, '2025-10-24 05:03:26', '2025-10-25'),
(2, 'task 2', 'i2 na2', 2, 'completed', NULL, NULL, '', 1, '2025-12-17 14:30:34', '2025-10-24 05:10:45', '2025-10-31'),
(3, 'task 3', 'tae', 2, 'completed', NULL, NULL, '', 1, '2025-12-17 14:30:26', '2025-10-24 05:11:06', '2025-10-29'),
(4, 'heyyy', 'gawin mo i2', 2, 'completed', NULL, NULL, NULL, NULL, NULL, '2025-10-24 05:12:43', '2025-11-01'),
(5, 'buhata ni des', 'karun dayun', NULL, 'completed', NULL, NULL, NULL, NULL, NULL, '2025-10-24 05:14:59', '2025-10-08'),
(6, 'new task', 'new', 5, 'completed', 'uploads/task_6_1765981547.pdf', NULL, 'good', 1, '2025-12-17 14:26:29', '2025-12-13 05:57:10', '2026-02-13'),
(7, 'opaw', 'opaw', 6, 'completed', NULL, NULL, NULL, NULL, NULL, '2025-12-13 06:32:03', '2025-12-31'),
(8, '1', '12', 5, 'completed', 'uploads/task_8_1765981943.jpg', NULL, 'yown pogi', 1, '2025-12-17 14:33:08', '2025-12-17 14:30:09', '2025-12-25'),
(9, '2', '34', 5, 'completed', 'uploads/task_9_1765982102.jpg', NULL, 'sheyt curse helmet', 1, '2025-12-17 14:35:39', '2025-12-17 14:34:36', '2025-12-17'),
(10, '3', '56', 5, 'completed', 'uploads/task_10_1765982423.png', NULL, 'goods', 1, '2025-12-17 14:40:53', '2025-12-17 14:38:56', '2025-12-17'),
(11, '5', '6123', 5, 'completed', 'uploads/task_11_1765986921.jpg', NULL, 'goods', 1, '2025-12-17 15:56:12', '2025-12-17 15:54:51', '2025-12-17'),
(12, 'bag o', 'asdasd', 5, 'completed', 'uploads/task_12_1765987349.jpg', NULL, 'pakyu', 1, '2025-12-17 16:03:03', '2025-12-17 16:01:45', '2025-12-18'),
(13, 'capstone', 'document', 7, 'completed', 'uploads/task_13_1766118816.docx', NULL, 'goods sya', 1, '2025-12-19 04:34:32', '2025-12-19 04:32:45', '2025-12-20'),
(14, 'capstone2', 'neededples', 7, 'completed', 'uploads/task_14_1766120075.pdf', NULL, '', 1, '2025-12-19 04:55:53', '2025-12-19 04:48:55', '2025-12-19'),
(15, 'c3', 'c3', 7, 'completed', 'uploads/task_15_1766120060.pdf', 'uploads/template_1766120018_Revised-ITSPEC5-TQ-FINAL-EXAMINATION (2).pdf', '', 1, '2025-12-19 04:55:01', '2025-12-19 04:53:38', '2025-12-19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'kennet', 'ken', '$2y$10$UU5QC7eJffwrhvxeNSGfwuGEnlp3WbbUUVLsh8RVUWjKWfFMmugcK', 'admin', '2025-10-22 17:56:32'),
(2, 'ga', 'gack', '$2y$10$SFuSU0FpRo8xv.EAWPx0HODkXhnTC6.wwrSOEYB2QCDPPTpdUFNlO', 'employee', '2025-10-24 05:01:58'),
(5, 'roel descartin', 'roel', '$2y$10$VmW13lwjDVZGuMjXwphtfuZinhv3ZFlUoY.83Yc8yDnMEU1WzrRHi', 'employee', '2025-12-13 05:48:14'),
(6, 'Paul Carpenters', 'purpaul', '$2y$10$XZwKlTnIyKqDcvyFV9unveQB..9y.DB6ctWwRiZOErR9eoRydNk1S', 'employee', '2025-12-13 06:19:03'),
(7, 'Sherwin Españo', 'sherwin', '$2y$10$ToPhIWpXB.hJLL.P9cSHKe26.M1pfBe1sTWxZEjRq/zUkbdtbLkfm', 'employee', '2025-12-19 04:30:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `screenshots`
--
ALTER TABLE `screenshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `attendance_id` (`attendance_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `screenshots`
--
ALTER TABLE `screenshots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `screenshots`
--
ALTER TABLE `screenshots`
  ADD CONSTRAINT `screenshots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `screenshots_ibfk_2` FOREIGN KEY (`attendance_id`) REFERENCES `attendance` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
