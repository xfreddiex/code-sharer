<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\Pack;
use Models\PackQuery;
use Models\UserPermission;
use Models\UserPermissionQuery;
use Models\GroupPermission;
use Models\GroupPermissionQuery;

class PackController extends BaseController{

	public function __construct(){
		parent::__construct();
	}

	protected function show($params){
		$this->data["pack"] = PackQuery::create()->useOwnerQuery()->filterByUsername($params["username"])->endUse()->filterByName($params["packname"])->findOne();
		if($this->data["pack"]){
			$this->viewFile($this->template);
		}
	}

	protected function newPack(){	
		if($this->data["loggedUser"]){
			$this->viewFile($this->template);	
		}
		else{
			$this->sendFlashMessage("To add new pack you must be signed in.", "error");
			redirect("/");
		}
	}

	protected function createPack(){
		if($this->data["loggedUser"]){
			if(isset($_POST["name"])){
				$pack = new Pack();
				$pack->setName($_POST["name"]);
				$pack->setOwner($this->data["loggedUser"]);
				if(isset($_POST["private"]))
					$pack->setPrivate(true);
				else
					$pack->setPrivate(false);
				if(isset($_POST["description"]))
					$pack->setDescription($_POST["description"]);
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
					}
				}
				else
					$this->sendFlashMessage("You have successfuly created new pack.", "success");
				redirect($this->data["referersURI"]);
			}
			else
				setHTTPStatusCode("400");
		}
		else{
			$this->sendFlashMessage("To add a pack you must be signed in.", "error");
			redirect("/");
		}
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

}