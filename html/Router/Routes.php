<?php
return array(
	array('uri' => '/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/home', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/user/[name]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'user'),
	array('uri' => '/pack/[id]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'pack'),
	array('uri' => '/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/sign-up', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'signUp'),
	array('uri' => '/sign-in', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'signIn'),
	array('uri' => '/sign-out', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'signOut'),
	array('uri' => '/profile', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'profile'),
	array('uri' => '/settings', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'settings'),
	array('uri' => '/new-pack', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'newPack'),
	array('uri' => '/create-pack', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'createPack'),
	array('uri' => '/user-update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'update'),
	array('uri' => '/avatar-update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'updateAvatar'),
	array('uri' => '/user-validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'validateOne'),
	array('uri' => '/pack-validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'validateOne'),
	array('uri' => '/username-exists', 'content_type' => 'application/json', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'usernameExistsJSON'),
	array('uri' => '/email-exists', 'content_type' => 'application/json', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'emailExistsJSON'),
	"error404" => array('uri' => '/404', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error404'),
	"error405" => array('uri' => '/405', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error405'),
	"error406" => array('uri' => '/406', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error406')
);