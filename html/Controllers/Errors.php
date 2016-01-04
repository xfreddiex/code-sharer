<?php
namespace Controllers;

use Controllers\Base;

class Errors extends Base{

	protected function error404(){
		header("HTTP/1.0 404 Not Found");
		$this->data["title"] = "Error404";
		$this->viewFile($this->template);
	}
}