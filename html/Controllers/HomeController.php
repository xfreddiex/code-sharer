<?php
namespace Controllers;

use Controllers\BaseController;

class HomeController extends BaseController{

	protected function index(){
		$this->viewFile($this->template);
	}
}