<?php

namespace Models;

use Models\Base\Pack as BasePack;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'pack' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Pack extends BasePack
{
	public function preSave(ConnectionInterface $con = null){
		return $this->validate();
	}

	

}
