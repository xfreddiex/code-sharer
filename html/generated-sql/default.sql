
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(30) NOT NULL,
    `name` VARCHAR(50),
    `surname` VARCHAR(50),
    `password` VARCHAR(50) NOT NULL,
    `email` VARCHAR(70) NOT NULL,
    `avatar` VARCHAR(70),
    `password_token` VARCHAR(64),
    `email_token` VARCHAR(64),
    `email_confirmed_at` DATETIME,
    `created_at` INTEGER NOT NULL,
    `changed_at` INTEGER,
    `deleted_at` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(250),
    `public` TINYINT(1) NOT NULL,
    `created_at` INTEGER NOT NULL,
    `changed_at` INTEGER,
    `deleted_at` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pack`;

CREATE TABLE `pack`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(250),
    `public` TINYINT(1) NOT NULL,
    `created_at` INTEGER NOT NULL,
    `changed_at` INTEGER,
    `deleted_at` INTEGER,
    `tags` VARCHAR(200),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- file
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(250),
    `type` VARCHAR(10) NOT NULL,
    `created_at` INTEGER NOT NULL,
    `changed_at` INTEGER,
    `deleted_at` INTEGER,
    `code` VARCHAR(200) NOT NULL,
    `pack_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `file_fi_c61110` (`pack_id`),
    CONSTRAINT `file_fk_c61110`
        FOREIGN KEY (`pack_id`)
        REFERENCES `pack` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
