<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;

class UserController extends BaseController{

	protected function signUp(){
		if(isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password-again"])){
			if(strlen($_POST["password"]) < 6){
				$this->sendFlashMessage("You have not been signed up. You have entered wrong password.", "danger");
			}
			else if($_POST["password"] != $_POST["password"]){
				$this->sendFlashMessage("You have not been signed up. Entered passwords do not match.", "danger");
			}
			else if($this->usernameExists($_POST["username"])){
				$this->sendFlashMessage("You have not been signed up. You have entered wrong username.", "danger");
			}
			else if($this->emailExists($_POST["email"])){
				$this->sendFlashMessage("You have not been signed up. You have entered wrong email.", "danger");
			}
			else if($this->addUser($_POST["email"], $_POST["username"], $_POST["password"])){
				$this->sendFlashMessage("You have been successfuly signed up.", "success");
			}
			else{
				$this->sendFlashMessage("Something has gone wrong. You have not been signed up.", "danger");
			}
			$this->redirectJS("/home");
		}
		$this->setHTTPStatusCode("400");
	}

	protected function addUser($email, $username, $password){
		if(strlen($password) >= 6 && !$this->usernameExists($username) && !$this->emailExists($email)){
			$user = new User();
			$user->setEmail($email);
			$user->setUsername($username);
			$user->setPassword($password);
			$user->save();
			return $user;
		}
		return 0;
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
		return UserQuery::create()->filterByUsername($username)->count() != 0;
	}

	protected function emailExists($email){
		return UserQuery::create()->filterByEmail($email)->count() != 0;
	}
}