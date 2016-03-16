<?php
namespace Controllers;

use Controllers\Controller;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;
use Models\Pack;
use Models\PackQuery;
 
abstract class BaseController extends Controller{

	public function __construct(){
		parent::__construct();
		$this->template = 'Views/basic_template.phtml';

		$this->data["referersURI"] = getReferersURIEnd();

		$this->data['title'] = 'Starling';
		$this->data['keywords'] = 'starling, code-sharer';
		$this->data['description'] = '';

		$this->addBeforeAll("prepareFlashMessages");
		$this->addBeforeAll("loadUser");

		$this->data["loggedUser"] = NULL;
	}

	protected function loadUser(){
		$user = null;
		if(isset($_SESSION["userId"])){
			$user = UserQuery::create()->findPK($_SESSION["userId"]);
		}
		else if(isset($_COOKIE["identityId"]) && isset($_COOKIE["identityToken"])){
			$identity = IdentityQuery::create()->findPK($_COOKIE["identityId"]);
			if($identity && $identity->checkToken($_COOKIE["identityToken"])){
				$user = UserQuery::create()->filterByIdentity($identity)->findOne();
				if($user){
						$_SESSION["userId"] = $user->getId();
						$token = generateRandomString(32);
						$identity->setToken($token)->setExpiresAt(time() + (86400 * 120))->save();
						setcookie("identityId", $identity->getId(), time() + (86400 * 120));
						setcookie("identityToken", $token, time() + (86400 * 120));
				}
			}
		}
		$this->data["loggedUser"] = $user;
		return true;
	}

	protected function userLogged(){
		if(!($this->data["loggedUser"])){
			$this->sendFlashMessage("You must be signed in.", "error");
			$this->redirect("/");
		}
		return true;
	}

	protected function userAuthorized(){
		if(!(isset($_POST["authorizationPassword"]) && $this->data["loggedUser"]->checkPassword($_POST["authorizationPassword"]))){
			$this->sendFlashMessage("You have not been authorized.", "error");
			$this->redirect("/");
		}
		return true;
	}

}