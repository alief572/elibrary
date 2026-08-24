-- ====================================================================
-- MIGRATION: ADD CHECKER SUMMARY & STATUS COLUMNS TO checksheet_process_data
-- ====================================================================

-- 1. Menambahkan kolom catatan kesimpulan & saran checker
ALTER TABLE `checksheet_process_data` 
ADD COLUMN `checker_summary_note` TEXT NULL AFTER `status`,
ADD COLUMN `checker_status` VARCHAR(50) NULL AFTER `checker_summary_note`,
ADD COLUMN `checked_at` DATETIME NULL AFTER `checker_status`,
ADD COLUMN `checked_by` INT(11) NULL AFTER `checked_at`;
