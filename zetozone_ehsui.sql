-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2022 at 11:44 AM
-- Server version: 5.6.51
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zetozone_ehsui`
--

-- --------------------------------------------------------

--
-- Table structure for table `config_app`
--

CREATE TABLE `config_app` (
  `id` int(11) NOT NULL,
  `host_id` int(4) NOT NULL,
  `time_zone` varchar(30) NOT NULL,
  `date_format` varchar(30) NOT NULL,
  `autosend_sms` int(1) NOT NULL,
  `autosend_email` int(1) NOT NULL,
  `voucher_row` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config_app`
--

INSERT INTO `config_app` (`id`, `host_id`, `time_zone`, `date_format`, `autosend_sms`, `autosend_email`, `voucher_row`) VALUES
(1, 0, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(2, 1, 'Asia/Kolkata', 'd/m/Y', 1, 1, 3),
(3, 17, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(4, 2, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(5, 15, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(6, 23, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(7, 25, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(8, 26, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3),
(9, 21, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `config_email`
--

CREATE TABLE `config_email` (
  `id` int(11) NOT NULL,
  `host_id` int(4) NOT NULL,
  `protocol` varchar(4) NOT NULL,
  `smtp_authentication` varchar(3) NOT NULL,
  `host_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `smtp_security` varchar(5) NOT NULL,
  `port_no` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config_email`
--

INSERT INTO `config_email` (`id`, `host_id`, `protocol`, `smtp_authentication`, `host_name`, `username`, `password`, `smtp_security`, `port_no`) VALUES
(1, 0, 'SMTP', 'Yes', 'smtp.mail.com', 'username', 'password', 'TLS', '25'),
(3, 1, 'SMTP', 'Yes', 'mail.server.com', 'username', 'password', 'TLS', '25'),
(4, 2, 'SMTP', 'Yes', 'smtp.mail.com', 'username', 'password', 'TLS', '25'),
(5, 15, 'SMTP', 'Yes', 'smtp.mail.com', 'username', 'password', 'TLS', '25'),
(6, 17, 'SMTP', 'Yes', 'smtp.mail.com', 'username', 'password', 'TLS', '25');

-- --------------------------------------------------------

--
-- Table structure for table `config_print`
--

CREATE TABLE `config_print` (
  `id` int(11) NOT NULL,
  `host_id` int(4) NOT NULL,
  `top_margin` decimal(5,2) NOT NULL,
  `bottom_margin` decimal(5,2) NOT NULL,
  `left_margin` decimal(5,2) NOT NULL,
  `right_margin` decimal(5,2) NOT NULL,
  `show_profile` varchar(3) NOT NULL,
  `show_password` varchar(3) NOT NULL,
  `show_email` varchar(3) NOT NULL,
  `show_telephone` varchar(3) NOT NULL,
  `show_data_limit` varchar(3) NOT NULL,
  `show_uptime_limit` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config_sms`
--

CREATE TABLE `config_sms` (
  `id` int(11) NOT NULL,
  `host_id` int(4) NOT NULL,
  `api_url` varchar(100) NOT NULL,
  `param_name1` varchar(50) NOT NULL,
  `param_value1` varchar(50) NOT NULL,
  `param_name2` varchar(50) NOT NULL,
  `param_value2` varchar(50) NOT NULL,
  `param_name3` varchar(50) NOT NULL,
  `param_value3` varchar(50) NOT NULL,
  `param_name4` varchar(50) NOT NULL,
  `param_value4` varchar(50) NOT NULL,
  `param_name5` varchar(50) NOT NULL,
  `param_value5` varchar(50) NOT NULL,
  `param_name6` varchar(50) NOT NULL,
  `param_value6` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config_sms`
--

INSERT INTO `config_sms` (`id`, `host_id`, `api_url`, `param_name1`, `param_value1`, `param_name2`, `param_value2`, `param_name3`, `param_value3`, `param_name4`, `param_value4`, `param_name5`, `param_value5`, `param_name6`, `param_value6`) VALUES
(1, 0, 'sms.api.com', '', '', '', '', '', '', '', '', '', '', '', ''),
(3, 1, 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms', 'message', 'message', 'mobileNos', 'mobile', 'AUTH_KEY', 'b8f93fb07624a1d860c2edfdf330c59b', 'senderId', 'ZetZon', 'routeId', '1', 'smsContentType', 'english'),
(4, 2, 'sms.api.com', '', '', '', '', '', '', '', '', '', '', '', ''),
(5, 15, 'sms.api.com', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, 17, 'sms.api.com', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `host`
--

CREATE TABLE `host` (
  `id` int(11) NOT NULL,
  `company` varchar(50) NOT NULL,
  `address` varchar(256) NOT NULL,
  `contact_person` varchar(30) NOT NULL,
  `telephone` varchar(22) NOT NULL,
  `email` varchar(35) NOT NULL,
  `host_ip` varchar(20) NOT NULL,
  `created_on` datetime NOT NULL,
  `valid_till` datetime NOT NULL,
  `logo_image` varchar(50) NOT NULL,
  `hotspot_name` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `host` varchar(64) NOT NULL,
  `user` varchar(15) NOT NULL,
  `pass` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `host`
--

INSERT INTO `host` (`id`, `company`, `address`, `contact_person`, `telephone`, `email`, `host_ip`, `created_on`, `valid_till`, `logo_image`, `hotspot_name`, `status`, `host`, `user`, `pass`) VALUES
(1, 'Demo Inc.', 'Kumily, Kerala - 685509', 'Jinu Devasia', '999 672 7811', 'ehsui@zetozone.com', '45.123.0.41', '2019-06-25 00:00:00', '2019-09-30 00:00:00', 'greenwoods.jpg', 'Demo Server', 'Active', '5d6905f89c1e.sn.mynetname.net', 'zetozone', 'zeto@111'),
(17, 'Grand Thekkady', 'Kumily', 'Jaison Abraham', '9999 9999 99', 'jaison@gmail.com', '103.16.45.238', '2019-07-17 15:42:47', '2023-01-31 00:00:00', '', 'Grand Thekkady', 'Active', '103.16.45.238', 'zetozone', 'zeto@222'),
(2, 'Kondody Greenwoods Resort', 'Thekkady, Kumily', 'Jaison Abraham', '7109291891', 'greenwoods@gmail.com', '45.123.0.41', '2019-06-27 00:00:00', '2023-01-31 00:00:00', 'images/2-avatar.jpg', 'Greenwoods', 'Active', '45.123.0.41', 'zetozone', 'zeto@111'),
(21, 'Spicegroove', 'Spicegroove\nAnakkara', 'Janeesh', '8281533116', 'fom@spicegroove.in', '137.59.78.147', '2019-10-16 13:18:09', '2022-02-28 00:00:00', '', 'Spice Groove', 'Active', '137.59.78.147', 'zetozone', 'zeto@111'),
(15, 'Shalimar Spice Garden', 'Kumily, Kerala', 'Jaison Abraham', '9999 9999 99', 'shalimar@gmail.com', '45.123.0.39', '2019-07-03 21:09:02', '2022-02-28 00:00:00', '', 'Shalimar', 'Active', '45.123.0.39', 'zetozone', 'zeto@111'),
(26, 'WILD SAGA', 'Wild Saga\nLake Road Thekkady', 'Sreejith', '9656589900', 'mail@wildsaga.com', '45.123.0.42', '2021-11-03 15:12:20', '2022-11-30 00:00:00', '', 'WILD SAGA', 'Active', '45.123.0.42', 'zeto', 'zeto@007'),
(18, 'Holidayvista', 'Near KSRTC Depot\nKoluthupalam\nKumily', 'Ashok', '9446055358', 'holidayvista2017@gmail.com', '117.213.19.111', '2019-09-26 16:43:57', '2022-02-14 00:00:00', '', 'HOLIDAY VISTA', 'Active', '117.213.19.111', 'zetozone', 'zone@111'),
(23, 'Mountain Courtyard', 'Thekkady kumily', 'Shamal', '9495614963', 'mail.courtyard.com', '45.123.0.52', '2020-02-27 15:18:04', '2023-02-28 00:00:00', '', 'Mountain Courtyard ', 'Active', '45.123.0.52', 'zetozone', 'zeto@111'),
(25, 'Springdale', 'SpringDale\nVandiperiyar', 'Jain', '0486922222', 'fom@springdaleheritage.in', '45.123.0.48', '2021-02-23 14:58:06', '2022-02-28 00:00:00', '', 'Springdale', 'Active', '45.123.0.48', 'zeto', 'zeto@007');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(256) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `user_level` int(1) NOT NULL,
  `host_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `created_on`, `name`, `address`, `avatar`, `phone`, `email`, `status`, `user_level`, `host_id`) VALUES
(1, 'admin', 'a339f227f2c667cdf1034599815bb4c145c91d2a', '2019-06-24 14:36:50', 'Super Admin', 'Easy Hotspot', 'admin.jpg', '+91 9020 150 150', 'sibyperiyar@gmail.com', 'Active', 1, 0),
(26, 'ehsman@ehs.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-07-28 12:19:36', 'Test User', 'EHS', 'avatar.jpg', '7795 885 69`1', 'ehsman@ehs.com', 'Active', 4, 15),
(23, 'shalimarspicegarden@amritara.co.in', '692bdd4fc855f8adf725f8621828ce33ad332786', '2019-07-27 17:31:47', 'Shalimar Spice Garden', 'Thekkady', 'avatar.jpg', '9605040066', 'shalimarspicegarden@amritara.co.in', 'Active', 4, 15),
(9, 'suja@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-06-25 20:02:24', 'Suja Joseph', 'Zetozone Kumily - 685 509', 'avatar.jpg', '9656 966 466', 'suja@gmail.com', 'Active', 3, 1),
(21, 'gm@grandthekkady.in', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-07-17 15:56:13', 'Anoop', 'Grand Thekkady', 'avatar.jpg', '9562422147', 'gm@grandthekkady.in', 'Active', 3, 17),
(22, 'rmssg@amritara.co.in', 'f4d9e5cee20a40b0c6be0351af6a1f47360f8486', '2019-07-27 17:13:40', 'Gireesh', 'Shalimar Spice Garden', 'avatar.jpg', '9605040022', 'rmssg@amritara.co.in', 'Active', 3, 15),
(15, 'benny@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-06-27 11:54:51', 'behanan', 'somewhere', 'avatar.jpg', '90909090', 'benny@gmail.com', 'Active', 3, 2),
(24, 'fom@shalimarspicegarden.com', 'cc70873c2c21d070d43a9b8b93d6b09653acb4b7', '2019-07-27 17:35:14', 'Shalimar Spice Garden', 'Thekkady', 'avatar.jpg', '9605140055', 'fom@shalimarspicegarden.com', 'Active', 4, 15),
(25, 'fom@greenwoods.in', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-07-27 19:54:43', 'Vinoj', 'Greenwoods Thekkady', 'avatar.jpg', '9497715036', 'fom@greenwoods.in', 'Active', 4, 2),
(20, 'jaison@ehs.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-07-17 15:43:28', 'Jaison Abraham', 'Zetozone Technologies, Kumily', 'avatar.jpg', '9999 9999 99', 'jaison@ehs.com', 'Active', 1, 0),
(2, 'manager', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '0000-00-00 00:00:00', 'Demo Manager', 'Demo Company', '', '9000 000 000', 'demomanager@demo.io', 'Active', 3, 1),
(3, 'executive', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '0000-00-00 00:00:00', 'Demo Executive', 'Demo Company', '', '9000 000 000', 'demoexecutive@demo.io', 'Active', 4, 1),
(27, 'ashokthanaraj@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-09-26 16:56:54', 'Ashok', 'Holiday vista', 'avatar.jpg', '9446055358', 'ashokthanaraj@gmail.com', 'Active', 3, 18),
(28, 'foholidayvista@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-09-26 17:01:46', 'Front office', 'holiday vista', 'avatar.jpg', '8589888139', 'foholidayvista@gmail.com', 'Active', 4, 18),
(32, 'fom@spicegrove.in', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2019-10-16 14:02:52', 'Spice Grove', 'Spice Grove Hotels & Resorts', 'avatar.jpg', '8281534115', 'fom@spicegrove.in', 'Active', 3, 21),
(33, 'fom@courtyard.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2020-02-27 15:25:28', 'Courtyard', 'Mountain Courtyard', 'avatar.jpg', '9526797907', 'fom@courtyard.com', 'Active', 4, 23),
(34, 'samalsankar@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2020-02-27 17:59:25', 'samalsankar', 'gm', 'avatar.jpg', '', 'samalsankar@gmail.com', 'Active', 3, 23),
(35, 'fom@springdale.in', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2021-02-23 15:20:31', 'sujo', 'Springdale', 'avatar.jpg', '9999999999', 'fom@springdale.in', 'Active', 4, 25),
(36, 'jain@springdale.in', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2021-07-06 14:05:37', 'Jain', 'Springdale', 'avatar.jpg', '9999999999', 'jain@springdale.in', 'Active', 3, 25),
(37, 'wildsagathekkady@gmail.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '2021-11-03 15:15:20', 'Wild Saga', 'Near Entrance Gate Thekkady', 'avatar.jpg', '9656589900', 'wildsagathekkady@gmail.com', 'Active', 4, 26);

-- --------------------------------------------------------

--
-- Table structure for table `user_host`
--

CREATE TABLE `user_host` (
  `id` int(11) NOT NULL,
  `user_id` int(5) NOT NULL,
  `host_id` int(5) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_host`
--

INSERT INTO `user_host` (`id`, `user_id`, `host_id`, `status`) VALUES
(8, 13, 1, 'Active'),
(47, 20, 15, ''),
(41, 10, 2, ''),
(45, 21, 17, 'Active'),
(11, 0, 0, ''),
(48, 22, 15, 'Active'),
(39, 15, 1, ''),
(43, 20, 0, 'Active'),
(44, 20, 17, ''),
(26, 11, 12, ''),
(46, 20, 2, ''),
(49, 23, 15, 'Active'),
(50, 24, 15, 'Active'),
(51, 25, 2, 'Active'),
(52, 26, 15, 'Active'),
(53, 27, 18, 'Active'),
(54, 28, 18, 'Active'),
(55, 29, 21, 'Active'),
(56, 30, 21, 'Active'),
(57, 31, 21, 'Active'),
(58, 32, 21, 'Active'),
(59, 33, 23, 'Active'),
(60, 34, 23, 'Active'),
(61, 35, 25, 'Active'),
(62, 36, 25, 'Active'),
(63, 37, 26, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config_app`
--
ALTER TABLE `config_app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_email`
--
ALTER TABLE `config_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_print`
--
ALTER TABLE `config_print`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_sms`
--
ALTER TABLE `config_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `host`
--
ALTER TABLE `host`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_host`
--
ALTER TABLE `user_host`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config_app`
--
ALTER TABLE `config_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `config_email`
--
ALTER TABLE `config_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `config_print`
--
ALTER TABLE `config_print`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_sms`
--
ALTER TABLE `config_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `host`
--
ALTER TABLE `host`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user_host`
--
ALTER TABLE `user_host`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
