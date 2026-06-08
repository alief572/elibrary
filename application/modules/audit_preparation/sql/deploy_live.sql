-- ============================================================
-- DEPLOY SCRIPT - Modul Audit Preparation (Jadwal & Persiapan Audit)
-- Jalankan file ini di database LIVE secara berurutan
-- Date: 2026-06-06
-- ============================================================

-- ============================================================
-- 1. CREATE TABLES (paling penting, harus duluan)
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
  `temuan_detail_id` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Referenced temuan detail',
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
  `process_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_process.id',
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
  `department_id` INT(11) NOT NULL COMMENT 'FK to companies.id_perusahaan',
  PRIMARY KEY (`id`),
  INDEX `idx_auditee_schedule_id` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 2. ALTER TABLE (modifikasi kolom yang sudah ada)
-- ============================================================

ALTER TABLE `audit_program_evaluation` MODIFY COLUMN `temuan_detail_id` TEXT NULL DEFAULT NULL COMMENT 'Comma-separated temuan detail IDs';

-- ============================================================
-- 3. INSERT MENU (tambah menu baru di sidebar)
-- NOTE: Cek dulu MAX(id) dari table menus, sesuaikan jika perlu
-- SELECT MAX(id) FROM menus;
-- ============================================================

INSERT INTO `menus` (`id`, `title`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`, `created_on`, `created_by`, `flag_new`)
VALUES (46, 'Jadwal & Persiapan Audit', 'audit_preparation', 'fa fa-calendar-check', 'sametab', 1, 0, 1, 1, 46, NOW(), 1, '1');

-- ============================================================
-- 4. INSERT GROUP MENU (assign permission menu ke group user)
-- Sesuaikan group_id dan company_id dengan kondisi live
-- ============================================================

INSERT INTO `group_menus` (`group_id`, `menu_id`, `company_id`, `read`, `create`, `update`, `delete`, `approve`, `download`, `created`, `created_by`)
VALUES (2, 46, 1, 1, 1, 1, 1, 0, 0, NOW(), '1');

-- ============================================================
-- SELESAI
-- Setelah menjalankan script ini, pastikan:
-- 1. Table baru muncul di database
-- 2. Menu "Jadwal & Persiapan Audit" tampil di sidebar
-- 3. User dengan group_id=2 bisa akses menu tersebut
-- ============================================================
