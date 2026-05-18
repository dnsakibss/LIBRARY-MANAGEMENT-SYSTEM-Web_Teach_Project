-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 11:47 AM
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
-- Database: `library_ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) NOT NULL,
  `isbn` varchar(25) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `publisher` varchar(150) DEFAULT NULL,
  `published_year` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `genre_id`, `publisher`, `published_year`, `description`, `cover_image_path`, `created_at`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 1, 'Scribner', '1925', 'A classic American novel.', 'uploads/covers/cover_1778705271_519.jpg', '2026-05-13 10:47:23'),
(3, 'Sapiens', 'Yuval Noah Harari', '9780062316097', 3, 'Harper Collins', '2011', 'History of humankind.', 'uploads/covers/cover_1778705042_157.jpg', '2026-05-13 10:47:23'),
(4, 'Clean Code', 'Robert C. Martin', '9780132350884', 4, 'Prentice Hall', '2008', 'Writing clean software.', 'uploads/covers/cover_1778705081_352.jpg', '2026-05-13 10:47:23'),
(5, '1984', 'George Orwell', '9780451524935', 1, 'Signet Classic', '1949', 'A dystopian masterpiece.', 'uploads/covers/cover_1778705209_329.jpg', '2026-05-13 10:47:23'),
(6, 'Atomic Habits', 'James Clear', '9780735211292', 9, 'Avery', '2018', 'Build good habits.', 'uploads/covers/cover_1778705150_488.jpg', '2026-05-13 10:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `book_reviews`
--

CREATE TABLE `book_reviews` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_reviews`
--

