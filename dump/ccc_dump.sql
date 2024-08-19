-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 19, 2024 at 10:40 PM
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
-- Database: `ccc`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `country` varchar(32) NOT NULL,
  `postal_code` int(10) UNSIGNED NOT NULL,
  `city` varchar(32) NOT NULL,
  `street` varchar(64) NOT NULL,
  `civic` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`profile_id`, `country`, `postal_code`, `city`, `street`, `civic`) VALUES
(1, 'Italy', 67100, 'L\'Aquila', 'Viale Duca degli Abruzzi', '7'),
(2, 'Italy', 118, 'Rome', 'Via Verdi', '3');

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `job_offer_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`candidate_id`, `job_offer_id`, `date`) VALUES
(1, 1, '2024-08-17');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(16) NOT NULL,
  `surname` varchar(16) NOT NULL,
  `age` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `name`, `surname`, `age`) VALUES
(1, 'Mario', 'Rossi', 30);

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`id`, `name`) VALUES
(2, 'CNB Comunicazione');

-- --------------------------------------------------------

--
-- Table structure for table `expertise`
--

CREATE TABLE `expertise` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expertise`
--

INSERT INTO `expertise` (`id`, `title`) VALUES
(1, 'Ads'),
(2, 'UX Design');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(32) NOT NULL,
  `path` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `employer_id` int(10) UNSIGNED NOT NULL,
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `type` enum('current','past') NOT NULL,
  `first_work_date` date NOT NULL,
  `last_work_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`employer_id`, `candidate_id`, `type`, `first_work_date`, `last_work_date`) VALUES
(2, 1, 'past', '2017-03-09', '2020-09-12');

-- --------------------------------------------------------

--
-- Table structure for table `job_offer`
--

CREATE TABLE `job_offer` (
  `id` int(10) UNSIGNED NOT NULL,
  `employer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `salary` float UNSIGNED NOT NULL,
  `quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_offer`
--

INSERT INTO `job_offer` (`id`, `employer_id`, `name`, `salary`, `quantity`, `description`) VALUES
(1, 2, 'Visual Designer', 1800, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `phone`, `description`) VALUES
(1, 2, '+393333333333', NULL),
(2, 3, '+390862000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_expertise`
--

CREATE TABLE `profile_expertise` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `expertise_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_expertise`
--

INSERT INTO `profile_expertise` (`profile_id`, `expertise_id`) VALUES
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `requirement`
--

CREATE TABLE `requirement` (
  `job_offer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` enum('1','2','3','4','5') NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirement`
--

INSERT INTO `requirement` (`job_offer_id`, `name`, `level`, `description`) VALUES
(1, 'Adobe Photoshop', '3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'Administrator', NULL),
(2, 'User', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_service`
--

CREATE TABLE `role_service` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_service`
--

INSERT INTO `role_service` (`role_id`, `service_id`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `script` varchar(255) DEFAULT NULL,
  `default` varchar(1) NOT NULL,
  `description` text DEFAULT NULL,
  `permission` varchar(1) NOT NULL,
  `entity` varchar(100) NOT NULL,
  `field` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`, `script`, `default`, `description`, `permission`, `entity`, `field`) VALUES
(1, 'Dashboard', 'dashboard.php', '', NULL, '', '', ''),
(2, 'Candidate profile', 'candidates_profile.php', '', NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` enum('1','2','3','4','5') NOT NULL,
  `description` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`candidate_id`, `name`, `level`, `description`) VALUES
(1, 'Figma', '4', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `social_account`
--

CREATE TABLE `social_account` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(16) NOT NULL,
  `uri` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_account`
--

INSERT INTO `social_account` (`profile_id`, `name`, `uri`) VALUES
(1, 'Facebook', 'https://facebook.com'),
(1, 'Instagram', 'https://www.instagram.com/');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin', 'admin@example.com'),
(2, 'candidate', 'candidate', 'candidate@example.com'),
(3, 'employer', 'employer', 'employer@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `username` varchar(32) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`username`, `role_id`) VALUES
('admin', 1),
('candidate', 2),
('employer', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD UNIQUE KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD UNIQUE KEY `foreign_candidate_id` (`candidate_id`) USING BTREE,
  ADD KEY `foreign_job_offer_id` (`job_offer_id`) USING BTREE;

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `expertise`
--
ALTER TABLE `expertise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_title` (`title`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD KEY `foreign_employer_key` (`employer_id`) USING BTREE,
  ADD KEY `foreign_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `job_offer`
--
ALTER TABLE `job_offer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_job_offer` (`employer_id`,`name`) USING BTREE;

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foreign_user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `profile_expertise`
--
ALTER TABLE `profile_expertise`
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE,
  ADD KEY `foreign_expertise_id` (`expertise_id`) USING BTREE;

--
-- Indexes for table `requirement`
--
ALTER TABLE `requirement`
  ADD UNIQUE KEY `unique_requirement` (`job_offer_id`,`name`) USING BTREE,
  ADD KEY `foreign_job_offer_id` (`job_offer_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`) USING BTREE;

--
-- Indexes for table `role_service`
--
ALTER TABLE `role_service`
  ADD UNIQUE KEY `unique_role_service` (`role_id`,`service_id`),
  ADD KEY `foreign_role_id` (`role_id`) USING BTREE,
  ADD KEY `foreign_service_id` (`service_id`) USING BTREE;

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD UNIQUE KEY `unique_skill` (`candidate_id`,`name`),
  ADD KEY `foreign_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `social_account`
--
ALTER TABLE `social_account`
  ADD UNIQUE KEY `unique_link` (`profile_id`,`name`),
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`) USING BTREE,
  ADD UNIQUE KEY `unique_email` (`email`) USING BTREE;

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD UNIQUE KEY `unique_user_role` (`username`,`role_id`),
  ADD KEY `foreign_user_id` (`username`) USING BTREE,
  ADD KEY `foreign_role_id` (`role_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expertise`
--
ALTER TABLE `expertise`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_offer`
--
ALTER TABLE `job_offer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employer`
--
ALTER TABLE `employer`
  ADD CONSTRAINT `employer_ibfk_1` FOREIGN KEY (`id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employer` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `job_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_offer`
--
ALTER TABLE `job_offer`
  ADD CONSTRAINT `job_offer_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile_expertise`
--
ALTER TABLE `profile_expertise`
  ADD CONSTRAINT `profile_expertise_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_expertise_ibfk_2` FOREIGN KEY (`expertise_id`) REFERENCES `expertise` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `requirement_ibfk_1` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_service`
--
ALTER TABLE `role_service`
  ADD CONSTRAINT `role_service_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `skill_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `social_account_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
