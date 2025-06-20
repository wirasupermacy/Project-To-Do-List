-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 04:32 AM
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
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `due_date`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(1, 1, 'Setup Database Schema', 'Membuat dan mengoptimalkan skema database todolist', '2025-06-05 00:00:00', 'completed', 'high', '2025-06-03 05:45:34', '2025-06-03 05:45:34'),
(2, 1, 'Implementasi Autentikasi', 'Membuat sistem login dan registrasi yang aman', '2025-06-08 00:00:00', 'pending', 'high', '2025-06-03 05:45:34', '2025-06-03 05:45:34'),
(5, 3, 'Meeting dengan Tim', 'Diskusi progress project minggu ini\n[Completed: 2025-06-03 13:58:03]', '2025-06-08 00:00:00', 'completed', 'high', '2025-06-03 05:45:34', '2025-06-03 05:58:03'),
(6, 3, 'Code Review', 'Review kode aplikasi todo list\n[Completed: 2025-06-03 13:46:23]\n[Completed: 2025-06-03 13:58:02]\n[Completed: 2025-06-03 13:58:33]\n[Completed: 2025-06-03 13:58:35]', '2025-06-12 00:00:00', 'pending', 'medium', '2025-06-03 05:45:34', '2025-06-03 05:58:37'),
(7, 4, 'Backup Database', 'Melakukan backup rutin database aplikasi', '2025-06-12 00:00:00', 'completed', 'low', '2025-06-03 05:45:34', '2025-06-03 05:45:34'),
(8, 4, 'Testing Aplikasi', 'Melakukan pengujian menyeluruh aplikasi', '2025-06-14 00:00:00', 'pending', 'high', '2025-06-03 05:45:34', '2025-06-03 05:45:34'),
(9, 5, 'Update Dokumentasi', 'Memperbarui dokumentasi API', '2025-06-20 00:00:00', 'pending', 'medium', '2025-06-03 05:45:34', '2025-06-03 05:45:34'),
(11, 3, 'Matematika', 'Beli buah\n[Completed: 2025-06-03 13:58:45]', '2025-06-19 00:00:00', 'pending', 'medium', '2025-06-03 05:55:51', '2025-06-03 05:58:51'),
(15, 13, 'TA', 'Tugas Akhir\n[Completed: 2025-06-05 17:07:08]', '2025-06-11 00:00:00', 'completed', 'high', '2025-06-05 09:06:52', '2025-06-05 09:07:08'),
(16, 8, 'Matematika', 'deadlinenya besok\n[Completed: 2025-06-05 20:45:56]\n[Completed: 2025-06-07 21:42:27]', '2025-06-06 00:00:00', 'completed', 'medium', '2025-06-05 12:45:01', '2025-06-07 13:42:27'),
(17, 8, 'makan', 'protein\n[Completed: 2025-06-06 10:15:13]', '2025-06-05 00:00:00', 'completed', 'low', '2025-06-05 13:40:01', '2025-06-06 02:15:13'),
(18, 8, 'zebra', 'hewan\n[Completed: 2025-06-06 16:32:52]', '2025-06-07 00:00:00', 'completed', 'high', '2025-06-05 13:40:40', '2025-06-06 08:32:52'),
(19, 8, 'asd', 'asdsd\n[Completed: 2025-06-06 16:32:51]', NULL, 'completed', 'medium', '2025-06-05 13:41:16', '2025-06-08 02:46:59'),
(20, 21, 'coming soon', 'deadline 10 juni\n[Completed: 2025-06-05 23:33:10]', '2025-06-05 00:00:00', 'completed', 'high', '2025-06-05 15:33:03', '2025-06-05 15:33:10'),
(21, 21, 'makan', 'nasi', '2025-06-05 00:00:00', 'pending', 'low', '2025-06-05 15:36:24', '2025-06-05 15:36:24'),
(22, 8, 'sasd', 'sdasd\n[Completed: 2025-06-11 21:10:55]', '2025-06-06 00:00:00', 'completed', 'medium', '2025-06-05 16:35:04', '2025-06-11 13:10:55'),
(23, 8, 'sasa', 'saaa', '2025-06-06 00:00:00', 'pending', 'medium', '2025-06-05 16:35:46', '2025-06-05 16:35:46'),
(24, 8, 'sasa', 'sasa', '2025-06-06 00:00:00', 'pending', 'medium', '2025-06-05 16:36:02', '2025-06-05 16:36:02'),
(25, 8, 'makan', 'sasa\n[Completed: 2025-06-07 22:53:06]', '2025-06-06 00:00:00', 'completed', 'high', '2025-06-05 16:43:16', '2025-06-07 14:53:06'),
(26, 8, 'fdada', '\n[Completed: 2025-06-07 22:53:05]', '2025-06-06 00:00:00', 'completed', 'medium', '2025-06-05 16:49:48', '2025-06-07 14:53:05'),
(27, 8, 'asdasd', 'dasdas\n[Completed: 2025-06-07 22:09:23]', '2025-06-06 00:00:00', 'completed', 'medium', '2025-06-05 16:50:05', '2025-06-07 14:09:23'),
(28, 8, 'sdasd', 'sdasd\n[Completed: 2025-06-07 22:09:31]\n[Completed: 2025-06-07 22:09:35]', '2025-06-06 00:00:00', 'completed', 'high', '2025-06-05 17:02:57', '2025-06-07 14:09:35'),
(30, 8, 'sadsdas', 'dsadsd\n[Completed: 2025-06-06 10:15:03]', '2025-06-06 00:00:00', 'completed', 'low', '2025-06-05 17:07:33', '2025-06-06 02:15:03'),
(32, 22, 'scas', 'csaca', '2025-06-06 00:00:00', 'pending', 'medium', '2025-06-05 17:18:55', '2025-06-05 17:18:55'),
(33, 23, 'as', 'ad', '2025-06-06 00:00:00', 'pending', 'high', '2025-06-06 01:15:35', '2025-06-06 01:15:35'),
(34, 23, 'sdas', 'czxczc', '2025-06-06 00:00:00', 'pending', 'medium', '2025-06-06 01:57:06', '2025-06-06 01:57:06'),
(35, 23, 'xczxc', 'kmknknkn', '2025-06-06 00:00:00', 'pending', 'high', '2025-06-06 01:57:21', '2025-06-06 01:58:27'),
(36, 8, 'sa', 'dsd\n[Completed: 2025-06-06 10:14:47]\n[Completed: 2025-06-06 10:14:53]\n[Completed: 2025-06-06 10:16:49]', '2025-06-06 00:00:00', 'completed', 'medium', '2025-06-06 02:14:45', '2025-06-06 02:16:49'),
(39, 25, 'makan', 'satae ayam\n[Completed: 2025-06-07 13:32:57]\n[Completed: 2025-06-07 13:34:10]', '2025-06-07 00:00:00', 'completed', 'medium', '2025-06-07 05:32:27', '2025-06-07 05:34:10'),
(40, 25, 'makan', 'babi\n[Completed: 2025-06-07 13:32:55]', '2025-06-08 00:00:00', 'completed', 'high', '2025-06-07 05:32:51', '2025-06-07 05:32:55'),
(42, 8, 'sadasdas', 'sas\n[Completed: 2025-06-07 22:43:25]', '2025-07-05 00:00:00', 'completed', 'low', '2025-06-07 14:05:13', '2025-06-07 14:43:25'),
(45, 8, 'sadasdas', 'aaaaa', '2025-06-07 00:00:00', 'pending', 'high', '2025-06-07 15:23:18', '2025-06-07 15:23:18'),
(46, 8, 'makan', '', '2025-06-07 00:00:00', 'pending', 'medium', '2025-06-07 15:23:36', '2025-06-07 15:23:36'),
(47, 8, 'makan', '\n[Completed: 2025-06-09 20:55:51]', '2025-06-07 00:00:00', 'pending', 'medium', '2025-06-07 15:27:23', '2025-06-09 12:55:53'),
(48, 8, 'makan', '', '2025-06-07 00:00:00', 'pending', 'medium', '2025-06-07 15:35:19', '2025-06-07 15:35:19'),
(49, 8, 'mandi', '', '2025-06-08 00:00:00', 'pending', 'low', '2025-06-08 02:47:19', '2025-06-08 02:47:19'),
(51, 8, 'mandi', 'adadaa\n[Completed: 2025-06-09 23:48:53]\n[Completed: 2025-06-09 23:49:12]', '2025-06-09 00:00:00', 'completed', 'high', '2025-06-09 15:48:47', '2025-06-09 15:49:12'),
(52, 29, 'makan', '\n[Completed: 2025-06-10 09:38:52]', '2025-06-10 00:00:00', 'completed', 'high', '2025-06-10 01:38:49', '2025-06-10 01:38:52'),
(53, 8, 'makan', 'aaa', '2025-06-10 00:00:00', 'pending', 'high', '2025-06-10 09:12:22', '2025-06-10 09:12:22'),
(54, 8, 'sadasdas', 'sdadasd\n[Completed: 2025-06-19 11:45:56]', '2025-06-10 21:06:00', 'completed', 'medium', '2025-06-10 09:26:44', '2025-06-19 03:45:56'),
(58, 30, 'coming soon', 'Upload Hari Ini\n[Completed: 2025-06-10 18:10:02]\n[Completed: 2025-06-10 18:10:35]', '2025-06-10 23:00:00', 'completed', 'high', '2025-06-10 10:09:45', '2025-06-10 10:10:44'),
(60, 32, 'Tugas Backend', 'Aplikasi Todolist berbasis website\n[Completed: 2025-06-11 00:46:29]', '2025-06-11 23:59:00', 'completed', 'high', '2025-06-10 16:28:22', '2025-06-10 16:46:29'),
(63, 34, 'makan', '', '2025-06-20 14:42:00', 'pending', 'high', '2025-06-19 01:42:28', '2025-06-19 01:42:28'),
(64, 36, 'Tugas Backend', 'qq', '2025-06-19 13:26:00', 'pending', 'medium', '2025-06-19 03:26:46', '2025-06-19 03:26:46'),
(66, 38, 'up', '', '2025-06-19 11:37:00', 'pending', 'medium', '2025-06-19 03:32:18', '2025-06-19 03:32:18');

