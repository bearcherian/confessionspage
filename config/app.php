<?php
require_once('db.php');

$app_accesstoken = "340879852685280|RhkelskXPauQjL1A0Rb3-dM02ok";
$addurl = "http://www.facebook.com/add.php?api_key=340879852685280&pages";

$domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));

$domain = ($domain == "pre") ? "temple" : "pre";

function getPageToken($domainString) {
	try {
		$db = new Database();
		$result = $db->query('SELECT access_token FROM pages WHERE cp_domain = :domain;',array(':domain' => $domainString));
	        return $result[0]["access_token"];

	} catch(PDOException $e) {
        	echo "Could not find proper configuration for " . $domainString . ".confessionspage.com.";
	}
}
?>
