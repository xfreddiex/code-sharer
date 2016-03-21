<?php

namespace Models;

use Models\Base\User as BaseUser;
use Propel\Runtime\Connection\ConnectionInterface;
use Models\Map\UserTableMap;
use Models\UserGroup as ChildUserGroup;
use Models\UserGroupQuery as ChildUserGroupQuery;

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
		if($this->validate()){
			$this->hashPassword();
		}
		return $this->validate();
	}

	public function setPassword($v){
		if ($v !== null) {
			$v = (string) $v;
		}

		if($this->password !== $v){
			$identities = $this->getIdentities();
			foreach($identities as $identity){
				$identity->delete();
			}
			$this->password = $v;
			$this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
		}

		return $this;
	}

	public function hashPassword(){
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
	}

	public function checkPassword($password){
		return password_verify($password, $this->password);
	}

	public function getAvatar250(){
		$dir = "Includes/images/avatars/250x250/";
		return "/".$dir.(file_exists($dir.$this->avatar_path) && is_file($dir.$this->avatar_path) ? $this->avatar_path : "default.png");
	}

	public function getAvatar40(){
		$dir = "Includes/images/avatars/40x40/";
		return "/".$dir.(file_exists($dir.$this->avatar_path) && is_file($dir.$this->avatar_path) ? $this->avatar_path : "default.png");
	}

	public function getAddedToGroup($group, $format){
		$timestamp = ChildUserGroupQuery::create()->filterByUser($this)->filterByGroup($group)->findOne()->getCreatedAt($format);
		return $timestamp;
	}
}
