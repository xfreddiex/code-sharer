<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\Pack;
use Models\PackQuery;
use Models\PackPermission;
use Models\PackPermissionQuery;

class PackController extends BaseController{

	public function __construct(){
		parent::__construct();

		$this->addBefore("new", array("userLogged"));
		$this->addBefore("show", array("load", "loadPermission"));
		$this->addBefore("create", array("userLogged"));
		$this->addBefore("updatePermissions", array("userLogged", "userAuthorized", "load", "loadPermission"));
	}

	/*
	*	View methods
	*/

	protected function show(){
		if($this->data["pack"]->getPrivate() && !$this->data["permission"]){
			$this->sendFlashMessage("You have not permission to view pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}
		$this->viewFile($this->template);
	}

	protected function createView(){	
		$this->viewFile($this->template);
	}

	/*
	*	Control methods
	*/

	protected function create(){
		if(isset($_POST["name"])){
			$pack = new Pack();
			$pack->setName($_POST["name"]);
			$pack->setOwner($this->data["loggedUser"]);
			$pack->setPrivate(isset($_POST["private"]));
			$pack->setDescription(isset($_POST["description"]) ? $_POST["description"] : null);
			if(isset($_POST["tags"])){
				$tags = array_map("trim", explode(",", $_POST["tags"]));
				$pack->setTags($tags);
			}

			if($pack->save() <= 0){
    			$failures = $pack->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("Your pack has not been created ".$failure->getMessage(), "error");
					}
					$this->redirect($this->data["referersURI"]);
				}
			}
			
			$this->sendFlashMessage("You have successfuly created new pack.", "success");
			$this->redirect("/pack/".$pack->getId());

		}
		else
			setHTTPStatusCode("400");
	}

	protected function delete(){
		$this->data["pack"]->setDeletedAt(time());
		$this->data["pack"]->save();
		$this->sendFlashMessage("Pack has been successfuly deleted.", "info");
		$this->redirect("/profile");	
	}


	protected function validateOne(){
		setContentType("json");
		$pack = new Pack();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "name"){
				$pack->setName($_POST["name"]);
			}
			else if($given[0] == "description"){
				$pack->setDescription($_POST["description"]);
			}
			else if($given[0] == "tags"){
				$tags = array_map("trim", explode(",", $_POST["tags"]));
				$pack->setTags($tags);
			}
			else{
				setHTTPStatusCode("400");
				return;
			}
			if(!$pack->validate()){
				foreach($pack->getValidationFailures() as $failure){
					if($given[0] == $failure->getPropertyPath()){
						$response["error"] = array(
							"name" => $failure->getPropertyPath(),
							"message" => $failure->getMessage()
						);
					}
				}
			}
			$this->viewString(json_encode($response));
		}
		else
			setHTTPStatusCode("400");
	}

	protected function update(){
		$pack = $this->data["pack"];
		if(isset($_POST["name"]))
			$pack->setName($_POST["name"]);
		if(isset($_POST["description"]))
			$pack->setDescription($_POST["description"]);
		if(isset($_POST["tags"])){
			$tags = array_map("trim", explode(",", $_POST["tags"]));
			$pack->setTags($tags);
		}
		$pack->setPrivate(isset($_POST["private"]));

		if($pack->save()){
    		$failures = $pack->getValidationFailures();
			if(count($failures) > 0){
				foreach($failures as $failure){
					$this->sendFlashMessage("Pack data has not been changeg. ".$failure->getMessage(), "error");
				}
			}
		}
		else
			$this->sendFlashMessage("Pack data has been successfuly updated.", "success");
		$this->redirect("/pack/".$pack->getId());
	}

	protected function updatePermissions(){
		setContentType("json");

		if($this->data["permission"] != 4){
			$this->sendFlashMessage("You do not have prmission to update pack with ID " . $this->data["pack"]->getId() . ".", "success");
			$this->redirect("/pack/".$this->data["pack"]->getId());
		}

		if(isset($_POST["user"])){
			foreach($_POST["user"] as $user){
				if(!isset($user["username"]))
					continue;
				$u = UserQuery::create()->findOneByUsername($user["username"]);
				if($u){
					if($u == $this->data["loggedUser"]){
						$response["messages"][] = "You can not add permission to yourself.";
						continue;
					}
					$permission = PackPermissionQuery::create()->filterByUser($u)->filterByPack($this->data["pack"])->findOneOrCreate();
					if(isset($user["remove"]))
						$permission->setValue(3);
					else if(isset($user["edit"]))
						$permission->setValue(2);
					else if(isset($user["view"]))
						$permission->setValue(1);
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setUser($u);
					$permission->save();
				}
				else
					$response["messages"][] = "User " . $user["username"] . " does not exist.";
			}
		}

		if(isset($_POST["group"])){
			foreach($_POST["group"] as $group){
				if(!isset($group["id"]))
					continue;
				$g = GroupQuery::create()->findPK($group["id"]);
				if($g && $g->getOwnerId() == $this->data["loggedUser"]->getId()){
					$permission = PackPermissionQuery::create()->filterByGroup($g)->filterByPack($this->data["pack"])->findOneOrCreate();
					if(isset($group["remove"]))
						$permission->setValue(3);
					else if(isset($group["edit"]))
						$permission->setValue(2);
					else if(isset($group["view"]))
						$permission->setValue(1);
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setGroup($g);
					$permission->save();
				}
				else
					$response["messages"][] = "Group with ID" . $group["id"] . " is not your or does not exist.";
			}
		}

		if(isset($response))
			$this->viewString(json_encode($response));
	}

	/*
	*	Before methods
	*/

	protected function load($args){
		$params = $args["params"];
		$this->data["pack"] = PackQuery::create()->findPK($params["id"]);
		if(!$this->data["pack"]){
			$this->sendFlashMessage("Pack with ID ".$params["id"]." does not exist.", "error");
			$this->redirect("/404");
		}
		else if($this->data["pack"]->getDeletedAt()){
			$this->sendFlashMessage("Pack with ID ".$params["id"]." was deleted on ".$this->data["pack"]->getDeletedAt("j M o").".", "error");
			$this->redirect("/404");	
		}
		return true;
	}

	protected function loadPermission(){
		$this->data["permission"] = 0;
		if($this->data["loggedUser"]){
			$permission = PackPermissionQuery::create()->filterByPack($this->data["pack"])->filterByUser($this->data["loggedUser"])->findOne();
			$this->data["permission"] = !$permission ?: $permission->getValue();
			if($this->data["loggedUser"]->getId() == $this->data["pack"]->getOwnerId())
				$this->data["permission"] = 4;
		}
		return true;
	}

}