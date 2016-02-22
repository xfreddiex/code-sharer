<?php
function generateRandomString($length){
	return bin2hex(openssl_random_pseudo_bytes($length/2));
}

function getEndURI($uri = ""){
	$uri = parse_url($_SERVER['HTTP_REFERER']);
	return (isset($uri["path"]) ? $uri["path"] : "").(isset($uri["query"]) ? "?".$uri["query"] : "").(isset($uri["fragment"]) ? "#".$uri["fragment"] : "");
}