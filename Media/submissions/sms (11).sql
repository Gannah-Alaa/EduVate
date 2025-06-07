-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2025 at 06:25 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `AdminName` varchar(255) NOT NULL,
  `AdminEmail` varchar(255) NOT NULL,
  `AdminPass` varchar(255) NOT NULL,
  `RoleId` int(11) NOT NULL,
  `AdminNumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `AdminName`, `AdminEmail`, `AdminPass`, `RoleId`, `AdminNumber`) VALUES
(1, 'Gannah Alaa Osman', 'gannaalaa02@gmail.com', '$2y$10$mCtyEYR4EqM3GTpnOYCIe.bkMw10EHHnwe.6tcxImt0QRgqr7gmh.', 1, '01068385809'),
(2, 'Sama', 'Sama@eduvate.edu', '$2y$10$JikgVJF7AJRHJwypzseDSODLYaIcbaACaHdOgmQ/S.zfLdAbwgCV6', 2, '01225556667'),
(3, 'Omaima ', 'Omaima@eduvate.edu', '$2y$10$15IOKMl/waZINZUYEQruAOwS/ZxKtRRnshgHHkEtnDrCrkHSnItzG', 2, '01133222999'),
(4, 'Moaz Mohamed', 'moaz@eduvate.edu', '$2y$10$bElnmKWwtAHm0.fiN63dnumcxLbobFmpH1Iiyuhm6PIfiWR.781KO', 1, '01225556667');

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `BusID` int(11) NOT NULL,
  `BusNumber` int(11) NOT NULL,
  `Destination` varchar(255) NOT NULL,
  `BusSupervisor` varchar(255) NOT NULL,
  `SupervisorNumber` varchar(255) NOT NULL,
  `Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`BusID`, `BusNumber`, `Destination`, `BusSupervisor`, `SupervisorNumber`, `Price`) VALUES
(1, 1, 'Nasr City', 'Ms. Noha', '01010101010', 3000),
(2, 2, 'New Cairo 1', 'Ms Taghreed', '01918617189', 3000),
(3, 3, 'New Cairo 3', 'ms. salma', '0191383919', 3000),
(4, 4, 'New Cairo 5', 'Ms Samar', '0182727828', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `ChatID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `ParentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`ChatID`, `TeacherID`, `ParentID`) VALUES
(2, 17, 7),
(3, 26, 1),
(4, 26, 5),
(1, 26, 7);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `ClassID` int(11) NOT NULL,
  `ClassName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`ClassID`, `ClassName`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F'),
(7, 'G');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `ContactID` int(11) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`ContactID`, `Message`, `Email`, `Subject`, `PhoneNumber`) VALUES
(1, 'gannaalaa02@gmail.com', 'help', 'how can I apply?', '01068385800'),
(2, 'moaz@eduvate.edu', 'request', 'blablabla', '01068385800');

-- --------------------------------------------------------

--
-- Table structure for table `family`
--

CREATE TABLE `family` (
  `StudentID` int(11) NOT NULL,
  `ParentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family`
--

INSERT INTO `family` (`StudentID`, `ParentID`) VALUES
(1, 5),
(2, 7),
(3, 7),
(5, 4),
(6, 5),
(7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `GradeID` int(11) NOT NULL,
  `GradeNumber` varchar(255) NOT NULL,
  `FeeAmount` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`GradeID`, `GradeNumber`, `FeeAmount`) VALUES
(1, 'Grade 1', 14000),
(2, 'Grade 2', 13500),
(3, 'Grade 3', 14200),
(4, 'Grade 4', 12900),
(5, 'Grade 5', 15000),
(6, 'Grade 6', 13300),
(7, 'Grade 7', 13400),
(8, 'Grade 8', 14000),
(9, 'Grade 9', 12000),
(10, 'Grade 10', 13100),
(11, 'Grade 11', 13600),
(12, 'Grade 12', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `MarkID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `MarkType` varchar(255) NOT NULL,
  `MarkValue` decimal(10,0) NOT NULL,
  `Semester` int(11) NOT NULL,
  `GradeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`MarkID`, `SubjectID`, `StudentID`, `MarkType`, `MarkValue`, `Semester`, `GradeID`) VALUES
(2, 2, 1, 'Quiz', 9, 1, 6),
(3, 2, 1, 'Quiz', 10, 1, 6),
(4, 2, 1, 'Quiz', 8, 1, 6),
(5, 16, 1, 'Participation', 12, 1, 2),
(6, 1, 2, 'Attendance', 1, 1, 2),
(7, 1, 2, 'Attendance', 1, 1, 2),
(8, 1, 2, 'Attendance', 0, 1, 2),
(9, 1, 2, 'Attendance', 1, 1, 2),
(10, 1, 2, 'Attendance', 1, 1, 2),
(11, 1, 2, 'Attendance', 0, 1, 2),
(12, 1, 7, 'Final', 80, 1, 2),
(13, 1, 7, 'Attendance', 1, 1, 2),
(14, 1, 7, 'Attendance', 1, 1, 2),
(17, 1, 2, 'Quiz', 10, 1, 2),
(18, 1, 7, 'Quiz', 10, 1, 2),
(19, 1, 7, 'Quiz', 9, 1, 2),
(20, 1, 2, 'Quiz', 7, 1, 2),
(21, 1, 7, 'Attendance', 0, 1, 2),
(22, 1, 7, 'Attendance', 1, 1, 2),
(23, 1, 2, 'Midterm', 48, 1, 2),
(24, 1, 2, 'Attendance', 1, 1, 2),
(25, 1, 7, 'Midterm', 46, 1, 2),
(26, 1, 2, 'Final', 98, 1, 2),
(27, 1, 2, 'Classwork', 45, 1, 2),
(28, 1, 6, 'Attendance', 1, 1, 2),
(29, 1, 6, 'Attendance', 0, 1, 2),
(30, 1, 6, 'Midterm', 47, 1, 2),
(31, 1, 6, 'Quiz', 8, 1, 2),
(32, 1, 6, 'Quiz', 7, 1, 2),
(35, 1, 9, 'Attendance', 1, 1, 2),
(36, 1, 2, 'Attendance', 1, 1, 2),
(37, 1, 7, 'Attendance', 1, 1, 2),
(38, 1, 5, 'Attendance', 0, 1, 2),
(39, 1, 8, 'Attendance', 0, 1, 2),
(40, 1, 6, 'Attendance', 0, 1, 2),
(41, 1, 9, 'Attendance', 1, 1, 2),
(42, 1, 2, 'Attendance', 0, 1, 2),
(43, 1, 7, 'Attendance', 0, 1, 2),
(44, 1, 5, 'Attendance', 0, 1, 2),
(45, 1, 8, 'Attendance', 0, 1, 2),
(46, 1, 6, 'Attendance', 0, 1, 2),
(47, 1, 9, 'Attendance', 1, 1, 2),
(48, 1, 2, 'Attendance', 1, 1, 2),
(49, 1, 7, 'Attendance', 1, 1, 2),
(50, 1, 5, 'Attendance', 1, 1, 2),
(51, 1, 8, 'Attendance', 1, 1, 2),
(52, 1, 6, 'Attendance', 1, 1, 2),
(55, 1, 9, 'Attendance', 1, 1, 2),
(56, 1, 2, 'Attendance', 1, 1, 2),
(57, 1, 7, 'Attendance', 0, 1, 2),
(58, 1, 5, 'Attendance', 1, 1, 2),
(59, 1, 8, 'Attendance', 0, 1, 2),
(60, 1, 6, 'Attendance', 10, 1, 2),
(61, 15, 9, 'Quiz', 7, 1, 2),
(62, 15, 2, 'Quiz', 9, 1, 2),
(63, 15, 7, 'Quiz', 10, 1, 2),
(64, 15, 5, 'Quiz', 2, 1, 2),
(65, 15, 8, 'Quiz', 2, 1, 2),
(66, 15, 6, 'Quiz', 2, 1, 2),
(67, 15, 9, 'Quiz', 10, 1, 2),
(68, 15, 9, 'Quiz', 10, 1, 2),
(69, 15, 2, 'Quiz', 10, 1, 2),
(70, 15, 7, 'Quiz', 10, 1, 2),
(71, 15, 5, 'Quiz', 10, 1, 2),
(72, 15, 8, 'Quiz', 10, 1, 2),
(73, 15, 6, 'Quiz', 10, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `MaterialID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Material` longtext DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `TeacherID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`MaterialID`, `Title`, `Material`, `body`, `TeacherID`, `ClassID`) VALUES
(1, 'Class Material Lesson 2', 'material.pdf', 'print this for next tuesday', 1, 5),
(10, '', '1697662116335.jpeg', 'aaa', 4, 4),
(12, '', 'material (1).pdf', 'Print this for our next session Tomorrow', 4, 4),
(14, '', 'file (7).pdf', 'Tuesday\'s Homework', 1, 6),
(15, '', '', 'Quiz Next Sunday On the Novel', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL,
  `ChatID` int(11) NOT NULL,
  `Text` varchar(255) NOT NULL,
  `Media` longtext NOT NULL,
  `Seen` enum('Seen','Unseen') NOT NULL,
  `DateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `Sender` enum('Teacher','Parent') NOT NULL,
  `IsDeleted` varchar(255) NOT NULL DEFAULT 'False'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MessageID`, `ChatID`, `Text`, `Media`, `Seen`, `DateTime`, `Sender`, `IsDeleted`) VALUES
