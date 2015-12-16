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
		array_push($this->data, array('title' => '', 'keywords' => '', 'description' => '', 'flash_messages' => array()));
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

	protected function view($view = NULL){
		if(!$view)
			$view = str_replace('Controllers\\', 'Views/', debug_backtrace()[1]["class"]."/".debug_backtrace()[1]["function"].".phtml");
		if(file_exists($view)){
			extract($this->data);
			require($view);
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

	protected function sendFlashMessage($message, $type = NULL){
		$_SESSION["flash_messages"][] = array("message" => $message, "type" => ($type == NULL ? "default" : $type));
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