<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1457639815.
 * Generated on 2016-03-10 20:56:55 by xfreddiex
 */
class PropelMigration_1457639815
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

DROP TABLE IF EXISTS `group_permission`;

ALTER TABLE `file`

  CHANGE `code` `code` VARCHAR(200) NOT NULL;

ALTER TABLE `pack_permission`

  CHANGE `type` `value` TINYINT NOT NULL;

CREATE TABLE `user_group`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    INDEX `user_group_fi_29554a` (`user_id`),
    INDEX `user_group_fi_3a4cbf` (`group_id`),
    CONSTRAINT `user_group_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `user_group_fk_3a4cbf`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_of_users` (`id`)
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

DROP TABLE IF EXISTS `user_group`;

ALTER TABLE `file`

  CHANGE `code` `code` VARCHAR(255) NOT NULL;

ALTER TABLE `pack_permission`

  CHANGE `value` `type` TINYINT NOT NULL;

CREATE TABLE `group_permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` TINYINT NOT NULL,
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `deleted_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `group_permission_fi_29554a` (`user_id`),
    INDEX `group_permission_fi_3a4cbf` (`group_id`),
    CONSTRAINT `group_permission_fk_3a4cbf`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_of_users` (`id`),
    CONSTRAINT `group_permission_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}