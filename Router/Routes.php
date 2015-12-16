<?php
return array(
	array('uri' => '/', 'content_type' => 'text/html', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/home', 'content_type' => 'text/html', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/user/[name]/[gg]', 'content_type' => 'text/html', 'controller' => 'User', 'method' => 'index'),
	array('uri' => '/home/change-color', 'content_type' => 'text/javascript', 'controller' => 'Home', 'method' => 'changeColor')
);