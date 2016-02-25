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
		$this->data["userLogged"] = false;
		$this->data["userAuthorized"] = false;
		$this->data["avatarDir250"] = "Includes/images/avatars/250x250/";
		$this->data["avatarDir40"] = "Includes/images/avatars/40x40/";
		$this->addBefore("checkAuthorization", array("update"));
	}

	protected function profile(){
		if($this->data["userLogged"]){
			$this->data["username"] = $this->data["user"]->getUsername();
			$this->data["name"] = $this->data["user"]->getName();
			$this->data["surname"] = $this->data["user"]->getSurname();
			$this->data["email"] = $this->data["user"]->getEmail();
			$this->data["avatarPath"] = $this->data["avatarDir250"].($this->data["user"]->getAvatarPath() ? $this->data["user"]->getAvatarPath() : "default.png");		
			$this->viewFile($this->template);	
		}
		else{
			$this->sendFlashMessage("To view your profile you must be signed in.", "error");
			redirect("/");
		}
	}

	protected function settings(){

	}

	protected function delete(){
		
	}

	protected function update(){
		if($this->data["userAuthorized"]){
			$user = $this->data["user"];
			if(isset($_POST["newName"]))
				$user->setName($_POST["newName"]);
			if(isset($_POST["newSurname"]))
				$user->setSurname($_POST["newSurname"]);
			if(isset($_POST["newEmail"]))
				$user->setEmail($_POST["newEmail"]);
			if(isset($_POST["newPassword"]))
				$user->setPassword($_POST["newPassword"]);

			if($user->save() <= 0){
    			$failures = $user->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("User data has not been changeg. ".$failure->getMessage(), "error");
					}
				}
			}
			else
				$this->sendFlashMessage("User data has been successfuly updated.", "success");
			redirect($this->data["referersURI"]);
		}
		else
			$this->sendFlashMessage("You have not been authorized to change user data.", "error");
	}

	protected function updateAvatar(){
		if(isset($_POST["newAvatar"]) && $this->data["userLogged"]){
			$data = explode(',', $_POST["newAvatar"]);
			if(count($data) == 2 && $data[0] == "data:image/png;base64" && base64_decode($data[1])){
				$dir = "Includes/images/avatars/250x250/";
				$img = imagecreatefromstring(base64_decode($data[1]));
				$name = $this->data["user"]->getAvatarPath();
				if(!$name){
					$name = md5(uniqid()).".png";
					$this->data["user"]->setAvatarPath($name)->save();
				}
				$path = $dir.$name;
				imagepng($img, $path);
		
				$dir = "Includes/images/avatars/40x40/";
				$path = $dir.$name;
				imagepng(resizeImg($img, 40, 40), $path);
				
				$this->sendFlashMessage("Your avatar has been successfuly changed. ", "success");
				redirect($this->data["referersURI"]);
			}
			else
				setHTTPStatusCode("400");
		}	
		else		
			setHTTPStatusCode("400");
	}

	protected function checkAutorizatition(){
		$_POST["userAuthorized"] = ($this->data["userLogged"] && isset($_POST["password"]) && $this->data["user"]->checkPassword($_POST["password"]));
	}

	protected function signUp(){
		if(isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])){
			$user = new User();
			$user->fromArray(array(
				"Email" => $_POST["email"], 
				"Username" => $_POST["username"], 
				"Password" => $_POST["password"]
			));
			if($user->save() <= 0){
    			$failures = $user->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("You have not been signed up. ".$failure->getMessage(), "error");
					}
				}
			}
			else
				$this->sendFlashMessage("You have been successfuly signed up.", "success");
			redirect("/");
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
			redirect($this->data["referersURI"]);
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
		redirect("/");
	}


	protected function usernameExistsJSON(){
		setContentType("json");
		if(isset($_GET["username"])){
			$this->viewString(json_encode(array("usernameExists" => $this->usernameExists($_GET["username"]))), "application/json");
			return;
		}
		setHTTPStatusCode("400");
	}

	protected function emailExistsJSON(){
		setContentType("json");
		if(isset($_GET["email"])){
			$this->viewString(json_encode(array("emailExists" => $this->emailExists($_GET["email"]))), "application/json");
			return;
		}
		setHTTPStatusCode("400");
	}

	protected function usernameExists($username){
		return UserQuery::create()
			->filterByUsername($username)
			->count() != 0;
	}

	protected function emailExists($email){
		return UserQuery::create()
			->filterByEmail($email)
			->count() != 0;
	}
}