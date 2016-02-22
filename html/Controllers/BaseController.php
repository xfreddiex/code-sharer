<?php
namespace Controllers;

use Controllers\Controller;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;
 
abstract class BaseController extends Controller{

	public function __construct(){
		parent::__construct();
		$this->template = 'Views/basic_template.phtml';

		$this->data['title'] = 'Starling';
		$this->data['keywords'] = 'starling, codesharer';
		$this->data['description'] = '';

		$this->addBeforeAll("prepareFlashMessages");
		$this->addBeforeAll("loadUser");
	}

	protected function loadUser(){
		if(isset($_SESSION["userId"])){
			$this->data["user"] = UserQuery::create()->findPK($_SESSION["userId"]);
		}
		else if(isset($_COOKIE["identityId"]) && isset($_COOKIE["identityToken"])){
			$identity = IdentityQuery::create()
				->findPK($_COOKIE["identityId"]);
			if($identity && $identity->checkToken($_COOKIE["identityToken"])){
				$this->data["user"] = UserQuery::create()->filterByIdentity($identity)->findOne();
				if($this->data["user"]){
						$token = generateRandomString(32);
						$identity->setToken($token)->setExpiresAt(time() + (86400 * 14))->save();
						setcookie("identityId", $identity->getId(), time() + (86400 * 14));
						setcookie("identityToken", $token, time() + (86400 * 14));
				}
			}
		}
		if($this->data["user"]){
			$this->data["userLogged"] = true;
			$this->data["userUsername"] = $this->data["user"]->getUsername();
			return;
		}
		$this->data["userLogged"] = false;
	}
}