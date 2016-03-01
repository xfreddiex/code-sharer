
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
    `username` VARCHAR(32) NOT NULL,
    `name` VARCHAR(50),
    `surname` VARCHAR(50),
    `password` VARCHAR(60) NOT NULL,
    `email` VARCHAR(70) NOT NULL,
    `avatar_path` VARCHAR(70),
    `password_reset_token` VARCHAR(64),
    `email_confirm_token` VARCHAR(64),
    `email_confirmed_at` DATETIME,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `unique_username` (`username`),
    UNIQUE INDEX `unique_email` (`email`)
) ENGINE=InnoDB CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- identity
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `identity`;

CREATE TABLE `identity`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `token` VARCHAR(60) NOT NULL,
    `user_id` INTEGER NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `identity_fi_29554a` (`user_id`),
    CONSTRAINT `identity_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

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
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

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
    `deleted_at` DATETIME,
    `tags` VARCHAR(200),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

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
    `deleted_at` DATETIME,
    `code` VARCHAR(200) NOT NULL,
    `pack_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `file_fi_c61110` (`pack_id`),
    CONSTRAINT `file_fk_c61110`
        FOREIGN KEY (`pack_id`)
        REFERENCES `pack` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
