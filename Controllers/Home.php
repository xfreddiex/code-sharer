<?php
namespace Controllers;

use Controllers\Base;

class Home extends Base{

	protected function index(){
		$this->view($this->template);
	}

	protected function changeColor(){
		require("Assets/javascript/gg.js");
	}
}