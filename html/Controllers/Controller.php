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
		//array_push($this->after);
	}

	public function __call($method, $params){
		if(method_exists($this, $method)){
			$this->before($method);
			$this->$method($params);
			$this->after($method);
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

	protected function before($method){
		foreach($this->beforeAll as $beforeAllMethod){
			$this->$beforeAllMethod();
		}
		if(isset($this->before[$method])){
			foreach($this->before[$method] as $beforeMethod){
				$this->$beforeMethod();
			}
		}
	}

	protected function after($method){
		foreach($this->afterAll as $afterAllMethod){
			$this->$afterAllMethod();
		}
		if(isset($this->after[$method])){
			foreach($this->after[$method] as $afterMethod){
				$this->$afterMethod();
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

	protected function addBefore($beforeMethod, $methods = array()){
		foreach($methods as $method){
			if(isset($this->before[$beforeMethod]) && !in_array($method, $this->before[$beforeMethod]))
				array_push($this->before[$beforeMethod], $method);
			else
				$this->before[$beforeMethod] = array($method);
		}
	}

	protected function addAfter($afterMethod, $methods = array()){
		foreach($methods as $method){
			if(isset($this->after[$afterMethod]) && !in_array($method, $this->after[$afterMethod]))
				array_push($this->after[$afterMethod], $method);
			else
				$this->after[$afterMethod] = array($method);
		}
	}

	protected function addBeforeAll($beforeAllMethod){
		$this->beforeAll[] = $beforeAllMethod;
	}

	protected function addAfterAll($afterAllMethod){
		$this->afterAll[] = $afterAllMethod;
	}		
}