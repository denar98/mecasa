-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2023 at 02:45 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mecasa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(25) NOT NULL,
  `client_address` varchar(100) NOT NULL,
  `client_interest` varchar(25) NOT NULL,
  `client_class` varchar(10) NOT NULL,
  `client_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client_meetings`
--

CREATE TABLE `client_meetings` (
  `client_meeting_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `meeting_location` varchar(255) NOT NULL,
  `meeting_type` varchar(50) NOT NULL,
  `meeting_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `progress_histories`
--

CREATE TABLE `progress_histories` (
  `progress_history_id` int(11) NOT NULL,
  `project_detail_id` int(11) NOT NULL,
  `progress_type` varchar(25) NOT NULL,
  `progress_note` text NOT NULL,
  `progress_time` datetime NOT NULL,
  `progress_percentage` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `progress_history_attachments`
--

CREATE TABLE `progress_history_attachments` (
  `progress_history_attachment_id` int(11) NOT NULL,
  `attachment_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_name` varchar(50) NOT NULL,
  `project_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project_details`
--

CREATE TABLE `project_details` (
  `project_detail_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `project_detail_type` varchar(50) NOT NULL,
  `project_detail_nominal` int(11) NOT NULL,
  `profit_estimation` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `brief` text NOT NULL,
  `project_detail_percentage` int(11) NOT NULL,
  `project_detail_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project_detail_attachments`
--

CREATE TABLE `project_detail_attachments` (
  `project_detail_id` int(11) NOT NULL,
  `project_detail_attachments_id` int(11) NOT NULL,
  `attachment_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project_financials`
--

CREATE TABLE `project_financials` (
  `project_financials_d` int(11) NOT NULL,
  `project_detail_id` int(11) NOT NULL,
  `financial_type` varchar(50) NOT NULL,
  `financial_note` varchar(255) NOT NULL,
  `financial_nominal` int(11) NOT NULL,
  `financial_date` date NOT NULL,
  `financial_pic` varchar(50) NOT NULL,
  `attachment_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(25) NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fullname`, `username`, `password`, `role`, `last_login`) VALUES
(1, 'Alfian Tito', 'tito', '01a5f5db2d97bd6b389e7a20bd889708', 'Admin', '2023-04-03 12:28:25'),
(2, 'Deni AR', 'deni', '01a5f5db2d97bd6b389e7a20bd889708', 'Project Manager', '2023-07-21 18:21:10'),
(3, 'Asep', 'asep', '01a5f5db2d97bd6b389e7a20bd889708', 'Project Manager', '0000-00-00 00:00:00'),
(4, 'Elwan', 'elwan', '01a5f5db2d97bd6b389e7a20bd889708', 'Project Manager', '2023-07-18 12:11:38'),
(5, 'Bustan', 'bustan', '01a5f5db2d97bd6b389e7a20bd889708', 'Admin', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_meetings`
--
ALTER TABLE `client_meetings`
  ADD PRIMARY KEY (`client_meeting_id`);

--
-- Indexes for table `progress_histories`
--
ALTER TABLE `progress_histories`
  ADD PRIMARY KEY (`progress_history_id`);

--
-- Indexes for table `progress_history_attachments`
--
ALTER TABLE `progress_history_attachments`
  ADD PRIMARY KEY (`progress_history_attachment_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_details`
--
ALTER TABLE `project_details`
  ADD PRIMARY KEY (`project_detail_id`);

--
-- Indexes for table `project_detail_attachments`
--
ALTER TABLE `project_detail_attachments`
  ADD PRIMARY KEY (`project_detail_attachments_id`);

--
-- Indexes for table `project_financials`
--
ALTER TABLE `project_financials`
  ADD PRIMARY KEY (`project_financials_d`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_meetings`
--
ALTER TABLE `client_meetings`
  MODIFY `client_meeting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_histories`
--
ALTER TABLE `progress_histories`
  MODIFY `progress_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_history_attachments`
--
ALTER TABLE `progress_history_attachments`
  MODIFY `progress_history_attachment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_details`
--
ALTER TABLE `project_details`
  MODIFY `project_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_detail_attachments`
--
ALTER TABLE `project_detail_attachments`
  MODIFY `project_detail_attachments_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_financials`
--
ALTER TABLE `project_financials`
  MODIFY `project_financials_d` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
