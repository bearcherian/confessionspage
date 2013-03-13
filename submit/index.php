<?php
require_once('../config/app.php');
require_once('../config/db.php');
require_once('../config/domain.php');

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
$db->insert($statement,$values);
$db->close();

?>
