<?php
require_once('db.php');

class Domain {
	
	var $domain;
	var $pageid;
	var $pagetoken;

	function __construct() {
		$this->setDomain();
		$this->setPageToken();
		$this->setPageId();
	}
	function __construct($dom) {
		$this->setDomain($dom);
		$this->setPageToken();
		$this->setPageId();
	}

	function getDomain() {
		return $this->domain;
	}

	function setDomain() {
		$this->domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));
		//$this->domain = ($this->domain == "pre") ? "temple" : "pre";
	}
	function setDomain($dom) {
		$this->domain = $dom;
	}

	function getPageToken() {
		return $this->pagetoken;
	}
	
	function setPageToken() {
		try {
			$db = new Database();
			$db->connect();
			$result = $db->query('SELECT access_token FROM pages WHERE cp_domain = :domain;',array(':domain' => $this->domain));
			$db->close();
		        $this->pagetoken = $result[0]["access_token"];
	
		} catch(PDOException $e) {
	        	echo "Could not find proper token for " . $this->domain . ".confessionspage.com.";
		}
	}

	function setPageToken($value) {
		if ($this->pagetoken != $value) {
			$this->pagetoken = $value;

			//Update DB with the new token
			$stmt = "UPDATE pages SET access_token = :token WHERE cp_domain = :domain";
			$vals = array(	':token' => $value,
					':domain' => $this->domain);
			$db = new Database();
			$db->connect();
			$db->insert($stmt,$vals);
			$db->close();
		}
	}

	function getPageId() {
		return $this->pageid;
	}

	function setPageId() {
                try {
                        $db = new Database();
                        $db->connect();
                        $result = $db->query('SELECT page_id FROM pages WHERE cp_domain = :domain;',array(':domain' => $this->domain));
                        $db->close();
                        $this->pageid = $result[0]["page_id"];

                } catch(PDOException $e) {
                        echo "Could not find proper configuration for " . $domain . ".confessionspage.com.";
                }
        }

	function isConfigured() {
		return (isset);
	}
}
?>
