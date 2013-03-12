<?php
class Database {

	var $db_user 	 = "cp";
	var $db_password = "Never4getDannybr0wn.";
	var $db_host 	 = "localhost";
	var $db_db 	 = "cp_cp";

	var $dbconn;	//DB Connection
	
	function connect() {
		$this->dbconn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_db,$this->db_user,$this->db_password);
		$this->dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/**Runs and returns results of a query
	 * $stmt - Prepared statement (e.g., SELECT * FROM table WHERE x = ?
	 * $values - Array of values used in prepared statement
	 */
	function query($stmt,$values) {
		try {
		        $qStmt = $this->dbconn->prepare($stmt);
			$qStmt->execute($values);
			return $qStmt->fetchAll();
		} catch(PDOException $e) {
			echo "Error on query";
		}
	}

        function insert($stmt,$values) {
                try {           
                        $qStmt = $this->dbconn->prepare($stmt);
                        $qStmt->execute($values);
                } catch(PDOException $e) {
                        echo "Error on insert"; //$e;
                }
        }	

	function close() {
		$this->dbconn = null;
	}
}

?>
