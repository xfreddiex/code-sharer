<?php
mb_internal_encoding("UTF-8");

use Router\Router;

function autoload($class){
	$base_dir = __DIR__;
	$file = $base_dir . str_replace('\\', '/', $class) . '.php';
	if (file_exists($file)){
        require_once($file);
    }
}

spl_autoload_register("autoload");

session_start();

$router = new Router();
$router->process(array($_SERVER['REQUEST_URI']));