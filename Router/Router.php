<?php
namespace Router;

class Router{
	private $controller;

	private function parseUrl($url){
		$parsed_url = parse_url($url);
		$parsed_url["path"] = ltrim($parsed_url["path"], "/");
		$parsed_url["path"] = trim($parsed_url["path"]);
		$path = explode("/", $parsed_url["path"]);
		return $path;
	}

	private function dashToCamelcase($text){
		$name = str_replace('-', ' ', $text);
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}

	public function process($params){
		$path = $this->parseUrl($params[0]);
		$controller_class = "";
		if(empty($path[0]))		
			$controller_class = "Home";
		else
			$controller_class = $this->dashToCamelcase(array_shift($path));
		if(file_exists('Controllers/' . $controller_class . '.php')){
			$controller_class = "Controllers\\" . $controller_class;
			$this->controller = new $controller_class();
		}
		else
			$this->controller = new Error("404");
		if($_SERVER["CONTENT_TYPE"] == 'text/javascript' && !empty($path[0])){
			$method = array_shift($path);
		}
		else
			$method = "index";
		$this->controller->$method($path);
	}
}