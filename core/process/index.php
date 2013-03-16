<?php
require_once('../process/process.php');
echo "<pre>";

//get all domains
//for each domain, pull first new post, filter, process
$process = new Process();
print_r($process->domain);
echo "<br />";
print_r($process->confession);

print_r($process->filter);

if ($process->filter != null) {

	echo "IP Filter: ";
	echo ($process->filter->recentIp()) ? 'true' : 'false';
	echo "\nDuplicate Filter: ";
	echo ($process->filter->duplicatePost()) ? 'true' : 'false';
	echo "\nNumber Filter: ";
	echo ($process->filter->hasNumber()) ? 'true' : 'false';
	echo "\nProfanity Filter: ";
	echo ($process->filter->hasProfanity()) ? 'true' : 'false';

}

echo "</pre>";



//$domain = new Domain();
//echo $domain->domain . " " . $domain->pageid . " " . $domain->pagetoken;
?>
