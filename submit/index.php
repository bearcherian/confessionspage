<?php
include_once('../config/app.php');
include_once('../config/db.php');
include_once('../filter/filters.php');

$confession = $_POST['confession'];
$ip = $_SERVER['REMOTE_ADDR'];
$ipProxy = $_SERVER['HTTP_X_FORWARDED_FOR'];

echo "Confession: " . $confession . "<br />Ip: " . $ip . "<br />Proxy: " . $ipProxy;
?>
