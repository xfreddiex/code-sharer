<?php
namespace Controllers;

use Models\User;
use Models\Group;
use Models\Pack;
use Models\Discusion;
 
abstract class Base{
	protected $data = array();
	protected $basic_template = "basic_template.phtml";
	protected $head = array('title' => '', 'keywords' => '', 'description' => '');
	protected $flash_messages = array();
	protected $before = array("prepareFlashMessages");

	public __construct

	public function view(){
		if($this->view){
			extract($this->data);
			require("views/".$this->view).".phtml";
		}
	}

	abstract function process($params){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
		}
	}

	protected function before(){
		foreach ($before as $method){
			$method();
		}
	}

	protected function after(){

	}

	protected function sendFlashMessages($message, $type = NULL){
		$_SESSION["flash_messages"][] = array("message" => $message, "type" => ($type == NULL ? "default" : $type));
	}

	protected function prepareFlashMessages(){
		if(isset($_SESSION["flash_messages"])){
			$this->data["flash_messages"] = $_SESSION["flash_messages"];
			unset($_SESSION["flash_messages"]);
		}
	}

	public function redirect($url){
		header("Location: /$url");
		header("Connection: close");
		exit;
	}
}