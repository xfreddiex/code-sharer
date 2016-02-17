<?php
namespace Controllers;

use Controllers\BaseController;

class HomeController extends BaseController{

	protected function index(){
		$this->data["title"] = "GGG";
		$this->viewFile($this->template);
	}
}