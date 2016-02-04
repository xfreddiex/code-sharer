<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1454575011.
 * Generated on 2016-02-04 09:36:51 by xfreddiex
 */
class PropelMigration_1454575011
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

ALTER TABLE `user`

  CHANGE `changed_at` `changed_at` INTEGER,

  CHANGE `deleted_at` `deleted_at` INTEGER;

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

DROP TABLE IF EXISTS `group`;

DROP TABLE IF EXISTS `pack`;

DROP TABLE IF EXISTS `file`;

ALTER TABLE `user`

  CHANGE `changed_at` `changed_at` INTEGER NOT NULL,

  CHANGE `deleted_at` `deleted_at` INTEGER NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}