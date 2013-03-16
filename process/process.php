<?php
require_once('../config/app.php');
require_once('../dao/db.php');
require_once('../model/domain.php');
require_once('../model/confession.php');

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
		
		if (isset($result->errorInfo)) {
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

	function postToFb() {
		$domain = new Domain($this->domain);
		$pageToken = $domain->getPageToken();
        	$pageId = $domain->getPageId();

        	$postURL = "https://graph.facebook.com/".$pageId."/feed";

       		$data = array(
                	'message' => $this->content, 
                	'access_token' => $pageToken
        	);

        	// use key 'http' even if you send the request to https://...
        	$options = array('http' => array('method'  => 'POST','content' => http_build_query($data)));
        	$context  = stream_context_create($options);
        	$result = file_get_contents($postURL, false, $context);

        	$fb = json_decode($result,true);	
		if (isset($fb["id"])) {
			$this->fb_id = $fb["id"];
			return true;
		} else {
			return false;
		}
	}
	
}
?>
