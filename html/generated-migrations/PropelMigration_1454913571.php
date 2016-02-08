<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1454913571.
 * Generated on 2016-02-08 07:39:31 by xfreddiex
 */
class PropelMigration_1454913571
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

  CHANGE `created_at` `created_at` DATETIME,

  CHANGE `deleted_at` `deleted_at` DATETIME,

  ADD `updated_at` DATETIME AFTER `created_at`,

  DROP `changed_at`;

ALTER TABLE `group`

  CHANGE `created_at` `created_at` DATETIME,

  CHANGE `deleted_at` `deleted_at` DATETIME,

  ADD `updated_at` DATETIME AFTER `created_at`,

  DROP `changed_at`;

ALTER TABLE `pack`

  CHANGE `created_at` `created_at` DATETIME,

  CHANGE `deleted_at` `deleted_at` DATETIME,

  ADD `updated_at` DATETIME AFTER `created_at`,

  DROP `changed_at`;

ALTER TABLE `user`

  CHANGE `created_at` `created_at` DATETIME,

  CHANGE `deleted_at` `deleted_at` DATETIME,

  ADD `updated_at` DATETIME AFTER `created_at`,

  DROP `changed_at`;

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

  CHANGE `created_at` `created_at` INTEGER NOT NULL,

  CHANGE `deleted_at` `deleted_at` INTEGER,

  ADD `changed_at` INTEGER AFTER `created_at`,

  DROP `updated_at`;

ALTER TABLE `group`

  CHANGE `created_at` `created_at` INTEGER NOT NULL,

  CHANGE `deleted_at` `deleted_at` INTEGER,

  ADD `changed_at` INTEGER AFTER `created_at`,

  DROP `updated_at`;

ALTER TABLE `pack`

  CHANGE `created_at` `created_at` INTEGER NOT NULL,

  CHANGE `deleted_at` `deleted_at` INTEGER,

  ADD `changed_at` INTEGER AFTER `created_at`,

  DROP `updated_at`;

ALTER TABLE `user`

  CHANGE `created_at` `created_at` INTEGER NOT NULL,

  CHANGE `deleted_at` `deleted_at` INTEGER,

  ADD `changed_at` INTEGER AFTER `created_at`,

  DROP `updated_at`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}