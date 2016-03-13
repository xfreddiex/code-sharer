<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1457471273.
 * Generated on 2016-03-08 22:07:53 by xfreddiex
 */
class PropelMigration_1457471273
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

ALTER TABLE `pack_permission`

  DROP FOREIGN KEY `pack_permission_fk_21351b`,

  DROP FOREIGN KEY `pack_permission_fk_49d0f8`,

  DROP INDEX `pack_permission_fi_49d0f8`,

  CHANGE `belonger_id` `user_id` INTEGER NOT NULL,

  ADD `group_id` INTEGER NOT NULL AFTER `user_id`,

  ADD INDEX `pack_permission_fi_29554a` (`user_id`),

  ADD INDEX `pack_permission_fi_0278b4` (`group_id`),

  ADD CONSTRAINT `pack_permission_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`),

  ADD CONSTRAINT `pack_permission_fk_0278b4`
    FOREIGN KEY (`group_id`)
    REFERENCES `group` (`id`);

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

ALTER TABLE `pack_permission`

  DROP FOREIGN KEY `pack_permission_fk_29554a`,

  DROP FOREIGN KEY `pack_permission_fk_0278b4`,

  DROP INDEX `pack_permission_fi_29554a`,

  DROP INDEX `pack_permission_fi_0278b4`,

  CHANGE `user_id` `belonger_id` INTEGER NOT NULL,

  DROP `group_id`,

  ADD INDEX `pack_permission_fi_49d0f8` (`belonger_id`),

  ADD CONSTRAINT `pack_permission_fk_21351b`
    FOREIGN KEY (`belonger_id`)
    REFERENCES `group` (`id`),

  ADD CONSTRAINT `pack_permission_fk_49d0f8`
    FOREIGN KEY (`belonger_id`)
    REFERENCES `user` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}