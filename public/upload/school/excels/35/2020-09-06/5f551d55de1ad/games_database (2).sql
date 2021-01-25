-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2019 at 01:42 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nidara_core_qa_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `games_database`
--

CREATE TABLE `games_database` (
  `id` int(11) NOT NULL,
  `game_id` varchar(128) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `games_name` varchar(255) DEFAULT NULL,
  `tina` int(11) NOT NULL,
  `rahul` int(11) NOT NULL,
  `games_folder` varchar(255) DEFAULT NULL,
  `daily_tips` text,
  `game_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='games_database from entry';

--
-- Dumping data for table `games_database`
--

INSERT INTO `games_database` (`id`, `game_id`, `status`, `created_at`, `created_by`, `modified_at`, `games_name`, `tina`, `rahul`, `games_folder`, `daily_tips`, `game_type`) VALUES
(85196, '85196', 1, '2019-07-04 16:43:11', 85196, '2019-07-04 16:43:11', 'verticalline3m1w1', 1, 1, 'preschool/corehealth/m1w1/physdevperceptualmotor/verticalline3m1w1/', 'Give your child crayons and have them scribble with what they like.', 'verticalline3m1w1'),
(85304, '85304', 1, '2019-07-04 16:58:33', 85304, '2019-07-04 16:58:33', 'verticalline3m1w1', 1, 1, 'prekindergarten/corehealth/m1w1/physdevperceptualmotor/verticalline3m1w1/', 'Give your child crayons and have them scribble with what they like.', 'verticalline3m1w1'),
(85438, '85438', 1, '2019-07-04 17:21:44', 85438, '2019-07-04 17:21:44', 'verticalline3m1w1', 1, 1, 'kindergarten/corehealth/m1w1/physdevperceptualmotor/verticalline3m1w1/', 'Give your child crayons and have them scribble with what they like.', 'verticalline3m1w1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games_database`
--
ALTER TABLE `games_database`
  ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
