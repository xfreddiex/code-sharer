<?php
namespace Router;

class Router{
	private $controller;

	private function parseUri($uri){
		$uri = trim($uri);
		$uri = trim($uri, "/");
		$uri = explode("/", $uri);
		return $uri;
	}

	private function getRoute($uri){
		$routes = require('Router/Routes.php');
		$uri = $this->parseUri($uri);
		
		foreach($routes as $route){
			$size = count($route["uri"]);
			
			if($route["content_type"] == explode(',', $_SERVER["HTTP_ACCEPT"])[0] || $size == count($uri)){
				$route["params"] = array();
				$route["uri"] = $this->parseUri($route["uri"]);
				
				for($i = 0; $i < $size; $i++){
					
					if(preg_match('/\[.+\]/', $route["uri"][$i])){
						array_push($route["params"], $route["uri"][$i]);
					}
					else if($route["uri"][$i] != $uri[$i])
						break;
					
					if($i + 1 == $size)
						return $route;
				}
			}
		}
		
		return array("aaa");
	}

	public function process($uri){
		$route = $this->getRoute($uri);
		$controller = 'Controllers\\'.$route["controller"];
		$this->controller = new $controller;
		$this->controller->$route["method"]($route["params"]);
	}
}