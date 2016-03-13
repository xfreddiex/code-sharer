<?php
return array(
	array('uri' => '/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/home', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	
	//User
	array('uri' => '/user/[name]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'show'),
	array('uri' => '/sign-up', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'create'),
	array('uri' => '/sign-in', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'signIn'),
	array('uri' => '/sign-out', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'signOut'),
	array('uri' => '/profile', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'profile'),
	array('uri' => '/settings', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'settings'),
	array('uri' => '/user-update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'update'),
	array('uri' => '/avatar-update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'updateAvatar'),
	array('uri' => '/user-validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'validateOne'),
	
	//Pack
	array('uri' => '/pack/[username]/[packname]/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'show'),
	array('uri' => '/new-pack', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'new'),
	array('uri' => '/create-pack', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'create'),
	array('uri' => '/pack-validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'validateOne'),
	
	array('uri' => '/update-pack-permissions', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'PackPermission', 'method' => 'update'),

	//Group
	array('uri' => '/create/group', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'create'),
	array('uri' => '/group/[id]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Group', 'method' => 'show'),
	array('uri' => '/group/[id]/update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'update'),
	array('uri' => '/group/[id]/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'delete'),
	array('uri' => '/group/[id]/add-users', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'addUsers'),
	array('uri' => '/group/[id]/remove-users', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'removeUsers'),
	array('uri' => '/group/[id]/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'delete'),

	//Errors
	"error404" => array('uri' => '/404', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error404'),
	"error405" => array('uri' => '/405', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error405'),
	"error406" => array('uri' => '/406', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error406')
);