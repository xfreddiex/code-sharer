<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1459180814.
 * Generated on 2016-03-28 18:00:14 by xfreddiex
 */
class PropelMigration_1459180814
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

ALTER TABLE `file`

  DROP `deleted_at`;

ALTER TABLE `group_of_users`

  DROP `deleted_at`;

ALTER TABLE `pack`

  DROP `deleted_at`;

ALTER TABLE `pack_permission`

  CHANGE `value` `value` ENUM("0","1","2") NOT NULL,

  DROP `deleted_at`;

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

ALTER TABLE `file`

  ADD `deleted_at` DATETIME AFTER `pack_id`;

ALTER TABLE `group_of_users`

  ADD `deleted_at` DATETIME AFTER `owner_id`;

ALTER TABLE `pack`

  ADD `deleted_at` DATETIME AFTER `owner_id`;

ALTER TABLE `pack_permission`

  CHANGE `value` `value` enum(\'0\',\'1\',\'2\') NOT NULL,

  ADD `deleted_at` DATETIME AFTER `pack_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}