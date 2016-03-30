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
		$this->addBefore("delete", array("userLogged", "load", "loadPermission", "isOwner"));
		$this->addBefore("show", array("userLogged", "load", "loadPermission"));
		$this->addBefore("update", array("userLogged", "load", "loadPermission", "isOwner"));
		$this->addBefore("addUsers", array("userLogged", "load", "loadPermission", "isOwner"));
		$this->addBefore("removeUser", array("userLogged", "load", "loadPermission", "isOwner"));
		$this->addBefore("newGroup", array("userLogged"));
		$this->addBefore("usersList", array("userLogged", "load"));

		$this->data["permission"] = 0;

	}

	/*
	*	View methods
	*/

	protected function show(){
		if(!$this->data["permission"]){
			$this->sendFlashMessage("You dont have permission to view this group.", "error");
			$this->redirect("/");
		}
		$this->viewFile($this->template);
	}

	protected function newGroup(){	
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
			$group->setDescription(isset($_POST["description"]) ? $_POST["description"] : null);


			if(!$group->save()){
				$failures = $group->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("Group has not been created. ".$failure->getMessage(), "error");
					}
				}
				$this->redirect("/group/new");
			}

			if(isset($_POST["users"])){
				$users = array_map("trim", explode(",", $_POST["users"]));
				foreach($users as $username){
					$u = UserQuery::create()->findOneByUsername($username);
					if($u){
						if($u == $this->data["loggedUser"]){
							$this->sendFlashMessage("You can not add yourself to group.", "error");
							continue;
						}
						$userGroup = UserGroupQuery::create()->filterByUser($u)->filterByGroup($group)->findOne();
						if($userGroup){
							$this->sendFlashMessage("User " . $username . " is already in this group.", "error");
							continue;	
						}
						$group->addUser($u);
					}
					else
						$this->sendFlashMessage("User " . $username . " does not exist.", "error");
				}
				$group->save();
			}

			$this->sendFlashMessage("Group has been successfully created.", "success");
			$this->redirect("/group/".$group->getId());
		}
		setHTTPStatusCode("400");
	}

	protected function update(){
		setContentType("json");

		$group = $this->data["group"];
		if(isset($_POST["name"]))
			$group->setName($_POST["name"]);
		if(isset($_POST["description"]))
			$group->setDescription($_POST["description"]);

		if(!$group->save()){
			$failures = $group->getValidationFailures();
			if(count($failures) > 0){
				$this->setStatus("error");
				foreach($failures as $failure){
					$this->sendFlashMessage("Group data has not been changeg. ".$failure->getMessage(), "error");
				}
			}
		}
		else{
			$this->data["response"]["data"]["description"] = $this->data["group"]->getDescription();
			$this->data["response"]["data"]["name"] = $this->data["group"]->getName();
		}
		

		$this->viewString(json_encode($this->data["response"]));
	}

	protected function addUsers($users){
		setContentType("json");
		if(isset($_POST["username"])){
			foreach($_POST["username"] as $username){
				$u = UserQuery::create()->findOneByUsername($username);
				if($u){
					if($u == $this->data["loggedUser"]){
						$this->data["response"]["messages"][] = "You can not add yourself to group.";
						continue;
					}
					$userGroup = UserGroupQuery::create()->filterByUser($u)->filterByGroup($this->data["group"])->findOne();
					if($userGroup){
						$this->data["response"]["messages"][] = "User " . $username . " is already in this group.";
						continue;	
					}
					$this->data["group"]->addUser($u);
					$this->data["group"]->save();
				}
				else
					$response["messages"][] = "User " . $username . " does not exist.";
			}
		}

		$this->viewString(json_encode($this->data["response"]));
	}

	protected function removeUser($params){
		setContentType("json");
		$response["messages"] = array();
		if(isset($params["username"])){
			$user = UserQuery::create()->findOneByUsername($params["username"]);
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
		
		$this->viewString(json_encode($response));
	}

	protected function usersList(){
		if($this->data["group"]->getOwnerId() != $this->data["loggedUser"]->getId() && !UserGroupQuery::create()->filterByUser($this->data["loggedUser"])->filterByGroup($this->data["group"])->count()){
			$this->redirect("/");
		}
		if($this->data["group"]->getOwnerId() == $this->data["loggedUser"]->getId()){
			$this->data["permission"] = 2;
		}
		$this->view();
	}

	protected function delete(){
		$this->data["group"]->delete();
		$this->sendFlashMessage("Group has been successfuly deleted.", "info");
		$this->redirect("/profile");	
	}

	protected function validateOne(){
		setContentType("json");
		$group = new Group();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "name"){
				$group->setName($_POST["name"]);
				$g = GroupQuery::create()->filterByOwner($this->data["loggedUser"])->filterByName($_POST["name"])->findOne();
				if($g){
					$response["error"] = array(
						"name" => "name",
						"message" => "You can not have two groups with the same name."
					);
					$this->viewString(json_encode($response));
					return;
				}
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
		return true;
	}

	protected function loadPermission(){
		if($this->data["group"]->getOwnerId() == $this->data["loggedUser"]->getId()){
			$this->data["permission"] = 2;
			return;
		}
		if(UserGroupQuery::create()->filterByUser($this->data["loggedUser"])->filterByGroup($this->data["group"])->count()){
			$this->data["permission"] = 1;
			return;
		}
		return true;
	}

	protected function isOwner(){
		if($this->data["permission"] != 2){	
			$this->sendFlashMessage("You must be owner of this group.", "error");
			$this->redirect("/");	
		}
		return true;
	}

}