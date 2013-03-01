<?php
include_once('../config/app.php');
include_once('../config/db.php');
include_once('../filter/filters.php');

try {
	$tokenStatement = $dbconn->prepare('SELECT access_token FROM pages WHERE cp_domain = :domain');
	$token 	= $tokenStatement->execute( array(':domain',$domain));
	echo $aToken;

} catch(PDOException $e) {
	
}

$confession = $_POST['confession'];
$ip = $_SERVER['REMOTE_ADDR'];
$ipProxy = $_SERVER['HTTP_X_FORWARDED_FOR'];



?>