(1, 1, 'hey Ms. Heba\r\n', '', 'Seen', '2025-06-04 02:25:33', 'Parent', 'FALSE'),
(2, 1, 'hi!', '', 'Seen', '2025-06-04 02:44:59', 'Teacher', 'FALSE'),
(3, 1, 'hru', '', 'Seen', '2025-06-04 02:45:15', 'Parent', 'FALSE'),
(4, 1, 'good good', '', 'Seen', '2025-06-04 02:45:49', 'Teacher', 'FALSE'),
(5, 1, '!!!', '', 'Seen', '2025-06-04 02:47:50', 'Parent', 'FALSE'),
(6, 1, 'lol', '', 'Seen', '2025-06-04 02:48:05', 'Teacher', 'FALSE'),
(7, 1, '!!', '', 'Seen', '2025-06-04 02:49:28', 'Teacher', 'FALSE'),
(8, 1, 'haha', '', 'Seen', '2025-06-04 02:49:42', 'Parent', 'FALSE'),
(9, 1, '', '683f92c5e912b_anon-removebg-preview.png', 'Seen', '2025-06-04 03:26:46', 'Teacher', 'FALSE'),
(10, 1, '', '683f92d5a63d2_anon-removebg-preview.png', 'Seen', '2025-06-04 03:27:01', 'Parent', 'True'),
(11, 1, 'test?', '', 'Seen', '2025-06-04 05:45:02', 'Parent', 'FALSE'),
(12, 1, 'idk man\r\n', '', 'Seen', '2025-06-04 05:45:17', 'Teacher', 'FALSE'),
(14, 1, 'gg', '', 'Seen', '2025-06-04 06:18:06', 'Parent', 'False'),
(15, 1, 'hey someone', '', 'Seen', '2025-06-04 06:18:12', 'Teacher', 'False'),
(16, 1, 'ahaha', '', 'Seen', '2025-06-04 06:19:43', 'Parent', 'False'),
(17, 1, 'Ganna', '', 'Seen', '2025-06-04 06:20:16', 'Teacher', 'True'),
(18, 1, 'kk', '', 'Seen', '2025-06-04 06:25:16', 'Teacher', 'True'),
(19, 1, '', '683fbd655e238_material (3).pdf', 'Seen', '2025-06-04 06:28:37', 'Teacher', 'True'),
(20, 1, 'jj', '', 'Seen', '2025-06-04 06:30:37', 'Teacher', 'True'),
(21, 1, 'gg', '', 'Seen', '2025-06-04 06:31:06', 'Teacher', 'True'),
(22, 1, 'ahaha', '', 'Seen', '2025-06-04 06:31:45', 'Teacher', 'False'),
(23, 1, 'Ganna', '', 'Seen', '2025-06-04 06:32:39', 'Parent', 'False'),
(24, 1, 'jj', '', 'Seen', '2025-06-04 06:32:56', 'Parent', 'False'),
(25, 1, 'ahaha', '', 'Seen', '2025-06-04 06:33:53', 'Parent', 'False'),
(26, 1, 'gg', '', 'Seen', '2025-06-04 06:34:10', 'Teacher', 'False'),
(27, 1, 'Moaz', '', 'Seen', '2025-06-04 06:34:31', 'Teacher', 'True'),
(28, 1, 'let\'s see', '', 'Seen', '2025-06-04 06:36:08', 'Teacher', 'False'),
(29, 1, '??', '', 'Seen', '2025-06-04 06:36:25', 'Parent', 'False'),
(30, 1, '??', '', 'Seen', '2025-06-04 06:36:39', 'Teacher', 'False'),
(31, 1, 'hey someone', '', 'Seen', '2025-06-04 06:38:45', 'Teacher', 'False'),
(32, 1, 'hey heba', '', 'Seen', '2025-06-04 06:39:08', 'Parent', 'False'),
(33, 1, 'lets see', '', 'Seen', '2025-06-04 06:42:38', 'Parent', 'False'),
(34, 1, '', '683fc184ab208_Default.png', 'Seen', '2025-06-04 06:46:12', 'Teacher', 'False'),
(35, 1, '', '683fc1c8f00d8_BIS graduation project format-1.pdf', 'Seen', '2025-06-04 06:47:20', 'Teacher', 'False'),
(36, 1, '', '683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 06:48:36', 'Teacher', 'False'),
(37, 1, '.', '', 'Seen', '2025-06-04 06:51:52', 'Teacher', 'False'),
(38, 1, '.', '', 'Seen', '2025-06-04 06:51:56', 'Parent', 'False'),
(39, 1, 'Hey Someone', '', 'Seen', '2025-06-04 06:52:03', 'Parent', 'False'),
(40, 1, 'Hey Heba', '', 'Seen', '2025-06-04 06:52:09', 'Teacher', 'False'),
(41, 1, 'hey Heba', '', 'Seen', '2025-06-04 06:52:30', 'Teacher', 'True'),
(42, 1, 'Hey Someone', '', 'Seen', '2025-06-04 06:52:41', 'Teacher', 'False'),
(43, 1, 'Hey Heba', '', 'Seen', '2025-06-04 06:52:46', 'Parent', 'False'),
(44, 1, 'test?', '', 'Seen', '2025-06-04 06:52:53', 'Parent', 'False'),
(45, 1, 'Let\'s see', '', 'Seen', '2025-06-04 06:53:00', 'Teacher', 'False'),
(46, 1, 'seen?', '', 'Seen', '2025-06-04 06:53:05', 'Teacher', 'False'),
(47, 1, '?', '', 'Seen', '2025-06-04 06:53:33', 'Teacher', 'True'),
(48, 1, '?', '', 'Seen', '2025-06-04 06:55:39', 'Parent', 'False'),
(49, 1, 'hey', '', 'Seen', '2025-06-04 06:55:53', 'Parent', 'True'),
(50, 1, 'hey?', '', 'Seen', '2025-06-04 06:56:13', 'Parent', 'False'),
(51, 1, 'Ganna', '', 'Seen', '2025-06-04 06:57:43', 'Parent', 'True'),
(52, 1, 'hi', '', 'Seen', '2025-06-04 06:58:07', 'Teacher', 'False'),
(53, 1, 'hw?', '', 'Seen', '2025-06-04 06:58:53', 'Parent', 'False'),
(54, 1, 'gg', '', 'Seen', '2025-06-04 06:59:11', 'Teacher', 'False'),
(55, 1, 'hi', '', 'Seen', '2025-06-04 07:00:01', 'Parent', 'False'),
(56, 1, 'hey', '', 'Seen', '2025-06-04 07:00:13', 'Teacher', 'True'),
(57, 1, '', '683fc4e9dac10_683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 07:00:41', 'Teacher', 'False'),
(58, 1, 'hey', '', 'Seen', '2025-06-04 07:03:52', 'Teacher', 'False'),
(59, 1, 'hi', '', 'Seen', '2025-06-04 07:04:16', 'Parent', 'False'),
(60, 1, '', '683fc5c902e7f_683fc4e9dac10_683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 07:04:25', 'Teacher', 'True'),
(61, 1, 'ahaha', '', 'Seen', '2025-06-04 07:09:30', 'Teacher', 'False'),
(62, 1, 'hey someone', '', 'Seen', '2025-06-04 07:10:26', 'Teacher', 'True'),
(63, 1, 'gg', '', 'Seen', '2025-06-04 07:10:44', 'Parent', 'False'),
(64, 1, '', '683fc759d598c_683fc5c902e7f_683fc4e9dac10_683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 07:11:05', 'Teacher', 'True'),
(65, 1, 'hey someone', '', 'Seen', '2025-06-04 07:12:51', 'Teacher', 'False'),
(66, 1, 'Hey!!\r\n', '', 'Seen', '2025-06-04 07:12:56', 'Parent', 'False'),
(67, 1, 'Homework?', '', 'Seen', '2025-06-04 07:13:03', 'Teacher', 'False'),
(68, 1, 'yea', '', 'Seen', '2025-06-04 07:13:09', 'Parent', 'False'),
(69, 1, '', '683fc7dbdb423_683fc5c902e7f_683fc4e9dac10_683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 07:13:15', 'Teacher', 'False'),
(70, 1, 'hey someone', '', 'Seen', '2025-06-04 08:06:38', 'Teacher', 'False'),
(71, 1, 'miss heba', '', 'Seen', '2025-06-04 08:06:57', 'Parent', 'False'),
(72, 1, 'albaha', '', 'Seen', '2025-06-04 08:07:02', 'Teacher', 'True'),
(73, 1, '', '683fd4826d018_683fc759d598c_683fc5c902e7f_683fc4e9dac10_683fc214d55d8_people_14512932.png', 'Seen', '2025-06-04 08:07:14', 'Teacher', 'False'),
(74, 4, 'hey wello', '', 'Seen', '2025-06-04 08:09:27', 'Teacher', 'True'),
(75, 1, 'Ganna', '', 'Seen', '2025-06-04 11:08:41', 'Parent', 'False'),
(76, 1, 'hh', '', 'Unseen', '2025-06-04 11:09:04', 'Parent', 'False');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `NewsID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Pics` longtext NOT NULL,
  `desc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`NewsID`, `Title`, `Pics`, `desc`) VALUES
(3, 'Computers', '684117c908bd1_globe-camera-books-near-laptop.jpg', 'hahahaha'),
(4, 'Privacy', '684119acd72b9_2147664249.jpg', 'lorem ba2a w kalam w keda');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `ParentID` int(11) NOT NULL,
  `ParentName` varchar(255) NOT NULL,
  `ParentEmail` varchar(255) NOT NULL,
  `ParentPass` varchar(255) NOT NULL,
  `ParentNumber` varchar(255) NOT NULL,
  `Is_subscribed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`ParentID`, `ParentName`, `ParentEmail`, `ParentPass`, `ParentNumber`, `Is_subscribed`) VALUES
