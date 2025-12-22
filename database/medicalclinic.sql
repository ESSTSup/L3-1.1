-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 21 déc. 2025 à 18:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `medicalclinic`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
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
-- Structure de la table `assistant`
--

CREATE TABLE `assistant` (
  `assis_id` int(11) NOT NULL,
  `assis_name` varchar(50) NOT NULL,
  `assis_lname` varchar(50) NOT NULL,
  `assis_email` varchar(100) NOT NULL,
  `assis_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `assistant`
--

INSERT INTO `assistant` (`assis_id`, `assis_name`, `assis_lname`, `assis_email`, `assis_password`) VALUES
(101, 'BERRAHMEN', '', 'berrahmen@clinic.com', '$2y$10$9Y3Kb7tS8/9frtqANscMoeGA2ZjeKHEE9zKGA45Cvsp178b9PjmXy'),
(102, 'SLIMANI', '', 'slimani@clinic.com', '$2y$10$1UoRkKZ4GKS0chuetwRwI.esenCf59Tx4wD7LGdCcdt7sT7Rg4QMu');

-- --------------------------------------------------------

--
-- Structure de la table `assistant_finance`
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
-- Structure de la table `clinics`
--

CREATE TABLE `clinics` (
  `clinic_id` int(11) NOT NULL,
  `clinic_email` varchar(100) NOT NULL,
  `clinic_password` varchar(255) NOT NULL,
  `clinic_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clinics`
--

INSERT INTO `clinics` (`clinic_id`, `clinic_email`, `clinic_password`, `clinic_name`) VALUES
(1, 'test@clinic.com', 'test123', 'TEST CLINIC');

-- --------------------------------------------------------

--
-- Structure de la table `clinic_schedules`
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
-- Structure de la table `consultation`
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
-- Structure de la table `doctor`
--

CREATE TABLE `doctor` (
  `doc_id` int(11) NOT NULL,
  `doc_email` varchar(100) NOT NULL,
  `doc_password` varchar(255) NOT NULL,
  `doc_specialite` varchar(100) NOT NULL,
  `doc_telephone` varchar(20) NOT NULL,
  `doc_role` varchar(20) DEFAULT NULL,
  `doc_name` varchar(50) NOT NULL,
  `doc_lname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `doctor`
--

INSERT INTO `doctor` (`doc_id`, `doc_email`, `doc_password`, `doc_specialite`, `doc_telephone`, `doc_role`, `doc_name`, `doc_lname`) VALUES
(1, 'doc@test.com', '1234', 'cardio', '0550123456', '', 'Ali', 'Bensaid'),
(2, 'moufouki@clinic.com', '$2y$10$QnqUMEWPTXFErgwmBcPQ2eJULQWgpTQefg3FV9ZuROkDabv/pNDMO', 'general', '0550000001', 'admin', 'MOUFOUKI', ''),
(3, 'djedjig@clinic.com', '1234', 'general', '0550000002', 'doctor', 'DJEDJIG', ''),
(4, 'hellal@clinic.com', '1234', 'general', '0550000003', 'doctor', 'HELLAL', ''),
(5, 'meklati@clinic.com', '1234', 'general', '0550000004', 'doctor', 'MEKLATI', ''),
(6, 'lachi@clinic.com', '1234', 'general', '0550000005', 'doctor', 'LACHI', ''),
(7, 'majed@clinic.com', '1234', 'general', '0550000006', 'doctor', 'MAJED', '');

-- --------------------------------------------------------

--
-- Structure de la table `doctor_assistant`
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
-- Structure de la table `doctor_availability`
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
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `pat_id` int(11) NOT NULL,
  `pat_name` varchar(50) NOT NULL,
  `pat_lname` varchar(50) NOT NULL,
  `pat_birthday` date NOT NULL,
  `pat_gender` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `pat_email` varchar(100) NOT NULL,
  `pat_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`pat_id`, `pat_name`, `pat_lname`, `pat_birthday`, `pat_gender`, `telephone`, `pat_email`, `pat_password`) VALUES
(2, 'Maroua', 'BELHINOUS', '1989-05-12', 'Female', '0567867891', 'maroua@gmail.com', '$2y$10$GJ3r6/Bo3QQkOJ3WvMKg8OGJF93kO50hzPzJCzvEtRNa0szADZX5u'),
(4, 'WAR', 'DII', '2025-12-27', 'Male', '+213541775494', 'warda.moufouki@esst-sup.com', '$2y$10$zE.6knmTYfkuvOgUxfF8A.j3iZHd4sI8.en5ZcdFtTQLDONsD.e6C');

-- --------------------------------------------------------

--
-- Structure de la table `patient_finance`
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
-- Structure de la table `patient_medical`
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
-- Déchargement des données de la table `patient_medical`
--

INSERT INTO `patient_medical` (`medical_id`, `pat_id`, `allergies`, `chronic_diseases`, `emergency_name`, `emergency_phone`, `created_at`) VALUES
(4, 4, 'KO', 'LO', 'AIII', '0565678799', '2025-12-21 16:39:07');

-- --------------------------------------------------------

--
-- Structure de la table `superadmin`
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
-- Structure de la table `waiting_list`
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `idx_patient` (`pat_id`),
  ADD KEY `idx_doctor` (`doc_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `idx_datetime` (`appointment_date`,`appointment_time`),
  ADD KEY `idx_status` (`status`);

--
-- Index pour la table `assistant`
--
ALTER TABLE `assistant`
  ADD PRIMARY KEY (`assis_id`);

--
-- Index pour la table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  ADD PRIMARY KEY (`salary_id`),
  ADD UNIQUE KEY `unique_assistant_month` (`assis_id`,`month`);

--
-- Index pour la table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Index pour la table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `unique_clinic_day` (`clinic_id`,`day_of_week`);

--
-- Index pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `pat_id` (`pat_id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Index pour la table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doc_id`);

--
-- Index pour la table `doctor_assistant`
--
ALTER TABLE `doctor_assistant`
  ADD PRIMARY KEY (`doc_id`,`assis_id`),
  ADD KEY `assis_id` (`assis_id`);

--
-- Index pour la table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `idx_doctor` (`doc_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `idx_day` (`day_of_week`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pat_id`);

--
-- Index pour la table `patient_finance`
--
ALTER TABLE `patient_finance`
  ADD PRIMARY KEY (`finance_id`),
  ADD KEY `pat_id` (`pat_id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- Index pour la table `patient_medical`
--
ALTER TABLE `patient_medical`
  ADD PRIMARY KEY (`medical_id`),
  ADD KEY `pat_id` (`pat_id`);

--
-- Index pour la table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_email` (`admin_email`);

--
-- Index pour la table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`waiting_id`),
  ADD UNIQUE KEY `unique_appointment` (`appointment_id`),
  ADD KEY `idx_appointment` (`appointment_id`),
  ADD KEY `idx_clinic` (`clinic_id`),
  ADD KEY `fk_waitinglist_patient` (`pat_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `assis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT pour la table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `patient`
--
ALTER TABLE `patient`
  MODIFY `pat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `patient_finance`
--
ALTER TABLE `patient_finance`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `patient_medical`
--
ALTER TABLE `patient_medical`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `waiting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `assistant_finance`
--
ALTER TABLE `assistant_finance`
  ADD CONSTRAINT `assistant_finance_ibfk_1` FOREIGN KEY (`assis_id`) REFERENCES `assistant` (`assis_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `clinic_schedules`
--
ALTER TABLE `clinic_schedules`
  ADD CONSTRAINT `clinic_schedules_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_3` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_4` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `doctor_assistant`
--
ALTER TABLE `doctor_assistant`
  ADD CONSTRAINT `doctor_assistant_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_assistant_ibfk_2` FOREIGN KEY (`assis_id`) REFERENCES `assistant` (`assis_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `doctor_availability_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_availability_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `patient_finance`
--
ALTER TABLE `patient_finance`
  ADD CONSTRAINT `patient_finance_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_finance_ibfk_2` FOREIGN KEY (`consultation_id`) REFERENCES `consultation` (`consultation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `patient_medical`
--
ALTER TABLE `patient_medical`
  ADD CONSTRAINT `patient_medical_ibfk_1` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD CONSTRAINT `waiting_list_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `waiting_list_ibfk_2` FOREIGN KEY (`pat_id`) REFERENCES `patient` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `waiting_list_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
