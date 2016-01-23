<?php
namespace Controllers;

use Models\User;
use Models\Group;
use Models\Pack;
use Models\Discusion;
 
abstract class Base{
	protected $data = array();
	protected $template;

	protected $before = array();
	protected $after = array();

	public function __construct(){
		$this->template = 'Views/basic_template.phtml';
		$this->data['title'] = '';
		$this->data['keywords'] = '';
		$this->data['description'] = '';
		$this->data['flash_messages'] = array();
		array_push($this->before, "prepareFlashMessages");
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
		$file = str_replace('Controllers\\', 'Views/', debug_backtrace()[1]["class"]."/".debug_backtrace()[1]["function"].".phtml");
		$this->viewFile($file);
	}

	protected function viewToTemplate(){
		$file = str_replace('Controllers\\', 'Views/', debug_backtrace()[3]["class"]."/".debug_backtrace()[3]["function"].".phtml");
		$this->viewFile($file);
	}

	protected function viewFile($file){
		if(file_exists($file)){
			extract($this->data);
			require($file);
		}
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
		header("Location: /$url");
		header("Connection: close");
		exit;
	}
}