<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\GroupPermission;
use Models\GroupPermissionQuery;
use Models\Pack;
use Models\PackQuery;
use Models\PackPermission;
use Models\PackPermissionQuery;

class PackPermissionController extends BaseController{

	public function __construct(){
		parent::__construct();

		$this->addBefore("update", array("userLogged", "userAuthorized", "loadPack"));
	}

	private function update(){
		var_dump($_POST["group".$i."id"]);
		for($i = 0; ; $i++){ 
			
			if(isset($_POST["user".$i."username"])){
				$user = UserQuery::create()->findOneByUsername($_POST["user".$i."username"]);
				if($user){
					$permission = PackPermissionQuery::create()->filterByBelongerType("user")->filterByUser($user)->filterByPack($this->data["pack"])->findOne();
					if(!$permission)
						$permission = new PackPermission();
					if(isset($_POST["user".$i."remove"]))
						$permission->setType("remove");
					else if(isset($_POST["user".$i."edit"]))
						$permission->setType("edit");
					else if(isset($_POST["user".$i."view"]))
						$permission->setType("view");
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setBelongerType("user");
					$permission->setUser($user);
					$permission->save();
				}
				else
					$this->sendFlashMessage("User " . $_POST["user".$i."username"] . " does not exist.", "error");
			}
			else
				break;

		}

		for($i = 0; ; $i++){ 
			
			if(isset($_POST["group".$i."id"])){
				$group = GroupQuery::create()->findPK($_POST["group".$i."id"]);
				if($group){
					$permission = PackPermissionQuery::create()->filterByBelongerType("group")->filterByGroup($group)->filterByPack($this->data["pack"])->findOne();
					if(!$permission)
						$permission = new PackPermission();
					if(isset($_POST["group".$i."remove"]))
						$permission->setType("remove");
					else if(isset($_POST["group".$i."edit"]))
						$permission->setType("edit");
					else if(isset($_POST["group".$i."view"]))
						$permission->setType("view");
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setBelongerType("group");
					$permission->setGroup($group);
					$permission->save();
				}
				else
					$this->sendFlashMessage("Group with ID " . $_POST["group".$i."id"] . " does not exist.", "error");
			}
			else
				break;

		}

		$this->redirect($this->data["referersURI"]);
	}

}