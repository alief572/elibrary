-- ============================================================
-- MIGRATION SCRIPT - Modul Audit Preparation: Audit Risk Assessment
-- Date: 2026-09-21
-- ============================================================

CREATE TABLE IF NOT EXISTS `audit_program_risk_assessment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `program_id` VARCHAR(11) NOT NULL COMMENT 'FK to audit_program.id',
  `subject_risk` VARCHAR(255) NOT NULL COMMENT 'Subject risk free text',
  `risk_opportunity` TEXT NOT NULL COMMENT 'Risiko/ Opportunity free text',
  `mitigation` TEXT NOT NULL COMMENT 'Mitigasi free text',
  `pic_id` INT(11) NULL DEFAULT NULL COMMENT 'FK to users.id_user (PIC)',
  `due_date` DATE NULL DEFAULT NULL COMMENT 'Due date',
  `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  `created_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_at` DATETIME NULL DEFAULT NULL,
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_risk_program_id` (`program_id`),
  INDEX `idx_risk_pic_id` (`pic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
