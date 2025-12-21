-- Migration: Add task_id column to notifications table
-- This allows notifications to be linked to specific tasks for clickable redirects

-- Check if column exists before adding (for safety)
SET @dbname = DATABASE();
SET @tablename = "notifications";
SET @columnname = "task_id";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " INT NULL, ADD FOREIGN KEY (", @columnname, ") REFERENCES tasks(id) ON DELETE SET NULL")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Alternative simpler version (uncomment if the above doesn't work):
-- ALTER TABLE notifications ADD COLUMN task_id INT NULL;
-- ALTER TABLE notifications ADD FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL;

