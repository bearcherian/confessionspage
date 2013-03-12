<?php
$app_accesstoken = "340879852685280|RhkelskXPauQjL1A0Rb3-dM02ok";
$addurl = "http://www.facebook.com/add.php?api_key=340879852685280&pages";

$domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));

$domain = ($domain == "pre") ? "temple" : "pre";
?>
