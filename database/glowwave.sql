-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 06:54 PM
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
-- Database: `glowwave`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointmentID` int(11) NOT NULL,
  `clientID` int(11) NOT NULL,
  `doctorID` int(11) NOT NULL,
  `book_date` date NOT NULL,
  `book_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(50) NOT NULL,
  `promotionID` int(11) DEFAULT NULL,
  `staffID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointmentID`, `clientID`, `doctorID`, `book_date`, `book_time`, `status`, `created_at`, `client_name`, `client_phone`, `promotionID`, `staffID`) VALUES
(2, 1, 2, '2026-03-02', '16:00:00', 'Confirmed', '2026-02-26 10:18:00', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(3, 1, 2, '2026-03-04', '17:00:00', 'Cancelled', '2026-02-26 10:28:29', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(4, 1, 3, '2026-02-28', '11:00:00', 'Cancelled', '2026-02-27 06:11:05', 'Hnin Aye Shwe Yee', '09872263838', 3, 0),
(5, 1, 2, '2026-03-09', '15:30:00', 'Cancelled', '2026-03-03 13:17:43', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(6, 1, 2, '2026-03-11', '17:30:00', 'Cancelled', '2026-03-03 13:21:14', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(7, 1, 4, '2026-03-04', '09:00:00', 'Confirmed', '2026-03-03 13:22:50', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(8, 2, 3, '2026-03-04', '14:00:00', 'Confirmed', '2026-03-03 13:29:46', 'Shoon Lae', '09872263838', NULL, 0),
(9, 2, 1, '2026-03-12', '14:30:00', 'Confirmed', '2026-03-03 16:17:02', 'Shoon Lae', '09872263838', NULL, 0),
(10, 2, 3, '2026-03-20', '13:00:00', 'Confirmed', '2026-03-03 16:17:52', 'Shoon Lae', '09872263838', NULL, 0),
(11, 2, 2, '2026-03-05', '15:30:00', 'Confirmed', '2026-03-03 16:26:07', 'Shoon Lae', '09872263838', NULL, 0),
(12, 1, 3, '2026-03-13', '13:00:00', 'Confirmed', '2026-03-04 14:15:04', 'Hnin Aye Shwe Yee', '09872263838', 3, 0),
(13, 1, 4, '2026-03-07', '17:00:00', 'Confirmed', '2026-03-06 06:25:04', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(14, 1, 2, '2026-03-09', '15:30:00', 'Confirmed', '2026-03-06 06:58:18', 'Hnin Aye Shwe Yee', '09872263838', 3, 0),
(15, 1, 2, '2026-03-28', '14:30:00', 'Cancelled', '2026-03-26 14:37:56', 'Hnin Aye Shwe Yee', '09872263838 ', 3, 0),
(16, 1, 4, '2026-03-26', '09:00:00', 'Confirmed', '2026-03-26 14:51:11', 'Hnin Aye Shwe Yee', '09872263838 ', NULL, 0),
(17, 1, 3, '2026-03-28', '10:30:00', 'Confirmed', '2026-03-27 06:16:04', 'Hnin Aye Shwe Yee ', '09872263838 ', NULL, 0),
(18, 1, 2, '2026-03-30', '15:00:00', 'Confirmed', '2026-03-28 02:58:26', 'Hnin Aye Shwe Yee', '09872263838', 3, 0),
(19, 1, 3, '2026-04-27', '13:00:00', 'Pending', '2026-04-26 11:42:51', 'Hnin Aye Shwe Yee', '09872263838', NULL, 0),
(20, 4, 2, '2026-04-30', '15:30:00', 'Confirmed', '2026-04-28 14:52:14', 'Yoyo', '09872263838', 6, 0),
(21, 5, 1, '2026-05-01', '09:00:00', 'Cancelled', '2026-04-28 15:11:28', 'Yuyu', '09786564564', 6, 0),
(22, 5, 2, '2026-05-01', '15:00:00', 'Confirmed', '2026-04-28 15:12:41', 'Yuyu', '09872263838', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_details`
--

CREATE TABLE `appointment_details` (
  `detailID` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_details`
--

INSERT INTO `appointment_details` (`detailID`, `appointmentID`, `treatmentID`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 3, 5),
(4, 4, 12),
(5, 5, 7),
(6, 6, 1),
(7, 7, 2),
(8, 8, 1),
(9, 9, 4),
(10, 10, 13),
(11, 11, 2),
(12, 11, 12),
(13, 12, 13),
(14, 13, 7),
(15, 14, 2),
(16, 15, 2),
(17, 16, 1),
(18, 17, 1),
(19, 18, 13),
(20, 19, 2),
(21, 20, 13),
(22, 21, 1),
(23, 21, 12),
(24, 22, 5);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `image`) VALUES
(1, 'Facial Treatments', '../images/CategoryImage/1772098105_FacialTreatments.png'),
(2, 'Body Contouring', '../images/CategoryImage/1772098202_BodyContouring.png'),
(3, 'Laser & IPL', '../images/CategoryImage/1772098317_Laser.png'),
(4, 'Injectables', '../images/CategoryImage/1772098392_cosmetic-injections.jpg'),
(5, 'Wellness & Spa', '../images/CategoryImage/1772098531_wellness&spa.png'),
(6, 'Skincare Consultations', '../images/CategoryImage/1772098625_Consultation.png'),
(7, 'Medical Aesthetics', '../images/CategoryImage/1772098758_11062b_cd79764dc47b45469a72176d8a01262f~mv2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `clientID` int(11) NOT NULL,
  `clientName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`clientID`, `clientName`, `email`, `phoneNumber`, `dateOfBirth`, `gender`, `password`, `image`) VALUES
(1, 'Hnin Aye Shwe Yee', 'yoy101819@gmail.com', '09765579200', '2005-07-06', 'Female', 'Hnin123!@#', '../images/CustomerImage_002.png'),
(2, 'Shoon Lae', 'shoon@gmail.com', '09782716392', '2003-07-10', 'Female', 'Shoon123!@#', '../images/CustomerImage_625502dd1e36470a3d02ccb17a285f79.jpg'),
(3, 'Rosey', 'rose@gmail.com', '09765579200 ', '1998-06-09', 'Female', 'Rose123!@#', '../images/CustomerImage_e950827e0c98958e6f2ec11a57b55361.jpg'),
(4, 'Yoyo', 'yoyo@gmail.com', '098262762', '2003-02-04', 'Female', 'Yoyo123!@#', '../images/CustomerImage_OIP.jpg'),
(5, 'Yuyu', 'yuyu@gmail.com', '0926728889', '2003-03-12', 'Female', 'Yuyu123!@#', '../images/CustomerImage_OIP.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `contactID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `status` enum('New','Read','Replied') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`contactID`, `name`, `email`, `subject`, `message`, `submitted_at`, `status`) VALUES
(2, 'Shoon Lae', 'shoon@gmail.com', 'General Inquiry', 'Hihihihihi', '2026-03-03 21:20:41', 'New'),
(3, 'Hnin Aye Shwe Yee', 'yoy101819@gmail.com', 'General Inquiry', 'hi', '2026-03-27 13:00:50', 'New'),
(4, 'Hnin Aye Shwe Yee', 'yoy101819@gmail.com', 'General Inquiry', 'Hello', '2026-03-28 09:29:22', 'New'),
(5, 'Yuyu', 'yuyu@gmail.com', 'General Inquiry', 'Hello. Hi', '2026-04-28 21:46:23', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctorID` int(11) NOT NULL,
  `doctorName` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctorID`, `doctorName`, `specialization`, `phone`, `email`, `image`) VALUES
(1, 'Dr. John', 'Aesthetic & Wellness Specializations', '0987765645', 'john@gmail.com', '../images/DoctorImage/1772097891_Doctor-Transparent-Free-PNG.png'),
(2, 'Dr. Wai', 'Wellness Specializations', '09656276768', 'wai@gmail.com', '../images/DoctorImage/1772097697_Wai.png'),
(3, 'Dr. Lynn', 'Dermotology', '09254117122', 'lynn@gmail.com', '../images/DoctorImage/1772097729_ai-generated-a-female-doctor-with-a-stethoscope-isolated-on-transparent-background-free-png.webp'),
(4, 'Dr. Yuri', 'Aesthetic & Wellness Specializations', '09877656455', 'yuri@gmail.com', '../images/DoctorImage/1772097767_yuri.png');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` enum('Pending','Paid') DEFAULT 'Pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `staffID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `appointmentID`, `amount`, `payment_method`, `status`, `payment_date`, `staffID`) VALUES
(1, 2, 150000.00, 'Cash', 'Paid', '2026-02-26 10:29:03', 5),
(2, 7, 100000.00, 'Cash', 'Paid', '2026-03-03 13:25:34', 5),
(3, 8, 50000.00, 'Cash', 'Paid', '2026-03-03 13:58:21', 5),
(4, 9, 130000.00, 'Cash', 'Paid', '2026-03-03 16:18:31', 5),
(5, 10, 230000.00, 'Cash', 'Paid', '2026-03-24 07:26:56', 5),
(6, 12, 230000.00, 'Cash', 'Paid', '2026-03-04 14:16:02', 5),
(7, 11, 280000.00, 'Cash', 'Paid', '2026-03-24 07:26:54', 5),
(8, 13, 200000.00, 'Cash', 'Paid', '2026-03-24 07:26:57', 5),
(9, 14, 100000.00, 'Cash', 'Paid', '2026-03-24 07:26:59', 5),
(10, 15, 100000.00, 'Cash', '', '2026-03-26 09:16:22', 5),
(11, 16, 50000.00, 'Cash', 'Paid', '2026-03-26 14:52:13', 5),
(12, 17, 50000.00, 'Cash', 'Paid', '2026-03-28 02:56:13', 5),
(13, 18, 230000.00, 'Cash', 'Paid', '2026-03-28 02:58:56', 5),
(14, 20, 230000.00, 'Cash', 'Paid', '2026-04-28 14:54:38', 5),
(15, 22, 180000.00, 'Cash', 'Paid', '2026-04-28 15:14:35', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `supplierID` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `productImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `supplierID`, `price`, `quantity`, `description`, `productImage`) VALUES
(1, 'Organic Clay Mask 250g', 1, 50000.00, 4, '', '../images/ProductImage/1772170842_NL61297_1_18011fac-0136-43a1-b818-dcb518b7d5cf.webp');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotionID` int(11) NOT NULL,
  `promotionName` varchar(150) NOT NULL,
  `discountRate` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotionID`, `promotionName`, `discountRate`, `startDate`, `endDate`, `description`) VALUES
(2, 'Valentine Special', 20, '2026-02-10', '2026-02-15', ''),
(3, 'Summer Refresh', 10, '2026-02-16', '2026-03-31', ''),
(4, 'Clear Skin Bundle', 10, '2026-04-01', '2026-04-20', ''),
(6, 'Summer Special', 20, '2026-04-25', '2026-04-30', '');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_treatments`
--

CREATE TABLE `promotion_treatments` (
  `promoTreatmentID` int(11) NOT NULL,
  `promotionID` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion_treatments`
--

INSERT INTO `promotion_treatments` (`promoTreatmentID`, `promotionID`, `treatmentID`) VALUES
(24, 4, 1),
(25, 4, 12),
(26, 3, 1),
(27, 3, 2),
(28, 3, 7),
(29, 3, 9),
(30, 3, 12),
(31, 3, 13),
(32, 2, 1),
(33, 2, 2),
(34, 2, 6),
(35, 2, 7),
(36, 2, 8),
(37, 2, 10),
(38, 6, 12),
(39, 6, 13);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleID` int(11) NOT NULL,
  `doctorID` int(11) NOT NULL,
  `available_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`scheduleID`, `doctorID`, `available_day`, `start_time`, `end_time`) VALUES
(1, 1, 'Monday', '09:00:00', '12:00:00'),
(2, 3, 'Monday', '13:00:00', '15:00:00'),
(3, 2, 'Monday', '15:00:00', '18:00:00'),
(4, 1, 'Tuesday', '09:00:00', '12:00:00'),
(5, 3, 'Tuesday', '13:00:00', '15:00:00'),
(6, 4, 'Tuesday', '15:00:00', '18:00:00'),
(7, 4, 'Wednesday', '09:00:00', '12:00:00'),
(8, 3, 'Wednesday', '13:00:00', '15:00:00'),
(9, 2, 'Wednesday', '15:00:00', '18:00:00'),
(10, 4, 'Thursday', '09:00:00', '12:00:00'),
(11, 1, 'Thursday', '13:00:00', '15:00:00'),
(12, 2, 'Thursday', '15:00:00', '18:00:00'),
(13, 1, 'Friday', '09:00:00', '12:00:00'),
(14, 3, 'Friday', '13:00:00', '15:00:00'),
(15, 2, 'Friday', '15:00:00', '18:00:00'),
(16, 1, 'Saturday', '09:00:00', '10:30:00'),
(17, 3, 'Saturday', '10:30:00', '12:00:00'),
(18, 2, 'Saturday', '13:00:00', '15:00:00'),
(20, 4, 'Saturday', '15:00:00', '19:00:00'),
(21, 4, 'Monday', '16:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` int(11) NOT NULL,
  `staffName` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `role` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffID`, `staffName`, `email`, `address`, `phoneNumber`, `role`, `password`, `image`, `status`) VALUES
(5, 'Aung Aung', 'aung1@gmail.com', 'Yangon', '0987690897', 'Admin', 'Aung123!@#', '../images/StaffImage/1772780032_002.png', 'Active'),
(9, 'Hnin Aye', 'hnin@gmail.com', 'No.7(003),11th street, Hlaing', '09765579200', 'Website Admin', 'Hnin123!@#', '../images/StaffImage_625502dd1e36470a3d02ccb17a285f79.jpg', 'Active'),
(10, 'Rose', 'rose@gmail.com', '12, Yangon', '0962767326', 'Admin', 'Rose123!@#', '../images/StaffImage/1777387502_OIP.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierID` int(11) NOT NULL,
  `supplierName` varchar(150) NOT NULL,
  `supplierAddress` text NOT NULL,
  `supplierEmail` varchar(100) NOT NULL,
  `supplierPhone` varchar(20) NOT NULL,
  `contactName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierID`, `supplierName`, `supplierAddress`, `supplierEmail`, `supplierPhone`, `contactName`) VALUES
(1, 'JM', 'Yangon', 'jm@gmail.com', '09783281726', 'Shoon'),
(2, 'Fashion Supply', 'No.7(003),11th street, Hlaing', 'yoy101819@gmail.com', '09765579200', 'Hnin Aye Shwe Yee'),
(4, 'Bee Bee ', 'Yangon', 'beebee@gmail.com', '092836298376', 'Pwint');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `treatmentID` int(11) NOT NULL,
  `treatmentName` varchar(150) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`treatmentID`, `treatmentName`, `categoryID`, `price`, `duration`, `description`, `image`) VALUES
(1, 'HydraFacial Deluxe', 1, 50000.00, '60', 'Deep cleansing, exfoliation, and hydration serum infusion.', '../images/TreatmentImage/1772099073_hydrafacial.jpg'),
(2, 'Oxygen Glow Facial', 1, 100000.00, '45', 'Oxygen-infused treatment for immediate radiance.', '../images/TreatmentImage/1772099165_oxygen_glow_facial.webp'),
(3, 'Cryo-Sculpting', 2, 120000.00, '90', 'Non-invasive fat freezing for target body areas.', '../images/TreatmentImage/1772099534_cry.jpg'),
(4, 'RF Skin Tightening', 2, 130000.00, '60', 'Radiofrequency energy to tighten loose skin.', '../images/TreatmentImage/1772099585_SkinTighting.jpg'),
(5, 'Laser Hair Removal (Full Leg)', 3, 180000.00, '45', 'Permanent reduction of unwanted hair.', '../images/TreatmentImage/1772099664_underarm-laser-hair-removal-1024x676.webp'),
(6, 'IPL Pigment Correction', 3, 90000.00, '30', 'Light therapy to reduce sun spots and redness.', '../images/TreatmentImage/1772099726_IPL-laser-1@2x.jpg'),
(7, 'Botox Anti-Wrinkle', 4, 200000.00, '20', 'Targeted treatment for fine lines and crow feet.', '../images/TreatmentImage/1772099764_1674050698botox-blog-big.jpg'),
(8, 'Dermal Fillers (1ml)', 4, 320000.00, '30', 'Hyaluronic acid filler for volume restoration.', '../images/TreatmentImage/1772099825_5-Reasons-Why-Botox-Dermal-Fillers-Are-the-Perfect-Pairing-And-How-They-Can-Transform-Your-Look.jpg'),
(9, 'Advanced Skin Analysis', 6, 0.00, '30', 'Digital skin mapping to identify hydration, pore size, and elasticity.', '../images/TreatmentImage/1772100045_4_sebum_quadrat.jpg'),
(10, 'Personalized Regimen Build', 6, 0.00, '30', 'Customized product roadmap for at-home skincare routines.', '../images/TreatmentImage/1772100117_Crafting_Your_Personalized_Gut-Health_Regimen.webp'),
(11, 'Microneedling Collagen Induction', 7, 100000.00, '60', 'Professional-grade device to trigger natural skin healing.', '../images/TreatmentImage/1772100161_Collagen-Induced-Therapy-Microneedling_2185130243.jpg.optimal.jpg'),
(12, 'TCA Chemical Peel', 7, 180000.00, '45', 'Clinical peel for resurfacing and deep acne scar treatment.', '../images/TreatmentImage/1772100201_tca-chemical-peel_2.webp'),
(13, 'Platelet-Rich Plasma (PRP)', 7, 230000.00, '75', 'Advanced skin rejuvenation using autologous plasma.', '../images/TreatmentImage/1772100301_Microneedling-services.png');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_product`
--

CREATE TABLE `treatment_product` (
  `id` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity_required` decimal(10,2) DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `clientID` (`clientID`),
  ADD KEY `doctorID` (`doctorID`),
  ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `appointment_details`
--
ALTER TABLE `appointment_details`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `treatmentID` (`treatmentID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`clientID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`contactID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctorID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `supplierID` (`supplierID`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotionID`);

--
-- Indexes for table `promotion_treatments`
--
ALTER TABLE `promotion_treatments`
  ADD PRIMARY KEY (`promoTreatmentID`),
  ADD KEY `promotionID` (`promotionID`),
  ADD KEY `treatmentID` (`treatmentID`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `doctorID` (`doctorID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`treatmentID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `treatment_product`
--
ALTER TABLE `treatment_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatmentID` (`treatmentID`),
  ADD KEY `productID` (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `appointment_details`
--
ALTER TABLE `appointment_details`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `clientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `contactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `promotion_treatments`
--
ALTER TABLE `promotion_treatments`
  MODIFY `promoTreatmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `treatmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `treatment_product`
--
ALTER TABLE `treatment_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`clientID`) REFERENCES `client` (`clientID`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctorID`) REFERENCES `doctor` (`doctorID`);

--
-- Constraints for table `appointment_details`
--
ALTER TABLE `appointment_details`
  ADD CONSTRAINT `appointment_details_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointments` (`appointmentID`),
  ADD CONSTRAINT `appointment_details_ibfk_2` FOREIGN KEY (`treatmentID`) REFERENCES `treatment` (`treatmentID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointments` (`appointmentID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`);

--
-- Constraints for table `promotion_treatments`
--
ALTER TABLE `promotion_treatments`
  ADD CONSTRAINT `promotion_treatments_ibfk_1` FOREIGN KEY (`promotionID`) REFERENCES `promotion` (`promotionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_treatments_ibfk_2` FOREIGN KEY (`treatmentID`) REFERENCES `treatment` (`treatmentID`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`doctorID`) REFERENCES `doctor` (`doctorID`) ON DELETE CASCADE;

--
-- Constraints for table `treatment`
--
ALTER TABLE `treatment`
  ADD CONSTRAINT `treatment_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`);

--
-- Constraints for table `treatment_product`
--
ALTER TABLE `treatment_product`
  ADD CONSTRAINT `treatment_product_ibfk_1` FOREIGN KEY (`treatmentID`) REFERENCES `treatment` (`treatmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_product_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
