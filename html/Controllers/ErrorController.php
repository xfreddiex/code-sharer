<?php
namespace Controllers;

use Controllers\BaseController;

class ErrorController extends BaseController{

	protected function error404(){
		setHTTPStatusCode("404");
		$this->data["title"] = "Error404";
		$this->viewFile($this->template);
	}

	protected function error405(){
		setHTTPStatusCode("405");
		$this->data["title"] = "Error405";
		$this->viewFile($this->template);
	}

	protected function error406(){
		setHTTPStatusCode("406");
		$this->data["title"] = "Error406";
		$this->viewFile($this->template);
	}
}