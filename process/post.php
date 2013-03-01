<?php
include_once '../config/app.php';
include_once '../config/domain.php';

$postURL = "https://graph.facebook.com/".$pageid."/feed";

$data = array( 
	'message' => $_POST['message'],
	'access_token' => $app_accesstoken
);
	

?>
