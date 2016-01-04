<?php
namespace Controllers;

use Controllers\Base;

class Home extends Base{

	protected function index(){
		$this->data["title"] = "GGG";
		$this->viewFile($this->template);
	}

	protected function changeColor(){
		header('Content-Type: text/javascript');
		$this->viewFile("Assets/javascript/gg.js");
	}
}