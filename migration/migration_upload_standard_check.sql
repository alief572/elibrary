-- ====================================================================
-- MIGRATION: ADD upload_standard_check TO checksheet_data_items & checksheet_process_details
-- ====================================================================

-- 1. Tambah upload_standard_check ke master items checksheet
ALTER TABLE `checksheet_data_items`
ADD COLUMN `upload_standard_check` VARCHAR(255) NULL AFTER `check_type`;

-- 2. Tambah upload_standard_check ke detail eksekusi checksheet
ALTER TABLE `checksheet_process_details`
ADD COLUMN `upload_standard_check` VARCHAR(255) NULL AFTER `check_type`;
