<?php
function generateRandomString($length){
	return bin2hex(openssl_random_pseudo_bytes($length/2));
}

function getReferersURIEnd(){
	$uri = isset($_SERVER["HTTP_REFERER"]) ? parse_url($_SERVER['HTTP_REFERER']) : "";
	return (isset($uri["path"]) ? $uri["path"] : "").(isset($uri["query"]) ? "?".$uri["query"] : "").(isset($uri["fragment"]) ? "#".$uri["fragment"] : "");
}

function setHTTPStatusCode($code){
	$HTTPStatusCodes = array(
		"400" => "400 Bad Request",
		"401" => "401 Unauthorized",
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

function resizeImg($img, $width, $height){
	$widthOrig = imagesx($img);
	$heightOrig = imagesy($img);
	$imgNew = imagecreatetruecolor($width, $height);
	imagecopyresampled($imgNew, $img, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);
	return $imgNew;
}