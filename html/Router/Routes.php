<?php
return array(
	array('uri' => '/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/home', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/user/[name]/[gg]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'index'),
	array('uri' => '/home/change-color', 'content_type' => 'text/javascript', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'changeColor'),
	"error404" => array('uri' => '/404', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Errors', 'method' => 'error404'),
	"error405" => array('uri' => '/405', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Errors', 'method' => 'error405'),
	"error405" => array('uri' => '/406', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Errors', 'method' => 'error406')
);