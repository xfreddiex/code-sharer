<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1452711677.
 * Generated on 2016-01-13 20:01:17 by xfreddiex
 */
class PropelMigration_1452711677
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

DROP TABLE IF EXISTS `user`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}