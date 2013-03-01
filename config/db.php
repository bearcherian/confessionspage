<?php
$db_user = "cp";
$db_password = "Never4getDannybr0wn.";
$db_host = "localhost";
$db_db = "cp_cp";

$dbconn = new PDO('mysql:host='.$db_host.';dbname='.$db_db,$db_user,$db_password);
$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
