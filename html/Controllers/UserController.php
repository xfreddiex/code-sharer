<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;

class UserController extends BaseController{

	protected function SignUp(){
		header('Content-Type: text/javascript');
		echo('
			window.location.href = "/search";

			');
	}
}