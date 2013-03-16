<?php
require_once('../config/app.php');
require_once('../dao/db.php');
require_once('../model/domain.php');

$domain = new Domain();

$confession = isset($_POST['confession']) ? $_POST['confession'] : null;
$ip = $_SERVER['REMOTE_ADDR'];
$ipProxy = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

//echo "Confession: " . $confession . "<br />Ip: " . $ip . "<br />Proxy: " . $ipProxy;

$statement = "INSERT INTO posts(ip_address, ip_proxy, post, post_status,domain) " .
		"VALUES (:ip, :proxy, :post,'new',:domain);";

$values = array(':ip' => $ip,
		':proxy' => $ipProxy,
		':post' => $confession,
		':domain' => $domain->getDomain());

//print_r($values);

$db = new Database();
$db->connect();
$result = $db->insert($statement,$values);
$db->close();
if (isset($result->errorInfo) {
	header('HTTP/1.0 500 Internal Server Error',true,500);
	echo "Unable to submit";
}
?>
