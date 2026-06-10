-- ============================================================
-- DEPLOY DATABASE - Semua perubahan database
-- Project: eLibrary Audit System
-- Date: 2026-06-10
-- 
-- CATATAN: Jalankan file ini di database server secara berurutan.
-- Semua query menggunakan IF NOT EXISTS / IF NOT EXISTS pattern
-- agar aman dijalankan ulang.
-- ============================================================

-- ============================================================
-- BAGIAN 1: MODUL AUDIT PREPARATION (Jadwal & Persiapan Audit)
-- ============================================================

CREATE TABLE IF NOT EXISTS `audit_program` (
  `id` VARCHAR(11) NOT NULL COMMENT 'Generated ID: APR{YYMM}-{NNN}',
  `company` VARCHAR(255) NOT NULL COMMENT 'Company name (free text)',
  `lead_auditor_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_auditor_consultant.id',
  `audit_scope` ENUM('Audit Khusus','Audit Regular') NOT NULL COMMENT 'Scope of audit',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_program_evaluation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_program.id',
  `audit_temuan_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_temuan.id',
  `temuan_detail_id` TEXT NULL DEFAULT NULL COMMENT 'Comma-separated temuan detail IDs',
  `weakness_description` TEXT NULL DEFAULT NULL COMMENT 'Weakness description from temuan',
  `improvement_action` TEXT NULL DEFAULT NULL COMMENT 'Improvement action (max 2000 chars)',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_evaluation_program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_program_critical_issue` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_program.id',
  `issue_description` TEXT NOT NULL COMMENT 'Issue description (max 2000 chars)',
  `management_input` TEXT NULL DEFAULT NULL COMMENT 'Management direction/input (max 2000 chars)',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_critical_issue_program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_program_opportunity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_program.id',
  `procedure_id` VARCHAR(20) NOT NULL COMMENT 'FK to procedures.id',
  `description` TEXT NOT NULL COMMENT 'Opportunity/problem description (max 1000 chars)',
  `investigation` TEXT NULL DEFAULT NULL COMMENT 'Investigation text per process',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_opportunity_program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_program_schedule` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_program.id',
  `process_id` VARCHAR(20) NULL DEFAULT NULL COMMENT 'FK to procedures.id',
  `process_name_free` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Free text process name',
  `auditor_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_auditor_consultant.id',
  `audit_date` DATE NOT NULL COMMENT 'Scheduled audit date',
  `start_time` TIME NOT NULL COMMENT 'Audit start time',
  `end_time` TIME NOT NULL COMMENT 'Audit end time',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_schedule_program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_program_schedule_auditee` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` INT(11) NOT NULL COMMENT 'FK to audit_program_schedule.id',
  `department_id` VARCHAR(20) NOT NULL COMMENT 'FK to audit_department.id',
  PRIMARY KEY (`id`),
  INDEX `idx_auditee_schedule_id` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- BAGIAN 2: MODUL AUDIT DEPARTMENT (Master Department)
-- ============================================================

CREATE TABLE IF NOT EXISTS `audit_department` (
  `id` VARCHAR(20) NOT NULL,
  `department_name` VARCHAR(100) NOT NULL,
  `status` ENUM('0','1','2') NOT NULL DEFAULT '1' COMMENT '0=deleted, 1=active, 2=inactive',
  `created_at` DATETIME DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `modified_at` DATETIME DEFAULT NULL,
  `modified_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- BAGIAN 3: MODUL CHECKLIST AUDIT NON STANDARD
-- ============================================================

CREATE TABLE IF NOT EXISTS `audit_checklist_non_standard` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` INT(11) NOT NULL COMMENT 'FK to audit_program_schedule.id',
  `checklist_text` TEXT NOT NULL COMMENT 'Free text checklist item',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_ns_checklist_schedule_id` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- BAGIAN 4: MODUL PELAKSANAAN AUDIT
-- ============================================================

CREATE TABLE IF NOT EXISTS `pelaksanaan_audit` (
  `id` VARCHAR(20) NOT NULL,
  `schedule_id` INT(11) NOT NULL COMMENT 'FK to audit_program_schedule.id',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pa_schedule_id` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pelaksanaan_audit_ns_details` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `audit_id` VARCHAR(20) NOT NULL COMMENT 'FK to pelaksanaan_audit.id',
  `checklist_id` INT(11) NOT NULL COMMENT 'FK to audit_checklist_non_standard.id',
  `catatan` TEXT NULL DEFAULT NULL,
  `kategori` VARCHAR(20) NULL DEFAULT NULL COMMENT 'OK / OFI / Minor / Major',
  `iso_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirements.id',
  `pasal_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirement_details.id',
  `file_name` VARCHAR(255) NULL DEFAULT NULL,
  `file_type` VARCHAR(20) NULL DEFAULT NULL,
  `file_size` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` CHAR(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pa_ns_audit_id` (`audit_id`),
  INDEX `idx_pa_ns_checklist_id` (`checklist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pelaksanaan_audit_std_details` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `audit_id` VARCHAR(20) NOT NULL COMMENT 'FK to pelaksanaan_audit.id',
  `checklist_detail_id` VARCHAR(30) NOT NULL COMMENT 'FK to audit_checklist_details.id',
  `catatan` TEXT NULL DEFAULT NULL,
  `kategori` VARCHAR(20) NULL DEFAULT NULL COMMENT 'OK / OFI / Minor / Major',
  `iso_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirements.id',
  `pasal_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirement_details.id',
  `file_name` VARCHAR(255) NULL DEFAULT NULL,
  `file_type` VARCHAR(20) NULL DEFAULT NULL,
  `file_size` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` CHAR(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pa_std_audit_id` (`audit_id`),
  INDEX `idx_pa_std_checklist_detail_id` (`checklist_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pelaksanaan_audit_conformity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `audit_id` VARCHAR(20) NOT NULL COMMENT 'FK to pelaksanaan_audit.id',
  `description` TEXT NOT NULL COMMENT 'Strong point description',
  `kategori` VARCHAR(20) NULL DEFAULT NULL COMMENT 'OK / OFI / Minor / Major',
  `iso_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirements.id',
  `pasal_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirement_details.id',
  `file_name` VARCHAR(255) NULL DEFAULT NULL,
  `file_type` VARCHAR(20) NULL DEFAULT NULL,
  `file_size` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` CHAR(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pa_conf_audit_id` (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pelaksanaan_audit_temuan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `audit_id` VARCHAR(20) NOT NULL COMMENT 'FK to pelaksanaan_audit.id',
  `description` TEXT NOT NULL COMMENT 'Temuan description (Problem, Location, Objectives Evidence, Reference)',
  `kategori` VARCHAR(20) NULL DEFAULT NULL COMMENT 'OK / OFI / Minor / Major',
  `iso_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirements.id',
  `pasal_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to requirement_details.id',
  `file_name` VARCHAR(255) NULL DEFAULT NULL,
  `file_type` VARCHAR(20) NULL DEFAULT NULL,
  `file_size` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` CHAR(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pa_temuan_audit_id` (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- BAGIAN 5: MODUL CORRECTIVE ACTION
-- ============================================================

CREATE TABLE IF NOT EXISTS `corrective_action` (
  `id` VARCHAR(15) NOT NULL COMMENT 'Format: CA{YYMM}-{NNN}',
  `pelaksanaan_id` INT(11) NOT NULL,
  `company_id` INT(11) NOT NULL,
  `status_ca` ENUM('draft','waiting_approval','approved') NOT NULL DEFAULT 'draft',
  `deleted` CHAR(1) NOT NULL DEFAULT '0' COMMENT '0=active, 1=deleted',
  `created_at` DATETIME DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `modified_at` DATETIME DEFAULT NULL,
  `modified_by` INT(11) DEFAULT NULL,
  `submitted_at` DATETIME DEFAULT NULL,
  `submitted_by` INT(11) DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `approved_by` INT(11) DEFAULT NULL,
  `rejected_at` DATETIME DEFAULT NULL,
  `rejected_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pelaksanaan_id` (`pelaksanaan_id`),
  INDEX `idx_status_ca` (`status_ca`),
  INDEX `idx_company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `corrective_action_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ca_id` VARCHAR(15) NOT NULL,
  `temuan_id` INT(11) NOT NULL,
  `fakta` TEXT DEFAULT NULL,
  `kesimpulan_penyebab` TEXT DEFAULT NULL,
  `correction` TEXT DEFAULT NULL,
  `corrective_action` TEXT DEFAULT NULL,
  `deleted` CHAR(1) NOT NULL DEFAULT '0',
  `created_at` DATETIME DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `modified_at` DATETIME DEFAULT NULL,
  `modified_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_ca_id` (`ca_id`),
  INDEX `idx_temuan_id` (`temuan_id`),
  CONSTRAINT `fk_cad_ca` FOREIGN KEY (`ca_id`) REFERENCES `corrective_action` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `corrective_action_file` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ca_detail_id` INT(11) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL COMMENT 'Encrypted filename on disk',
  `file_name_original` VARCHAR(255) NOT NULL COMMENT 'Original filename for display',
  `file_type` VARCHAR(10) NOT NULL COMMENT 'File extension',
  `file_size` INT(11) NOT NULL DEFAULT 0 COMMENT 'Size in bytes',
  `deleted` CHAR(1) NOT NULL DEFAULT '0',
  `created_at` DATETIME DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_ca_detail_id` (`ca_detail_id`),
  CONSTRAINT `fk_caf_cad` FOREIGN KEY (`ca_detail_id`) REFERENCES `corrective_action_detail` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `corrective_action_rejection` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ca_id` VARCHAR(15) NOT NULL,
  `reason` TEXT NOT NULL,
  `rejected_by` INT(11) NOT NULL,
  `rejected_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_ca_id` (`ca_id`),
  CONSTRAINT `fk_car_ca` FOREIGN KEY (`ca_id`) REFERENCES `corrective_action` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- BAGIAN 6: ALTER TABLE (jika tabel sudah ada sebelumnya)
-- Jalankan hanya jika tabel sudah dibuat dari deploy sebelumnya
-- dan belum memiliki kolom/perubahan ini.
-- ============================================================

-- 6a. Tambah kolom investigation di audit_program_opportunity
-- (Abaikan jika error "Duplicate column name", artinya sudah ada)
ALTER TABLE `audit_program_opportunity` ADD COLUMN `investigation` TEXT NULL DEFAULT NULL COMMENT 'Investigation text per process' AFTER `description`;

-- 6b. Ubah department_id jadi VARCHAR(20) untuk match audit_department.id
ALTER TABLE `audit_program_schedule_auditee` MODIFY COLUMN `department_id` VARCHAR(20) NOT NULL COMMENT 'FK to audit_department.id';

-- 6c. Ubah process_id jadi VARCHAR(20) untuk match procedures.id
ALTER TABLE `audit_program_schedule` MODIFY COLUMN `process_id` VARCHAR(20) NULL DEFAULT NULL COMMENT 'FK to procedures.id';

-- 6d. Tambah kolom process_name_free di audit_program_schedule
-- (Abaikan jika error "Duplicate column name", artinya sudah ada)
ALTER TABLE `audit_program_schedule` ADD COLUMN `process_name_free` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Free text process name' AFTER `process_id`;

-- 6e. Ubah temuan_detail_id jadi TEXT (comma-separated)
ALTER TABLE `audit_program_evaluation` MODIFY COLUMN `temuan_detail_id` TEXT NULL DEFAULT NULL COMMENT 'Comma-separated temuan detail IDs';

-- ============================================================
-- BAGIAN 7: INSERT MENU
-- PENTING: Cek dulu MAX(id) dari table menus di server!
-- SELECT MAX(id) FROM menus;
-- Sesuaikan ID jika sudah ada conflict.
-- ============================================================

-- Menu: Jadwal & Persiapan Audit
INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (46, 'Jadwal & Persiapan Audit', 'audit_preparation', 'fa fa-calendar-check', 'sametab', 1, 0, 1, 1, 46, NOW(), 1, '1');

-- Menu: Department (Master Audit)
INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (47, 'Department', 'audit_department', 'fa fa-building', 'sametab', 1, 0, 1, 1, 47, NOW(), 1, '1');

-- Menu: Checklist Audit Non Standard
INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (48, 'Checklist Audit Non Standard', 'audit_checklist_non_standard', 'fa fa-clipboard-list', 'sametab', 1, 0, 1, 1, 48, NOW(), 1, '1');

-- Menu: Pelaksanaan Audit
INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (49, 'Pelaksanaan Audit', 'pelaksanaan_audit', 'fa fa-clipboard-check', 'sametab', 1, 0, 1, 1, 49, NOW(), 1, '1');

-- Menu: Corrective Action
INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (50, 'Corrective Action', 'corrective_action', 'fa fa-wrench', 'sametab', 1, 0, 1, 1, 50, NOW(), 1, '1');

-- ============================================================
-- BAGIAN 8: INSERT GROUP MENU (Permission)
-- Sesuaikan group_id dan company_id dengan kondisi database server
-- ============================================================

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 46, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 47, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 48, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 49, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 50, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

-- ============================================================
-- SELESAI!
-- 
-- Checklist setelah deploy:
-- 1. Pastikan semua tabel baru muncul di database
-- 2. Pastikan menu baru tampil di sidebar
-- 3. Pastikan user dengan group_id=2 bisa akses semua menu baru
-- 4. Buat folder upload: directory/CORRECTIVE_ACTION/
-- ============================================================
