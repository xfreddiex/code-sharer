<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;

class UserController extends BaseController{

	public function __construct(){
		parent::__construct();
		$this->data["userAuthorized"] = false;
		$this->addBefore("update", array("userLogged", "userAuthorized"));
		$this->addBefore("profile", array("userLogged"));
		$this->addBefore("settings", array("userLogged"));
		$this->addBefore("updateAvatar", array("userLogged"));
	}

	protected function show($params){
		$this->data["user"] = UserQuery::create()->findOneByUsername($params["username"]);
		if($this->data["user"] == $this->data["loggedUser"])
			$this->redirect("/profile");
		if($this->data["user"])
			$this->viewFile($this->template);
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
			$this->redirect("/");
		}
	}

	protected function profile(){
		$this->viewFile($this->template);	
	}

	protected function settings(){
		$this->viewFile($this->template);	
	}

	protected function delete(){
		
	}

	protected function update(){
		$user = $this->data["loggedUser"];
		if(isset($_POST["newName"]))
			$user->setName($_POST["newName"]);
		if(isset($_POST["newSurname"]))
			$user->setSurname($_POST["newSurname"]);
		if(isset($_POST["newEmail"]) && $_POST["newEmail"])
			$user->setEmail($_POST["newEmail"]);
		if(isset($_POST["newPassword"]) && $_POST["newPassword"])
			$user->setPassword($_POST["newPassword"]);

		if($user->save()){
    		$failures = $user->getValidationFailures();
			if(count($failures) > 0){
				foreach($failures as $failure){
					$this->sendFlashMessage("User data has not been changeg. ".$failure->getMessage(), "error");
				}
			}
		}
		else
			$this->sendFlashMessage("User data has been successfuly updated.", "success");
	}

	protected function updateAvatar(){
		if(isset($_POST["newAvatar"])){
			$data = explode(',', $_POST["newAvatar"]);
			if(count($data) == 2 && $data[0] == "data:image/png;base64" && base64_decode($data[1])){
				$img = imagecreatefromstring(base64_decode($data[1]));
				$nameToDelete = $this->data["loggedUser"]->getAvatarPath();
				$name = md5(uniqid()).".png";
				$this->data["loggedUser"]->setAvatarPath($name)->save();

				$dir = "Includes/images/avatars/250x250/";
				if(is_file($dir.$nameToDelete))
					unlink($dir.$nameToDelete);
				imagepng(resizeImg($img, 250, 250), $dir.$name);
		
				$dir = "Includes/images/avatars/40x40/";
				if(is_file($dir.$nameToDelete))
					unlink($dir.$nameToDelete);
				imagepng(resizeImg($img, 40, 40), $dir.$name);

				$this->sendFlashMessage("Your avatar has been successfuly changed. ", "success");
				$this->redirect($this->data["referersURI"]);
			}
			else
				setHTTPStatusCode("400");
		}	
		else		
			setHTTPStatusCode("400");
	}

	protected function signUp(){
		if(isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])){
			$user = new User();
			$user->fromArray(array(
				"Email" => $_POST["email"], 
				"Username" => $_POST["username"], 
				"Password" => $_POST["password"]
			));
			
			if($user->save()){
    			$failures = $user->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("You have not been signed up. ".$failure->getMessage(), "error");
					}
				}
			}
			else
				$this->sendFlashMessage("You have been successfuly signed up.", "success");
			$this->redirect("/");
		}
		else
			setHTTPStatusCode("400");
	}

	protected function signIn(){	
		if(isset($_POST["username"]) && isset($_POST["password"])){
			$user = UserQuery::create()
				->findOneByUsername($_POST["username"]);
			if(!$user){
				$this->sendFlashMessage("You have not been signed in. User does not exist.", "error");
			}
			else if($user->checkPassword($_POST["password"])){
				$_SESSION["userId"] = $user->getId();
				if(isset($_POST["rememberMe"])){
					if(isset($_COOKIE["identityId"])){
						$identity = IdentityQuery::create()
							->filterById($_COOKIE["identityId"])
							->delete();
					}
					$token = generateRandomString(32);
					$identity = new Identity();
					$identity->setToken($token)
						->setUser($user)
						->setExpiresAt(time() + (86400 * 14))
						->save();
					setcookie("identityId", $identity->getId(), time() + (86400 * 120));
					setcookie("identityToken", $token, time() + (86400 * 120));
				}
			}
			else
				$this->sendFlashMessage("You have not been signed in. You entered wrong password.", "error");
			$this->redirect($this->data["referersURI"]);
		}
		else
			setHTTPStatusCode("400");
	}

	protected function signOut(){
		unset($_SESSION["userId"]);
		if(isset($_COOKIE["identityId"])){
			$identity = IdentityQuery::create()->findPK($_COOKIE["identityId"]);
			if($identity){
				$identity->delete();
				setcookie("identityId", "", time() - 86400);
				setcookie("identityToken", "", time() - 86400);
			}
		}
		$this->redirect("/");
	}

	protected function validateOne(){
		setContentType("json");
		$user = new User();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "username"){
				$user->setUsername($_POST["username"]);
			}
			else if($given[0] == "password"){
				$user->setPassword($_POST["password"]);
			}
			else if($given[0] == "email"){
				$user->setEmail($_POST["email"]);
			}
			else if($given[0] == "name"){
				$user->setName($_POST["name"]);
			}
			else if($given[0] == "surname"){
				$user->setSurname($_POST["surname"]);
			}
			else{
				setHTTPStatusCode("400");
				return;
			}
			if(!$user->validate()){
				foreach($user->getValidationFailures() as $failure){
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