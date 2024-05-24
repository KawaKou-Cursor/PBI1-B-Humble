-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-05-07 14:50:24
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `projectdb`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `language` varchar(50) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `projects`
--

INSERT INTO `projects` (`id`, `title`, `code`, `description`, `language`, `image_name`, `created_at`, `image`) VALUES
(58, 'うぇｆ', 'うぇふぇｗｆ', 'うぇふぇｆｗふぇ', 'C', '', '2024-05-07 04:44:44', NULL),
(59, 'うぇｆ', 'うぇふぇｗｆ', 'うぇふぇｆｗふぇ', 'C', '', '2024-05-07 04:45:46', NULL),
(60, 'あさｓかｓかｓかｓ', 'あｓかｓかｓかかｓかｓｓｓｓｓｓｓｓｓｓｓｓｓｓｓｓｓ', 'あｓかｓかｓかｓかｓｃ', 'Kotlin', '', '2024-05-07 04:46:55', NULL),
(61, 'w背えええええええええうぇｃｗｃうぇえｃうぇｃｗｃｗｃうぇｃうぇｃうぇｃｗｗｃｗせっうぇｗｃｗせｗｃｗｗｗせｗｃえええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええええ', 'うぇｃうぇｃｗｃｗ', 'ｗｃｗｃうぇｃ', 'C', '', '2024-05-07 04:55:37', NULL),
(62, 'ｖｓｖｄｖ', 'ｓｄｖｓｄｖ', 'ｓｄｖｓｄｖｖ', 'C++', '', '2024-05-07 05:06:35', NULL),
(63, '10回繰り返すfor文', '#include <stdio.h>\r\n\r\nint main()\r\n{\r\n    int i;\r\n\r\n    for (i = 1; i <= 10; i++)\r\n    {\r\n        printf(\"Hello!\\n\");\r\n    }\r\n}', '簡単なもの', 'C', '', '2024-05-07 05:42:27', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `ratings`
--

INSERT INTO `ratings` (`id`, `project_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(31, 61, 5, NULL, '2024-05-07 05:18:59', '2024-05-07 05:18:59'),
(32, 61, 3, NULL, '2024-05-07 05:23:24', '2024-05-07 05:23:24');

-- --------------------------------------------------------

--
-- テーブルの構造 `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `projects` ADD FULLTEXT KEY `image_name` (`image_name`);

--
-- テーブルのインデックス `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- テーブルの AUTO_INCREMENT `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- テーブルの AUTO_INCREMENT `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
