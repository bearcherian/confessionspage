<?php
require_once '../config/app.php';
require_once '../config/domain.php';
require_once '../config/confession.php';

$postId = (isset($_POST['message'])) ? $_POST['message'] : null;
$domain = new Domain();

if ($postId == null) {

//	$confession = new Confession($postId);
	$message = "Testing";
	$pageToken = $domain->getPageToken();
	$pageId = $domain->getPageId();

	$postURL = "https://graph.facebook.com/".$pageId."/feed";

	$data = array( 
		'message' => $message,
		'access_token' => $pageToken
	);

	// use key 'http' even if you send the request to https://...
	$options = array('http' => array('method'  => 'POST','content' => http_build_query($data)));
	$context  = stream_context_create($options);
	$result = file_get_contents($postURL, false, $context);

	$fb = json_decode($result);	
	echo $fb->id;
} else {
	echo "No post specified.";
}
?>
