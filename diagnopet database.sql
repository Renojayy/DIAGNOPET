-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2025 at 04:53 PM
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
-- Database: `diagnopet`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_assistant`
--

CREATE TABLE `ai_assistant` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Version` varchar(50) NOT NULL,
  `AI Pre Table` varchar(255) NOT NULL,
  `Response Time` varchar(50) NOT NULL,
  `Knowledge Base` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `Pet Owner` varchar(100) NOT NULL,
  `Veterinary License Number` varchar(255) DEFAULT NULL,
  `Consultations Date` date NOT NULL,
  `Symptoms Discussed` varchar(255) NOT NULL,
  `Remarks` varchar(255) NOT NULL,
  `Level of Threats` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`consultation_id`, `Pet Owner`, `Veterinary License Number`, `Consultations Date`, `Symptoms Discussed`, `Remarks`, `Level of Threats`) VALUES
(1, 'Jayson', '', '2025-11-22', 'rwrewr', 'erwrw', 'Medium'),
(2, 'John', NULL, '2025-11-29', 'fssfsdf', 'wasdwasdwa', 'High'),
(3, 'Dabid', NULL, '2025-11-11', 'ssdf', 'dsfsfsaff', 'Critical');

-- --------------------------------------------------------

--
-- Table structure for table `petowners`
--