INSERT INTO `book_reviews` (`id`, `book_id`, `member_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 3, 4, 4, '', '2026-05-13 20:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_records`
--

CREATE TABLE `borrow_records` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `librarian_id` int(11) DEFAULT NULL,
  `status` enum('pending','active','returned','rejected') NOT NULL DEFAULT 'pending',
  `borrow_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `renewals_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrow_records`
--

INSERT INTO `borrow_records` (`id`, `member_id`, `book_id`, `branch_id`, `librarian_id`, `status`, `borrow_date`, `due_date`, `return_date`, `renewals_count`, `created_at`) VALUES
(3, 4, 5, 1, 3, 'active', '2026-05-13', '2026-05-27', NULL, 0, '2026-05-13 11:04:38'),
(5, 4, 4, 1, 3, 'active', '2026-05-13', '2026-05-27', NULL, 0, '2026-05-13 11:17:31'),
(6, 4, 1, 1, 3, 'active', '2026-05-13', '2026-05-27', NULL, 0, '2026-05-13 11:28:10'),
(8, 6, 5, 1, 3, 'active', '2026-05-13', '2026-05-27', NULL, 0, '2026-05-13 20:39:24'),
(9, 4, 3, 1, 3, 'rejected', NULL, NULL, NULL, 0, '2026-05-13 20:40:20'),
(10, 6, 1, 1, 3, 'active', '2026-05-14', '2026-05-28', NULL, 0, '2026-05-14 20:35:37'),
(11, 4, 6, 1, 3, 'active', '2026-05-14', '2026-05-28', NULL, 0, '2026-05-14 20:36:28'),
(12, 6, 4, 1, 3, 'active', '2026-05-15', '2026-05-29', NULL, 0, '2026-05-15 09:17:01'),
(13, 7, 5, 2, NULL, 'pending', NULL, NULL, NULL, 0, '2026-05-15 09:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `city`, `phone`, `manager_id`, `is_active`, `created_at`) VALUES
(1, 'Central Branch', '12 Library Road', 'Dhaka', '01700000001', 2, 1, '2026-05-13 10:47:23'),
(2, 'North Branch', '45 University Ave', 'Chittagong', '01700000002', NULL, 1, '2026-05-13 10:47:23'),
(3, 'South Branch', '78 College Street', 'Sylhet', '01700000003', NULL, 1, '2026-05-13 10:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `branch_inventory`
--

CREATE TABLE `branch_inventory` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 0,
  `available_copies` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_inventory`
--

INSERT INTO `branch_inventory` (`id`, `book_id`, `branch_id`, `total_copies`, `available_copies`) VALUES
(1, 1, 1, 3, 1),
(3, 3, 1, 4, 4),
(4, 4, 1, 2, 0),
(5, 5, 1, 5, 2),
(6, 6, 1, 3, 2),
(7, 1, 2, 2, 2),
(8, 3, 2, 3, 3),
(9, 5, 2, 2, 2),
(11, 4, 3, 2, 2),
(12, 6, 3, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `branch_policies`
--

CREATE TABLE `branch_policies` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `max_borrow_days` int(11) NOT NULL DEFAULT 14,
  `max_books_per_member` int(11) NOT NULL DEFAULT 5,
  `fine_rate_per_day` decimal(6,2) NOT NULL DEFAULT 5.00,
  `max_renewals` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_policies`
--

INSERT INTO `branch_policies` (`id`, `branch_id`, `max_borrow_days`, `max_books_per_member`, `fine_rate_per_day`, `max_renewals`) VALUES
(1, 1, 14, 5, 5.00, 2),
(2, 2, 10, 4, 7.00, 1),
(3, 3, 21, 6, 3.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `borrow_record_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `reason` varchar(255) NOT NULL DEFAULT 'Overdue',
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(8, 'Biography'),
(1, 'Fiction'),
(3, 'History'),
(6, 'Literature'),
(5, 'Mathematics'),
(7, 'Philosophy'),
(2, 'Science'),
(9, 'Self-Help'),
(4, 'Technology');

-- --------------------------------------------------------

--
-- Table structure for table `inter_branch_requests`
--

CREATE TABLE `inter_branch_requests` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reading_lists`
--

CREATE TABLE `reading_lists` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `reserved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('waiting','fulfilled','cancelled') NOT NULL DEFAULT 'waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `k` varchar(100) NOT NULL,
  `v` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`k`, `v`) VALUES
('allow_self_register', '1'),
('default_fine_rate', '5.00'),
('default_max_books', '5'),
('default_max_days', '14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `role` enum('member','librarian','branch_manager','admin') NOT NULL DEFAULT 'member',
  `profile_pic` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `branch_id`, `is_active`, `created_at`) VALUES
(1, 'System Admin', 'admin@library.com', '$2b$10$7zhF27n5yDXn7dL.O.oOa.1STsmo./M1u3lz2/OXGcUloGK8G3T9a', NULL, 'admin', NULL, NULL, 1, '2026-05-13 10:47:23'),
(2, 'Rahim Manager', 'manager@library.com', '$2b$10$agfAlUyHlVZyKobR9xDyiO5tY8yRrcH/Qrth2lo/zqp3rD1oU7kXO', '01711000010', 'branch_manager', NULL, 1, 1, '2026-05-13 10:47:23'),
(3, 'Karim Librarian', 'librarian@library.com', '$2b$10$0EU0SzsiBJ5Np.N/J/V3UuO6LVMHpzmwqi4jm9o.GJ51t60cXdIlq', '01711000020', 'librarian', NULL, 1, 1, '2026-05-13 10:47:23'),
(4, 'Alice Member', 'member@library.com', '$2b$10$7u87VMdrCXpQgfGfx87JEe4BiFAJwUzuhogFeygFA7vWkhjrc7qJ6', '01711000030', 'member', NULL, 1, 1, '2026-05-13 10:47:23'),
(6, 'NAZMUS SAKIB SAMI', 'saminazmussakib0@gmail.com', '$2y$10$TCDljleAPjk.V5rwjQy7G.rRPfhZID8tgEqiGhAUkrhWW1vqxlEQW', 'saminazmussakib0@gmail.co', 'member', NULL, 1, 1, '2026-05-13 11:28:31'),
(7, 'Zakaria Rahman', 'zakaria@gmail.com', '$2y$10$Y/G/ezIBsK63dPnt8K20ROO0BNsQaLOHvudEGIkkehSUDNs6ZIBb6', '01310331111', 'member', NULL, 2, 1, '2026-05-15 09:42:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_member_book` (`member_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `librarian_id` (`librarian_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_book_branch` (`book_id`,`branch_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `branch_policies`
--
ALTER TABLE `branch_policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_id` (`branch_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_record_id` (`borrow_record_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `inter_branch_requests`
--
ALTER TABLE `inter_branch_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `from_branch_id` (`from_branch_id`),
  ADD KEY `to_branch_id` (`to_branch_id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `reading_lists`
--
ALTER TABLE `reading_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_member_book_rl` (`member_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`k`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `book_reviews`
--
ALTER TABLE `book_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrow_records`
--
ALTER TABLE `borrow_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `branch_policies`
--
ALTER TABLE `branch_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inter_branch_requests`
--
ALTER TABLE `inter_branch_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reading_lists`
--
ALTER TABLE `reading_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD CONSTRAINT `book_reviews_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_reviews_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD CONSTRAINT `borrow_records_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_records_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_records_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_records_ibfk_4` FOREIGN KEY (`librarian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  ADD CONSTRAINT `branch_inventory_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_inventory_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_policies`
--
ALTER TABLE `branch_policies`
  ADD CONSTRAINT `branch_policies_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`borrow_record_id`) REFERENCES `borrow_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fines_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inter_branch_requests`
--
ALTER TABLE `inter_branch_requests`
  ADD CONSTRAINT `inter_branch_requests_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inter_branch_requests_ibfk_2` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inter_branch_requests_ibfk_3` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inter_branch_requests_ibfk_4` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reading_lists`
--
ALTER TABLE `reading_lists`
  ADD CONSTRAINT `reading_lists_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reading_lists_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;


-- ============================================================
-- FIXES APPLIED AUTOMATICALLY
-- ============================================================

-- Fix pending borrow request to go to member's own branch (North Branch = 2)
-- Record already exists at branch 2 which is correct for Zakaria (branch 2)
-- We add a North Branch librarian so they can see and approve it

-- Add North Branch Librarian (password: librarian123)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `branch_id`, `is_active`, `created_at`) VALUES
(8, 'North Librarian', 'librarian2@library.com', '$2b$10$.rcUp.ei/ALkSII8988rm.k8WngMNKJpmH1yqmO2eRXcltTVN24yC', '01711000022', 'librarian', NULL, 2, 1, NOW());

-- Add North Branch Manager assignment  
UPDATE `branches` SET `manager_id` = 2 WHERE `id` = 2 AND `manager_id` IS NULL;

-- Add inventory for North Branch books if missing
INSERT IGNORE INTO `branch_inventory` (`book_id`, `branch_id`, `total_copies`, `available_copies`)
SELECT b.id, 2, 3, 3 FROM books b
WHERE b.id NOT IN (SELECT book_id FROM branch_inventory WHERE branch_id = 2);

-- Add North Branch policy if missing
INSERT IGNORE INTO `branch_policies` (`branch_id`, `max_borrow_days`, `max_books_per_member`, `fine_rate_per_day`, `max_renewals`)
VALUES (2, 10, 4, 7.00, 1);

-- Add South Branch policy if missing
INSERT IGNORE INTO `branch_policies` (`branch_id`, `max_borrow_days`, `max_books_per_member`, `fine_rate_per_day`, `max_renewals`)
VALUES (3, 21, 6, 3.00, 3);

-- ============================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
