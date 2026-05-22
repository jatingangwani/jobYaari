-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2026 at 09:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobYaari`
--
-- CREATE DATABASE IF NOT EXISTS `jobYaari` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE `jobYaari`;

-- --------------------------------------------------------

--
-- Table structure for table `blogcategories`
--

CREATE TABLE `blogcategories` (
  `blogcategoryid` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogcategories`
--

INSERT INTO `blogcategories` (`blogcategoryid`, `category`, `slug`, `created_at`) VALUES
(3, 'Jobs', 'jobs', '2026-05-22 01:00:59'),
(4, 'Result', 'result', '2026-05-22 01:00:59'),
(5, 'Information', 'information', '2026-05-22 01:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `blogcategorylink`
--

CREATE TABLE `blogcategorylink` (
  `blogcategorylinkid` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `blogcategoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogcategorylink`
--

INSERT INTO `blogcategorylink` (`blogcategorylinkid`, `blogid`, `blogcategoryid`) VALUES
(25, 2, 3),
(24, 3, 4),
(23, 4, 5),
(22, 5, 5),
(21, 6, 3),
(19, 7, 3),
(18, 8, 3),
(17, 9, 3),
(16, 10, 3),
(15, 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blogid` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blogid`, `title`, `slug`, `short_description`, `content`, `featured_image`, `meta_title`, `meta_description`, `status`, `views`, `created_at`, `updated_at`) VALUES
(2, 'UPSSSC Lekhpal Recruitment 2026: Apply Online for 7994 Posts, Eligibility & Last Date', 'upsssc-lekhpal-recruitment-2026-apply-online-for-7994-posts-eligibility-and-last-date', 'UPSSSC Lekhpal Recruitment 2026 notification released for 7994 posts. Check apply online link, eligibility, age limit, application fee, important dates, and selection process. Last date: 28 January 2026.Important Highlig', '<p>UPSSSC Lekhpal Recruitment 2026 notification released for 7994 posts. Check apply online link, eligibility, age limit, application fee, important dates, and selection process. Last date: 28 January 2026.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'aa4022597e74b341cac1ab9942a6e3a3.png', 'UPSSSC Lekhpal Recruitment 2026: Apply Online for 7994 Posts, Eligibility & Last Date', 'UPSSSC Lekhpal Recruitment 2026 notification released for 7994 posts. Check apply online link, eligibility, age limit, application fee, important dates, and selection process. Last date: 28 January 2026.Important Highlig', 'published', 6, '2025-12-30 10:00:00', '2026-05-22 13:10:01'),
(3, 'WBP Constable Result 2025 Declared - Check Merit List, PET/PMT Date', 'wbp-constable-result-2025-declared-check-merit-list-pet-pmt-date', 'WBP Constable Result 2025 has been declared. Check merit list PDF, shortlisted candidates, PET/PMT schedule, and next steps at the official recruitment portal.Important HighlightsThis JobYaari update is prepared for cand', '<p>WBP Constable Result 2025 has been declared. Check merit list PDF, shortlisted candidates, PET/PMT schedule, and next steps at the official recruitment portal.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'fe772ab7fad8d0aaf9b43a899d586134.png', 'WBP Constable Result 2025 Declared - Check Merit List, PET/PMT Date', 'WBP Constable Result 2025 has been declared. Check merit list PDF, shortlisted candidates, PET/PMT schedule, and next steps at the official recruitment portal.Important HighlightsThis JobYaari update is prepared for cand', 'published', 2, '2025-12-30 09:30:00', '2026-05-22 13:09:55'),
(4, 'NBEMS Exam Calendar 2026 Released: Check Exam Dates & Schedule', 'nbems-exam-calendar-2026-released-check-exam-dates-and-schedule', 'The National Board of Examinations in Medical Sciences has released the NBEMS Exam Calendar 2026. Check exam dates, schedule, and download details from the official website.Important HighlightsThis JobYaari update is pre', '<p>The National Board of Examinations in Medical Sciences has released the NBEMS Exam Calendar 2026. Check exam dates, schedule, and download details from the official website.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'f50d01dddfb5cf8274c956f876def151.png', 'NBEMS Exam Calendar 2026 Released: Check Exam Dates & Schedule', 'The National Board of Examinations in Medical Sciences has released the NBEMS Exam Calendar 2026. Check exam dates, schedule, and download details from the official website.Important HighlightsThis JobYaari update is pre', 'published', 0, '2025-12-30 09:00:00', '2026-05-22 13:09:20'),
(5, 'RRB Group D Recruitment Explained: Benefits, Job Security & Future Scope', 'rrb-group-d-recruitment-explained-benefits-job-security-and-future-scope', 'RRB Group D Recruitment is a strong opportunity with thousands of vacancies. Know why you should apply, job benefits, importance, and future career growth after clearing the exam.Important HighlightsThis JobYaari update ', '<p>RRB Group D Recruitment is a strong opportunity with thousands of vacancies. Know why you should apply, job benefits, importance, and future career growth after clearing the exam.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'e340fff0e0299bdd33bd83479bd9bc1d.png', 'RRB Group D Recruitment Explained: Benefits, Job Security & Future Scope', 'RRB Group D Recruitment is a strong opportunity with thousands of vacancies. Know why you should apply, job benefits, importance, and future career growth after clearing the exam.Important HighlightsThis JobYaari update ', 'published', 0, '2025-12-29 12:00:00', '2026-05-22 13:09:14'),
(6, 'RRB Group D Level-1 Recruitment 2026 - Apply Online for 22000+ Posts', 'rrb-group-d-level-1-recruitment-2026-apply-online-for-22000-posts', 'Railway Recruitment Board has released RRB Group D Level-1 Recruitment 2026 for 22000+ posts. Check eligibility, fee, last date, and apply online process.Important HighlightsThis JobYaari update is prepared for candidate', '<p>Railway Recruitment Board has released RRB Group D Level-1 Recruitment 2026 for 22000+ posts. Check eligibility, fee, last date, and apply online process.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', '2f2dac30b258b825f2a660e8f8705164.png', 'RRB Group D Level-1 Recruitment 2026 - Apply Online for 22000+ Posts', 'Railway Recruitment Board has released RRB Group D Level-1 Recruitment 2026 for 22000+ posts. Check eligibility, fee, last date, and apply online process.Important HighlightsThis JobYaari update is prepared for candidate', 'published', 0, '2025-12-29 11:30:00', '2026-05-22 13:09:08'),
(7, 'Gujarat Driver Recruitment 2026 | 48 Posts | Apply Offline', 'gujarat-driver-recruitment-2026-48-posts-apply-offline', 'Gujarat Postal Circle has released Driver Recruitment 2026 for 48 posts. Offline application starts from 15 December 2025 and last date is 19 January 2026.Important HighlightsThis JobYaari update is prepared for candidat', '<p>Gujarat Postal Circle has released Driver Recruitment 2026 for 48 posts. Offline application starts from 15 December 2025 and last date is 19 January 2026.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'c1e9247bad0d6dfedaf830007be47492.png', 'Gujarat Driver Recruitment 2026 | 48 Posts | Apply Offline', 'Gujarat Postal Circle has released Driver Recruitment 2026 for 48 posts. Offline application starts from 15 December 2025 and last date is 19 January 2026.Important HighlightsThis JobYaari update is prepared for candidat', 'published', 0, '2025-12-29 10:30:00', '2026-05-22 13:08:52'),
(8, 'IOCL Non-Executive Personnel Recruitment 2025: Apply Online for 394 Posts', 'iocl-non-executive-personnel-recruitment-2025-apply-online-for-394-posts', 'IOCL Non-Executive Personnel Recruitment 2025 notification is out for 394 posts. Check eligibility, application fee, important dates, and apply online from 20 December 2025 to 09 January 2026.Important HighlightsThis Job', '<p>IOCL Non-Executive Personnel Recruitment 2025 notification is out for 394 posts. Check eligibility, application fee, important dates, and apply online from 20 December 2025 to 09 January 2026.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', '4a8c7c5f862e0e5a7231427fc5d2baaf.png', 'IOCL Non-Executive Personnel Recruitment 2025: Apply Online for 394 Posts', 'IOCL Non-Executive Personnel Recruitment 2025 notification is out for 394 posts. Check eligibility, application fee, important dates, and apply online from 20 December 2025 to 09 January 2026.Important HighlightsThis Job', 'published', 0, '2025-12-28 10:00:00', '2026-05-22 13:08:45'),
(9, 'United India Insurance Apprentice Recruitment 2026: Apply Online for 153 Graduate Apprentice Posts', 'united-india-insurance-apprentice-recruitment-2026-apply-online-for-153-graduate-apprentice-posts', 'United India Insurance UIIC Apprentice Recruitment 2026 notification released for 153 Graduate Apprentice posts. Check eligibility, application fee, last date, and apply online.Important HighlightsThis JobYaari update is', '<p>United India Insurance UIIC Apprentice Recruitment 2026 notification released for 153 Graduate Apprentice posts. Check eligibility, application fee, last date, and apply online.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', '1435fe94daf86f79e845fdadbe85e921.png', 'United India Insurance Apprentice Recruitment 2026: Apply Online for 153 Graduate Apprentice Posts', 'United India Insurance UIIC Apprentice Recruitment 2026 notification released for 153 Graduate Apprentice posts. Check eligibility, application fee, last date, and apply online.Important HighlightsThis JobYaari update is', 'published', 0, '2025-12-20 11:00:00', '2026-05-22 13:08:38'),
(10, 'Engineers India Limited Recruitment 2025: Apply Online for 22 Manager & AGM Posts', 'engineers-india-limited-recruitment-2025-apply-online-for-22-manager-and-agm-posts', 'Engineers India Limited Recruitment 2025 notification released for 22 Assistant General Manager, Senior Manager, Manager and Deputy Manager posts. Check eligibility, fee, and last date.Important HighlightsThis JobYaari u', '<p>Engineers India Limited Recruitment 2025 notification released for 22 Assistant General Manager, Senior Manager, Manager and Deputy Manager posts. Check eligibility, fee, and last date.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'a69e7fc8d6db060118c5bc65ae1c038f.png', 'Engineers India Limited Recruitment 2025: Apply Online for 22 Manager & AGM Posts', 'Engineers India Limited Recruitment 2025 notification released for 22 Assistant General Manager, Senior Manager, Manager and Deputy Manager posts. Check eligibility, fee, and last date.Important HighlightsThis JobYaari u', 'published', 0, '2025-12-20 10:30:00', '2026-05-22 13:08:21'),
(11, 'RPSC Protection Officer Recruitment 2026: Apply Online for 12 Posts, Eligibility, Dates', 'rpsc-protection-officer-recruitment-2026-apply-online-for-12-posts-eligibility-dates', 'RPSC Protection Officer Recruitment 2026 notification released for 12 posts. Check eligibility, application fee, age limit, important dates, and apply online dates.Important HighlightsThis JobYaari update is prepared for', '<p>RPSC Protection Officer Recruitment 2026 notification released for 12 posts. Check eligibility, application fee, age limit, important dates, and apply online dates.</p><h2>Important Highlights</h2><p>This JobYaari update is prepared for candidates who want quick, clear information before checking the official notification. Review the eligibility, important dates, fee details, selection process, and application steps carefully before applying.</p><h2>What Candidates Should Check</h2><ul><li>Confirm the official application dates and deadline.</li><li>Read the qualification, age limit, and category relaxation details.</li><li>Keep scanned documents, photograph, signature, and payment details ready where required.</li><li>Save the final submitted form or acknowledgement for future reference.</li></ul><p>For the most accurate information, always verify the details on the official department or recruitment board website before submitting your application.</p>', 'e165d46acca4e80e0695fb4c34869b8e.png', 'RPSC Protection Officer Recruitment 2026: Apply Online for 12 Posts, Eligibility, Dates', 'RPSC Protection Officer Recruitment 2026 notification released for 12 posts. Check eligibility, application fee, age limit, important dates, and apply online dates.Important HighlightsThis JobYaari update is prepared for', 'published', 0, '2025-12-19 10:00:00', '2026-05-22 13:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `blogtaglink`
--

CREATE TABLE `blogtaglink` (
  `blogtaglinkid` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `blogtagid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogtaglink`
--

INSERT INTO `blogtaglink` (`blogtaglinkid`, `blogid`, `blogtagid`) VALUES
(69, 2, 6),
(71, 2, 7),
(70, 2, 8),
(68, 3, 9),
(66, 3, 10),
(67, 3, 11),
(65, 4, 12),
(63, 4, 13),
(64, 4, 14),
(62, 5, 15),
(61, 5, 16),
(60, 5, 17),
(59, 6, 18),
(57, 6, 19),
(58, 6, 20),
(52, 7, 21),
(51, 7, 22),
(53, 7, 23),
(49, 8, 24),
(50, 8, 25),
(48, 8, 26),
(47, 9, 27),
(45, 9, 28),
(46, 9, 29),
(43, 10, 30),
(44, 10, 31),
(42, 10, 32),
(41, 11, 33),
(39, 11, 34),
(40, 11, 35);

-- --------------------------------------------------------

--
-- Table structure for table `blogtags`
--

CREATE TABLE `blogtags` (
  `blogtagid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogtags`
--

INSERT INTO `blogtags` (`blogtagid`, `tag`, `slug`, `created_at`) VALUES
(6, 'Lekhpal', 'lekhpal', '2026-05-22 01:00:59'),
(7, 'UPSSSC', 'upsssc', '2026-05-22 01:00:59'),
(8, 'Recruitment', 'recruitment', '2026-05-22 01:00:59'),
(9, 'WBP Constable', 'wbp-constable', '2026-05-22 01:00:59'),
(10, 'Merit List', 'merit-list', '2026-05-22 01:00:59'),
(11, 'PET PMT', 'pet-pmt', '2026-05-22 01:00:59'),
(12, 'NBEMS', 'nbems', '2026-05-22 01:00:59'),
(13, 'Exam Calendar', 'exam-calendar', '2026-05-22 01:00:59'),
(14, 'Medical Exams', 'medical-exams', '2026-05-22 01:00:59'),
(15, 'RRB Group D', 'rrb-group-d', '2026-05-22 01:00:59'),
(16, 'Railway Jobs', 'railway-jobs', '2026-05-22 01:00:59'),
(17, 'Career Scope', 'career-scope', '2026-05-22 01:00:59'),
(18, 'RRB', 'rrb', '2026-05-22 01:00:59'),
(19, 'Group D', 'group-d', '2026-05-22 01:00:59'),
(20, 'Level 1', 'level-1', '2026-05-22 01:00:59'),
(21, 'Gujarat Postal Circle', 'gujarat-postal-circle', '2026-05-22 01:00:59'),
(22, 'Driver', 'driver', '2026-05-22 01:00:59'),
(23, 'Offline Form', 'offline-form', '2026-05-22 01:00:59'),
(24, 'IOCL', 'iocl', '2026-05-22 01:00:59'),
(25, 'Non Executive', 'non-executive', '2026-05-22 01:00:59'),
(26, 'Application Dates', 'application-dates', '2026-05-22 01:00:59'),
(27, 'UIIC', 'uiic', '2026-05-22 01:00:59'),
(28, 'Apprentice', 'apprentice', '2026-05-22 01:00:59'),
(29, 'Graduate Apprentice', 'graduate-apprentice', '2026-05-22 01:00:59'),
(30, 'Engineers India Limited', 'engineers-india-limited', '2026-05-22 01:00:59'),
(31, 'Manager', 'manager', '2026-05-22 01:00:59'),
(32, 'AGM', 'agm', '2026-05-22 01:00:59'),
(33, 'RPSC', 'rpsc', '2026-05-22 01:00:59'),
(34, 'Protection Officer', 'protection-officer', '2026-05-22 01:00:59'),
(35, 'Rajasthan Jobs', 'rajasthan-jobs', '2026-05-22 01:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `emplogin`
--

CREATE TABLE `emplogin` (
  `eid` int(15) NOT NULL,
  `mobileno` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `freeze` int(10) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `dp` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emplogin`
--

INSERT INTO `emplogin` (`eid`, `mobileno`, `password`, `freeze`, `firstname`, `lastname`, `role`, `email`, `dp`) VALUES
(1, '1234567890', 'admin', 0, 'jatin', 'gangwani', 'Admin', 'jatingangwani005@gmail.com', '60f56262f0b7609ee936bb9e626614c3md9a9046d60f3ec4358ba9e9594d5f1a71.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogcategories`
--
ALTER TABLE `blogcategories`
  ADD PRIMARY KEY (`blogcategoryid`),
  ADD UNIQUE KEY `category_slug` (`slug`);

--
-- Indexes for table `blogcategorylink`
--
ALTER TABLE `blogcategorylink`
  ADD PRIMARY KEY (`blogcategorylinkid`),
  ADD UNIQUE KEY `blog_category_unique` (`blogid`,`blogcategoryid`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blogid`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blogtaglink`
--
ALTER TABLE `blogtaglink`
  ADD PRIMARY KEY (`blogtaglinkid`),
  ADD UNIQUE KEY `blog_tag_unique` (`blogid`,`blogtagid`);

--
-- Indexes for table `blogtags`
--
ALTER TABLE `blogtags`
  ADD PRIMARY KEY (`blogtagid`),
  ADD UNIQUE KEY `tag_slug` (`slug`);

--
-- Indexes for table `emplogin`
--
ALTER TABLE `emplogin`
  ADD PRIMARY KEY (`eid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogcategories`
--
ALTER TABLE `blogcategories`
  MODIFY `blogcategoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blogcategorylink`
--
ALTER TABLE `blogcategorylink`
  MODIFY `blogcategorylinkid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blogid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `blogtaglink`
--
ALTER TABLE `blogtaglink`
  MODIFY `blogtaglinkid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `blogtags`
--
ALTER TABLE `blogtags`
  MODIFY `blogtagid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `emplogin`
--
ALTER TABLE `emplogin`
  MODIFY `eid` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
