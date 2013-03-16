<?php
require_once('../config/app.php');
require_once('../dao/db.php');
require_once('../model/domain.php');
require_once('../model/confession.php');
require_once('../process/filter.php');

class Process {

	var $confession;
	var $page;
	var $domain;
	var $allpages;

	function __construct() {
		$this->domain = $this->getNextPage();
		$this->updatePage();
		$this->confession = $this->getNextPost();
	}

	function getNextPage() {
		$stmt = "SELECT * FROM pages ORDER BY lastprocessed ASC LIMIT 1;";
		$db = new Database();
		$db->connect();
		$result = $db->query($stmt,null);
		$db->close();
	
		return new Domain($result[0]['cp_domain']);

	}

	function getNextPost() {
		$stmt = "SELECT * FROM " . $this->domain->domain . "_posts WHERE post_status = 'new' ORDER BY timestamp ASC LIMIT 1;";
		$db = new Database();
		$db->connect();
		$result = $db->query($stmt,null);
		$db->close();
		
		if (isset($result->errorInfo) || empty($result)) {
			return null;
		} else {
			return new Confession($result[0]['post_id'],$this->domain->domain);
		}
	}

	function updatePage() {
		$stmt = "UPDATE pages " . 
			"SET lastprocessed = NOW() " .
			"WHERE cp_domain = :page;";
		$values = array( ":page" => $this->domain->domain); //['cp_domain']);
		$db = new Database();
		$db->connect();
		$result = $db->insert($stmt,$values);
		$db->close();
	}
	
	function passFilter() {
		
	
		return true;
	}

	function postToFb() {
		$pageToken = $this->domain->getPageToken();
        	$pageId = $this->domain->getPageId();
        	$postURL = "https://graph.facebook.com/".$pageId."/feed";

       		$data = array(
                	'message' => $this->confession->content, 
                	'access_token' => $this->domain->pageToken
        	);

        	// use key 'http' even if you send the request to https://...
        	$options = array('http' => array('method'  => 'POST','content' => http_build_query($data)));
        	$context  = stream_context_create($options);
        	$result = file_get_contents($postURL, false, $context);

        	$fb = json_decode($result,true);	
		if (isset($fb["id"])) {
			$this->confession->setFbId($fb["id"]);
			$this->updateConfessionFBId();
			return true;
		} else {
			return false;
		}
	}
	
	function updateConfessionFBId() {
		$stmt = "UPDATE " . $this->domain->domain . "_posts " . 
			"SET fb_id = :fbid WHERE post_id = :postid;";
		$vals = array(":fbid" => $confession->fbid,
				":postid" => $confession->postid);
		$db = new Database();
		$db->connect();
		$db->insert($stmt,$vals);
		$db->close();
	}	
}
?>
