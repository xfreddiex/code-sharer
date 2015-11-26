<?php
namespace Controllers;

use Models\User;
use Models\Group;
use Models\Pack;
use Models\Discusion;
 
abstract class Base{
	protected $data = array();
	protected $basic_template;
	protected $controller_view;
	protected $before = array();
	protected $after = array();

	public function __construct(){
		$this->basic_template = 'Views/basic_template.phtml';
		$this->controller_view = str_replace('Controllers\\', 'Views/', __CLASS__) . '.phtml';
		array_push($this->data, array('title' => '', 'keywords' => '', 'description' => '', 'flash_messages' => array()));
		array_push($this->before, "prepareFlashMessages");
		array_push($this->after, "view");
	}

	public function view(){
		extract($this->data);
		require($this->basic_template);
	}

	public function index($params){
		$this->before();
		$this->instructions($params);
		$this->after();
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

	abstract function instructions($params);
}