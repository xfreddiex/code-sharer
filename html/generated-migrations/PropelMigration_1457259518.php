<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1457259518.
 * Generated on 2016-03-06 11:18:38 by xfreddiex
 */
class PropelMigration_1457259518
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

DROP TABLE IF EXISTS `permission`;

ALTER TABLE `pack`

  CHANGE `tags` `tags` TEXT;

CREATE TABLE `pack_permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `permission_type` TINYINT NOT NULL,
    `belonger_id` INTEGER NOT NULL,
    `belonger_type` TINYINT NOT NULL,
    `pack_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `pack_permission_fi_49d0f8` (`belonger_id`),
    INDEX `pack_permission_fi_c61110` (`pack_id`),
    CONSTRAINT `pack_permission_fk_49d0f8`
        FOREIGN KEY (`belonger_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `pack_permission_fk_21351b`
        FOREIGN KEY (`belonger_id`)
        REFERENCES `group` (`id`),
    CONSTRAINT `pack_permission_fk_c61110`
        FOREIGN KEY (`pack_id`)
        REFERENCES `pack` (`id`)
) ENGINE=InnoDB CHARACTER SET=\'utf8\' COLLATE=\'utf8_unicode_ci\';

CREATE TABLE `group_permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `permission_type` TINYINT NOT NULL,
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `group_permission_fi_29554a` (`user_id`),
    INDEX `group_permission_fi_0278b4` (`group_id`),
    CONSTRAINT `group_permission_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `group_permission_fk_0278b4`
        FOREIGN KEY (`group_id`)
        REFERENCES `group` (`id`)
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

DROP TABLE IF EXISTS `pack_permission`;

DROP TABLE IF EXISTS `group_permission`;

ALTER TABLE `pack`

  CHANGE `tags` `tags` VARCHAR(200);

CREATE TABLE `permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `permission_type` TINYINT NOT NULL,
    `belonger_id` INTEGER NOT NULL,
    `belonger_type` TINYINT NOT NULL,
    `target_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `permission_fi_49d0f8` (`belonger_id`),
    CONSTRAINT `permission_fk_21351b`
        FOREIGN KEY (`belonger_id`)
        REFERENCES `group` (`id`),
    CONSTRAINT `permission_fk_49d0f8`
        FOREIGN KEY (`belonger_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}