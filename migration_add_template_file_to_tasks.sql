-- Migration: Add template_file column to tasks table
-- This allows admins to upload templates/guides when creating tasks

-- Check if column exists before adding (for safety)
SET @dbname = DATABASE();
SET @tablename = "tasks";
SET @columnname = "template_file";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " VARCHAR(255) NULL AFTER submission_file")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Alternative simpler version (uncomment if the above doesn't work):
-- ALTER TABLE tasks ADD COLUMN template_file VARCHAR(255) NULL AFTER submission_file;

