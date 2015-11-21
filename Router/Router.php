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

	public function process($parametres){
		$path = $this->parseUrl($parametres[0]);
		$controller_class = "";
		if (empty($path[0]))		
			$controller_class = "Home";
		else
			$controller_class = $this->dashToCamelcase(array_shift($path));
		if (file_exists('Controllers/' . $controller_class . '.php'))
			$this->controller = "Cotrollers\\" . $controller_class;
			$this->controller = new $controller_class();
		else
			$this->controller = new Error("404"));
		$this->controller->process($path);
	}
}