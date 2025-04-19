-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 03:00 PM
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
-- Database: `task_buddy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `password`) VALUES
(1, 'A', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'B', '$2y$10$KLeat0Wx3TOAvUqLtbima.sAfYMTar.t9I06.Cwsdai1HK5s.zKdS'),
(3, 'C', '$2y$10$Ypo98rqUqUu2drT6ILZ6M.cGZmS7CWZBviJybuEXn1xhQBNWUCL0a');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(40, 19, '1', 1),
(41, 19, '2', NULL),
(42, 20, '1', 1),
(43, 20, '2', NULL),
(44, 21, '3', 1),
(51, 24, '1', NULL),
(52, 24, '22', NULL),
(53, 24, '5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple choice','text') NOT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `survey_id`, `question_text`, `question_type`, `correct_answer`, `order_num`) VALUES
(19, 19, 'what is 1+2 ?', 'multiple choice', '', 0),
(20, 21, 'what is 1+2 ?', 'multiple choice', '', 0),
(21, 23, 'what is 1+2 ?', 'multiple choice', '', 0),
(24, 26, 'what is 1+4?', 'multiple choice', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `reward` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `description`, `reward`, `created_at`, `created_by`, `status`) VALUES
(18, '4', 'eg', 100.00, '2025-04-15 21:07:34', NULL, 'active'),
(19, '4', 'eg', 100.00, '2025-04-15 21:07:48', NULL, 'active'),
(20, '66', '', 100.00, '2025-04-15 21:09:42', NULL, 'active'),
(21, '66', '', 100.00, '2025-04-15 21:09:52', NULL, 'active'),
(23, 'vvvvv', '  dfgdfgdfg', 10.00, '2025-04-15 21:39:28', NULL, 'active'),
(26, 'Io', 'Io', 5.00, '2025-04-19 10:37:05', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed','pending') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`, `priority`) VALUES
(11, 11, 'hei', 'i', 'open', '2025-04-14 19:28:51', 'high'),
(12, 11, 'g', 'gg', 'open', '2025-04-14 21:29:44', 'high'),
(13, 12, 'p', 'ppp', 'open', '2025-04-14 21:48:47', 'high'),
(14, 12, 'h', 'mm', 'open', '2025-04-14 21:52:59', 'high'),
(15, 12, 'l', 'lll', 'open', '2025-04-14 22:05:28', 'high'),
(16, 14, 'Yo', 'Hi', 'open', '2025-04-15 17:00:11', 'medium'),
(17, 16, 'hello', 'Hii', 'open', '2025-04-15 22:57:55', 'medium'),
(18, 16, 'bro', 'hi\r\n', 'open', '2025-04-15 23:01:06', 'high'),
(20, 22, 'ijuhhuh', 'hello', 'open', '2025-04-19 10:18:06', 'medium'),
(21, 23, 'hi', 'hello', 'open', '2025-04-19 10:33:29', 'medium');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `reply_message` text NOT NULL,
  `replied_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `reply_message`, `replied_at`, `user_id`, `is_admin`) VALUES
(10, 11, 'great\r\n', '2025-04-15 01:42:32', 11, 1),
(11, 11, 'ok', '2025-04-15 01:43:07', 11, 1),
(12, 11, 'p', '2025-04-15 02:00:06', 11, 1),
(13, 11, 'hi', '2025-04-15 02:19:17', 11, 1),
(14, 11, 'hi', '2025-04-15 03:13:48', 1, 1),
(15, 11, 'hi', '2025-04-15 03:16:56', 1, 1),
(16, 11, 'what?', '2025-04-15 03:17:18', 1, 1),
(17, 11, 'huh', '2025-04-15 03:20:55', 1, 1),
(18, 11, 'hmm', '2025-04-15 03:21:37', 1, 1),
(19, 11, 'hey', '2025-04-15 03:26:11', 1, 1),
(20, 12, 'hey', '2025-04-15 03:34:27', 1, 1),
(21, 12, 'g', '2025-04-15 03:37:18', 1, 1),
(22, 12, 'hiio', '2025-04-15 03:47:31', 1, 1),
(23, 13, 'pp', '2025-04-15 03:49:10', 1, 1),
(24, 13, 'hey', '2025-04-15 03:51:16', 1, 1),
(25, 13, 'b', '2025-04-15 03:51:51', 1, 1),
(26, 14, 'Hi', '2025-04-15 03:57:39', 1, 1),
(27, 14, 'hey', '2025-04-15 04:09:05', 1, 1),
(28, 15, 'hey', '2025-04-15 04:32:02', 1, 1),
(29, 16, 'ok', '2025-04-16 01:48:57', 1, 1),
(30, 16, 'hi', '2025-04-16 02:12:39', 1, 1),
(31, 18, 'hi', '2025-04-16 05:19:28', 1, 1),
(32, 18, 'Hi', '2025-04-16 05:41:21', 1, 1),
(33, 18, 'Hi', '2025-04-16 05:51:18', 1, 1),
(34, 18, 'hello', '2025-04-16 06:05:59', 16, 0),
(35, 18, 'hiii', '2025-04-17 13:26:41', 1, 1),
(37, 20, 'Hi i need help', '2025-04-19 16:18:27', 22, 0),
(38, 20, 'Ok I got you message', '2025-04-19 16:21:50', 1, 1),
(39, 20, 'Yeah help me now', '2025-04-19 16:22:33', 22, 0),
(40, 21, 'hello I need help', '2025-04-19 16:33:48', 23, 0),
(41, 21, 'Hello I have recived', '2025-04-19 16:38:28', 1, 1),
(42, 21, 'Thank you', '2025-04-19 16:39:07', 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('deposit','withdrawal','survey_reward') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Bank Transfer','Bkash') NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `bkash_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `status`, `created_at`, `payment_method`, `bank_name`, `account_number`, `bkash_number`) VALUES
(27, 11, 'withdrawal', 10.00, '', '2025-04-15 01:26:03', 'Bank Transfer', '5555', '5556666', NULL),
(28, 14, 'withdrawal', 100.00, '', '2025-04-15 21:10:57', 'Bank Transfer', NULL, NULL, NULL),
(29, 15, 'withdrawal', 10.00, '', '2025-04-15 21:41:04', 'Bank Transfer', NULL, NULL, NULL),
(30, 16, 'withdrawal', 10.00, '', '2025-04-15 21:47:17', 'Bank Transfer', NULL, NULL, NULL),
(31, 16, 'withdrawal', 10.00, '', '2025-04-16 05:58:40', 'Bank Transfer', NULL, '042135488', NULL),
(32, 16, 'withdrawal', 10.00, '', '2025-04-16 06:05:48', 'Bank Transfer', NULL, '85468', NULL),
(33, 22, 'withdrawal', 10.00, '', '2025-04-19 10:17:42', 'Bank Transfer', NULL, '879978879', NULL),
(34, 23, 'withdrawal', 100.00, '', '2025-04-19 10:33:00', 'Bank Transfer', NULL, '5487878', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reward` int(11) DEFAULT 0,
  `balance` decimal(10,2) DEFAULT 0.00,
  `completed_tasks` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `reward`, `balance`, `completed_tasks`) VALUES
(11, 'p p', 'g@gmail.com', '$2y$10$F1UlyXs22poWvOLGWjuhv.KKVI4Tqxf5TK7RfYo30gvOKbfcWswWu', '2025-04-14 18:48:24', 10, -10.00, 1),
(12, 'p@gmail.com p', 'p@gmail.com', '$2y$10$h/YhbckJ2K91gMKp/eIIseOWAbomUMC6zh.oGKSeq06dlcoQlqzRi', '2025-04-14 21:48:26', 450, 450.00, 1),
(14, 'l p', 'l@gmail.com', '$2y$10$z6361NVxp3r03/XtTSxCyusyZi4bNOy09bAeVspsOU5XS33tzeLZy', '2025-04-15 16:55:09', 100, -100.00, 1),
(15, 'v p', 'v@gmail.com', '$2y$10$0iFi.jdxR.hZoMk47EhMOeCTyDfo9N34Bwe/Uuu5CeWFB1ym1Ssd2', '2025-04-15 21:40:25', 10, -10.00, 1),
(16, 'pp gg', 'x@gmail.com', '$2y$10$IRabs9FznvOB2uSOcHAlr.dluHqOq6VYgT0sTYnw.eutd4auPJ3ri', '2025-04-15 21:45:39', 130, 100.00, 4),
(17, 'rasel islam', 'rasel@gmail.com', '$2y$10$3UANWEV0nfJBGPC1k.idt.VFpV9cX3TBb90Uu70fInemZLtzLkfPm', '2025-04-17 07:19:34', 0, 0.00, 0),
(18, 'sudip goru', 'sudip@gmail.com', '$2y$10$99ZqzrCau5s6kvJIgVVkgOxXI9qwqec7pKp/ufQjK3MGQkTv2grIC', '2025-04-17 07:22:00', 0, 0.00, 0),
(19, 'ab b', 'a@gmail.com', '$2y$10$qtZXDMZe07GV5sn5hel4wuIP/wYrl1pnmVty1mN42sawPRTkGoQpy', '2025-04-17 07:23:39', 0, 0.00, 0),
(22, 'j p', 'j@gmail.com', '$2y$10$x82kHdmbPhCre07k6TqL9eulgUBj3jrkn4bHiRhAlL4rorIAIJYBq', '2025-04-19 10:15:26', 10, 0.00, 1),
(23, 'f f', 'f@gmail.com', '$2y$10$8Gl4IMDmlvIMvDN7KFwP..OPhKMaMoYjgE5SAcJU3UEJGLez.GzWW', '2025-04-19 10:29:40', 110, 10.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `last_name`, `email`, `mobile_number`, `age`, `address`, `state`, `zip_code`, `city`, `country`, `created_at`, `updated_at`) VALUES
(8, 11, 'p', 'p', 'g@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-14 18:48:24', '2025-04-14 18:48:24'),
(9, 12, 'p@gmail.com', 'p', 'p@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-14 21:48:26', '2025-04-14 21:48:26'),
(10, 14, 'l', 'p', 'l@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-15 16:55:09', '2025-04-15 16:55:09'),
(11, 15, 'v', 'p', 'v@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-15 21:40:25', '2025-04-15 21:40:25'),
(12, 16, 'pp', 'gg', 'x@gmail.com', '', 25, '', '', '', '', '', '2025-04-15 21:45:39', '2025-04-16 06:25:11'),
(13, 17, 'rasel', 'islam', 'rasel@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-17 07:19:34', '2025-04-17 07:19:34'),
(14, 18, 'sudip', 'goru', 'sudip@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-17 07:22:00', '2025-04-17 07:22:00'),
(15, 19, 'ab', 'b', 'a@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-17 07:23:39', '2025-04-17 07:23:39'),
(17, 22, 'j', 'p', 'j@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 10:15:26', '2025-04-19 10:15:26'),
(18, 23, 'f', 'f', 'f@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 10:29:40', '2025-04-19 10:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_surveys`
--

CREATE TABLE `user_surveys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `survey_id` int(11) DEFAULT NULL,
  `status` enum('completed','pending','in progress') DEFAULT 'pending',
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_surveys`
--

INSERT INTO `user_surveys` (`id`, `user_id`, `survey_id`, `status`, `completed_at`) VALUES
(26, 14, 19, 'completed', '2025-04-15 21:08:29'),
(27, 14, 21, 'completed', '2025-04-15 21:10:40'),
(28, 15, 23, 'completed', '2025-04-15 21:40:42'),
(32, 16, 19, 'completed', '2025-04-16 06:05:09'),
(33, 16, 20, 'completed', '2025-04-16 06:05:14'),
(34, 16, 21, 'completed', '2025-04-16 06:05:21'),
(35, 16, 23, 'completed', '2025-04-16 06:05:27'),
(36, 22, 23, 'completed', '2025-04-19 10:16:44'),
(38, 23, 21, 'completed', '2025-04-19 10:31:52'),
(39, 23, 19, 'completed', '2025-04-19 10:32:07'),
(40, 23, 23, 'completed', '2025-04-19 10:32:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_name` (`admin_name`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_surveys`
--
ALTER TABLE `user_surveys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_survey_unique` (`user_id`,`survey_id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_surveys`
--
ALTER TABLE `user_surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`);

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_surveys`
--
ALTER TABLE `user_surveys`
  ADD CONSTRAINT `user_surveys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_surveys_ibfk_2` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
