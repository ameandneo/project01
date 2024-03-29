-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-03-10 16:28:18
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `whydb`
--

-- --------------------------------------------------------

--
-- 資料表結構 `course`
--

CREATE TABLE `course` (
  `courseID` int(11) NOT NULL,
  `title` text NOT NULL,
  `intro` text NOT NULL,
  `syllabus` text NOT NULL,
  `teacherSN` int(11) NOT NULL,
  `courseImg` text DEFAULT NULL,
  `approverID` int(11) DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否上架',
  `price` int(11) NOT NULL,
  `whenApply` date NOT NULL COMMENT '送出申請時間',
  `whenApproved` int(11) DEFAULT NULL COMMENT '審核通過時間',
  `courseClassSN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `course`
--

INSERT INTO `course` (`courseID`, `title`, `intro`, `syllabus`, `teacherSN`, `courseImg`, `approverID`, `available`, `price`, `whenApply`, `whenApproved`, `courseClassSN`) VALUES
(1, '未核准的課程', '1234', 'syllabus', 1, '', NULL, 0, 3000, '2024-03-01', NULL, 7),
(2, '已上架課程2', '', 'syllabus', 1, '', 2, 1, 0, '2024-03-01', NULL, 6),
(3, '核准未上架的課程', 'intro', 'intro', 1, NULL, 2, 0, 23333, '2024-03-01', NULL, 9),
(15, '貓咪飼養1', '貓咪飼養', '貓咪飼養', 5, 'dc6927e8c6c3c94fdf7805512830ecfb90e088d2.jpg', 2, 0, 2000, '2024-03-03', 2147483647, 8),
(21, '', '', '', 1, '', 2, 0, 0, '2024-03-10', 2147483647, 5);

-- --------------------------------------------------------

--
-- 資料表結構 `courseclass`
--

CREATE TABLE `courseclass` (
  `courseClassSN` int(11) NOT NULL,
  `className` text NOT NULL,
  `whenAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `whenEditted` timestamp NULL DEFAULT NULL,
  `addedBy` int(11) NOT NULL,
  `edittedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `courseclass`
--

INSERT INTO `courseclass` (`courseClassSN`, `className`, `whenAdded`, `whenEditted`, `addedBy`, `edittedBy`) VALUES
(5, '物理學', '2024-03-10 04:27:22', NULL, 2, NULL),
(6, '歷史', '2024-03-10 04:30:24', NULL, 2, NULL),
(7, '化學', '2024-03-10 04:27:33', NULL, 2, NULL),
(8, '生物學', '2024-03-10 04:27:46', NULL, 2, NULL),
(9, '地球科學', '2024-03-10 04:27:46', NULL, 2, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `employee`
--

CREATE TABLE `employee` (
  `employeeID` int(11) NOT NULL,
  `staffName` text NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `employee`
--

INSERT INTO `employee` (`employeeID`, `staffName`, `addtime`) VALUES
(2, 'Elena', '2024-03-01 07:05:40');

-- --------------------------------------------------------

--
-- 資料表結構 `payment`
--

CREATE TABLE `payment` (
  `paymentSN` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `paytime` date NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `promotion`
--

CREATE TABLE `promotion` (
  `promotionSN` int(11) NOT NULL,
  `promotionName` text NOT NULL,
  `courseID` int(11) NOT NULL,
  `whenStarted` date NOT NULL,
  `whenEnded` date NOT NULL,
  `limitAmount` int(11) NOT NULL,
  `percentage` int(11) NOT NULL,
  `promoterStaffID` int(11) DEFAULT NULL,
  `promoterTeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `promotion`
--

INSERT INTO `promotion` (`promotionSN`, `promotionName`, `courseID`, `whenStarted`, `whenEnded`, `limitAmount`, `percentage`, `promoterStaffID`, `promoterTeacherID`) VALUES
(1, '優惠1', 1, '2024-03-01', '2024-03-03', 10, 50, NULL, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `isTeacher` tinyint(1) NOT NULL DEFAULT 0,
  `userName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`userID`, `isTeacher`, `userName`) VALUES
(1, 1, '劉德華'),
(2, 0, '王家衛衛'),
(3, 0, '王家衛'),
(4, 0, '王麗美'),
(5, 1, '李嘉欣');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseID`),
  ADD KEY `approverID` (`approverID`),
  ADD KEY `teacherSN` (`teacherSN`),
  ADD KEY `courseClassSN` (`courseClassSN`);

--
-- 資料表索引 `courseclass`
--
ALTER TABLE `courseclass`
  ADD PRIMARY KEY (`courseClassSN`),
  ADD KEY `addedBy` (`addedBy`),
  ADD KEY `deletedBy` (`edittedBy`);

--
-- 資料表索引 `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employeeID`);

--
-- 資料表索引 `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentSN`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `courseID` (`courseID`);

--
-- 資料表索引 `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotionSN`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `promoterSN` (`promoterTeacherID`),
  ADD KEY `promoterStaffID` (`promoterStaffID`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `course`
--
ALTER TABLE `course`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `courseclass`
--
ALTER TABLE `courseclass`
  MODIFY `courseClassSN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `employee`
--
ALTER TABLE `employee`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentSN` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotionSN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`approverID`) REFERENCES `employee` (`employeeID`),
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`teacherSN`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `course_ibfk_3` FOREIGN KEY (`courseClassSN`) REFERENCES `courseclass` (`courseClassSN`);

--
-- 資料表的限制式 `courseclass`
--
ALTER TABLE `courseclass`
  ADD CONSTRAINT `courseclass_ibfk_1` FOREIGN KEY (`addedBy`) REFERENCES `employee` (`employeeID`),
  ADD CONSTRAINT `courseclass_ibfk_2` FOREIGN KEY (`edittedBy`) REFERENCES `employee` (`employeeID`);

--
-- 資料表的限制式 `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`);

--
-- 資料表的限制式 `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`),
  ADD CONSTRAINT `promotion_ibfk_2` FOREIGN KEY (`promoterTeacherID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `promotion_ibfk_3` FOREIGN KEY (`promoterStaffID`) REFERENCES `employee` (`employeeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
