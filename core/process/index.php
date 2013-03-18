<?php
if ($_SERVER['HTTP_HOST'] != "localhost") {
	error_log("Unauthorized access. Host: " . $_SERVER['HTTP_HOST']);
	exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/core/process/process.php');

//get all domains
//for each domain, pull first new post, filter, process
$process = new Process();
/*
echo "<pre>";
print_r($process->domain);
echo "<br />";
print_r($process->confession);
print_r($process->filter);
*/
if ($process->filter != null) {

	if (	
		$process->filter->recentIp() || 
		$process->filter->duplicatePost() ||
		$process->filter->hasNumber() ||
		$process->filter->hasProfanity()
	) {
		$process->postFiltered();
	} else {
		$process->postToFb();
	}
}

?>
