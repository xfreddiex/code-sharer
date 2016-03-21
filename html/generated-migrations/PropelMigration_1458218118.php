<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458218118.
 * Generated on 2016-03-17 13:35:18 by xfreddiex
 */
class PropelMigration_1458218118
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

ALTER TABLE `group_of_users`

  DROP FOREIGN KEY `group_of_users_fk_ac5b84`,

  DROP INDEX `group_of_users_fi_ac5b84`,

  DROP `owner_id`;

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

ALTER TABLE `group_of_users`

  ADD `owner_id` INTEGER NOT NULL AFTER `description`,

  ADD INDEX `group_of_users_fi_ac5b84` (`owner_id`),

  ADD CONSTRAINT `group_of_users_fk_ac5b84`
    FOREIGN KEY (`owner_id`)
    REFERENCES `user` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}