-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 05:11 AM
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
-- Database: `ip_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `campuses`
--

CREATE TABLE `campuses` (
  `campus_id` int(11) NOT NULL,
  `campus_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campuses`
--

INSERT INTO `campuses` (`campus_id`, `campus_name`) VALUES
(1, 'Los Baños'),
(2, 'San Pablo'),
(3, 'Sta. Cruz'),
(4, 'Siniloan');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `campus_id`, `department_name`) VALUES
(1, 3, 'College of Arts and Sciences'),
(2, 3, 'College of Business, Administration and Accountancy'),
(3, 3, 'College of Computer Studies'),
(4, 3, 'College of Criminal Justice Education'),
(5, 3, 'College of Engineering'),
(6, 3, 'College of Industrial Technology'),
(7, 3, 'College of International Hospitality and Tourism Management'),
(8, 3, 'College of Law'),
(9, 3, 'College of Nursing and Allied Health'),
(10, 3, 'College of Teacher Education'),
(11, 1, 'College of Arts and Sciences'),
(12, 1, 'College of Business, Administration and Accountancy'),
(13, 1, 'College of Computer Studies'),
(14, 1, 'College of Criminal Justice Education'),
(15, 1, 'College of Fisheries'),
(16, 1, 'College of Food Nutrition and Dietetics'),
(17, 1, 'College of International Hospitality and Tourism Management'),
(18, 1, 'College of Teacher Education'),
(19, 2, 'College of Arts and Sciences'),
(20, 2, 'College of Business, Administration and Accountancy'),
(21, 2, 'College of Computer Studies'),
(22, 2, 'College of Criminal Justice Education'),
(23, 2, 'College of Engineering'),
(24, 2, 'College of Industrial Technology'),
(25, 2, 'College of International Hospitality and Tourism Management'),
(26, 2, 'College of Teacher Education'),
(27, 4, 'College of Agriculture'),
(28, 4, 'College of Arts and Sciences'),
(29, 4, 'College of Business, Administration and Accountancy'),
(30, 4, 'College of Computer Studies'),
(31, 4, 'College of Criminal Justice Education'),
(32, 4, 'College of Engineering'),
(33, 4, 'College of Food Nutrition and Dietetics'),
(34, 4, 'College of International Hospitality and Tourism Management'),
(35, 4, 'College of Teacher Education');

-- --------------------------------------------------------

--
-- Table structure for table `intellectual_properties`
--

CREATE TABLE `intellectual_properties` (
  `ip_id` int(11) NOT NULL,
  `tracking_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `authors` varchar(255) DEFAULT NULL,
  `classification` enum('Copyright','Trademark','Patent','Utility Model','Industrial Design') NOT NULL,
  `endorsement_letter` varchar(255) DEFAULT NULL,
  `status` enum('Ongoing','Pending','Completed','For Revision') NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `application_form` varchar(255) DEFAULT NULL,
  `submitted` tinyint(1) DEFAULT 0,
  `application_fee` varchar(255) DEFAULT NULL,
  `issued_certificate` varchar(255) DEFAULT NULL,
  `project_file` varchar(255) DEFAULT NULL,
  `authors_file` varchar(255) NOT NULL,
  `date_submitted_to_ipophil` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `date_submitted_to_itso` datetime NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `campus_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `ip_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `campus_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `campus_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Director','Chairperson','Coordinator') NOT NULL,
  `department_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `campus_id`, `password`, `role`, `department_id`, `status`, `created_at`) VALUES
(1, 'superadmin', NULL, '$2y$10$wI2RlI2YKlySQIf9yUdRYuNE9Wyr88IAunooM9qsTqsKvKuLY4YmC', 'Director', 0, 'approved', '2026-01-04 22:53:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`campus_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `campus_id` (`campus_id`);

--
-- Indexes for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  ADD PRIMARY KEY (`ip_id`),
  ADD UNIQUE KEY `tracking_id` (`tracking_id`),
  ADD KEY `fk_campus` (`campus_id`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ip` (`ip_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campuses`
--
ALTER TABLE `campuses`
  MODIFY `campus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  MODIFY `ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`campus_id`);

--
-- Constraints for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  ADD CONSTRAINT `fk_campus` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`campus_id`),
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_ip` FOREIGN KEY (`ip_id`) REFERENCES `intellectual_properties` (`ip_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
