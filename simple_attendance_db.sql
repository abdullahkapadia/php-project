
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `attendance_tbl` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_date` date NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1 = Present, 2 = Late, 3 = Absent, 4 = Holiday',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`id`, `student_id`, `class_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2023-11-30', 2, '2023-11-30 08:52:11', '2023-11-30 09:07:40'),
(2, 3, '2023-11-30', 1, '2023-11-30 08:52:11', '2023-11-30 09:06:53'),

-- --------------------------------------------------------

--
-- Table structure for table `class_tbl`
--

CREATE TABLE `class_tbl` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_tbl`
--

INSERT INTO `class_tbl` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Grade 8-1 - English', '2023-11-16 11:37:26', '2023-11-16 11:52:34'),
(2, 'Grade 8-2 - English', '2023-11-16 11:52:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students_tbl`
--

CREATE TABLE `students_tbl` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_tbl`
--

INSERT INTO `students_tbl` (`id`, `class_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'John Smith', '2023-11-16 13:18:15', '2023-11-16 13:18:27'),
(3, 1, 'John Doe', '2023-11-16 13:18:49', NULL),
(4, 1, 'Claire Blake', '2023-11-16 13:18:56', NULL),
(5, 1, 'Mark Cooper', '2023-11-16 13:19:18', NULL),
(6, 1, 'Samantha Lou', '2023-11-16 13:19:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id_fk` (`student_id`);

--
-- Indexes for table `class_tbl`
--
ALTER TABLE `class_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_tbl`
--
ALTER TABLE `students_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id_fk` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `class_tbl`
--
ALTER TABLE `class_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students_tbl`
--
ALTER TABLE `students_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD CONSTRAINT `student_id_fk` FOREIGN KEY (`student_id`) REFERENCES `students_tbl` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `students_tbl`
--
ALTER TABLE `students_tbl`
  ADD CONSTRAINT `class_id_fk` FOREIGN KEY (`class_id`) REFERENCES `class_tbl` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;
