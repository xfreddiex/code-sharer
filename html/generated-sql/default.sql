
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
    `nick` VARCHAR(30) NOT NULL,
    `name` VARCHAR(50),
    `surname` VARCHAR(50),
    `password` VARCHAR(50) NOT NULL,
    `email` VARCHAR(70) NOT NULL,
    `avatar` VARCHAR(70),
    `password_token` VARCHAR(64),
    `email_token` VARCHAR(64),
    `email_confirmed_at` DATETIME,
    `created_at` INTEGER NOT NULL,
    `changed_at` INTEGER NOT NULL,
    `deleted_at` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
