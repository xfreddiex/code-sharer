<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1457269743.
 * Generated on 2016-03-06 14:09:03 by xfreddiex
 */
class PropelMigration_1457269743
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

ALTER TABLE `group`

  ADD `user_id` INTEGER NOT NULL AFTER `private`,

  ADD INDEX `group_fi_29554a` (`user_id`),

  ADD CONSTRAINT `group_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`);

ALTER TABLE `pack`

  ADD `user_id` INTEGER NOT NULL AFTER `private`,

  ADD INDEX `pack_fi_29554a` (`user_id`),

  ADD CONSTRAINT `pack_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`);

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

ALTER TABLE `group`

  DROP FOREIGN KEY `group_fk_29554a`,

  DROP INDEX `group_fi_29554a`,

  DROP `user_id`;

ALTER TABLE `pack`

  DROP FOREIGN KEY `pack_fk_29554a`,

  DROP INDEX `pack_fi_29554a`,

  DROP `user_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}