-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2025 at 02:58 PM
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
-- Database: `medicalclinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('requested','confirmed','rejected','cancelled','completed') DEFAULT 'requested',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE `assistant` (
  `assis_id` int(11) NOT NULL,
  `assis_name` varchar(50) NOT NULL,
  `assis_lname` varchar(50) NOT NULL,
  `assis_email` varchar(100) NOT NULL,
  `assis_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`assis_id`, `assis_name`, `assis_lname`, `assis_email`, `assis_password`) VALUES
(101, 'BERRAHMEN', '', 'berrahmen@clinic.com', '$2y$10$9Y3Kb7tS8/9frtqANscMoeGA2ZjeKHEE9zKGA45Cvsp178b9PjmXy'),
(102, 'SLIMANI', '', 'slimani@clinic.com', '$2y$10$1UoRkKZ4GKS0chuetwRwI.esenCf59Tx4wD7LGdCcdt7sT7Rg4QMu');

-- --------------------------------------------------------

--
-- Table structure for table `assistant_finance`
--

CREATE TABLE `assistant_finance` (
  `salary_id` int(11) NOT NULL,
  `assis_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `payment_status` enum('pending','paid','partially_paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `clinic_id` int(11) NOT NULL,
  `clinic_email` varchar(100) NOT NULL,
  `clinic_password` varchar(255) NOT NULL,
  `clinic_name` varchar(100) NOT NULL,
  `clinic_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `subscription_plan` enum('free','premium') DEFAULT 'free',
  `handicap_accessible` enum('handicap-friendly','not-accessible','partial') DEFAULT 'not-accessible',
  `number_of_doctors` int(11) DEFAULT 1,
  `gps_latitude` decimal(10,8) DEFAULT NULL,
  `gps_longitude` decimal(11,8) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subscription_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`clinic_id`, `clinic_email`, `clinic_password`, `clinic_name`, `clinic_phone`, `address`, `city`, `state`, `postal_code`, `country`, `subscription_plan`, `handicap_accessible`, `number_of_doctors`, `gps_latitude`, `gps_longitude`, `archived`, `archived_at`, `created_at`, `subscription_updated_at`) VALUES
