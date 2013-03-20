<?php

class Filter {

	var $confession;
	var $tablename;

	function __construct($confession) {
		$this->confession = $confession;
		$this->tablename = $confession->getDomain() . "_posts";
	}
	
        function isApproved() {
                return ((bool)($this->confession->status == "approved"));
        }

	/**
	 *
	 */
	function hasCaps() {
		return preg_match("/[A-Z]{3,}/",$this->confession->getContent());
	}

	/**
	 *	Returns true if this IP has posted within the last hour.
	 */
	function recentIp() {
		$stmt = "SELECT * FROM " . $this->tablename . " WHERE fb_id IS NOT NULL AND ip_address = :ip AND post_id <> :currentid ORDER BY timestamp ASC LIMIT 1;";
		$vals = array( ":ip" => $this->confession->getIp(),
				":currentid" => $this->confession->getPostId());		
		$db = new Database();
		$db->connect();
		$result = $db->query($stmt,$vals);
		$db->close();

		//check query results
		if (empty($result)) {
			return false;
		} else if (isset($result->errorInfo)) {
			echo "Error on IP check";
			return false;
		} else {
			//check that this IP hasn't posted within the hour
			$currentTime = new Datetime($this->confession->getTimestamp());
			$oldTime = new Datetime($result[0]["timestamp"]);
			$diff = $currentTime->getTimestamp() - $oldTime->getTimestamp();

			if ( $diff <= 3600 ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 *	Returns true if another post within the last 30 days contains the same content.
	 */
	function duplicatePost() {
		$stmt = "SELECT * FROM " . $this->tablename . " WHERE post_id <> :postid AND timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY);";
		$vals = array(":postid" => $this->confession->getPostId());
		$db = new Database();
		$db->connect();
		$results = $db->query($stmt,$vals);
		$db->close();

		if (empty($results) || isset($results->errorInfo)) { 
			return false;
		} else {
			foreach ($results as $r ) {
				if ($r['post'] == $this->confession->getContent()) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

	/**
	 *	Returns true if it contains any of the specified profanity words
	 */
	function hasProfanity() {
		$profaneExp = "/(fuck|shit|bitch|cock|cunt|fag|slut|nigger)/i";

		return (bool)preg_match($profaneExp, $this->confession->getContent());
	}

	/**
	 *	Returns true if the content contains 3 or more digits in a row
	 */
	function hasNumber() {
		return (bool)preg_match("/([0-9]){3,}/",$this->confession->getContent());
	}
}
?>