--
-- Triggers `tasks`
--
DELIMITER $$
CREATE TRIGGER `tr_task_status_update` BEFORE UPDATE ON `tasks` FOR EACH ROW BEGIN
    -- Set updated_at
    SET NEW.updated_at = NOW();
    
    -- Jika status berubah ke completed, catat waktu completion di description
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        SET NEW.description = CONCAT(
            COALESCE(OLD.description, ''), 
            '\n[Completed: ', NOW(), ']'
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `task_details`
-- (See below for the actual view)
--
CREATE TABLE `task_details` (
`id` int(11)
,`title` varchar(200)
,`description` text
,`due_date` datetime
,`status` enum('pending','completed')
,`priority` enum('low','medium','high')
,`created_at` timestamp
,`updated_at` timestamp
,`username` varchar(255)
,`email` varchar(255)
,`task_status_detail` varchar(9)
,`days_until_due` int(7)
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@todolist.com', '$2y$10$dlQk.bHZ51CxYu1vCY3mY.DgDZ0VgRcR0lnzIh5OOaITKwCwdi072', '2025-06-03 05:45:34'),
(3, 'Wira Leonhart', 'putugalager80@sma.belajar.id', '$2y$10$FW8kU/zE1YlbhouiHZZ2seFShhuKawVcd1sOXEBFzMNK.eAODUNRu', '2025-05-30 05:46:56'),
(4, 'dimas', 'kiritolord387@gmail.com', '$2y$10$1Eo3slTuNFVOYTkazblQIOkOUrDMWTkrIQnjo58FCEwA19qZzq8km', '2025-05-30 05:53:52'),
(5, 'galager', 'galager@2005.com', '$2y$10$eZ2ST8wuuAzdxJAoRxpHf.mJhIgAFagT/l2RwgT.ReiyefLPJ..EK', '2025-05-31 20:38:09'),
(8, 'wira', 'wirapramana18.id@gmail.com', '$2y$10$ehq/5rVqyetkDPpYK7CbaeWKdGPwEYjfGIMHcB.blDQvHL8VqFhmO', '2025-06-05 04:34:46'),
(9, 'diki', 'diki@12334423e1.gmail.com', '$2y$10$THtD2riQgHSnsJmvyMra9OBrkgym3icfLZaGNTCQ4PRhPAu7JWvrG', '2025-06-05 07:26:43'),
(10, 'melan', 'melan@123gmail.com', '$2y$10$/VUVI1CfSL9tq9oWJv9KHeNY1zOtJ5b5D9itVxb3W6IdhbkYbawBW', '2025-06-05 07:28:43'),
(11, 'juna', 'juna@gmail.com', '$2y$10$w.Eo4/Zo24/dwSOnCOMgXeivxPbFHpSharJFaVnwMGnlIY2kyXzAm', '2025-06-05 08:08:58'),
(12, 'dimas', 'dimas@gmail.com', '$2y$10$dmwLB/7cWccbepRNI7xQROMRCfsw2kPipelQqNKObhm/icg.LrZqq', '2025-06-05 08:09:48'),
(13, 'adi', 'adi@gmail.com', '$2y$10$UGifdTbCGx3N0VF7rDUo8OlKqc6B.ZFobwQmUz5bmSVIQS7mKfxYa', '2025-06-05 09:04:35'),
(14, 'Wira Leonhart', 'wira@gmail.com', '$2y$10$qFDqxwe31PZLSAsKoXde0eXNBb2E0ZKQVbaVBhLVxEv..BBHDK24O', '2025-06-05 13:57:53'),
(15, 'LALA', 'lala@gmail.com', '$2y$10$aB0DWnqB5rf46epTNOMXTO9GIkDvBbTqKIrNJmJk0DPlOZ4W1PZty', '2025-06-05 14:39:36'),
(16, 'alal', 'alal@gmail.com', '$2y$10$IToZCB0Z2veVuWtcNtn3qOcVca6eDDLiCVXjBnfbSOUd3BfVFsa7y', '2025-06-05 14:42:40'),
(18, 'kiri', 'kiri@gmail.com', '$2y$10$.bI3c.EdJ3bz51crQf60x.iWac02e79w7iqhoM4TBgGOH12lly7T6', '2025-06-05 15:16:43'),
(19, 'prema', 'prema@gmail.com', '$2y$10$IiAVYc4vo1CIRlDngAVd/O3QUkIW7gxYtoZl1EiHPdvlldsb.ZAre', '2025-06-05 15:22:38'),
(20, 'xixixxi', 'xixix@gmail.com', '$2y$10$hUnfx67e6AFFSbgdlqkZVOWvxUAkWu7jLVW33HOBAwD9Ll1y8QVlO', '2025-06-05 15:27:19'),
(21, 'rigog', 'rigog@gmail.com', '$2y$10$BwDVf33CMWYTcoDQP3/NWuNn3BUICnGoHBPV2cJPLoqpLS0jOPSze', '2025-06-05 15:32:02'),
(22, 'niel', 'niel@gmail.com', '$2y$10$1kj8qfZWcJ5QA46R/roGlufVcmcRej964izw15mYmIgHbgI03lYsy', '2025-06-05 17:18:24'),
(23, 'adam', 'adam@gmail.com', '$2y$10$9SKYfhfknPz6kCtGf.aTwej.V7EgHqcv05hbWKOj5v/9TDXk5Lzla', '2025-06-06 01:15:07'),
(24, 'adsdasda', 'sda@gmail.com', '$2y$10$pFHPUpNL.F5TU4bJWb9teec.0UPg7lPLWlYNY5VypfD2LwHcFKzCq', '2025-06-06 10:06:34'),
(25, 'gusde', 'gusde@gmail.com', '$2y$10$HETQLE47apYBE3.XP7Zky..w4.OHFw28xC.zkDUPsnEKNWd35bleC', '2025-06-07 05:31:11'),
(26, 'Dipa Okta', 'dipa@gmail.com', '$2y$10$ThgQyUsJyYDRB3nc53r.9uP/EAqS1VZpvdHFyWK8XX21rYHAeFDma', '2025-06-07 15:43:10'),
(27, 'kirito', 'kirxd@gmal.com', '$2y$10$Uk4og2PIS09RSKAyRe/w3ujkgGENj/AI3uaRwrJnBU504aYcF6qIe', '2025-06-09 14:56:57'),
(28, 'galager', 'bali@gmail.com', '$2y$10$0SpcBlCc17Li5K0DOfKfuOA8gXBmUUDjRpiop48DBRg72R29WxcCS', '2025-06-09 15:47:18'),
(29, 'Rindogh', 'rindgogh@gmail.com', '$2y$10$vo1Dp4PYs9MhcV/acFJW0.NcG1Fa7FaLsQuFWtT.49UeHry7DYoee', '2025-06-10 01:38:17'),
(30, 'Lerdi', 'lerdi@gmail.com', '$2y$10$2fKLomTn5rf9G/E3xHv/w.Co8PFtqtUiACZjytnNyameWQ.NRn1AK', '2025-06-10 10:09:04'),
(31, 'Ajus', 'Ajus@gmail.com', '$2y$10$DlO6YsaayWj7MwPfgAVzFOyAdAYT6wWeBs1h7p6hEx1nRqZDR62nG', '2025-06-10 10:17:22'),
(32, 'Wira Pramana', 'wp@gmail.com', '$2y$10$vilQt9ALeMHrGSUO5obuoeyE1K/xM96MrY0rJz1tnm.99KEJ/M7TK', '2025-06-10 16:24:15'),
(33, 'cokis', 'cokis@123gmail.com', '$2y$10$f8aS.u1Is/OILwz06cE7Yu2013pxcToIBkArBy16TynCs9Bsn4rqG', '2025-06-11 03:35:40'),
(34, 'jaya', 'jayadi@gmail.com', '$2y$10$rUW.2jEbddPLoLfswF/RLOtJk/RvxChVR3QI/4qg/HqQXqKy6cL2O', '2025-06-19 01:33:36'),
(35, 'mangde', 'mangde@gmail.com', '$2y$10$hrmc.ChmzDh5lgfo1n0HjuKvyko4NnX1SN/3mCNBOWT4oIcMEOX2O', '2025-06-19 03:21:45'),
(36, 'dicky', 'dicky@gmail.com', '$2y$10$SVAzSXhKygM5ERvz7bpWdefIIVtMF8SKQvgKgF8w/0rF7LYmSlOPm', '2025-06-19 03:26:26'),
(37, 'riki', 'riki@gmail.com', '$2y$10$LNae5YFVnU23jijDiRRLtOtpY33qjuWUc6xWnGudHmufMMhfRfKbW', '2025-06-19 03:30:59'),
(38, 'dika', 'dika@gmail.com', '$2y$10$YdIkDak34jD9w8nvjXfuc.A4Tc2QFKw3hhKeCEeimEoYVkRFe6iGC', '2025-06-19 03:31:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_task_stats`
-- (See below for the actual view)
--
CREATE TABLE `user_task_stats` (
`user_id` int(11)
,`username` varchar(255)
,`email` varchar(255)
,`total_tasks` bigint(21)
,`completed_tasks` decimal(22,0)
,`pending_tasks` decimal(22,0)
,`overdue_tasks` decimal(22,0)
,`due_today_tasks` decimal(22,0)
,`completion_percentage` decimal(28,2)
);

-- --------------------------------------------------------

--
-- Structure for view `task_details`
--
DROP TABLE IF EXISTS `task_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `task_details`  AS SELECT `t`.`id` AS `id`, `t`.`title` AS `title`, `t`.`description` AS `description`, `t`.`due_date` AS `due_date`, `t`.`status` AS `status`, `t`.`priority` AS `priority`, `t`.`created_at` AS `created_at`, `t`.`updated_at` AS `updated_at`, `u`.`username` AS `username`, `u`.`email` AS `email`, CASE WHEN `t`.`due_date` < curdate() AND `t`.`status` = 'pending' THEN 'overdue' WHEN `t`.`due_date` = curdate() AND `t`.`status` = 'pending' THEN 'due_today' ELSE `t`.`status` END AS `task_status_detail`, to_days(`t`.`due_date`) - to_days(curdate()) AS `days_until_due` FROM (`tasks` `t` join `users` `u` on(`t`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `user_task_stats`
--
DROP TABLE IF EXISTS `user_task_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_task_stats`  AS SELECT `u`.`id` AS `user_id`, `u`.`username` AS `username`, `u`.`email` AS `email`, count(`t`.`id`) AS `total_tasks`, sum(case when `t`.`status` = 'completed' then 1 else 0 end) AS `completed_tasks`, sum(case when `t`.`status` = 'pending' then 1 else 0 end) AS `pending_tasks`, sum(case when `t`.`due_date` < curdate() and `t`.`status` = 'pending' then 1 else 0 end) AS `overdue_tasks`, sum(case when `t`.`due_date` = curdate() and `t`.`status` = 'pending' then 1 else 0 end) AS `due_today_tasks`, round(sum(case when `t`.`status` = 'completed' then 1 else 0 end) / count(`t`.`id`) * 100,2) AS `completion_percentage` FROM (`users` `u` left join `tasks` `t` on(`u`.`id` = `t`.`user_id`)) GROUP BY `u`.`id`, `u`.`username`, `u`.`email` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_due_date` (`due_date`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
