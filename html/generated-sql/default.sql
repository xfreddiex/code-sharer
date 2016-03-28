
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
    UNIQUE INDEX `unique_email` (`email`),
    FULLTEXT INDEX `fulltext` (`username`, `name`, `surname`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

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
    INDEX `identity_fi_29554a` (`user_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- pack_permission
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pack_permission`;

CREATE TABLE `pack_permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `value` ENUM("0","1","2") NOT NULL,
    `user_id` INTEGER,
    `group_id` INTEGER,
    `pack_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `pack_permission_fi_29554a` (`user_id`),
    INDEX `pack_permission_fi_3a4cbf` (`group_id`),
    INDEX `pack_permission_fi_c61110` (`pack_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- pack
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pack`;

CREATE TABLE `pack`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `description` VARCHAR(256),
    `private` TINYINT(1) NOT NULL,
    `owner_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `pack_u_103a4b` (`owner_id`, `name`),
    FULLTEXT INDEX `fulltext` (`name`, `description`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- file
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `description` VARCHAR(256),
    `type` VARCHAR(32) NOT NULL,
    `size` INTEGER NOT NULL,
    `content` MEDIUMBLOB NOT NULL,
    `pack_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `file_fi_c61110` (`pack_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- group_of_users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_of_users`;

CREATE TABLE `group_of_users`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `description` VARCHAR(256),
    `owner_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `group_of_users_fi_ac5b84` (`owner_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `pack_id` INTEGER,
    `text` VARCHAR(1024) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `comment_fi_29554a` (`user_id`),
    INDEX `comment_fi_c61110` (`pack_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

-- ---------------------------------------------------------------------
-- user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`user_id`,`group_id`),
    INDEX `user_group_fi_3a4cbf` (`group_id`)
) ENGINE=MyISAM CHARACTER SET='utf8' COLLATE='utf8_unicode_ci';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
