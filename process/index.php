<?php
include_once('../config/app.php');
include_once('../config/db.php');
include_once('../filter/filters.php');

try {
	$tokenStatement = $dbconn->prepare('SELECT access_token FROM pages WHERE cp_domain = :domain;');
	$tokenStatement->execute( array(':domain' => $domain));
	$result = $tokenStatement->fetch();
	$aToken = $result["acces_token"];

} catch(PDOException $e) {
	echo "Could not find proper configuration for " . $domain . ".confessionspage.com.";
}

$confession = $_POST['confession'];
$ip = $_SERVER['REMOTE_ADDR'];
$ipProxy = $_SERVER['HTTP_X_FORWARDED_FOR'];



?>
