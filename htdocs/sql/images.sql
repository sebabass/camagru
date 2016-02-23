-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2016 at 07:10 AM
-- Server version: 5.6.28
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camagru`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id_image` int(11) NOT NULL,
  `by_user` int(11) NOT NULL,
  `src` varchar(255) NOT NULL,
  `likes` mediumtext NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id_image`, `by_user`, `src`, `likes`, `date`) VALUES
(1, 1, 'img/sebabass_7e7eca884077ca7eee979b6f811f00e3.png', 'a:1:{i:0;i:1;}', '0000-00-00 00:00:00'),
(2, 1, 'img/sebabass_bd3998b417331f753194acad3ea53ec7.png', 'a:0:{}', '0000-00-00 00:00:00'),
(3, 1, 'img/sebabass_c2726f47401563a8aceaada1cde1801f.png', 'a:1:{i:0;i:1;}', '0000-00-00 00:00:00'),
(4, 1, 'img/sebabass_af92d6da289b198435f11094b663e3a2.png', 'a:0:{}', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_image`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
