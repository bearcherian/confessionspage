<?php
require_once('../dao/db.php');

class Domain {
	
	var $domain;
	var $pageid;
	var $pagetoken;

	function __construct($dom = null) {
		$this->setDomain($dom);
		$this->setPageToken();
		$this->setPageId();

		$config = $this->isConfigured();
		if ($config == "TABLECHECK_FAILED") {
			$this->createTable();
		}
	}

	function getDomain() {
		return $this->domain;
	}

	function setDomain($dom) {
		if (empty($dom)) {
			$this->domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));
		} else {
			$this->domain = $dom;
		}
	}

	function getPageToken() {
		return $this->pagetoken;
	}
	
	function setPageToken($value = null) {
		if (empty($value)) {
			try {
				$db = new Database();
				$db->connect();
				$result = $db->query('SELECT access_token FROM pages WHERE cp_domain = :domain;',array(':domain' => $this->domain));
				$db->close();
		        	$this->pagetoken = $result[0]["access_token"];
			} catch(PDOException $e) {
	        		echo "Could not find proper token for " . $this->domain . ".confessionspage.com.";
			}
		} elseif ($this->pagetoken != $value) {
			$this->pagetoken = $value;

			//Update DB with the new token
			$stmt = "UPDATE pages SET access_token = :token WHERE cp_domain = :domain";
			$vals = array(	':token' => $this->pagetoken,
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
		if (!($this->tableCheck())) {
			error_log("tablecheck_failed");
			return "TABLECHECK_FAILED";
		}
		if (empty($this->pageid)) {
			return "PAGEID_FAILED";
		}
		if (empty($this->pagetoken)) {
			return "PAGETOKEN_FAILED";
		}
		return true;
	}
	
	function tableCheck() {
		try {
			$tablename = $this->domain . "_posts";
			$stmt = "SELECT 1 FROM " . $tablename . " LIMIT 1;";
			$db = new Database();
			$db->connect();
			$result = $db->query($stmt,null);
			$db->close();
			if (isset($result->errorInfo)) {
				error_log("Domain::tablecheck() - No table found.");
				return false;
			}	
			
			return true;
		} catch (PDOException $e) {
			error_log("Domin::tablecheck() - No table found.");
			return false;
		}		
	}
	
	function createTable() {
		try {
			$tablename = $this->domain . "_posts";
			$stmt = "CREATE TABLE " . $tablename . " " . 
				"AS (SELECT * FROM posts WHERE domain = :domain)";
			$values = array(':domain' => $this->domain);
			$db = new Database();
			$db->connect();
			$e = $db->insert($stmt,$values);
			if (isset($e->errorInfo)) {
				$code = $e->getCode();
				if ($code != "42S01") {
					//echo $e->getMessage() . "<br />";
					//print_r($e);
					return false;
				}
			}
			$db->close();
			return true;
		} catch (PDOException $e) {
			echo "Unable to create table for " . $doamin;
		}
	}
}
?>
