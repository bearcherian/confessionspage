<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/config/app.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/dao/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/model/domain.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/model/confession.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/process/filter.php');

class Process {

	var $confession;
	var $page;
	var $domain;
	var $allpages;
	var $filter;

	function __construct() {
		$stmt = "SELECT * FROM pages ORDER BY lastprocessed ASC;";
		$db = new Database();
		$db->connect();
		$result = $db->query($stmt,null);
		$db->close();

		//Find the first page with a new post and process only that one
		foreach ($result as $page) {
			$db=null;
			$stmt = "SELECT * FROM " . $page["cp_domain"] . "_posts " . 
				"WHERE fb_id IS NULL AND (post_status = 'new' OR post_status = 'approved') " . 
				"ORDER BY timestamp ASC LIMIT 1;";
                	$db = new Database();
                	$db->connect();
                	$result = $db->query($stmt,null);
                	$db->close();		

			if(isset($result[0]['post_id'])) {
				$this->domain = new Domain($page["cp_domain"]);
				$this->updatePage();
				$this->confession = new Confession($result[0]['post_id'],$this->domain->domain);
				$this->filter = new Filter(new Confession($this->confession->getPostId(),$this->domain->domain));
				break;
			}
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

	/**
	 *	Posts to Facebook based on domain. Updated db with FB upon success.
	 */
	function postToFb() {
		$pageToken = $this->domain->getPageToken();
        	$pageId = $this->domain->getPageId();
        	$postURL = "https://graph.facebook.com/".$pageId."/feed";

       		$data = array(
                	'message' => $this->confession->content, 
                	'access_token' => $this->domain->getPageToken()
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
			$this->postError();
			return false;
		}
	}

	/**
	 *	Updates DB with fb ID for this post
	 */	
	function updateConfessionFBId() {
		$stmt = "UPDATE " . $this->domain->domain . "_posts " . 
			"SET fb_id = :fbid, post_status = 'approved' WHERE post_id = :postid;";
		$vals = array(":fbid" => $this->confession->fbid,
				":postid" => $this->confession->postid);
		$db = new Database();
		$db->connect();
		$db->insert($stmt,$vals);
		$db->close();
	}	
	
	function postFiltered() {
		$stmt = "UPDATE " . $this->domain->domain . "_posts " .
                        "SET post_status = 'filtered' WHERE post_id = :postid;";
                $db = new Database();
		$vals = array(":postid" => $this->confession->postid);
                $db->connect();
                $db->insert($stmt,$vals);
                $db->close();
        }

	function postError() {
		$stmt = "UPDATE " . $this->domain->domain . "_posts " .
                        "SET post_status = 'error' WHERE post_id = :postid;";
                $db = new Database();
		$vals = array(":postid" => $this->confession->postid);
                $db->connect();
                $db->insert($stmt,$vals);
                $db->close();
        }

}
?>
