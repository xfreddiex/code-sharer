<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458155389.
 * Generated on 2016-03-16 20:09:49 by xfreddiex
 */
class PropelMigration_1458155389
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

CREATE TABLE `comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `file_id` INTEGER,
    `pack_id` INTEGER,
    `text` VARCHAR(1024) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `comment_fi_29554a` (`user_id`),
    INDEX `comment_fi_38afab` (`file_id`),
    INDEX `comment_fi_c61110` (`pack_id`),
    CONSTRAINT `comment_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `comment_fk_38afab`
        FOREIGN KEY (`file_id`)
        REFERENCES `file` (`id`),
    CONSTRAINT `comment_fk_c61110`
        FOREIGN KEY (`pack_id`)
        REFERENCES `pack` (`id`)
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

DROP TABLE IF EXISTS `comment`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}