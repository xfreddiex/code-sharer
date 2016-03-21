<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458218536.
 * Generated on 2016-03-17 13:42:16 by xfreddiex
 */
class PropelMigration_1458218536
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

ALTER TABLE `user_group`

  DROP INDEX `user_group_fi_29554a`,

  DROP PRIMARY KEY,

  DROP `id`,

  ADD PRIMARY KEY (`user_id`,`group_id`);

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

ALTER TABLE `user_group`

  DROP PRIMARY KEY,

  ADD `id` INTEGER NOT NULL AUTO_INCREMENT AFTER `group_id`,

  ADD PRIMARY KEY (`id`),

  ADD INDEX `user_group_fi_29554a` (`user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}