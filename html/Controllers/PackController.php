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
use Models\File;
use Models\FileQuery;
use Models\Comment;
use Models\CommentQuery;

class PackController extends BaseController{

	public function __construct(){
		parent::__construct();

		$this->addBefore("new", array("userLogged"));
		$this->addBefore("show", array("load", "loadPermission"));
		$this->addBefore("create", array("userLogged"));
		$this->addBefore("createView", array("userLogged"));
		$this->addBefore("updatePermissions", array("userLogged", "userAuthorized", "load", "loadPermission"));
		$this->addBefore("delete", array("userLogged", "userAuthorized", "load", "loadPermission"));
		$this->addBefore("addFile", array("userLogged", "load", "loadPermission"));
		$this->addBefore("addComment", array("userLogged", "load"));
		$this->addBefore("deleteFile", array("userLogged", "load", "loadPermission", "loadFile"));

		$this->data["fileExtensionAccept"] = array(
			"txt",
			"php",
			"html",
			"css",
			"scss",
			"phtml",
			"js",
			"xml",
			"json",
			"",
			"",
			"",
			""
		);
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

	protected function newPack(){	
		$this->viewFile($this->template);
	}

	protected function settings(){	
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
						$this->sendFlashMessage("Your pack has not been created. ".$failure->getMessage(), "error");
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
		if($this->data["permission"] != 3 ){
			$this->sendFlashMessage("You have not permission to delete pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}
		$this->data["pack"]->setDeletedAt(time());
		$this->data["pack"]->save();
		$this->sendFlashMessage("Pack has been successfuly deleted.", "info");
		$this->redirect("/profile");	
	}

	protected function addFile(){
		if($this->data["permission"] < 2 ){
			$this->sendFlashMessage("You have not permission to modify pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}

		if(isset($_FILES["file"])){
			
			$fileName = $_FILES['file']['name'];
			$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
			$fileSize = $_FILES["file"]["size"];
			$fileTmpName  = $_FILES['file']['tmp_name'];
			$fileType = $_FILES['file']['type'];

			if(!in_array($fileExtension, $this->data["fileExtensionAccept"])){
				$this->sendFlashMessage('"'.$fileExtension .'" file extension is not accepted.', "error");
				$this->redirect("/pack/".$this->data["pack"]->getId());	
			}

			if($fileSize > 281474943156225){
				$this->sendFlashMessage("File is too large.", "error");
				$this->redirect("/pack/".$this->data["pack"]->getId());	
			}

			$file = FileQuery::create()->filterByPack($this->data["pack"])->filterByName($fileName)->findOne();
			if($file){
				$this->sendFlashMessage('File "'.$fileName.'" already exists in this pack.', "error");
				$this->redirect("/pack/".$this->data["pack"]->getId());		
			}

			$file = new File();
			
			$f = fopen($fileTmpName, 'r');
			$content = fread($f, filesize($fileTmpName));
			fclose($f);
			
			$file->setName($fileName);
			if(isset($_POST["description"]))
				$file->setDescription($_POST["description"]);
			$file->setType($fileType);
			$file->setSize($fileSize);
			$file->setContent($content);
			$file->setPack($this->data["pack"]);

			if($file->save() <= 0){
    			$failures = $file->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("File has not been added. ".$failure->getMessage(), "error");
					}
					$this->redirect("/pack/".$this->data["pack"]->getId());
				}
			}
			
			$this->sendFlashMessage("You have successfuly created new pack.", "success");
			$this->redirect("/pack/".$this->data["pack"]->getId()."/".$file->getName());
		}
		else
			setHTTPStatusCode("400");		
	}

	protected function deleteFile(){
		if($this->data["permission"] < 2 ){
			$this->sendFlashMessage("You have not permission to delete file with ID ".$this->data["file"]->getId().".", "error");
			$this->redirect("/");
		}
		$this->data["file"]->setDeletedAt(time());
		$this->data["file"]->save();
		$this->sendFlashMessage("File has been successfuly deleted.", "info");
		$this->redirect("/pack/".$this->data["pack"]->getId());
	}

	protected function updateFile(){
		
	}

	protected function addComment(){
		if(isset($_POST["text"])){
			$comment = new Comment();
			$comment->setText($_POST["text"]);
			$comment->setUser($this->data["loggedUser"]);
			$comment->setPack($this->data["pack"]);

			if($comment->save() <= 0){
    			$failures = $comment->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("Your comment has not been added. ".$failure->getMessage(), "error");
					}
					$this->redirect("/pack/".$this->data["pack"]->getId());
				}
			}

			$this->sendFlashMessage("Your comment has been added.", "success");
			$this->redirect("/pack/".$this->data["pack"]->getId());
		}
		else
			setHTTPStatusCode("400");
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
		if($this->data["permission"] != 3 ){
			$this->sendFlashMessage("You have not permission to change pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/pack/".$this->data["pack"]->getId());
		}
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
					$this->sendFlashMessage("Pack data has not been changed. ".$failure->getMessage(), "error");
				}
			}
		}
		else
			$this->sendFlashMessage("Pack data has been successfuly updated.", "success");
		$this->redirect("/pack/".$pack->getId());
	}

	protected function updatePermissions(){
		setContentType("json");

		if($this->data["permission"] != 3){
			$response["messages"][] = "You do not have permission to update pack with ID " . $this->data["pack"]->getId() . ".";
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
					if(isset($user["edit"]))
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
					if(isset($group["edit"]))
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
			if($this->data["loggedUser"]->getId() == $this->data["pack"]->getOwnerId()){
				$this->data["permission"] = 3;
				return true;
			}

			$permission = PackPermissionQuery::create()->filterByPack($this->data["pack"])->filterByUser($this->data["loggedUser"])->findOne();
			if($permission){
				$this->data["permission"] = $permission->getValue();
				return true;
			}

			$permission = PackPermissionQuery::create()->filterByPack($this->data["pack"])->useGroupQuery()->useUserGroupQuery()->filterByUser($this->data["loggedUser"])->endUse()->endUse()->findOne();
			if($permission){
				$this->data["permission"] = $permission->getValue();
				return true;
			}
			
		}
		return true;
	}

	protected function loadFile($args){
		$params = $args["params"];
		$this->data["file"] = FileQuery::create()->filterByPack($this->data["pack"])->findOneByName($params["name"]);
		if(!$this->data["file"]){
			$this->sendFlashMessage("File ".$params["name"]." does not exist in this pack.", "error");
			$this->redirect("/404");
		}
		else if($this->data["file"]->getDeletedAt()){
			$this->sendFlashMessage("File with ID ".$this->data["file"]->getId()." was deleted on ".$this->data["file"]->getDeletedAt("j M o").".", "error");
			$this->redirect("/404");	
		}
		return true;
	}

}