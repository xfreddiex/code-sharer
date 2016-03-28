<?php
return array(
	array('uri' => '/', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/home', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'index'),
	array('uri' => '/search', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Home', 'method' => 'search'),
	
	//User
	array('uri' => '/update-avatar', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'updateAvatar'),
	array('uri' => '/sign-up', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'create'),
	array('uri' => '/sign-in', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'signIn'),
	array('uri' => '/sign-out', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'signOut'),
	array('uri' => '/profile', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'profile'),
	array('uri' => '/settings', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'settings'),
	array('uri' => '/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'delete'),
	array('uri' => '/update', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'update'),
	array('uri' => '/user/validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'User', 'method' => 'validateOne'),
	array('uri' => '/user/[username]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'show'),
	array('uri' => '/user/[username]/email-confirm/[token]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'emailConfirm'),
	array('uri' => '/user/[username]/send-email-confirm-email', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'sendEmailConfirmEmail'),
	array('uri' => '/user/[username]/send-restore-account-email', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'sendRestoreAccountEmail'),
	array('uri' => '/user/[username]/restore-account/[token]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'restoreAccount'),
	array('uri' => '/user/[email]/send-password-reset-email', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'sendPasswordResetEmail'),
	array('uri' => '/user/[username]/reset-password/[token]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'User', 'method' => 'resetPassword'),
	//Pack
	array('uri' => '/pack/create', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'create'),
	array('uri' => '/pack/new', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'newPack'),
	array('uri' => '/pack/[id]/update', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'update'),
	array('uri' => '/pack/[id]/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'delete'),
	array('uri' => '/pack/[id]/settings', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'settings'),
	array('uri' => '/pack/[id]/add-files', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'addFiles'),
	array('uri' => '/pack/[id]/update-permissions', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'updatePermissions'),
	array('uri' => '/pack/[id]/files-list', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'filesList'),
	array('uri' => '/pack/[id]/add-comment', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'addComment'),
	array('uri' => '/pack/[id]/get-comments', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'comments'),
	array('uri' => '/pack/[id]/comment/[comment_id]/delete', 'content_type' => 'application/json', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'deleteComment'),
	array('uri' => '/pack/validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'validateOne'),
	array('uri' => '/pack/[id]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'show'),

	//Group
	array('uri' => '/group/new', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Group', 'method' => 'newGroup'),
	array('uri' => '/group/create', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'create'),
	array('uri' => '/group/[id]/update', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'update'),
	array('uri' => '/group/[id]/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'delete'),
	array('uri' => '/group/[id]/add-users', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'addUsers'),
	array('uri' => '/group/[id]/users-list', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Group', 'method' => 'usersList'),
	array('uri' => '/group/[id]/remove-user/[username]', 'content_type' => 'application/json', 'request_method' => 'GET', 'controller' => 'Group', 'method' => 'removeUser'),
	array('uri' => '/group/[id]/delete', 'content_type' => 'text/html', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'delete'),
	array('uri' => '/group/validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Group', 'method' => 'validateOne'),
	array('uri' => '/group/[id]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Group', 'method' => 'show'),

	//File
	array('uri' => '/pack/[id]/[name]', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'showFile'),
	array('uri' => '/pack/[id]/[name]/content', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'getFileContent'),
	array('uri' => '/pack/[id]/[name]/delete', 'content_type' => 'application/json', 'request_method' => 'GET', 'controller' => 'Pack', 'method' => 'deleteFile'),
	array('uri' => '/pack/[id]/[name]/update', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'updateFile'),
	array('uri' => '/file/validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'fileValidateOne'),
	
	array('uri' => '/comment/validate-one', 'content_type' => 'application/json', 'request_method' => 'POST', 'controller' => 'Pack', 'method' => 'commentValidateOne'),

	//Errors
	"error404" => array('uri' => '/404', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error404'),
	"error405" => array('uri' => '/405', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error405'),
	"error406" => array('uri' => '/406', 'content_type' => 'text/html', 'request_method' => 'GET', 'controller' => 'Error', 'method' => 'error406')
);