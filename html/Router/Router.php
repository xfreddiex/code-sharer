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
			if(count($route_uri) == count($uri)){
				$route["params"] = array();
				$ok = true;
				
				for($i = 0; $i < count($route_uri); $i++){
					
					if(preg_match('/\[.+\]/', $route_uri[$i])){
						array_push($route["params"], $uri[$i]);
					}
					else if($route_uri[$i] != $uri[$i])
						$ok = false;
				}
				if($ok){
					if($route["request_method"] != $_SERVER["REQUEST_METHOD"])
						return $routes["error405"];
					if(!(in_array($route["content_type"], array_map('trim', explode(',', $_SERVER["HTTP_ACCEPT"])))))
						return $routes["error406"];
					return $route;
				}
			}
		}
		return $routes["error404"];
	}

	public function process($uri){
		$route = $this->getRoute($uri);
		$controller = 'Controllers\\'.$route["controller"].'Controller';
		$this->controller = new $controller;
		$this->controller->$route["method"](isset($route["params"]) ? $route["params"] : NULL);
	}
}