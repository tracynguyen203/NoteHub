-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2025 at 11:40 PM
-- Server version: 5.7.41-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ijdtkfvr_user_accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `activation_tokens`
--

CREATE TABLE `activation_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activation_tokens`
--

INSERT INTO `activation_tokens` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(3, 'test@gmail.com', 'ae1c52916e5819cbc661b0c3bb8af436', '2025-05-11 03:34:47', '2025-05-10 19:34:47'),
(30, 'hoainamxmen6@gmail.com', '96b0530affaca533996882ef680c82b7', '2025-05-17 01:11:30', '2025-05-16 17:11:30'),
(31, 'mmbmmbmmb1@gmail.com', '62ed7b04177a4d29a3cda9ea4f790f42', '2025-05-17 01:14:29', '2025-05-16 17:14:29'),
(34, 'hoainamxmen66@gmail.com', 'd5e91c827a79257d93a87fdd15e75f66', '2025-05-17 03:26:29', '2025-05-16 19:26:29'),
(35, 'mmbmmbmmb111@gmail.com', '972f22ea5d230d77e98c599dbdbe10c2', '2025-05-17 03:30:00', '2025-05-16 19:30:00'),
(36, '123213@gmail.com', '090b39fe96d1cb5d188d443aa7095599', '2025-05-17 03:39:25', '2025-05-16 19:39:25'),
(42, 'trantran7233@gmmail.com', 'bd15083dbad6c740a874e7bd1dedefeb', '2025-05-18 00:12:37', '2025-05-17 16:12:37'),
(43, 'trantran7233@gmail.com', 'd8715990a0485da5639bd86cb26335fb', '2025-05-18 00:17:24', '2025-05-17 16:17:24'),
(44, 'vxvxvxv@dad.com', '2c1bc1932dbf44efa443e95112e10db0', '2025-05-18 22:04:43', '2025-05-18 14:04:43'),
(46, 'ojw73314@jioso.com', '0ded7c9a6d31d24784b927a77bca6087', '2025-05-19 00:02:09', '2025-05-18 16:02:09'),
(48, 'trantran20325@gmail.com', '8f9cb45838a39de0c727f200c9d8e74f', '2025-05-19 00:23:57', '2025-05-18 16:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE `avatars` (
  `user_id` int(11) NOT NULL,
  `avatar_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `avatars`
--

INSERT INTO `avatars` (`user_id`, `avatar_url`) VALUES
(53, 'https://n0tehub.me/user_preference/avatar/11.png'),
(54, 'https://n0tehub.me/user_preference/avatar/10.png'),
(55, 'https://n0tehub.me/user_preference/avatar/1.png'),
(56, 'https://n0tehub.me/user_preference/avatar/8.png'),
(57, 'https://n0tehub.me/user_preference/avatar/4.png'),
(58, 'https://n0tehub.me/user_preference/avatar/4.png');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password` varchar(255) DEFAULT NULL,
  `is_password_protected` tinyint(1) DEFAULT '0',
  `pin_note` tinyint(1) NOT NULL,
  `font_note` varchar(255) DEFAULT 'Arial',
  `note_color` varchar(255) DEFAULT '#FAF1E6'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`, `password`, `is_password_protected`, `pin_note`, `font_note`, `note_color`) VALUES
(215, 6, 'Hello', '<div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p>Hello?\n                                            \n                                        </p></div>\n                                        ', '2025-05-16 11:56:42', '2025-05-18 10:59:10', NULL, 0, 0, 'Arial', '#FAF1E6'),
(258, 6, 'Two', '                                            <br>\n                                            <div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p><u>                                            \n                                                                                        \n                                                                                        \n                                            \n                                                \n                                            \n                                            ????\n                                            \n                                                \n                                            \n                                            \n                                            \n                                                \n                                            \n                                                Hello????\n                                            \n                                        \n                                        \n                                        </u></p></div>\n                                        ', '2025-05-17 04:13:46', '2025-05-18 10:54:47', NULL, 0, 0, 'Arial', '#FAF1E6'),
(261, 6, 'One', '                                            <br>\n                                            <div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p><i>                                            \n                                            \n                                                \n                                            \n                                            HOOO\n                                            \n                                                \n                                            \n                                            \n                                            \n                                                \n                                            \n                                            Nice to meet you!!! 123\n                                            \n                                        </i></p></div>\n                                        ', '2025-05-17 04:23:28', '2025-05-18 10:54:25', NULL, 0, 0, 'Arial', '#FAF1E6'),
(276, 6, 'Nine', '<div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p><b>????? Hi</b></p>\n                                        <p></p></div>\n                                        <p></p></div>\n                                        ', '2025-05-17 15:56:10', '2025-05-18 14:33:22', NULL, 0, 0, 'Arial', '#FAF1E6'),
(278, 54, 'Untitled Notertwe', ' ', '2025-05-17 16:21:26', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(279, 54, 'Untitled Note', ' ', '2025-05-17 16:25:39', '2025-05-18 15:23:09', NULL, 0, 1, 'Courier New', '#FFF9F0'),
(280, 54, 'Untitled Noteytrhbt', ' ', '2025-05-17 16:25:43', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(281, 54, 'Untitled Note', ' ', '2025-05-17 16:26:04', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(282, 54, 'Untitled', '                                            gertger                                            gertgerertbfge\n                                        ', '2025-05-17 16:26:20', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(285, 54, 'Untitled Note', '                                            \n                                                                                        ghczcfxbzdfb\n                                        ', '2025-05-17 16:35:15', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(287, 54, 'Untitle', '                                            sdg \n                                        ', '2025-05-17 17:16:21', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(349, 54, 'Untitled Note', ' ', '2025-05-18 14:03:20', '2025-05-18 15:23:09', NULL, 0, 0, 'Courier New', '#FFF9F0'),
(372, 1, 'Untitled Note', '                                            asdasdsa<br>\n                                            <div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p> </p></div>\n                                        ', '2025-05-18 14:25:37', '2025-05-18 14:25:56', '$2y$10$PSdsTkVgrdxunCQndOSbNucdfyhLNoVwdzvz6.eKE7lJT7ZQbqKNC', 1, 0, 'Arial', '#FAF1E6'),
(373, 1, 'Untitled Note', ' ', '2025-05-18 14:25:37', '2025-05-18 14:25:37', NULL, 0, 0, 'Arial', '#FAF1E6'),
(378, 56, 'Five', '                                            <br>\n                                            <div class=\"noteArea\" style=\"font-family:Arial;\n                                                        background:#FAF1E6;\"><p><i><b><u>Xin chÃ o tháº§y!</u></b></i></p></div>\n                                        ', '2025-05-18 14:52:45', '2025-05-18 16:40:24', NULL, 0, 0, 'Arial', '#FAF1E6'),
(381, 58, 'Untitled Note', ' ', '2025-05-18 16:13:26', '2025-05-18 16:13:26', NULL, 0, 0, 'Arial', '#FAF1E6'),
(383, 58, 'Untitled Note', ' ', '2025-05-18 16:13:30', '2025-05-18 16:13:30', NULL, 0, 0, 'Arial', '#FAF1E6'),
(384, 58, 'Untitled Note', ' ', '2025-05-18 16:15:10', '2025-05-18 16:15:10', NULL, 0, 0, 'Arial', '#FAF1E6'),
(386, 58, 'Untitled Note', ' ', '2025-05-18 16:15:34', '2025-05-18 16:15:34', NULL, 0, 0, 'Arial', '#FAF1E6'),
(388, 1, 'Untitled Note', ' ', '2025-05-18 16:15:57', '2025-05-18 16:15:57', NULL, 0, 0, 'Arial', '#FAF1E6');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(12, 'hoainamxmen@gmail.com', '7738a96f60d8235f0529d57479f830c8', '2025-05-14 12:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_verified`, `created_at`, `remember_token`) VALUES
(1, '523C0019', 'omriceuwu@gmail.com', '$2y$10$Cel5Fg4k2oVNb3PGs3Te9uDci/OM0pgXo18Hk19x9KT46lk4XrSHe', 1, '2025-05-04 17:40:47', '22f02db84976674dc1dc1b0aae6847bc'),
(4, 'mmb', 'jdkdjdkdndjdkk@gmail.com', '$2y$10$L5RbX6WWLDK/4UwuQcGObenD3zFFI/IxZl/RS7N5Q9JMByYWgqjyu', 0, '2025-04-21 09:31:31', NULL),
(5, '523C0019', 'hoainamxmen@gmail.com', '$2y$10$xHuqWx8jqBAYb9rLqp6AXe1VKEwqEp3WS38L9Z09q2Gqn3FkV44bu', 0, '2025-04-21 10:55:34', '176c290070d8b02901adfe673a183b81'),
(6, 'hi', 'hi@gmail.com', '$2y$10$BNqMTZNltjJcnhEfJZIBUeGxO4WfehvN65rlTyVLcAULLY92ZZ.Ea', 0, '2025-05-04 14:23:13', '73251944e982c7ed548b6de1d9ce83a2'),
(7, 'mmb', 'mmbmmbmmb@gmail.com', '$2y$10$1lVuYXcXau.I4CFImRrVre7EjZzENLCFi7o2Ydz2L0f.cuFHxR0GG', 0, '2025-05-04 14:26:58', NULL),
(13, 'testtt', 'test@gmail.com', '$2y$10$DdRInPEtcQ.RSCteCNu3TuPyh.o5TmvrGIb5krRxu8JFNEzFCqoTu', 0, '2025-05-10 19:34:47', 'bef3d45e72da7ad0b2b3c974dd54df0f'),
(40, 'mmb', 'hoainamxmen6@gmail.com', '$2y$10$ttW16Kcf2D/DoLc/yEq9k.L2CYL.Q1VoIQh3mRUhsEnJRAsD5A3Qu', 0, '2025-05-16 17:11:30', NULL),
(41, 'shigeui', 'mmbmmbmmb1@gmail.com', '$2y$10$ngPn1Kye1gQqUBo9.GjUWOBgERMy5N3sLSnCdxi2Sz44tD9zVV6gq', 0, '2025-05-16 17:14:29', NULL),
(44, 'admin', 'hoainamxmen66@gmail.com', '$2y$10$zPPBi3LUzIhoQQdLPDEFHOzYZYYrtLf39Wn.tG0hn9gLthom4lF6S', 0, '2025-05-16 19:26:29', NULL),
(45, 'mmb', 'mmbmmbmmb111@gmail.com', '$2y$10$ER3WdYU6PEz5vQkafryDmejOlpYB5q6XIpRVnpdE0LRiDyl1xcmDO', 0, '2025-05-16 19:30:00', NULL),
(46, 'admin', 'mmbmmbmmb312@gmail.com', '$2y$10$1qEKVitBWNDEj21kjFJ2ougeLu8G6mfEjnfIVvSQPAbhm9UHp/T5W', 0, '2025-05-16 19:35:07', NULL),
(47, 'mmb', '123213@gmail.com', '$2y$10$SWtcrb.E3gIoWpDg9TMTk.ex.kKyyKupo0Z5C4140oSy3ryhyv3ty', 0, '2025-05-16 19:39:25', NULL),
(53, 'tr', 'trantran7233@gmmail.com', '$2y$10$HECvtg9sB1owafjL46fTf.8I3xHqkB/O1DdoeSCHA1WWyOaLVy4pC', 0, '2025-05-17 16:12:37', NULL),
(54, 'tran', 'trantran7233@gmail.com', '$2y$10$4dc6omLqKWBaAJXS/ZJMu.BMOalP01DZHhFVbJ0zO8AuhbuTC8re2', 0, '2025-05-17 16:17:24', '9883c02fd892adb74edbe5e9dc411932'),
(55, '[ - AtLast ]', 'vxvxvxv@dad.com', '$2y$10$JIkiFxj8Bl27CQ/Qtud46.Tmfv1YhYlAmWb.5elYZtuPnerhS08J6', 0, '2025-05-18 14:04:43', NULL),
(56, 'admin', 'notehub1805@gmail.com', '$2y$10$.MHckaHWn2Fiz977EXP1Vefamw.Ap5jZocxkKaGAjUyo5B4SwDkq6', 1, '2025-05-18 14:38:20', 'f2e5f075116840cd79fa433d01cbbfd3'),
(57, 'mmb', 'ojw73314@jioso.com', '$2y$10$ZhbvdqNIUsRELyYFpxrot.eaZu/ywcYBceQcJdexBALbsNNxNBjNy', 0, '2025-05-18 16:02:09', NULL),
(58, 'asdasds', 'vof64330@jioso.com', '$2y$10$IT/ZqKOwHICYrD3m/cdv3.qNGTVv84UZAUnKxIs4dl5GfC6Kht/02', 1, '2025-05-18 16:06:17', 'ba54c162813843a276b0800578a57519'),
(59, 't', 'trantran20325@gmail.com', '$2y$10$UyHhW2Uf3ZKoUt16OiOKBeqwdqB2pc6eXCs6cYAt9NT1fhaV09Ske', 0, '2025-05-18 16:23:57', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation_tokens`
--
ALTER TABLE `activation_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_ibfk_1` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
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
-- AUTO_INCREMENT for table `activation_tokens`
--
ALTER TABLE `activation_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activation_tokens`
--
ALTER TABLE `activation_tokens`
  ADD CONSTRAINT `activation_tokens_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `avatars`
--
ALTER TABLE `avatars`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
