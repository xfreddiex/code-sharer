<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\UserGroup;
use Models\UserGroupQuery;

class GroupController extends BaseController{

	public function __construct(){
		parent::__construct();

		$this->addBefore("create", array("userLogged"));
		$this->addBefore("delete", array("userLogged", "load", "isUserOwner"));
		$this->addBefore("show", array("load"));
		$this->addBefore("update", array("userLogged", "load", "isUserOwner"));
		$this->addBefore("addUsers", array("userLogged", "load", "isUserOwner"));
		$this->addBefore("removeUsers", array("userLogged", "load", "isUserOwner"));
	}

	/*
	*	View methods
	*/

	protected function show(){
		if($this->data["group"]->getPrivate()){
			if(!$this->data["loggedUser"] || ($this->data["group"]->getOwnerId() != $this->data["loggedUser"]->getId() && !UserGroupQuery::create()->filterByUser($this->data["loggedUser"])->filterByGroup($this->data["group"])->count())){
				$this->sendFlashMessage("You dont have permission to view this group.", "error");
				$this->redirect("/");
			}
		}
		$this->viewFile($this->template);
	}

	/*
	*	Control methods
	*/

	protected function create(){
		if(isset($_POST["name"])){
			$group = GroupQuery::create()->filterByOwner($this->data["loggedUser"])->filterByName($_POST["name"])->findOne();
			if($group){
				$this->sendFlashMessage("You already have a group with this name.", "error");
				$this->redirect($this->data["referersURI"]);
			}
			$group = new Group();
			$group->setName($_POST["name"]);
			$group->setOwner($this->data["loggedUser"]);
			$group->setPrivate(isset($_POST["private"]));
			$group->setDescription(isset($_POST["description"]) ? $_POST["description"] : null);

			if(!$group->save()){
				$failures = $group->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("Group has not been created. ".$failure->getMessage(), "error");
					}
				}
				$this->redirect($this->data["referersURI"]);
			}	

			$this->sendFlashMessage("Group has been successfully created.", "success");
			$this->redirect("/group/".$group->getId());
		}
		setHTTPStatusCode("400");
	}

	protected function update(){
		$group = $this->data["group"];
		if(isset($_POST["name"]))
			$group->setName($_POST["name"]);
		if(isset($_POST["description"]))
			$group->setDescription($_POST["description"]);
		if(isset($_POST["newEmail"]) && $_POST["newEmail"])
			$group->setEmail($_POST["newEmail"]);
		if(isset($_POST["newPassword"]) && $_POST["newPassword"])
			$group->setPassword($_POST["newPassword"]);

		if($group->save()){
			$failures = $group->getValidationFailures();
			if(count($failures) > 0){
				foreach($failures as $failure){
					$this->sendFlashMessage("User data has not been changeg. ".$failure->getMessage(), "error");
				}
			}
		}
		else
			$this->sendFlashMessage("User data has been successfuly updated.", "success");
		$this->redirect($this->data["referersURI"]);	
	}

	protected function addUsers(){
		setContentType("json");
		$response["messages"] = array();
		if(isset($_POST["user"])){
			foreach($_POST["user"] as $user){
				if(!isset($user["username"]))
					continue;
				$u = UserQuery::create()->findOneByUsername($user["username"]);
				if($u){
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
		/*
		if(isset($_POST["group"])){
			foreach($_POST["group"] as $group){
				if(!isset($group["id"]))
					continue;
				$g = UserQuery::create()->filterByOwner($this->data["loggedUser"])->filterById($group["id"])->findOne();
				if($g){
					$users->getUsers();
					foreach($users as $user) {
						$userGroup = UserGroupQuery::create()->filterByUser($user)->filterByGroup($this->data["group"]);
						if($userGroup){
							$response["messages"][] = "User " . $user["username"] . " is already in this group.";
							continue;	
						}
						$userGroup = new UserGroup();
						$userGroup->setUser($user);
						$userGroup->setGroup($this->data["group"]);	
						$userGroup->save();
					}
				}
				else
					$response["messages"][] = "You can add groups owned only by you. Group with ID ". $group["id"] ." is not your.";
			}
		}*/

		$this->viewString(json_encode($response));
	}

	protected function removeUsers(){
		setContentType("json");
		$response["messages"] = array();
		if(isset($_POST["user"])){
			foreach($_POST["user"] as $user){
				if(!isset($user["username"]))
					continue;
				$user = UserQuery::create()->findOneByUsername($user["username"]);
				if($user){
					$userGroup = UserGroupQuery::create()->filterByUser($user)->filterByGroup($this->data["group"])->findOne();
					if($userGroup)
						$userGroup->delete();
					else
						$response["messages"][] = "User " . $user["username"] . " is not in group ".$this->data["group"]->getId().".";
				}
				else
					$response["messages"][] = "User " . $user["username"] . " does not exist.";
			}
		}

		$this->viewString(json_encode($response));
	}

	protected function delete(){
		$this->data["group"]->setDeletedAt(time());
		$this->data["group"]->save();
		$this->sendFlashMessage("Group has been successfuly deleted.", "info");
		$this->redirect($this->data["referersURI"]);	
	}

	protected function validateOne(){
		setContentType("json");
		$group = new Group();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "name"){
				$group->setName($_POST["name"]);
			}
			else if($given[0] == "description"){
				$group->setDescription($_POST["description"]);
			}
			else{
				setHTTPStatusCode("400");
				return;
			}
			if(!$group->validate()){
				foreach($group->getValidationFailures() as $failure){
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

	/*
	*	Before methods
	*/

	protected function load($args){
		$params = $args["params"];
		$this->data["group"] = GroupQuery::create()->findPK($params["id"]);
		if(!$this->data["group"]){
			$this->sendFlashMessage("Group with ID ".$params["id"]." does not exist.", "error");
			$this->redirect("/404");
		}
		else if($this->data["group"]->getDeletedAt()){
			$this->sendFlashMessage("Group with ID ".$params["id"]." was deleted on ".$this->data["group"]->getDeletedAt("j M o").".", "error");
			$this->redirect("/404");	
		}
		return true;
	}

	protected function isUserOwner(){
		if($this->data["group"]->getOwnerId() != $this->data["loggedUser"]->getId()){
			$this->sendFlashMessage("You dont have permission to modify this group.", "error");
			$this->redirect("/");
		}
		return true;
	}
}