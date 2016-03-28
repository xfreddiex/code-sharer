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
use Propel\Runtime\ActiveQuery\Criteria;

class PackController extends BaseController{

	public function __construct(){
		parent::__construct();

		$this->addBefore("new", array("userLogged"));
		$this->addBefore("show", array("load", "loadPermission", "loadComments"));
		$this->addBefore("create", array("userLogged"));
		$this->addBefore("createView", array("userLogged"));
		$this->addBefore("updatePermissions", array("userLogged", "load", "loadPermission"));
		$this->addBefore("delete", array("userLogged", "userAuthorized", "load", "loadPermission"));
		$this->addBefore("addFiles", array("userLogged", "load", "loadPermission"));
		$this->addBefore("addComment", array("userLogged", "load", "loadPermission"));
		$this->addBefore("deleteComment", array("userLogged", "load", "loadPermission"));
		$this->addBefore("comments", array("load", "loadPermission", "loadComments"));
		$this->addBefore("deleteFile", array("userLogged", "load", "loadPermission", "loadFile"));
		$this->addBefore("showFile", array("load", "loadPermission", "loadFile"));
		$this->addBefore("getFileContent", array("load", "loadPermission", "loadFile"));
		$this->addBefore("filesList", array("load", "loadPermission"));
		$this->addBefore("settings", array("userLogged", "load", "loadPermission"));
		$this->addBefore("update", array("userLogged", "load", "loadPermission"));
		$this->addBefore("updateFile", array("userLogged", "load", "loadPermission", "loadFile"));

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
			"yaml",
			"yml",
			"htm",
			"xml",
			"c",
			"cpp",
			"h",
			"class",
			"dist"
		);
	}

	/*
	*	View methods
	*/

	protected function show(){
		if(!$this->data["permission"]){
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

	protected function showFile(){	
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
		$this->data["pack"]->delete();
		$this->sendFlashMessage("Pack has been successfuly deleted.", "info");
		$this->redirect("/profile");	
	}

	protected function filesList(){
		if(!$this->data["permission"]){
			$this->redirect("/");
		}	
		else{
			$this->view();
		}
	}

	protected function addFiles(){
		if($this->data["permission"] < 2 ){
			$this->sendFlashMessage("You have not permission to modify pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}

		if(isset($_FILES["files"])){

			for($i = 0; $i < count($_FILES["files"]["name"]); $i++){

				$fileName = $_FILES["files"]["name"][$i];
				$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
				$fileSize = $_FILES["files"]["size"][$i];
				$fileTmpName  = $_FILES["files"]["tmp_name"][$i];
				$fileType = $_FILES["files"]["type"][$i];

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
			}
	
			$this->sendFlashMessage("You have successfuly added new files.", "success");
			$this->redirect("/pack/".$this->data["pack"]->getId());
		}
		else
			setHTTPStatusCode("400");		
	}

	protected function deleteFile(){
		setContentType("json");
		if($this->data["permission"] < 2 ){
			$this->sendFlashMessage("You have not permission to delete file with ID ".$this->data["file"]->getId().".", "error");
		}
		else{
			$this->data["file"]->delete();
		}
		$this->viewString(json_encode($this->data["response"]));
	}

	protected function updateFile(){
		setContentType("json");

		if($this->data["permission"] < 2 ){
			$this->sendFlashMessage("You have not permission to change file ".$this->data["file"]->getName(), "error");
			$this->redirect("/");
		}
		$file = $this->data["file"];
		if(isset($_POST["description"]))
			$file->setDescription($_POST["description"]);
		if(isset($_POST["content"]))
			$file->setContent($_POST["content"]);		

		if(!$file->save()){
			$failures = $file->getValidationFailures();
			if(count($failures) > 0){
				$this->setStatus("error");
				foreach($failures as $failure){
					$this->sendFlashMessage("Pack data has not been changed. ".$failure->getMessage(), "error");
				}
			}
		}
		else{
			$this->data["response"]["data"]["description"] = htmlspecialchars($file->getDescription());
			$this->data["response"]["data"]["content"] = $file->getContent();
		}
		
		$this->viewString(json_encode($this->data["response"]));
	}

	protected function getFileContent(){
		$content = $this->data["file"]->getContent();
		header('Content-Disposition: attachment; filename="'.$this->data["file"]->getName().'"');
		header('Content-Type: '.$this->data["file"]->getType());
		header('Content-Length: ' . strlen($content));
		header('Connection: close');
		echo $content;
	}

	protected function addComment(){
		if(!$this->data["permission"]){
			$this->sendFlashMessage("You do not have permission add comments to pack with ID " . $this->data["pack"]->getId() . ".", "error");
		}
		else{

			if(isset($_POST["text"])){
				$comment = new Comment();
				$comment->setText($_POST["text"]);
				$comment->setUser($this->data["loggedUser"]);
				$comment->setPack($this->data["pack"]);

				if($comment->save() <= 0){
	    			$failures = $comment->getValidationFailures();
	    			var_dump($failures);exit;
	    			setStatus("error");
					if(count($failures) > 0){
						foreach($failures as $failure){
							$this->sendFlashMessage("Your comment has not been added. ".$failure->getMessage(), "error");
						}
					}
				}
			}
			else
				setHTTPStatusCode("400");

			$this->viewString(json_encode($this->data["response"]));
		}
	}

	protected function deleteComment($params){
		if($this->data["permission"] != 3 ){
			$this->sendFlashMessage("You have not permission to delete pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}

		$comment = CommentQuery::create()->findPK($params["comment_id"]);
		if($comment)
			$comment->delete();	

		$this->viewString(json_encode($this->data["response"]));
	}

	protected function comments(){
		if(!$this->data["permission"]){
			$this->sendFlashMessage("You do not have permission to view comments of pack with ID " . $this->data["pack"]->getId() . ".", "error");
			$this->redirect("/");
		}

		$this->view();
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
	protected function fileValidateOne(){
		setContentType("json");
		$file = new File();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "description"){
				$file->setDescription($_POST["description"]);
			}

			if(!$file->validate()){
				foreach($file->getValidationFailures() as $failure){
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
	}

	protected function commentValidateOne(){
		setContentType("json");
		$comment = new Comment();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "text"){
				$comment->setText($_POST["text"]);
			}

			if(!$comment->validate()){
				foreach($comment->getValidationFailures() as $failure){
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
	}

	protected function update(){
		setContentType("json");

		if($this->data["permission"] != 3 ){
			$this->sendFlashMessage("You have not permission to change pack with ID ".$this->data["pack"]->getId().".", "error");
			$this->redirect("/");
		}
		$pack = $this->data["pack"];
		if(isset($_POST["name"]))
			$pack->setName($_POST["name"]);
		if(isset($_POST["description"]))
			$pack->setDescription($_POST["description"]);
		$pack->setPrivate(isset($_POST["private"]));

		if(!$pack->save()){
    		$failures = $pack->getValidationFailures();
			if(count($failures) > 0){
				$this->setStatus("error");
				foreach($failures as $failure){
					$this->sendFlashMessage("Pack data has not been changed. ".$failure->getMessage(), "error");
				}
			}
		}
		else{
			$this->data["response"]["data"]["description"] = $pack->getDescription();
			$this->data["response"]["data"]["name"] = $pack->getName();
			$this->data["response"]["data"]["private"] = $pack->getPrivate();
		}
		
		$this->viewString(json_encode($this->data["response"]));
	}

	protected function updatePermissions(){

		if($this->data["permission"] != 3){
			$this->sendFlashMessage("You do not have permission to update pack with ID " . $this->data["pack"]->getId() . ".", "error");
			$this->redirect("/");
		}

		if(isset($_POST["user"])){
			foreach($_POST["user"] as $user){
				if(!isset($user["username"]) || $user["username"] == "")
					continue;
				$u = UserQuery::create()->findOneByUsername($user["username"]);
				if($u){
					if($u == $this->data["loggedUser"]){
						$this->sendFlashMessage("You can not add permission to yourself.", "error");
						continue;
					}
					$permission = PackPermissionQuery::create()->filterByUser($u)->filterByPack($this->data["pack"])->findOneOrCreate();
					if(isset($user["permission"])){
						$permission->setValue($user["permission"]);
					}
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setUser($u);
					$permission->save();
				}
				else
					$this->sendFlashMessage("User " . $user["username"] . " does not exist.", "error");
			}
		}

		if(isset($_POST["group"])){
			foreach($_POST["group"] as $group){
				if(!isset($group["name"]) || $group["name"] == "")
					continue;
				$g = GroupQuery::create()->filterByOwner($this->data["loggedUser"])->filterByName($group["name"])->findOne();
				if($g && $g->getOwnerId() == $this->data["loggedUser"]->getId()){
					$permission = PackPermissionQuery::create()->filterByGroup($g)->filterByPack($this->data["pack"])->findOneOrCreate();
					if(isset($group["permission"]))
						$permission->setValue($group["permission"]);
					else{
						$permission->delete();
						continue;
					}
					$permission->setPack($this->data["pack"]);
					$permission->setGroup($g);
					$permission->save();
				}
				else
					$this->sendFlashMessage("Group with name" . $group["name"] . " is not your or does not exist.", "error");
			}
		}

		$this->sendFlashMessage("Permissions was updated.", "info");

		$this->redirect("/pack/" . $this->data["pack"]->getId() . "/settings");
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

			$permissionUser = PackPermissionQuery::create()->filterByPack($this->data["pack"])->filterByUser($this->data["loggedUser"])->findOne();
			$permissionGroup = PackPermissionQuery::create()->filterByPack($this->data["pack"])->useGroupQuery()->filterByUser($this->data["loggedUser"])->endUse()->orderByValue(Criteria::DESC)->findOne();
			
			if($permissionUser)
				$this->data["permission"] = $permissionUser->getValue();
			else if($permissionGroup)
				$this->data["permission"] = $permissionGroup->getValue();

			if(!$this->data["pack"]->getPrivate() && !$this->data["permission"]){
				$this->data["permission"] = 1;
			}		
			return true;
		}
		if(!$this->data["pack"]->getPrivate()){
			$this->data["permission"] = 1;
			return true;
		}
		return true;
	}

	protected function loadFile($args){
		$params = $args["params"];
		$this->data["file"] = FileQuery::create()->filterByPack($this->data["pack"])->findOneByName(urldecode($params["name"]));
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

	protected function loadComments(){
		$page = 1;
		$perPage = 10;

		if(isset($_GET["page"]) && $_GET["page"] > 0)
			$page = $_GET["page"];
		if(isset($_GET["perPage"]) && $_GET["perPage"] > 0)
			$perPage = $_GET["perPage"];
		$this->data["comments"] = CommentQuery::create()->filterByPack($this->data["pack"])->lastCreatedFirst()->paginate($page, $perPage);
	}

}