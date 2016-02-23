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
		$this->data["userAuthorizated"] = false;
		$this->addBefore("checkAuthorization", array("update"));
	}

	protected function profile(){
		if($this->data["userLogged"]){
			$this->data["userUsername"] = $this->data["user"]->getUsername();
			$this->data["userName"] = $this->data["user"]->getName();
			$this->data["userSurname"] = $this->data["user"]->getSurname();
			$this->data["userEmail"] = $this->data["user"]->getEmail();
			$this->data["userAvatarPath"] = $this->data["user"]->getAvatarPath();			
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
		if($this->data["userAuthorizated"]){
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
		if($this->data["userAuthorizated"]){
			
		}			
	}

	protected function checkAutorizatition(){
		if($this->data["userLogged"] && isset($_POST["password"]) && $this->data["user"]->checkPassword($_POST["password"])){
			$_POST["userAuthorizated"] = true;
		}
		else
			$_POST["userAuthorizated"] = false;
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