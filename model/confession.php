<?php
require_once('../config/app.php');
require_once('../dao/db.php');

class Confession {
	
	var $postid;
	var $content;
	var $ip;
	var $timestamp;
	var $fbid;
	var $status;
	var $proxy;

	function __construct($value,$domain) {
		$stmt = "SELECT * FROM " . $domain . "_posts WHERE post_id = :postid;";
		$values = array(':postid' => $value); //$this->postid);

		$db = new Database();
		$db->connect();
		$results = $db->query($stmt,$values);
		$db->close();

		if ( isset($results->errorInfo)) {
			return false;
		}
		$this->setPostId($results[0]["post_id"]);
		$this->setIp($results[0]["ip_address"]);
		$this->setProxy($results[0]["ip_proxy"]);
		$this->setContent($results[0]["post"]);
		$this->setTimestamp($results[0]["timestamp"]);
		$this->setStatus($results[0]["post_status"]);
		$this->setFbId($results[0]["fb_id"]);
		//$this->setDomain($results[0]["domain"]);
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

}
?>
