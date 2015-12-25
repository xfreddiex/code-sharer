<?php
namespace Router;

class Router{
	private $controller;

	private function parseUri($uri){
		$uri = parse_url($uri, PHP_URL_PATH);
		$uri = trim($uri);
		$uri = trim($uri, "/");
		$uri = explode("/", $uri);
		return $uri;
	}

	private function getRoute($uri){
		$routes = require('Router/Routes.php');
		$uri = $this->parseUri($uri);
		foreach($routes as $route){
			$route_uri = $this->parseUri($route["uri"]);
			if(in_array($route["content_type"], explode(',', $_SERVER["HTTP_ACCEPT"])) && count($route_uri) == count($uri) && $route["request_method"] == $_SERVER["REQUEST_METHOD"]){
				$route["params"] = array();
				
				for($i = 0; $i < count($route_uri); $i++){
					
					if(preg_match('/\[.+\]/', $route_uri[$i])){
						array_push($route["params"], $route_uri[$i]);
					}
					else if($route_uri[$i] != $uri[$i])
						break;
					
					if($i + 1 == count($route_uri))
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