(1, 'Alaa Osman', 'alaa.osman1970@gmail.com', '$2y$10$5P7lNmQjsLgK6Ip3XSLYZemyL5Rt3Rbg0PNyPvqyCA/C2zEL.cYE2', '01068385800', 0),
(3, 'Alaa Osman', 'alaa.osman19870@gmail.com', '$2y$10$8goS2C9ammnFJO1nm7dJ7ObvAYe81tVBC2FYMDqpM3.LILSh0cL6a', '01068385800', 0),
(4, 'Badr', 'Badr@gmail.com', '$2y$10$hD5ZuyWcLgUxsDwrpoZiaOiyPL94x7GZ8oYI9j480yMwTIdYkiYnK', '01010100000', 1),
(5, 'Waleed Ahmed', 'Waleed@eduvate.edu', '$2y$10$N5J13VK6qRTMDYee5/Uj1uu9sL4smmDBUjpoQjyC.W4LxWWMvS9OW', '01133222999', 0),
(6, 'Hoda Maher ', 'HodaMaher@eduvate.edu', '$2y$10$n/lGUprgCIUD/HvoLa2dnOToLicBJMFMoXb.q5JSgm.mafzdNVk7m', '01005212532', 0),
(7, 'someone', 'Someonesdad@eduvate.edu', '$2y$10$VjLFoxH6rYJogEP9dmywVu3xW0qFkAyOg9o7vdgkOkSvqL0HiyvH.', '01005212532', 1),
(8, 'Mohamed', 'Mohamed@eduvate.edu', '$2y$10$3gDok5voSmR7FpWpRHRMmOOlj3NSt7KAZ/gvAhV.4f2KjzRPeieVe', '01005212532', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Fees` float DEFAULT NULL,
  `TotalPrice` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `StudentID`, `Fees`, `TotalPrice`) VALUES
(2, 2, 25000, 25000),
(3, 2, 13500, 13500);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `RoleID` int(11) NOT NULL,
  `RoleTitle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`RoleID`, `RoleTitle`) VALUES
