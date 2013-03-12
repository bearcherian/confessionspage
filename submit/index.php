<?php
include_once('../config/app.php');
include_once('../config/db.php');
include_once('../filter/filters.php');

$confession = isset($_POST['confession']) ? $_POST['confession'] : null;
$ip = $_SERVER['REMOTE_ADDR'];
$ipProxy = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

//echo "Confession: " . $confession . "<br />Ip: " . $ip . "<br />Proxy: " . $ipProxy;

$statement = "INSERT INTO " . $domain . "_posts(ip_address, ip_proxy, post, post_status) " .
		"VALUES (:ip, :proxy, :post,'new');";
$values = array(':ip' => $ip,
		':proxy' => $ipProxy,
		':post' => $confession);

//print_r($values);

$db = new Database();
$db->connect();
$db->insert($statement,$values);
$db->close();

?>
