<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1457637027.
 * Generated on 2016-03-10 20:10:27 by xfreddiex
 */
class PropelMigration_1457637027
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `team`;

ALTER TABLE `group_permission`

  DROP FOREIGN KEY `group_permission_fk_6fa21b`,

  DROP INDEX `group_permission_fi_6fa21b`,

  ADD INDEX `group_permission_fi_3a4cbf` (`group_id`),

  ADD CONSTRAINT `group_permission_fk_3a4cbf`
    FOREIGN KEY (`group_id`)
    REFERENCES `group_of_users` (`id`);

ALTER TABLE `pack_permission`

  DROP FOREIGN KEY `pack_permission_fk_6fa21b`,

  DROP INDEX `pack_permission_fi_6fa21b`,

  ADD INDEX `pack_permission_fi_3a4cbf` (`group_id`),

  ADD CONSTRAINT `pack_permission_fk_3a4cbf`
    FOREIGN KEY (`group_id`)
    REFERENCES `group_of_users` (`id`);

CREATE TABLE `group_of_users`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `description` VARCHAR(256),
    `private` TINYINT(1) NOT NULL,
    `user_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `group_of_users_fi_29554a` (`user_id`),
    CONSTRAINT `group_of_users_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB CHARACTER SET=\'utf8\' COLLATE=\'utf8_unicode_ci\';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `group_of_users`;

ALTER TABLE `group_permission`

  DROP FOREIGN KEY `group_permission_fk_3a4cbf`,

  DROP INDEX `group_permission_fi_3a4cbf`,

  ADD INDEX `group_permission_fi_6fa21b` (`group_id`),

  ADD CONSTRAINT `group_permission_fk_6fa21b`
    FOREIGN KEY (`group_id`)
    REFERENCES `team` (`id`);

ALTER TABLE `pack_permission`

  DROP FOREIGN KEY `pack_permission_fk_3a4cbf`,

  DROP INDEX `pack_permission_fi_3a4cbf`,

  ADD INDEX `pack_permission_fi_6fa21b` (`group_id`),

  ADD CONSTRAINT `pack_permission_fk_6fa21b`
    FOREIGN KEY (`group_id`)
    REFERENCES `team` (`id`);

CREATE TABLE `team`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `description` VARCHAR(256),
    `private` TINYINT(1) NOT NULL,
    `user_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `team_fi_29554a` (`user_id`),
    CONSTRAINT `team_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}