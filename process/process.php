<?php
require_once('../config/app.php');
require_once('../config/db.php');
require_once('../config/domain.php');
require_once('../config/confession.php');

class Process() {

	var $confession;
	var $pages;

	function __construct($postid) {
		$this-confession = new Confession($postid);
	}

	function getNextPage() {
		$stmt = "SELECT * FROM pages ORDER BY timestamp DESC LIMIT 1;";
		$values = null;
		$db = new Database();
		$db->connect();
		$this->pages = $db->query($stmt,$values);
		$db->close();

	}

}
?>
