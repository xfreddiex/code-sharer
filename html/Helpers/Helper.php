<?php
function generateRandomString($length){
	return bin2hex(openssl_random_pseudo_bytes($length/2));
}

function getReferersURIEnd(){
	$uri = isset($_SERVER["HTTP_REFERER"]) ? parse_url($_SERVER['HTTP_REFERER']) : "";
	return (isset($uri["path"]) ? $uri["path"] : "").(isset($uri["query"]) ? "?".$uri["query"] : "").(isset($uri["fragment"]) ? "#".$uri["fragment"] : "");
}

function redirect($url){
	$url = trim($url, "/");
	header("Location: /$url");
	header("Connection: close");
	exit;
}

function setHTTPStatusCode($code){
	$HTTPStatusCodes = array(
		"400" => "400 Bad Request",
		"404" => "404 Not Found",
		"405" => "405 Method Not Allowed",
		"406" => "406 Not Acceptable"
	);
	header("HTTP/1.0 " . $HTTPStatusCodes[$code]);
}

function setContentType($type){
	$contentTypes = array(
		"json" => "application/json",
		"html" => "text/html",
		"javascript" => "application/javascript",
		"css" => "text/css"
	);
	header("Content-Type: " . $contentTypes[$type]);
}