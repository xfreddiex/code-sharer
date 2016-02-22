<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;

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
			$this->redirect("/");
		}
		else
			$this->setHTTPStatusCode("400");
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
					if(isset($_COOCKIE["identityId"])){
						$identity = Identity::create()
							->findPK($_COOCKIE["identityId"])
							->delete();
						setcookie("identityId", null, time() - 3600);
						setcookie("identityToken", null, time() - 3600);
					}
					$token = generateRandomString(32);
					$identity = new Identity();
					$identity->setToken($token)
						->save();
					$user->addIdentity($identity)
						->save();
					setcookie("identityId", $identity->getId, time() + (86400 * 15), "/");
					setcookie("identityToken", $token, time() + (86400 * 15), "/");
				}
			}
			else
				$this->sendFlashMessage("You have not been signed in. You entered wrong password.", "danger");
			$this->redirect(getEndURI($_SERVER["HTTP_REFERER"]));
		}
		else
			$this->setHTTPStatusCode("400");
	}

	protocted function signOut(){
		
	}


	protected function usernameExistsJSON(){
		$this->setContentType("json");
		if(isset($_GET["username"])){
			$this->viewString(json_encode(array("usernameExists" => $this->usernameExists($_GET["username"]))), "application/json");
			return;
		}
		$this->setHTTPStatusCode("400");
	}

	protected function emailExistsJSON(){
		$this->setContentType("json");
		if(isset($_GET["email"])){
			$this->viewString(json_encode(array("emailExists" => $this->emailExists($_GET["email"]))), "application/json");
			return;
		}
		$this->setHTTPStatusCode("400");
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