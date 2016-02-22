<?php

namespace Models;

use Models\Base\Identity as BaseIdentity;
use Models\Map\IdentityTableMap;

/**
 * Skeleton subclass for representing a row from the 'identity' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Identity extends BaseIdentity
{
	public function setToken($token)
	{
		if ($token !== null) {
			$token = (string) $token;
		}
		
		$this->token = password_hash($token, PASSWORD_DEFAULT);
		$this->modifiedColumns[IdentityTableMap::COL_TOKEN] = true;

		return $this;
	}

	public function checkToken($token){
		return password_verify($token, $this->token);
	}

}
