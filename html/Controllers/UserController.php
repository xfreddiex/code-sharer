<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;

class UserController extends BaseController{

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
						$this->sendFlashMessage("You have not been signed up. ".$failure->getMessage(), "danger");
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
				$this->sendFlashMessage("You have not been signed in. User does not exist.", "danger");
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
					setcookie("identityId", $identity->getId(), time() + (86400 * 14));
					setcookie("identityToken", $token, time() + (86400 * 14));
				}
			}
			else
				$this->sendFlashMessage("You have not been signed in. You entered wrong password.", "danger");
			redirect(getEndURI($_SERVER["HTTP_REFERER"]));
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