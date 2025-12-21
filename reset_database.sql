-- Database Reset Script
-- This will truncate all tables and create a fresh admin user
-- 
-- Admin credentials:
-- Username: admin
-- Password: admin123

-- Disable foreign key checks to allow truncation
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all tables (clears all data and resets AUTO_INCREMENT)
TRUNCATE TABLE `screenshots`;
TRUNCATE TABLE `attendance`;
TRUNCATE TABLE `notifications`;
TRUNCATE TABLE `tasks`;
TRUNCATE TABLE `users`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert admin user
-- Run get_password_hash.php in browser to get the correct password hash, then replace it below
INSERT INTO `users` (`full_name`, `username`, `password`, `role`, `created_at`) VALUES
('Administrator', 'admin', 'REPLACE_WITH_HASH_FROM_get_password_hash.php', 'admin', NOW());
