-- Migration: Dashboard Cards Table for eLibrary v2
-- Description: Menyimpan daftar shortcut card dashboard yang dapat dikelola oleh Administrator

CREATE TABLE IF NOT EXISTS `dashboard_cards` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `link` varchar(255) NOT NULL,
    `picture` varchar(255) NOT NULL,
    `sort_order` int(11) DEFAULT 0,
    `is_active` enum('Y','N') DEFAULT 'Y',
    `created_at` datetime DEFAULT NULL,
    `created_by` int(11) DEFAULT NULL,
    `modified_at` datetime DEFAULT NULL,
    `modified_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
