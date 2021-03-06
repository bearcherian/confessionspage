<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/config/app.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/dao/db.php');

class Confession {
	
	var $postid;
	var $ip;
	var $proxy;
	var $timestamp;
	var $content;
	var $fbid;
	var $status;
	var $domain;

	function __construct($value,$domain) {
		$stmt = "SELECT * FROM " . $domain . "_posts WHERE post_id = :postid;";
		$values = array(':postid' => $value); //$this->postid);

		$db = new Database();
		$db->connect();
		$results = $db->query($stmt,$values);
		$db->close();

		if ( isset($results->errorInfo) || empty($results)) {
			return false;
		}
		$this->setPostId($results[0]["post_id"]);
		$this->setIp($results[0]["ip_address"]);
		$this->setProxy($results[0]["ip_proxy"]);
		$this->setContent($results[0]["post"]);
		$this->setTimestamp($results[0]["timestamp"]);
		$this->setStatus($results[0]["post_status"]);
		$this->setFbId($results[0]["fb_id"]);
		$this->setDomain($domain);
	}
	
	function getPostId() { return $this->postid; }
	function setPostId($value) { $this->postid = $value; }	

	function getContent() { return $this->content; }
	function setContent($value) { $this->content = $value; }	

	function getIp() { return $this->ip; }
	function setIp($value) { $this->ip = $value; }	

	function getTimestamp() { return $this->timestamp; }
	function setTimestamp($value) { $this->timestamp = $value; }	

	function getFbId() { return $this->fbid; }
	function setFbId($value) { $this->fbid = $value;}	

	function getStatus() { return $this->status; }
	function setStatus($value) { $this->status = $value; }	

	function getProxy() { return $this->proxy; }
	function setProxy($value) { $this->proxy = $value; }	
	
	function getDomain() { return $this->domain; }
	function setDomain($value) { $this->domain = $value; }
}
?>
