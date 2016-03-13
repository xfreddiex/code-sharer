<?php
namespace Controllers;

use Models\User;
use Models\UserQuery;
 
abstract class Controller{
	protected $data = array();
	protected $template;

	protected $beforeAll = array();
	protected $afterAll = array();
	protected $before = array();
	protected $after = array();

	protected $HTTPStatusCodes = array();
	protected $contentTypes = array();
	
	public function __construct(){
		$this->data['title'] = '';
		$this->data['keywords'] = '';
		$this->data['description'] = '';

		$this->data['flash_messages'] = array();
	}

	public function __call($method, $params){
		if(method_exists($this, $method)){
			$this->before(array("method" => $method, "params" => $params[0]));
			$this->$method($params[0]);
			$this->after(array("method" => $method, "params" => $params[0]));
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

	protected function before($args){
		foreach($this->beforeAll as $beforeAllMethod){
			$this->$beforeAllMethod($args);
		}
		if(isset($this->before[$args["method"]])){
			foreach($this->before[$args["method"]] as $beforeMethod){
				$this->$beforeMethod($args);
			}
		}
	}

	protected function after($args){
		foreach($this->afterAll as $afterAllMethod){
			$this->$afterAllMethod($args);
		}
		if(isset($this->after[$args["method"]])){
			foreach($this->after[$args["method"]] as $afterMethod){
				$this->$afterMethod($args);
			}
		}
	}

	protected function sendFlashMessage($message, $type = "info"){
		$type = $type == "error" ? "danger" : $type;
		$_SESSION["flash_messages"][] = array("message" => $message, "type" => $type);
	}

	protected function sendFlashMessageNow($message, $type = "info"){
		$type = $type == "error" ? "danger" : $type;
		$this->data["flash_messages"] = array("message" => $message, "type" => $type);
	}

	protected function prepareFlashMessages(){
		if(isset($_SESSION["flash_messages"])){
			$this->data["flash_messages"] = $_SESSION["flash_messages"];
			unset($_SESSION["flash_messages"]);
		}
	}

	protected function addBefore($method, $beforeMethods = array()){
		foreach($beforeMethods as $beforeMethod){
			if(isset($this->before[$method]) && !in_array($beforeMethod, $this->before[$method]))
				array_push($this->before[$method], $beforeMethod);
			else
				$this->before[$method] = array($beforeMethod);
		}
	}

	protected function addAfter($method, $afterMethods = array()){
		foreach($afterMethods as $afterMethod){
			if(isset($this->after[$method]) && !in_array($afterMethod, $this->after[$method]))
				array_push($this->after[$method], $afterMethod);
			else
				$this->after[$method] = array($afterMethod);
		}
	}

	protected function addBeforeAll($beforeAllMethod){
		$this->beforeAll[] = $beforeAllMethod;
	}

	protected function addAfterAll($afterAllMethod){
		$this->afterAll[] = $afterAllMethod;
	}	

	protected function redirect($url){
		$url = trim($url, "/");
		header("Location: /$url");
		header("Connection: close");
		exit;
	}
}