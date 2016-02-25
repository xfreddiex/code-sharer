<?php

namespace Models;

use Models\Base\User as BaseUser;
use Propel\Runtime\Connection\ConnectionInterface;
use Models\Map\UserTableMap;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
	public function preSave(ConnectionInterface $con = null){
		return $this->validate();
	}

	public function setPassword($v){
		if ($v !== null) {
			$v = (string) $v;
		}

		if (!password_verify($v, $this->password)) {
			$identities = $this->getIdentities();
			foreach($identities as $identity){
				$identity->delete();
			}
			$this->password = password_hash($v, PASSWORD_DEFAULT);
			$this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
		}

		return $this;
	}

	public function checkPassword($password){
		return password_verify($password, $this->password);
	}

}