(1, 'Administrator'),
(2, 'Follow Up'),
(3, 'Supervisor'),
(4, 'Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `ScheduleID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `Semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`ScheduleID`, `ClassID`, `grade`, `Semester`) VALUES
(1, 5, 12, '1'),
(2, 3, 6, '1'),
(3, 2, 6, '1'),
(4, 3, 6, '1'),
(5, 4, 6, '1'),
(6, 1, 7, '1'),
(7, 2, 7, '1'),
(8, 3, 7, '1'),
(9, 4, 7, '1'),
(10, 1, 8, '1'),
(11, 2, 8, '1'),
(12, 3, 8, '1'),
(13, 4, 8, '1'),
(14, 4, 9, '1'),
(15, 2, 9, '1'),
(16, 3, 9, '1'),
(17, 4, 9, '1'),
(18, 1, 10, '1'),
(19, 2, 10, '1'),
(20, 3, 10, '1'),
(21, 4, 10, '1'),
(22, 1, 11, '1'),
(23, 2, 11, '1'),
(24, 3, 11, '1'),
(25, 4, 11, '1'),
(26, 1, 12, '1'),
(27, 2, 12, '1'),
(28, 3, 12, '1'),
(29, 4, 12, '1'),
(31, 6, 2, '1'),
(33, 1, 1, '1'),
(34, 6, 9, '1'),
(35, 2, 1, '1'),
(36, 3, 1, '1'),
(37, 7, 2, '1'),
(38, 6, 1, '1'),
(39, 2, 2, '1'),
(40, 7, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `StudentName` varchar(255) NOT NULL,
  `StudentEmail` varchar(255) NOT NULL,
  `StudentPass` varchar(255) NOT NULL,
  `StudentAddress` varchar(255) NOT NULL,
  `StudentNumber` varchar(255) NOT NULL,
  `FeePayment` tinyint(1) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Class` int(11) NOT NULL,
  `Picture` longtext NOT NULL DEFAULT 'studentpp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `StudentName`, `StudentEmail`, `StudentPass`, `StudentAddress`, `StudentNumber`, `FeePayment`, `Grade`, `Class`, `Picture`) VALUES
(1, 'Gannah', 'gannaalaa02@gmail.com', '$2y$10$jgWbvidMBwNyAdxwtSLzT.CKdj2ehtwX/zyrDcgMHbaNgXueRrTYu', 'New Cairo, 10008', '01068385809', 0, 6, 1, ''),
(2, 'Ganna Alaa Eldin Osman', 'gannaalaa2@gmail.com', '$2y$10$2s8SUVn0gNLlox2bsoDkEeOfrDbSOUNtZBlac6/3.oFt3MLLIb3v2', 'New Cairo, 10008', '01068385809', 1, 2, 6, '683ba2ccb76e9.jpg'),
(3, 'Gannah Alaa', 'gannaalaa@gmail.com', '$2y$10$zaSBcfKXrWZOpN8XtbTigOURSj4Y0fWdIGspd/Ucl6GH2eIQ4BLCK', 'New Cairo, 10008', '01068385800', 1, 12, 5, '680d65c646893_1697662116335.jpeg'),
(5, 'Mohamed Tarek Badr', 'taroo2@gmail.com', '$2y$10$djh4IfVhC4p4G0A7uv.e6.Zk//VVLWaXxBscwFnvIu6MUNJquUcbq', 'Maadi, Cairo', '01068385800', 0, 2, 6, 'studentpp.png'),
(6, 'Sama Waleed Ahmed', 'Sama@eduvate.edu', '$2y$10$ejfZZEsP/Z3pqIFYIaHG/eQCyUO9VMdJ/qU1VGvgiHzYNublAm6B.', 'Nasr City', '01225556667', 0, 2, 6, 'studentpp.png'),
(7, 'Moaz Mohamed', 'moaz@eduvate.edu', '$2y$10$sgl.jPpRu9hKyYH/FbmWNu/ZCbR.CzKgLBjAVahxGa6I9kh/qBMjG', 'Nasr City', '01225556667', 0, 2, 6, 'studentpp.png'),
(8, 'Omaima', 'Omaima@eduvate.edu', '$2y$10$/4ckLPwEGo1Y31IVo.uy7eS3SU404.XVwyAmzswSEfTLeibxsVfna', 'sdfghjk', '01333222999', 0, 2, 6, 'studentpp.png'),
(9, 'Ahmed Hussien', 'AhmedH@eduvate.edu', '$2y$10$PALP8q6To6cRvoGr9mT9u.t662nmOGs9pSp3s48f4sijrjqtZUovm', 'sdfghjk', '01333222999', 0, 2, 6, 'studentpp.png');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL,
  `Ebooks` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectID`, `SubjectName`, `Ebooks`) VALUES
(1, 'English', 'file.pdf'),
(2, 'Arabic and Religion', NULL),
(3, 'Math', NULL),
(4, 'social Studies', NULL),
(5, 'Science', NULL),
(6, 'History and Civics', NULL),
(7, 'French', NULL),
(8, 'German', NULL),
(9, 'geography', NULL),
(10, 'Physics', NULL),
(11, 'Chemistry', NULL),
(12, 'Biology', NULL),
(13, 'Philosophy and Logic', NULL),
(14, 'Psychology and sociology', NULL),
(15, 'IT', NULL),
(16, 'Art and Music', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `submitions`
--

CREATE TABLE `submitions` (
  `SubmitID` int(11) NOT NULL,
  `MaterialID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  `Media` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submitions`
--

INSERT INTO `submitions` (`SubmitID`, `MaterialID`, `StudentID`, `Comment`, `Media`) VALUES
(16, 1, 3, 'aksakas', '1697049454492.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `s_details`
--

CREATE TABLE `s_details` (
  `DetailID` int(11) NOT NULL,
  `ScheduleID` int(11) NOT NULL,
  `Weekday` varchar(255) NOT NULL,
  `PeriodNumber` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `s_details`
--

INSERT INTO `s_details` (`DetailID`, `ScheduleID`, `Weekday`, `PeriodNumber`, `SubjectID`, `TeacherID`, `StartTime`, `EndTime`) VALUES
(1, 1, 'Sunday', 1, 8, 1, '08:00:00', '08:45:00'),
(2, 1, 'Sunday', 2, 15, 1, '08:45:00', '09:30:00'),
(3, 1, 'Sunday', 3, 5, 1, '09:30:00', '10:15:00'),
(4, 1, 'Sunday', 4, 2, 1, '10:15:00', '11:00:00'),
(5, 1, 'Sunday', 5, 14, 1, '11:30:00', '12:15:00'),
(6, 1, 'Sunday', 6, 3, 1, '12:15:00', '13:00:00'),
(7, 1, 'Sunday', 7, 1, 1, '13:00:00', '13:45:00'),
(8, 1, 'Monday', 1, 3, 1, '08:00:00', '08:45:00'),
(9, 1, 'Monday', 2, 2, 1, '08:45:00', '09:30:00'),
(10, 1, 'Monday', 3, 2, 1, '09:30:00', '10:15:00'),
(11, 1, 'Monday', 4, 5, 1, '10:15:00', '11:00:00'),
(12, 1, 'Monday', 5, 1, 1, '11:30:00', '12:15:00'),
(13, 1, 'Monday', 6, 9, 1, '12:15:00', '13:00:00'),
(14, 1, 'Monday', 7, 13, 1, '13:00:00', '13:45:00'),
(15, 1, 'Tuesday', 1, 1, 1, '08:00:00', '08:45:00'),
(16, 1, 'Tuesday', 2, 1, 1, '08:45:00', '09:30:00'),
(17, 1, 'Tuesday', 3, 10, 1, '09:30:00', '10:15:00'),
(18, 1, 'Tuesday', 4, 8, 1, '10:15:00', '11:00:00'),
(19, 1, 'Tuesday', 5, 3, 1, '11:30:00', '12:15:00'),
(20, 1, 'Tuesday', 6, 3, 1, '12:15:00', '13:00:00'),
(21, 1, 'Tuesday', 7, 16, 1, '13:00:00', '13:45:00'),
(22, 1, 'Wednesday', 1, 6, 1, '08:00:00', '08:45:00'),
(23, 1, 'Wednesday', 2, 15, 1, '08:45:00', '09:30:00'),
(24, 1, 'Wednesday', 3, 2, 1, '09:30:00', '10:15:00'),
(25, 1, 'Wednesday', 4, 2, 1, '10:15:00', '11:00:00'),
(26, 1, 'Wednesday', 5, 1, 1, '11:30:00', '12:15:00'),
(27, 1, 'Wednesday', 6, 1, 1, '12:15:00', '13:00:00'),
(28, 1, 'Wednesday', 7, 3, 1, '13:00:00', '13:45:00'),
(29, 1, 'Thursday', 1, 12, 1, '08:00:00', '08:45:00'),
(30, 1, 'Thursday', 2, 1, 1, '08:45:00', '09:30:00'),
(31, 1, 'Thursday', 3, 1, 1, '09:30:00', '10:15:00'),
(32, 1, 'Thursday', 4, 3, 1, '10:15:00', '11:00:00'),
(33, 1, 'Thursday', 5, 11, 1, '11:30:00', '12:15:00'),
(34, 1, 'Thursday', 6, 2, 1, '12:15:00', '13:00:00'),
(35, 1, 'Thursday', 7, 10, 1, '13:00:00', '13:45:00'),
(36, 2, 'Sunday', 1, 15, 9, '08:00:00', '08:45:00'),
(37, 2, 'Sunday', 2, 3, 1, '08:45:00', '09:30:00'),
(38, 2, 'Sunday', 3, 2, 5, '09:30:00', '10:15:00'),
(39, 2, 'Sunday', 4, 5, 6, '10:15:00', '11:00:00'),
(40, 2, 'Sunday', 5, 7, 7, '11:30:00', '12:15:00'),
(41, 2, 'Sunday', 6, 1, 4, '12:15:00', '13:00:00'),
(42, 2, 'Sunday', 7, 16, 27, '13:00:00', '13:45:00'),
(43, 2, 'Monday', 1, 1, 4, '08:00:00', '08:45:00'),
(44, 2, 'Monday', 2, 1, 4, '08:45:00', '09:30:00'),
(45, 2, 'Monday', 3, 4, 10, '09:30:00', '10:15:00'),
(46, 2, 'Monday', 4, 6, 11, '10:15:00', '11:00:00'),
(47, 2, 'Monday', 5, 2, 5, '11:30:00', '12:15:00'),
(48, 2, 'Monday', 6, 5, 6, '12:15:00', '13:00:00'),
(49, 2, 'Monday', 7, 16, 8, '13:00:00', '13:45:00'),
(50, 2, 'Tuesday', 1, 2, 5, '08:00:00', '08:45:00'),
(51, 2, 'Tuesday', 2, 3, 1, '08:45:00', '09:30:00'),
(52, 2, 'Tuesday', 3, 1, 4, '09:30:00', '10:15:00'),
(53, 2, 'Tuesday', 4, 7, 7, '10:15:00', '11:00:00'),
(54, 2, 'Tuesday', 5, 15, 9, '11:30:00', '12:15:00'),
(55, 2, 'Tuesday', 6, 5, 6, '12:15:00', '13:00:00'),
(56, 2, 'Tuesday', 7, 4, 10, '13:00:00', '13:45:00'),
(57, 2, 'Wednesday', 1, 1, 4, '08:00:00', '08:45:00'),
(58, 2, 'Wednesday', 2, 3, 1, '08:45:00', '09:30:00'),
(59, 2, 'Wednesday', 3, 2, 5, '09:30:00', '10:15:00'),
(60, 2, 'Wednesday', 4, 6, 11, '10:15:00', '11:00:00'),
(61, 2, 'Wednesday', 5, 16, 8, '11:30:00', '12:15:00'),
(62, 2, 'Wednesday', 6, 7, 7, '12:15:00', '13:00:00'),
(63, 2, 'Wednesday', 7, 15, 9, '13:00:00', '13:45:00'),
(64, 2, 'Thursday', 1, 3, 1, '08:00:00', '08:45:00'),
(65, 2, 'Thursday', 2, 1, 4, '08:45:00', '09:30:00'),
(66, 2, 'Thursday', 3, 5, 6, '09:30:00', '10:15:00'),
(67, 2, 'Thursday', 4, 2, 5, '10:15:00', '11:00:00'),
(68, 2, 'Thursday', 5, 4, 10, '11:30:00', '12:15:00'),
(69, 2, 'Thursday', 6, 16, 8, '12:15:00', '13:00:00'),
(70, 2, 'Thursday', 7, 7, 7, '13:00:00', '13:45:00'),
(71, 14, 'Sunday', 1, 3, 1, '08:00:00', '08:45:00'),
(72, 14, 'Sunday', 2, 10, 12, '08:45:00', '09:30:00'),
(73, 14, 'Sunday', 3, 11, 13, '09:30:00', '10:15:00'),
(74, 14, 'Sunday', 4, 12, 14, '10:15:00', '11:00:00'),
(75, 14, 'Sunday', 5, 1, 4, '11:30:00', '12:15:00'),
(76, 14, 'Sunday', 6, 2, 5, '12:15:00', '13:00:00'),
(77, 14, 'Sunday', 7, 15, 9, '13:00:00', '13:45:00'),
(78, 14, 'Monday', 1, 10, 12, '08:00:00', '08:45:00'),
(79, 14, 'Monday', 2, 11, 13, '08:45:00', '09:30:00'),
(80, 14, 'Monday', 3, 7, 7, '09:30:00', '10:15:00'),
(81, 14, 'Monday', 4, 3, 1, '10:15:00', '11:00:00'),
(82, 14, 'Monday', 5, 12, 14, '11:30:00', '12:15:00'),
(83, 14, 'Monday', 6, 16, 8, '12:15:00', '13:00:00'),
(84, 14, 'Monday', 7, 1, 4, '13:00:00', '13:45:00'),
(85, 14, 'Tuesday', 1, 3, 1, '08:00:00', '08:45:00'),
(86, 14, 'Tuesday', 2, 10, 12, '08:45:00', '09:30:00'),
(87, 14, 'Tuesday', 3, 11, 13, '09:30:00', '10:15:00'),
(88, 14, 'Tuesday', 4, 12, 14, '10:15:00', '11:00:00'),
(89, 14, 'Tuesday', 5, 2, 5, '11:30:00', '12:15:00'),
(90, 14, 'Tuesday', 6, 1, 4, '12:15:00', '13:00:00'),
(91, 14, 'Tuesday', 7, 15, 9, '13:00:00', '13:45:00'),
(92, 14, 'Wednesday', 1, 11, 13, '08:00:00', '08:45:00'),
(93, 14, 'Wednesday', 2, 10, 12, '08:45:00', '09:30:00'),
(94, 14, 'Wednesday', 3, 3, 1, '09:30:00', '10:15:00'),
(95, 14, 'Wednesday', 4, 16, 8, '10:15:00', '11:00:00'),
(96, 14, 'Wednesday', 5, 1, 4, '11:30:00', '12:15:00'),
(97, 14, 'Wednesday', 6, 7, 7, '12:15:00', '13:00:00'),
(98, 14, 'Wednesday', 7, 2, 5, '13:00:00', '13:45:00'),
(99, 14, 'Thursday', 1, 2, 5, '08:00:00', '08:45:00'),
(100, 14, 'Thursday', 2, 3, 1, '08:45:00', '09:30:00'),
(101, 14, 'Thursday', 3, 10, 12, '09:30:00', '10:15:00'),
(102, 14, 'Thursday', 4, 11, 13, '10:15:00', '11:00:00'),
(103, 14, 'Thursday', 5, 1, 4, '11:30:00', '12:15:00'),
(104, 14, 'Thursday', 6, 1, 4, '12:15:00', '13:00:00'),
(105, 14, 'Thursday', 7, 15, 9, '13:00:00', '13:45:00'),
(106, 3, 'Sunday', 1, 1, 15, '08:00:00', '08:45:00'),
(107, 3, 'Sunday', 2, 3, 16, '08:45:00', '09:30:00'),
(108, 3, 'Sunday', 3, 2, 17, '09:30:00', '10:15:00'),
(109, 3, 'Sunday', 4, 5, 18, '10:15:00', '11:00:00'),
(110, 3, 'Sunday', 5, 7, 19, '11:30:00', '12:15:00'),
(111, 3, 'Sunday', 6, 16, 20, '12:15:00', '13:00:00'),
(112, 3, 'Sunday', 7, 15, 21, '13:00:00', '13:45:00'),
(113, 3, 'Monday', 1, 3, 16, '08:00:00', '08:45:00'),
(114, 3, 'Monday', 2, 1, 15, '08:45:00', '09:30:00'),
(115, 3, 'Monday', 3, 4, 22, '09:30:00', '10:15:00'),
(116, 3, 'Monday', 4, 6, 23, '10:15:00', '11:00:00'),
(117, 3, 'Monday', 5, 2, 17, '11:30:00', '12:15:00'),
(118, 3, 'Monday', 6, 5, 18, '12:15:00', '13:00:00'),
(119, 3, 'Monday', 7, 16, 20, '13:00:00', '13:45:00'),
(120, 3, 'Tuesday', 1, 2, 17, '08:00:00', '08:45:00'),
(121, 3, 'Tuesday', 2, 3, 16, '08:45:00', '09:30:00'),
(122, 3, 'Tuesday', 3, 1, 15, '09:30:00', '10:15:00'),
(123, 3, 'Tuesday', 4, 7, 19, '10:15:00', '11:00:00'),
(124, 3, 'Tuesday', 5, 15, 21, '11:30:00', '12:15:00'),
(125, 3, 'Tuesday', 6, 5, 18, '12:15:00', '13:00:00'),
(126, 3, 'Tuesday', 7, 4, 22, '13:00:00', '13:45:00'),
(127, 3, 'Wednesday', 1, 1, 15, '08:00:00', '08:45:00'),
(128, 3, 'Wednesday', 2, 3, 16, '08:45:00', '09:30:00'),
(129, 3, 'Wednesday', 3, 2, 17, '09:30:00', '10:15:00'),
(130, 3, 'Wednesday', 4, 6, 23, '10:15:00', '11:00:00'),
(131, 3, 'Wednesday', 5, 16, 20, '11:30:00', '12:15:00'),
(132, 3, 'Wednesday', 6, 7, 19, '12:15:00', '13:00:00'),
(133, 3, 'Wednesday', 7, 15, 21, '13:00:00', '13:45:00'),
(134, 3, 'Thursday', 1, 3, 16, '08:00:00', '08:45:00'),
(135, 3, 'Thursday', 2, 1, 15, '08:45:00', '09:30:00'),
(136, 3, 'Thursday', 3, 5, 18, '09:30:00', '10:15:00'),
(137, 3, 'Thursday', 4, 2, 17, '10:15:00', '11:00:00'),
(138, 3, 'Thursday', 5, 4, 22, '11:30:00', '12:15:00'),
(139, 3, 'Thursday', 6, 16, 20, '12:15:00', '13:00:00'),
(140, 3, 'Thursday', 7, 7, 19, '13:00:00', '13:45:00'),
(141, 6, 'Sunday', 1, 1, 24, '08:00:00', '08:45:00'),
(142, 6, 'Sunday', 2, 3, 25, '08:45:00', '09:30:00'),
(143, 6, 'Sunday', 3, 2, 26, '09:30:00', '10:15:00'),
(144, 6, 'Sunday', 4, 5, 27, '10:15:00', '11:00:00'),
(145, 6, 'Sunday', 5, 7, 19, '11:30:00', '12:15:00'),
(146, 6, 'Sunday', 6, 16, 20, '12:15:00', '13:00:00'),
(147, 6, 'Sunday', 7, 15, 21, '13:00:00', '13:45:00'),
(148, 6, 'Monday', 1, 3, 25, '08:00:00', '08:45:00'),
(149, 6, 'Monday', 2, 1, 24, '08:45:00', '09:30:00'),
(150, 6, 'Monday', 3, 4, 22, '09:30:00', '10:15:00'),
(151, 6, 'Monday', 4, 6, 23, '10:15:00', '11:00:00'),
(152, 6, 'Monday', 5, 2, 26, '11:30:00', '12:15:00'),
(153, 6, 'Monday', 6, 5, 27, '12:15:00', '13:00:00'),
(154, 6, 'Monday', 7, 16, 20, '13:00:00', '13:45:00'),
(155, 6, 'Tuesday', 1, 2, 26, '08:00:00', '08:45:00'),
(156, 6, 'Tuesday', 2, 3, 25, '08:45:00', '09:30:00'),
(157, 6, 'Tuesday', 3, 1, 24, '09:30:00', '10:15:00'),
(158, 6, 'Tuesday', 4, 7, 19, '10:15:00', '11:00:00'),
(159, 6, 'Tuesday', 5, 15, 21, '11:30:00', '12:15:00'),
(160, 6, 'Tuesday', 6, 5, 27, '12:15:00', '13:00:00'),
(161, 6, 'Tuesday', 7, 4, 22, '13:00:00', '13:45:00'),
(162, 6, 'Wednesday', 1, 1, 24, '08:00:00', '08:45:00'),
(163, 6, 'Wednesday', 2, 3, 25, '08:45:00', '09:30:00'),
(164, 6, 'Wednesday', 3, 2, 26, '09:30:00', '10:15:00'),
(165, 6, 'Wednesday', 4, 6, 23, '10:15:00', '11:00:00'),
(166, 6, 'Wednesday', 5, 16, 20, '11:30:00', '12:15:00'),
(167, 6, 'Wednesday', 6, 7, 19, '12:15:00', '13:00:00'),
(168, 6, 'Wednesday', 7, 15, 21, '13:00:00', '13:45:00'),
(169, 6, 'Thursday', 1, 3, 25, '08:00:00', '08:45:00'),
(170, 6, 'Thursday', 2, 1, 24, '08:45:00', '09:30:00'),
(171, 6, 'Thursday', 3, 5, 27, '09:30:00', '10:15:00'),
(172, 6, 'Thursday', 4, 2, 26, '10:15:00', '11:00:00'),
(173, 6, 'Thursday', 5, 4, 22, '11:30:00', '12:15:00'),
(174, 6, 'Thursday', 6, 16, 20, '12:15:00', '13:00:00'),
(175, 6, 'Thursday', 7, 7, 19, '13:00:00', '13:45:00'),
(176, 18, 'Sunday', 1, 1, 4, '08:00:00', '08:45:00'),
(177, 18, 'Sunday', 2, 1, 4, '08:45:00', '09:30:00'),
(178, 18, 'Sunday', 3, 11, 13, '09:30:00', '10:15:00'),
(179, 18, 'Sunday', 4, 12, 14, '10:15:00', '11:00:00'),
(180, 18, 'Sunday', 5, 3, 1, '11:30:00', '12:15:00'),
(181, 18, 'Sunday', 6, 2, 5, '12:15:00', '13:00:00'),
(182, 18, 'Sunday', 7, 15, 9, '13:00:00', '13:45:00'),
(183, 18, 'Monday', 1, 10, 12, '08:00:00', '08:45:00'),
(184, 18, 'Monday', 2, 11, 13, '08:45:00', '09:30:00'),
(185, 18, 'Monday', 3, 1, 4, '09:30:00', '10:15:00'),
(186, 18, 'Monday', 4, 3, 1, '10:15:00', '11:00:00'),
(187, 18, 'Monday', 5, 12, 14, '11:30:00', '12:15:00'),
(188, 18, 'Monday', 6, 16, 8, '12:15:00', '13:00:00'),
(189, 18, 'Monday', 7, 7, 7, '13:00:00', '13:45:00'),
(190, 18, 'Tuesday', 1, 3, 1, '08:00:00', '08:45:00'),
(191, 18, 'Tuesday', 2, 10, 12, '08:45:00', '09:30:00'),
(192, 18, 'Tuesday', 3, 11, 13, '09:30:00', '10:15:00'),
(193, 18, 'Tuesday', 4, 1, 4, '10:15:00', '11:00:00'),
(194, 18, 'Tuesday', 5, 2, 5, '11:30:00', '12:15:00'),
(195, 18, 'Tuesday', 6, 12, 14, '12:15:00', '13:00:00'),
(196, 18, 'Tuesday', 7, 15, 9, '13:00:00', '13:45:00'),
(197, 18, 'Wednesday', 1, 11, 13, '08:00:00', '08:45:00'),
(198, 18, 'Wednesday', 2, 10, 12, '08:45:00', '09:30:00'),
(199, 18, 'Wednesday', 3, 1, 4, '09:30:00', '10:15:00'),
(200, 18, 'Wednesday', 4, 1, 4, '10:15:00', '11:00:00'),
(201, 18, 'Wednesday', 5, 3, 8, '11:30:00', '12:15:00'),
(202, 18, 'Wednesday', 6, 7, 7, '12:15:00', '13:00:00'),
(203, 18, 'Wednesday', 7, 2, 5, '13:00:00', '13:45:00'),
(204, 18, 'Thursday', 1, 1, 4, '08:00:00', '08:45:00'),
(205, 18, 'Thursday', 2, 3, 1, '08:45:00', '09:30:00'),
(206, 18, 'Thursday', 3, 10, 12, '09:30:00', '10:15:00'),
(207, 18, 'Thursday', 4, 11, 13, '10:15:00', '11:00:00'),
(208, 18, 'Thursday', 5, 12, 14, '11:30:00', '12:15:00'),
(209, 18, 'Thursday', 6, 2, 5, '12:15:00', '13:00:00'),
(210, 18, 'Thursday', 7, 15, 9, '13:00:00', '13:45:00'),
(281, 31, 'Sunday', 1, 1, 1, '08:00:00', '08:45:00'),
(282, 31, 'Sunday', 2, 1, 1, '08:45:00', '09:30:00'),
(283, 31, 'Sunday', 3, 6, 17, '09:30:00', '10:15:00'),
(284, 31, 'Sunday', 4, 15, 26, '10:15:00', '11:00:00'),
(285, 31, 'Sunday', 5, 2, 8, '11:30:00', '12:15:00'),
(286, 31, 'Sunday', 6, 3, 11, '12:15:00', '13:00:00'),
(287, 31, 'Sunday', 7, 3, 11, '13:00:00', '13:45:00'),
(288, 31, 'Monday', 1, 2, 8, '08:00:00', '08:45:00'),
(289, 31, 'Monday', 2, 2, 8, '08:45:00', '09:30:00'),
(290, 31, 'Monday', 3, 12, 23, '09:30:00', '10:15:00'),
(291, 31, 'Monday', 4, 11, 22, '10:15:00', '11:00:00'),
(292, 31, 'Monday', 5, 16, 27, '11:30:00', '12:15:00'),
(293, 31, 'Monday', 6, 13, 24, '12:15:00', '13:00:00'),
(294, 31, 'Monday', 7, 9, 20, '13:00:00', '13:45:00'),
(295, 31, 'Tuesday', 1, 8, 19, '08:00:00', '08:45:00'),
(296, 31, 'Tuesday', 2, 10, 21, '08:45:00', '09:30:00'),
(297, 31, 'Tuesday', 3, 9, 20, '09:30:00', '10:15:00'),
(298, 31, 'Tuesday', 4, 12, 23, '10:15:00', '11:00:00'),
(299, 31, 'Tuesday', 5, 1, 1, '11:30:00', '12:15:00'),
(300, 31, 'Tuesday', 6, 2, 8, '12:15:00', '13:00:00'),
(301, 31, 'Tuesday', 7, 3, 11, '13:00:00', '13:45:00'),
(302, 31, 'Wednesday', 1, 1, 1, '08:00:00', '08:45:00'),
(303, 31, 'Wednesday', 2, 1, 1, '08:45:00', '09:30:00'),
(304, 31, 'Wednesday', 3, 10, 21, '09:30:00', '10:15:00'),
(305, 31, 'Wednesday', 4, 9, 20, '10:15:00', '11:00:00'),
(306, 31, 'Wednesday', 5, 15, 26, '11:30:00', '12:15:00'),
(307, 31, 'Wednesday', 6, 2, 8, '12:15:00', '13:00:00'),
(308, 31, 'Wednesday', 7, 2, 8, '13:00:00', '13:45:00'),
(309, 31, 'Thursday', 1, 3, 11, '08:00:00', '08:45:00'),
(310, 31, 'Thursday', 2, 3, 11, '08:45:00', '09:30:00'),
(311, 31, 'Thursday', 3, 8, 19, '09:30:00', '10:15:00'),
(312, 31, 'Thursday', 4, 2, 8, '10:15:00', '11:00:00'),
(313, 31, 'Thursday', 5, 1, 1, '11:30:00', '12:15:00'),
(314, 31, 'Thursday', 6, 6, 17, '12:15:00', '13:00:00'),
(315, 31, 'Thursday', 7, 16, 27, '13:00:00', '13:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `TeacherID` int(11) NOT NULL,
  `TeacherName` varchar(255) NOT NULL,
  `TeacherEmail` varchar(255) NOT NULL,
  `TeacherPass` varchar(255) NOT NULL,
  `RoleId` int(11) NOT NULL,
  `TeacherNumber` varchar(255) NOT NULL,
  `TeacherPic` longtext NOT NULL DEFAULT 'teacherpp.png',
  `Subject` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`TeacherID`, `TeacherName`, `TeacherEmail`, `TeacherPass`, `RoleId`, `TeacherNumber`, `TeacherPic`, `Subject`) VALUES
(1, 'Gannah Alaa', 'gannaalaa02@gmail.com', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01068385800', 'teachlogo.png', 1),
(4, 'Mohamed Ali', 'mohamed.ali@school.edu', '$2y$10$6OLsrJ68BYClWnZLO1SD7epUTW5vwuqm3XmawdYRBefWmtNb5TvIC', 3, '01123456789', '680dc4e833674_R.png', 1),
(5, 'Ahmed Hassan', 'ahmed.hassan@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01134567890', 'teachlogo.png', 1),
(6, 'Youssef Mahmoud', 'youssef.mahmoud@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01145678901', 'teachlogo.png', 1),
(7, 'Amira Salah', 'amira.salah@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 3, '01156789017', '683a84a711d76.jpeg', 2),
(8, 'Hana Ibrahim', 'hana.ibrahim@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01167890123', '683a86ee061f4.jpg', 2),
(9, 'Karim Adel', 'karim.adel@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01178901234', 'teachlogo.png', 2),
(10, 'Nourhan Samir', 'nourhan.samir@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 3, '01189012345', 'teachlogo.png', 3),
(11, 'Omar Tarek', 'omar.tarek@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01190123456', 'teachlogo.png', 3),
(12, 'Dalia Wael', 'dalia.wael@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01101234567', 'teachlogo.png', 3),
(13, 'Tamer Hosny', 'tamer.hosny@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 3, '01112345098', 'teachlogo.png', 4),
(14, 'Samia Farouk', 'samia.farouk@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01123450987', 'teachlogo.png', 4),
(15, 'Hassan Mohamed', 'hassan.mohamed@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 3, '01134509876', 'teachlogo.png', 5),
(16, 'Fatma Ali', 'fatma.ali@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01145098765', 'teachlogo.png', 5),
(17, 'Sherif Gamal', 'sherif.gamal@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01150987654', 'teachlogo.png', 6),
(18, 'Mona Hisham', 'mona.hisham@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01109876543', 'teachlogo.png', 7),
(19, 'Khaled Ashraf', 'khaled.ashraf@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01198765432', 'teachlogo.png', 8),
(20, 'Rania Magdy', 'rania.magdy@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01187654321', 'teachlogo.png', 9),
(21, 'Waleed Samy', 'waleed.samy@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01176543210', 'teachlogo.png', 10),
(22, 'Sara Ahmed', 'sara.ahmed@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01165432109', 'teachlogo.png', 11),
(23, 'Ali Mostafa', 'ali.mostafa@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01154321098', 'teachlogo.png', 12),
(24, 'Laila Karim', 'laila.karim@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01143210987', 'teachlogo.png', 13),
(25, 'Amr Salah', 'amr.salah@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01132109876', 'teachlogo.png', 14),
(26, 'Heba Nasser', 'heba.nasser@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01121098765', 'teachlogo.png', 15),
(27, 'Karim Fawzy', 'karim.fawzy@school.edu', '$2y$10$YZeifP4QI5ULEYGof605DezPworJU0rgdI1mJouIBtsku8QeISxo6', 4, '01110987654', 'teachlogo.png', 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`),
  ADD KEY `RoleId` (`RoleId`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`BusID`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`ChatID`),
  ADD KEY `TeacherID` (`TeacherID`,`ParentID`),
  ADD KEY `ParentID` (`ParentID`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`ContactID`);

--
-- Indexes for table `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`StudentID`,`ParentID`),
  ADD KEY `StudentID` (`StudentID`,`ParentID`),
  ADD KEY `ParentID` (`ParentID`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`GradeID`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`MarkID`),
  ADD KEY `SubjectID` (`SubjectID`,`StudentID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `GradeID` (`GradeID`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`MaterialID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `ChatID` (`ChatID`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`NewsID`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`ParentID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `grade` (`grade`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `Grade` (`Grade`),
  ADD KEY `Class` (`Class`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectID`);

--
-- Indexes for table `submitions`
--
ALTER TABLE `submitions`
  ADD PRIMARY KEY (`SubmitID`),
  ADD KEY `MaterialID` (`MaterialID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `s_details`
--
ALTER TABLE `s_details`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `ScheduleID` (`ScheduleID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `TeacherID` (`TeacherID`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`TeacherID`),
  ADD KEY `RoleId` (`RoleId`),
  ADD KEY `Subject` (`Subject`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `BusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `ChatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `GradeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `MarkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `MaterialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `NewsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `ParentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `ScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `submitions`
--
ALTER TABLE `submitions`
  MODIFY `SubmitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `s_details`
--
ALTER TABLE `s_details`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`RoleId`) REFERENCES `roles` (`RoleID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`ParentID`) REFERENCES `parents` (`ParentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `family`
--
ALTER TABLE `family`
  ADD CONSTRAINT `family_ibfk_1` FOREIGN KEY (`ParentID`) REFERENCES `parents` (`ParentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `family_ibfk_2` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marks_ibfk_3` FOREIGN KEY (`GradeID`) REFERENCES `grades` (`GradeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `material_ibfk_2` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`ChatID`) REFERENCES `chat` (`ChatID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`grade`) REFERENCES `grades` (`GradeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`Class`) REFERENCES `class` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`Grade`) REFERENCES `grades` (`GradeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submitions`
--
ALTER TABLE `submitions`
  ADD CONSTRAINT `submitions_ibfk_1` FOREIGN KEY (`MaterialID`) REFERENCES `material` (`MaterialID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submitions_ibfk_2` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `s_details`
--
ALTER TABLE `s_details`
  ADD CONSTRAINT `s_details_ibfk_1` FOREIGN KEY (`ScheduleID`) REFERENCES `schedule` (`ScheduleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_details_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_details_ibfk_3` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`RoleId`) REFERENCES `roles` (`RoleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`Subject`) REFERENCES `subjects` (`SubjectID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
