<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: json.class.php
		Description : This PHP class handles the fetching of data and conversion to json object.	
	**/

	class json {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		
		function __construct() {
			include_once('php/api/settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'json.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		public function fetch($table) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;

			if (!isset($table) || (!$table)) {
				die($error400);
				return;
			}

			$table = strtolower(filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));


			if ($table == 'loctable') {
				$qry = "CALL fetch_json('loctable')";
			} elseif ($table == 'loyaltytable') {
				$qry = "CALL fetch_json('loyaltytable')";
			} elseif ($table == 'producttable') {
				$qry = "CALL fetch_json('producttable')";
			} elseif ($table == 'settings') {
				$qry = "CALL fetch_json('settings')";
			} elseif ($table == 'skutable') {
				$qry = "CALL fetch_json('skutable')";
			} elseif ($table == 'faqtable') {
				$qry = "CALL fetch_json('faqtable')";
			} else {
				$logs->write_logs('JSON Fetch', $file_name, "Bad Request - [Message: Unable to complete process.] [Table: $table]");
		 		die($error400);
				return;
		 	}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();
			
			$output = array();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('JSON Fetch', $file_name, "Table: [$table]\t Status: [Success]");
			}

			$json_string = json_encode(array(array("response"=>"Success", "data"=>$output)));
			$json_string = str_replace('\r\n', '<br>', $json_string);
			$json_string = str_replace('\r', '<br>', $json_string);
			$json_string = str_replace('\n', '<br>', $json_string);

			return $json_string;

			// return json_encode(array(array("response"=>"Success", "data"=>$output)));
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'json.class.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>