(1, 'test@clinic.com', 'test123', 'TEST CLINIC', NULL, NULL, NULL, NULL, NULL, NULL, 'free', 'not-accessible', 1, NULL, NULL, 1, '2025-12-24 21:48:52', '2025-12-22 16:58:35', NULL),
(8, 'manel.belaiouer@esst-sup.com', '$2y$10$fRkk8CM.QebFPyzeI5NmE.dcn9zX2t2Lj23nXmbDaaDH39qBPnxA6', 'jnh', '0654789523', 'hebcyub2euy2e', 'hube2uy', 'hbnbuo', '4444', NULL, 'free', 'handicap-friendly', 1, NULL, NULL, 1, '2025-12-24 21:48:40', '2025-12-22 14:52:00', NULL),
(9, 'romaissa.haddad@esst-sup.com', '$2y$10$Ap6fGJTE3GjvSVTvwkuYI.sweQcHcbcb5D7L92iRxxjmv5BpgJuwW', 'jnh', '0654789523', 'hebcyub2euy2e', 'hube2uy', 'hbnbuo', '4444', NULL, 'free', 'handicap-friendly', 1, NULL, NULL, 1, '2025-12-24 21:48:47', '2025-12-22 15:00:55', NULL),
(10, 'yasmine@gm.com', '$2y$10$QA6PLa4xIQYfenQ7lqsA9ukn9fVwxnkphs/w1T2MJCg7voihmF63i', 'jnh', '0654789523', 'hebcyub2euy2e', 'hube2uy', 'hbnbuo', '4444', NULL, 'free', 'handicap-friendly', 1, NULL, NULL, 1, '2025-12-22 15:38:07', '2025-12-22 15:36:59', NULL),
(11, 'yasmine@ygvtf.lkygvy', '$2y$10$ECcUzKqio4bv8dnAf8VqT.BcE6Rwco.RyA7WrPxdPUNXsDvKSTlqi', 'haha', '0654789523', 'hebcyub2euy2e', 'hube2uy', 'hbnbuo', '4444', NULL, 'free', 'handicap-friendly', 1, NULL, NULL, 0, NULL, '2025-12-22 18:34:57', NULL),
(12, 'romaissa@esst-sup.com', '$2y$10$ZViYR5j/DlKwF.eW/b/iC.07DNWQ74.F7HNUVcKz2nAMRjanwy9SW', 'jnh', '0654789523', 'hebcyub2euy2e', 'hube2uy', 'hbnbuo', '4444', NULL, 'free', 'handicap-friendly', 1, NULL, NULL, 1, '2025-12-24 21:47:37', '2025-12-24 21:44:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clinic_schedules`
--

CREATE TABLE `clinic_schedules` (
  `schedule_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `day_of_week` tinyint(1) NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `is_working_day` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `consultation_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `consultation_date` date NOT NULL,
  `consultation_time` time NOT NULL,
  `symptoms` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `blood_pressure` varchar(10) DEFAULT NULL,
  `heart_rate` int(3) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `followup_needed` tinyint(1) DEFAULT 0,
  `followup_date` date DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doc_id` int(11) NOT NULL,
  `doc_email` varchar(100) NOT NULL,
  `doc_password` varchar(255) NOT NULL,
  `doc_specialite` varchar(100) NOT NULL,
  `doc_telephone` varchar(20) NOT NULL,
  `doc_role` enum('admin','member') NOT NULL,
  `doc_name` varchar(50) NOT NULL,
  `doc_lname` varchar(50) NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `is_principal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doc_id`, `doc_email`, `doc_password`, `doc_specialite`, `doc_telephone`, `doc_role`, `doc_name`, `doc_lname`, `clinic_id`, `is_principal`) VALUES
(1, 'romaissa.haddad@esst-sup.com', '$2y$10$CMMLaigCl/BrORaUtpM.ROxrrYZHnkYvJ8g6vkKQweftrt1SrgGZO', 'General Medicine', '0654789523', 'admin', 'Yasmine', 'Djedjig', 10, 1),
(8, 'vytf@f6rtf.com', '$2y$10$ZecnTMWfg8oTFdli9k.r8ea1/Feuq1kzLycstZwqi/c8To513COfq', 'General Medicine', '0654789523', 'admin', 'Yasmine', 'Djedjig', 11, 1),
(9, 'yasmine@jngrkl.jrngj', '$2y$10$M37oD2Bx/6/kfE3x7Bu1nub/dnRvyujAu1jiUi5bsMxwbGQNO4CiO', 'General Medicine', '0654789523', 'admin', 'Yasmine', 'Djedjig', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_assistant`
--

CREATE TABLE `doctor_assistant` (
  `doc_id` int(11) NOT NULL,
  `assis_id` int(11) NOT NULL,
  `is_responsable` tinyint(1) NOT NULL,
  `date_debut_affect` date NOT NULL,
  `date_fin_affect` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `availability_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `day_of_week` tinyint(1) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `effective_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `pat_id` int(11) NOT NULL,
  `pat_name` varchar(50) NOT NULL,
  `pat_lname` varchar(50) NOT NULL,
  `pat_birthday` date NOT NULL,
  `pat_gender` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `pat_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_finance`
--

CREATE TABLE `patient_finance` (
  `finance_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `payment_status` enum('pending','partially_paid','fully_paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_medical`
--

CREATE TABLE `patient_medical` (
  `medical_id` int(11) NOT NULL,
  `pat_id` int(11) NOT NULL,
  `allergies` text DEFAULT NULL,
  `chronic_diseases` text DEFAULT NULL,
  `emergency_name` varchar(100) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_medical`
--

INSERT INTO `patient_medical` (`medical_id`, `pat_id`, `allergies`, `chronic_diseases`, `emergency_name`, `emergency_phone`, `created_at`) VALUES
(4, 4, 'KO', 'LO', 'AIII', '0565678799', '2025-12-21 16:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_requests`
--

CREATE TABLE `subscription_requests` (
  `request_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `current_plan` enum('free','premium') NOT NULL,
  `requested_plan` enum('free','premium') NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waiting_list`
--

CREATE TABLE `waiting_list` (
  `waiting_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `checkin_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('waiting','in_progress','completed','missed') DEFAULT 'waiting',
  `pat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `idx_patient` (`pat_id`),
  ADD KEY `idx_doctor` (`doc_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `idx_datetime` (`appointment_date`,`appointment_time`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `assistant`
--
ALTER TABLE `assistant`
  ADD PRIMARY KEY (`assis_id`);

--
-- Indexes for table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  ADD PRIMARY KEY (`salary_id`),
  ADD UNIQUE KEY `unique_assistant_month` (`assis_id`,`month`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Indexes for table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `unique_clinic_day` (`clinic_id`,`day_of_week`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `pat_id` (`pat_id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `doctor_assistant`
--
ALTER TABLE `doctor_assistant`
  ADD PRIMARY KEY (`doc_id`,`assis_id`),
  ADD KEY `assis_id` (`assis_id`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `idx_doctor` (`doc_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `idx_day` (`day_of_week`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pat_id`);

--
-- Indexes for table `patient_finance`
--
ALTER TABLE `patient_finance`
  ADD PRIMARY KEY (`finance_id`),
  ADD KEY `pat_id` (`pat_id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- Indexes for table `patient_medical`
--
ALTER TABLE `patient_medical`
  ADD PRIMARY KEY (`medical_id`),
  ADD KEY `pat_id` (`pat_id`);

--
-- Indexes for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_email` (`admin_email`);

--
-- Indexes for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`waiting_id`),
  ADD UNIQUE KEY `unique_appointment` (`appointment_id`),
  ADD KEY `idx_appointment` (`appointment_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `fk_waitinglist_patient` (`pat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `assis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `pat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient_finance`
--
ALTER TABLE `patient_finance`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_medical`
--
ALTER TABLE `patient_medical`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `waiting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  ADD CONSTRAINT `assistant_finance_ibfk_1` FOREIGN KEY (`assis_id`) REFERENCES `assistant` (`assis_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  ADD CONSTRAINT `clinic_schedules_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_3` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_4` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_assistant`
--
ALTER TABLE `doctor_assistant`
  ADD CONSTRAINT `doctor_assistant_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_assistant_ibfk_2` FOREIGN KEY (`assis_id`) REFERENCES `assistant` (`assis_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `doctor_availability_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_availability_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_finance`
--
ALTER TABLE `patient_finance`
  ADD CONSTRAINT `patient_finance_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_finance_ibfk_2` FOREIGN KEY (`consultation_id`) REFERENCES `consultation` (`consultation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD CONSTRAINT `subscription_requests_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD CONSTRAINT `waiting_list_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `waiting_list_ibfk_2` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `waiting_list_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
