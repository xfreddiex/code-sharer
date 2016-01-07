<?php
namespace Controllers;

use Controllers\Base;

class Errors extends Base{

	protected function error404(){
		header("HTTP/1.0 404 Not Found");
		$this->data["title"] = "Error404";
		$this->viewFile($this->template);
	}

	protected function error405(){
		header("HTTP/1.0 405 Method Not Allowed");
		$this->data["title"] = "Error405";
		$this->viewFile($this->template);
	}

	protected function error406(){
		header("HTTP/1.0 406 Not Acceptable");
		$this->data["title"] = "Error406";
		$this->viewFile($this->template);
	}
}