<?php
mb_internal_encoding("UTF-8");

require('../vendor/autoload.php');

require('generated-conf/config.php');

require("Helpers/Helper.php");

use Router\Router;

function autoload($class){
	$file = str_replace('\\', '/', $class) . '.php';
	if (file_exists($file)){
        require_once($file);
    }
}

spl_autoload_register("autoload");

session_start();

$router = new Router();
$router->process($_SERVER['REQUEST_URI']);