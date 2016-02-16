<?php
namespace Controllers;

use Models\User;
use Models\UserQuery;
 
abstract class BaseController{
	protected $data = array();
	protected $template;

	protected $before = array();
	protected $after = array();

	protected $HTTPStatusCodes = array();
	protected $contentTypes = array();

	public function __construct(){
		$this->template = 'Views/basic_template.phtml';
		$this->data['title'] = '';
		$this->data['keywords'] = '';
		$this->data['description'] = '';
		$this->data['flash_messages'] = array();
		array_push($this->before, "prepareFlashMessages");
		$this->HTTPStatusCodes = array(
			"400" => "400 Bad Request",
			"404" => "404 Not Found",
			"405" => "405 Method Not Allowed",
			"406" => "406 Not Acceptable"
		);
		$this->contentTypes = array(
			"json" => "application/json",
			"html" => "text/html",
			"javascript" => "application/javascript",
			"css" => "text/css"
		);
		//array_push($this->after);
	}

	public function __call($method, $params){
		if(method_exists($this, $method)){
			$this->before();
			$this->$method($params);
			$this->after();
		}
	}

	protected function view(){
		$file = str_replace('Controller', '', str_replace('Controllers\\', 'Views/', debug_backtrace()[1]["class"]."/".debug_backtrace()[1]["function"].".phtml"));
		$this->viewFile($file);
	}

	protected function viewToTemplate(){
		$file = str_replace('Controller', '', str_replace('Controllers\\', 'Views/', debug_backtrace()[3]["class"]."/".debug_backtrace()[3]["function"].".phtml"));
		$this->viewFile($file);
	}

	protected function viewFile($file){
		if(file_exists($file)){
			extract($this->data);
			require($file);
		}
	}

	protected function viewString($string){
		echo $string;
	}

	protected function before(){
		foreach ($this->before as $method){
			$this->$method();
		}
	}

	protected function after(){
		foreach ($this->after as $method){
			$this->$method();
		}
	}

	protected function sendFlashMessage($message, $type = "info"){
		$_SESSION["flash_messages"][] = array("message" => $message, "type" => $type);
	}

	protected function prepareFlashMessages(){
		if(isset($_SESSION["flash_messages"])){
			$this->data["flash_messages"] = $_SESSION["flash_messages"];
			unset($_SESSION["flash_messages"]);
		}
	}

	protected function redirect($url){
		$url = trim($url, "/");
		header("Location: /$url");
		header("Connection: close");
		exit;
	}

	protected function setHTTPStatusCode($code){
		header("HTTP/1.0 " . $this->HTTPStatusCodes[$code]);
	}

	protected function setContentType($type){
		header("Content-Type: " . $this->contentTypes[$type]);
	}
}