CREATE TABLE `petowners` (
  `owner_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `ContactNo` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `Pet Type` varchar(100) NOT NULL,
  `Pet Name` varchar(100) NOT NULL,
  `Pet Gender` varchar(20) NOT NULL,
  `Pet Weight` varchar(50) NOT NULL,
  `Pet Breed` varchar(100) NOT NULL,
  `Pet Age` varchar(20) NOT NULL,
  `Pet Symptoms` varchar(255) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `Pet Type`, `Pet Name`, `Pet Gender`, `Pet Weight`, `Pet Breed`, `Pet Age`, `Pet Symptoms`, `user_name`, `avatar`) VALUES
(13, 'Dog', 'Renojayyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy', 'Male', '20', 'Labrador', '5', '', 'User942', 'uploads/691d9025e1ab4_labra.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `symptom` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `pet_id`, `symptom`, `date_added`, `user_name`) VALUES
(7, 13, 'Loss of appetite', '2025-11-19 09:38:45', 'User942');

-- --------------------------------------------------------

--
-- Table structure for table `veterinarian`
--

CREATE TABLE `veterinarian` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `prc_id_path` varchar(255) DEFAULT NULL,
  `verification_status` enum('verified','not_verified') DEFAULT 'not_verified',
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `registration_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinarian`
--

INSERT INTO `veterinarian` (`id`, `name`, `specialization`, `license_number`, `expiration_date`, `prc_id_path`, `verification_status`, `password`, `email`, `location`, `clinic_name`, `created_at`, `registration_time`, `status`, `latitude`, `longitude`) VALUES
(1, 'Test Vet', 'General', '12345', NULL, NULL, 'verified', '$2y$10$GqClM1XXq.cECQi/oF03vO3BD8gAMzmmGSgArtBNt5cmT3YTSCYfS', 'test@example.com', 'Test City', 'Test Clinic', '2025-11-17 16:14:22', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(2, 'Test Vet', 'General', '12345', NULL, NULL, 'verified', '$2y$10$0UHsxXpneNdlYuK4Za0VreZGT69qVW22Hp9.1UOCj35StQZl8QXSe', 'test2@example.com', 'Test City', 'Test Clinic', '2025-11-17 16:15:50', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(3, 'Bronny James', 'Opthalmologist', '1234567', NULL, NULL, 'verified', '$2y$10$r129njrE.8LjjrHFZaHOxOAKC1WdNkKa0P/0etNV2oQYLq4DgxAEC', 'bronnyjames@test.com', 'Talisay City', 'Talisay Vet', '2025-11-17 16:16:16', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(5, 'Dabid Bsound', 'Guitarist', '12345678', NULL, NULL, 'verified', '$2y$10$EmJGWCqfkPXLVTkYA/n4D.twJ/nTEOaEHfwLnxB65vfz72qdLXxo.', 'davidbsound@test.com', 'Pahanocoy', 'Pahanocoy Vet', '2025-11-17 16:27:44', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(6, 'Earl', 'Drummer', '1234567890', NULL, NULL, 'verified', '$2y$10$2U8WqUq.4YKuyfSkYMmf9u.a75Y6tYJiVbUTKnw4gM5PA.IYTmUvG', 'earl@test.com', 'Murcia', 'Murcia vet', '2025-11-18 09:43:47', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(7, 'Genkitron', 'Transformer', '788786', NULL, NULL, 'verified', '$2y$10$h1i6dAg.UKYErER/Bq4BFOa8/WEiniwoMsMdFgU/kAovBErf6wVPe', 'genkitron@test.com', 'Talisay City', 'Talisay Vet', '2025-11-19 15:23:30', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(12, 'John Reno', 'Opthalmologist', '1234', '2025-12-01', NULL, 'not_verified', '$2y$10$KtkjYwh6g91cJa0BFjkiceGV76mukEjMQ8yKmfBi7iHwVOL77d0IW', 'johnreno@test.com', 'Talisay City', 'Ta', '2025-11-23 08:08:31', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(13, 'Bsound', 'Opthalmologist', '343242', '2025-12-01', NULL, 'not_verified', '$2y$10$eljsxaEFT1/eBihaTt1VzuMV8MQKbmD0WTMMtKKBXc4MkddprUrSe', 'bsound@test.com', 'Talisay City', 'Talisay Vet', '2025-11-23 08:09:17', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(14, 'Early', 'Opthalmologist', '4343443', '2025-12-01', NULL, 'not_verified', '$2y$10$yTnu828Hiuxm6ZRQZJDrsu1fAzV04MZxTR9eojkC5TzYN0wPPJrC2', 'early@test.com', 'Talisay City', 'Murcia Clinic', '2025-11-23 09:06:47', '2025-11-23 17:17:46', 'pending', NULL, NULL),
(19, 'Dabid Bsound', 'Guitarist', '4332423423', '2025-12-02', NULL, 'not_verified', '$2y$10$WGutDtXpfhFcABqo0JAduOROgsphYBGdZIOMxoqVknY4L9AVFoNse', 'dabidbsound@test.com', 'Talisay City', 'Talisay Vets', '2025-11-23 09:28:03', '2025-11-23 17:28:03', 'approved', NULL, NULL),
(20, 'John Renojayy', 'Opthalmologist', '432234', '2025-12-02', NULL, 'not_verified', '$2y$10$2eSGvBF44bmNkjRsy7EB6uX/opDCwVU3le4jq3Xq1WjO6xGzR1.Ki', 'johnrenojayyy@test.com', 'Talisay City', 'Talisay Vets', '2025-11-23 09:30:45', '2025-11-23 17:30:45', 'approved', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `veterinaryclinics`
--

CREATE TABLE `veterinaryclinics` (
  `clinic_id` int(11) NOT NULL,
  `Clinic Name` varchar(150) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Contact Number` varchar(50) NOT NULL,
  `Latitude` decimal(10,7) NOT NULL,
  `Longitude` decimal(10,7) NOT NULL,
  `Services Offered` varchar(255) DEFAULT NULL,
  `Opening Hours` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_assistant`
--
ALTER TABLE `ai_assistant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`);

--
-- Indexes for table `petowners`
--
ALTER TABLE `petowners`
  ADD PRIMARY KEY (`owner_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `veterinarian`
--
ALTER TABLE `veterinarian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `veterinaryclinics`
--
ALTER TABLE `veterinaryclinics`
  ADD PRIMARY KEY (`clinic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_assistant`
--
ALTER TABLE `ai_assistant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `petowners`
--
ALTER TABLE `petowners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `veterinarian`
--
ALTER TABLE `veterinarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `veterinaryclinics`
--
ALTER TABLE `veterinaryclinics`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD CONSTRAINT `symptoms_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
