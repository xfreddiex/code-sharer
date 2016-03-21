<?php

namespace Models;

use Models\Base\Group as BaseGroup;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'group' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Group extends BaseGroup
{
	public function preSave(ConnectionInterface $con = null){
		return $this->validate();
	}

	public function addUsersFromArray($users = array()){
		foreach($users as $user){
				$u = UserQuery::create()->findOneByUsername($user["username"]);
				if($u){
					if($u == $this->data["loggedUser"]){
						$response["messages"][] = "You can not add yourself to group.";
						continue;
					}
					$userGroup = UserGroupQuery::create()->filterByUser($u)->filterByGroup($this->data["group"])->findOne();
					if($userGroup){
						$response["messages"][] = "User " . $user["username"] . " is already in this group.";
						continue;	
					}
					$userGroup = new UserGroup();
					$userGroup->setUser($u);
					$userGroup->setGroup($this->data["group"]);
					$userGroup->save();
				}
				else
					$response["messages"][] = "User " . $user["username"] . " does not exist.";
			}
	}
}
