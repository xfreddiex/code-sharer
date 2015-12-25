<?php
namespace Controllers;

use Controllers\Base;

class Home extends Base{

	protected function index(){
		$this->data["title"] = "GGG";
		$this->view($this->template);
	}

	protected function changeColor(){
		header('Content-Type: text/javascript');
		require("Assets/javascript/gg.js");
	}
}