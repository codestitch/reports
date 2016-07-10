<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: dashboard.class.php
		Description : This PHP class handles all the dashboard related major functions.	
	**/

	// require_once('reports/php/excelwriter.class.php'); 
	include 'reports/php/PHPExcel.php';
	include 'reports/php/PHPExcel/Writer/Excel2007.php';

	class reports {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		private static $tmp_path;
		
		function __construct() {
			include_once('php/api/settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'reports.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
			self::$tmp_path = PATH; 
		}

		/********** Session Checker **********/
		public function session_checker($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_session_checker(?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Session Checker', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Session Checker', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_session_checker] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_session_checker] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_session_checker] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$result = $row['result'];
				$sql->close();
				return json_encode(array(array("response"=>"Success", "data"=>array("message"=>$row['result']))));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "data"=>array("message"=>"Expired"))));
			}
		}

		/********** Login **********/
		public function login($username, $password) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_login(?, ?)";

			if (!isset($username) || (!$username)) {
				$logs->write_logs('Dashboard Login', $file_name, "Bad Request - Invalid/Empty [username: $username]");
				die($error400);
				return;
			}

			if (!isset($password) || (!$password)) {
				$logs->write_logs('Dashboard Login', $file_name, "Bad Request - Invalid/Empty [password: $password]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_login] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $username, $password)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_login] [Param: $username, $password]");
			    die($error000);
				return;
			}

			$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_login] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if ($row['result'] == 'Failed') {
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
					die();
				} else {
					$accountID = $row['result'];
					$loginSession = $row['loginSession'];
					$role = $row['role'];
					$sql->close();
					return json_encode(array(array("response"=>"Success", "data"=>array("accountID"=>$accountID, "loginSession"=>$loginSession, "role"=>$role))));
				}
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
			}
		}

		/********** Password **********/
		public function password($accountID, $my_session_id, $old_password, $new_password) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_password(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($old_password) || (!$old_password)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [old_password: $old_password]");
				die($error400);
				return;
			}

			if (!isset($new_password) || (!$new_password)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [new_password: $new_password]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_password] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $old_password, $new_password)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_password] [Param: $accountID, $my_session_id, $old_password, $new_password]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$old_password = filter_var($old_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$new_password = filter_var($new_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_password] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** JSON **********/
		public function json($accountID, $my_session_id, $table) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_json(?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($table) || (!$table)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [table: $table]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_json] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $table)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_json] [Param: $accountID, $my_session_id, $table]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_json] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('REPORTS LOG', $file_name, "Table: [$table]\t Status: [Success]");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Error")));
			}			
		}

		/********** User Registration **********/
		public function get_userPlatform($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userPlatform(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userPlatform] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userPlatform] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userPlatform] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }

				    $row['v_platform'] = ucfirst($row['v_platform']);

					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "User Registration");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_userDownload($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userDownload(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userDownload] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userDownload] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userDownload] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }

				    $row['platform'] = ucfirst($row['platform']);
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "User Download");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** User Download **********/
		public function get_userage($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userage(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userage] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userage] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userage] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "User Age");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_usergender($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_usergender(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_usergender] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_usergender] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_usergender] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
				    
				    $row['gender'] = ucfirst($row['gender']);
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "User Gender");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_customerdailyRegistration($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customerdailyRegistration(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyRegistration] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyRegistration] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customerdailyRegistration] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Customer Daily Registration");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		/********** User Download **********/
		public function get_customermonthlyBday($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customermonthlyBday(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customermonthlyBday] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customermonthlyBday] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customermonthlyBday] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Monthly User Birthday");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_customerdailyBday($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customerdailyBday(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyBday] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyBday] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customerdailyBday] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily User Birthday");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		/********** User Download **********/
		public function get_customerdailySales($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customerdailySales(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailySales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailySales] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customerdailySales] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				        if ($array_key == 'TotalSales') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'TotalSnap') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily User Sales");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		/********** User Download **********/
		public function get_customerdailyRedemption($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customerdailyRedemption(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyRedemption] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customerdailyRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 
				        if ($array_key == 'TotalRedemption') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily User Redemption");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		/********** Customer Daily Statistics **********/
		public function get_customerdailyStatistics($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_customerdailyStatistics(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_customerdailyStatistics] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_customerdailyStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');

				        if ($array_key == 'earnedPoints') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'totalSales') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'earnTransactions') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'redeemTransactions') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily User Statistics");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_dailyproductStatistics($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_dailyproductStatistics(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailyproductStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailyproductStatistics] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_dailyproductStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Product Stat");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Spent Yearly Sales **********/
		public function get_spentdailySales($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_spentdailySales(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentdailySales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentdailySales] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_spentdailySales] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "SPent Daily Sales");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Spent Daily Sales**********/
		public function get_spentYearlySales($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_spentYearlySales(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentYearlySales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentYearlySales] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_spentYearlySales] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Spent Yearly Sales");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Spent Daily Customer**********/
		public function get_spentdailyCustomer($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_spentdailyCustomer(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentdailyCustomer] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentdailyCustomer] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_spentdailyCustomer] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				        if ($array_key == 'TotalSpend') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    	else if ($array_key == 'Average') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Spent Yearly Customer");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Spent Daily Sales**********/
		public function get_spentaverageCustomer($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_spentaverageCustomer(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentaverageCustomer] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_spentaverageCustomer] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_spentaverageCustomer] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    	if ($array_key == 'TotalSpend') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    	elseif($array_key == 'AveragePerTransaction') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    	elseif($array_key == 'AverageDailySpend') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    	
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Spent Average Customer");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output), JSON_NUMERIC_CHECK));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Total Redemption **********/
		public function get_dailyRedeem($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_dailyRedeem(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailyRedeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailyRedeem] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_dailyRedeem] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Redemption");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Total Redemption **********/
		public function get_totalRedeem($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_totalRedeem(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_totalRedeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_totalRedeem] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_totalRedeem] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Total Redemption");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}
 
		/********** Get Branch Daily Sales **********/
		public function get_dailybranchSales($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_dailybranchSales(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchSales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchSales] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_dailybranchSales] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				        if ($array_key == 'TotalSales') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'TotalSnap') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Sales");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** User Download **********/
		public function get_dailybranchRedemption($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_dailybranchRedemption(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchRedemption] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_dailybranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				        if ($array_key == 'totalRedemption') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}  
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Redemption");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** User Download **********/
		public function get_dailybranchStatistics($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_dailybranchStatistics(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_dailybranchStatistics] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_dailybranchStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				        if ($array_key == 'earnedPoints') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	} 
				        else if ($array_key == 'totalSales') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				        else if ($array_key == 'earnTransactions') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				        else if ($array_key == 'redeemTransactions') {
				    		$row[$array_key] = (int)str_replace(array(' ', ','), '', $row[$array_key]); 
				    	}
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Statistics");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_voucherBranchRedemption($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_voucherBranchRedemption(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherBranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherBranchRedemption] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_voucherBranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Statistics");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		
		/********** User Download **********/
		public function get_voucherBranchDailyRedemption($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_voucherBranchDailyRedemption(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherBranchDailyRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherBranchDailyRedemption] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_voucherBranchDailyRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Statistics");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** User Download **********/
		public function get_voucherDailyCustomers($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_voucherDailyCustomers(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherDailyCustomers] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_voucherDailyCustomers] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_voucherDailyCustomers] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 
				    }
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Reports Fetch', $file_name, "Daily Branch Statistics");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}




		/********** Export User Platform **********/
		public function export_registeredcustomerapp($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_registeredcustomerapp(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_registeredcustomerapp] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_registeredcustomerapp] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_registeredcustomerapp] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 
 
				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Reports Export', $file_name, "Export Registered customer app");

				if (!isset($row['result'])) {  
					// echo "resutl";
					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'registeredcustomerapp.xlsx';
 
					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__));  
					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export User Platform **********/
		public function export_registeredcustomercard($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_registeredcustomercard(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_registeredcustomercard] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_registeredcustomercard] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_registeredcustomercard] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 
 
				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Reports Export', $file_name, "Registered Customer card");

				if (!isset($row['result'])) {  
					// echo "resutl";
					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'registeredcustomercard.xlsx';
 
					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__));  
					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}




		/********** Export User Platform **********/
		public function export_userPlatform($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_userPlatform(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userPlatform] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userPlatform] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_userPlatform] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "User Platform");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'userplatform.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 

					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export User Platform **********/
		public function export_userDownload($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_userDownload(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userDownload] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userDownload] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_userDownload] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "User Download");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report');  
					$filename = 'userDownload.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 

					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		/********** Export User Platform **********/
		public function export_userage($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_userage(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userage] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_userage] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_userage] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "User Age");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 

					$filename = 'userAge.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 

					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export User Platform **********/
		public function export_usergender($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_usergender(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_usergender] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_usergender] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_usergender] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "User Gender");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'userGender.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		// ----------------------------------------------------------------------------------------
		// ------------------------------------ EXPORT BRANCH SECTION -----------------------------
		// ----------------------------------------------------------------------------------------


		/********** Export Daily Branch Sales **********/
		public function export_dailybranchSales($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_dailybranchSales(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchSales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchSales] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_dailybranchSales] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Daily Branch Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'userGender.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export Daily Branch Redemption **********/
		public function export_dailybranchRedemption($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_dailybranchRedemption(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchRedemption] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_dailybranchRedemption] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Daily Branch Redemption");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'userGender.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/******************************************************************************************/
		/******************************************************************************************/

		/************************************ BRANCH STATISTICS ***********************************/ 

		/******************************************************************************************/
		/******************************************************************************************/



		/********** Export Daily Branch Statistics **********/
		public function export_dailybranchStatistics($accountID, $my_session_id ) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_dailybranchStatistics(?, ? )";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}  

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_dailybranchStatistics] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_dailybranchStatistics] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Daily Branch Statistics");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'dailybranchStatistics.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		} 


		/********** Export Branch Transaction History Points **********/
		public function export_branchtranshistory_points($accountID, $my_session_id, $startDate, $endDate, $locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranshistory_points(?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($locID) ) {
				$locID = NULL;
			}
 
			// echo "CALL export_branchtranshistory_points('$accountID', '$my_session_id', '$startDate', '$endDate', '$locID')";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_points] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id, $startDate, $endDate, $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_points] [Param: $accountID, $my_session_id, $startDate, $endDate, $locID]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranshistory_points] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction History Points");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'branchtranshistory_points.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export Branch Transaction History Redeem **********/
		public function export_branchtranshistory_redeem($accountID, $my_session_id, $startDate, $endDate, $locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranshistory_redeem(?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			}  
			
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_redeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id, $startDate, $endDate, $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_redeem] [Param: $accountID, $my_session_id, $startDate, $endDate, $locID]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranshistory_redeem] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				}  

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction History Redeem");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'transhistory_redeem.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction History Sales **********/
		public function export_branchtranshistory_sales($accountID, $my_session_id, $startDate, $endDate, $locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranshistory_sales(?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			}  

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_sales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id, $startDate, $endDate, $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranshistory_sales] [Param: $accountID, $my_session_id, $startDate, $endDate, $locID]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranshistory_sales] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction History Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'branchtranshistory_sales.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Points **********/
		public function export_branchtranssummary_points($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranssummary_points(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_points] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_points] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranssummary_points] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Points");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'branchtranssummary_points.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Redeem **********/
		public function export_branchtranssummary_redeem($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranssummary_redeem(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_redeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_redeem] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranssummary_redeem] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0; 

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Redeem");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'branchtranssummary_redeem.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Sales **********/
		public function export_branchtranssummary_sales($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_branchtranssummary_sales(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_sales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_branchtranssummary_sales] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_branchtranssummary_sales] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'branchtranssummary_sales.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}






		/******************************************************************************************/
		/******************************************************************************************/

		/************************************ CUSTOMER STATISTICS ***********************************/ 

		/******************************************************************************************/
		/******************************************************************************************/



		/********** Export Customer Transaction History Points **********/
		public function export_customerinformation($accountID, $my_session_id, $email, $fname, $lname, $city) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customerinformation(?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}  

			if (!isset($email)  ) {
				$logs->write_logs('Reports Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if($email != "")
			{
				if (!$this->validate_email($email)) {
					$logs->write_logs('Reports Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
					die($error400);
					return;
				}

			}

			if (!isset($fname)  ) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
				die($error400);
				return;
			} 

			if (!isset($lname)  ) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
				die($error400);
				return;
			} 

			if (!isset($city)  ) {
				$logs->write_logs('Reports JSON Fetch', $file_name, "Bad Request - Invalid/Empty [city: $city]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customerinformation] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $email, $fname, $lname, $city)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customerinformation] [Param: $accountID, $my_session_id, $email, $fname, $lname, $city]");
			    die($error000);
				return;
			}
 
 			// echo "CALL export_customerinformation('$accountID','$my_session_id','$email','$fname','$lname','$city')";

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customerinformation] [Query: $qry]");
			    die($error000);
				return;
			}   

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Reports Export', $file_name, "Customer Information");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customertinformation.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export Customer Daily Statistics **********/
		public function export_customerdailyStatistics($accountID, $my_session_id ) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customerdailyStatistics(?, ? )";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}  

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customerdailyStatistics] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id )) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customerdailyStatistics] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customerdailyStatistics] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Daily Statistics");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customerdailyStatistics.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		} 


		/********** Export Customer Transaction History Points **********/
		public function export_customertranshistory_points($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customertranshistory_points(?, ?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($email)  ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if($email != "")
			{
				if (!$this->validate_email($email)) {
					$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
					die($error400);
					return;
				}

			}

			if (!isset($fname)  ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
				die($error400);
				return;
			} 

			if (!isset($lname)  ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
				die($error400);
				return;
			} 

			// echo "CALL export_customertranshistory_points('$accountID','$my_session_id','$startDate','$endDate','$email','$fname','$lname')";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_points] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_points] [Param: $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customertranshistory_points] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Transaction History Points");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customertranshistory_points.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		/********** Export Customer Transaction History Redeem **********/
		public function export_customertranshistory_redeem($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customertranshistory_redeem(?, ?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($email) ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}


			if($email != "")
			{
				if (!$this->validate_email($email)) {
					$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
					die($error400);
					return;
				}
				
			}

			if (!isset($fname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
				die($error400);
				return;
			} 

			if (!isset($lname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_redeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_redeem] [Param: $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customertranshistory_redeem] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Transaction History Redeem");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customertranshistory_redeem.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction History Sales **********/
		public function export_customertranshistory_sales($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customertranshistory_sales(?, ?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($email)  ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			} 

			if($email != "")
			{
				if (!$this->validate_email($email)) {
					$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
					die($error400);
					return;
				}
				
			}

			if (!isset($fname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
				die($error400);
				return;
			} 

			if (!isset($lname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_sales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customertranshistory_sales] [Param: $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customertranshistory_sales] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Transaction History Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customertranshistory_sales.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Points **********/
		public function export_customersummary_points($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customersummary_points(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_points] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_points] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customersummary_points] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Transaction Summary Points");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customersummary_points.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Redeem **********/
		public function export_customersummary_redeem($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customersummary_redeem(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_redeem] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_redeem] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customersummary_redeem] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;
 

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Redeem");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customersummary_redeem.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}

		
		/********** Export Branch Transaction Summary Sales **********/
		public function export_customersummary_sales($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_customersummary_sales(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_sales] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_customersummary_sales] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_customersummary_sales] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'customersummary_sales.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		// ----------------------------------------------------------------------------------------
		// ------------------------------------ SPEND SECTION ---------------------------
		// ----------------------------------------------------------------------------------------



		
		/********** Total Customer Spent **********/
		public function export_totalCustomerSpent($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_totalCustomerSpent(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_totalCustomerSpent] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_totalCustomerSpent] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

 
			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_totalCustomerSpent] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Total Customer Spent");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'totalCustomerSpent.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}


		
		/********** Export Branch Transaction Summary Sales **********/
		public function export_averageCustomerSpent($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_averageCustomerSpent(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_averageCustomerSpent] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_averageCustomerSpent] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_averageCustomerSpent] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Branch Transaction Summary Sales");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'averageCustomer.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		// ----------------------------------------------------------------------------------------
		// ------------------------------------ EXPORT REWARD SECTION -----------------------------
		// ----------------------------------------------------------------------------------------




		
		/********** Export Redemption Breakdown **********/
		public function export_redemptionBreakdown($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_redemptionBreakdown(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_redemptionBreakdown] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_redemptionBreakdown] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_redemptionBreakdown] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Redemption Breakdown");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'redemptionBreakdown.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}




		
		/********** Export Promo Breakdowns **********/
		public function export_promoBreakdown($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_promoBreakdown(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_promoBreakdown] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_promoBreakdown] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_promoBreakdown] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Promo Breakdown");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'promoBreakdown.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}




		// ----------------------------------------------------------------------------------------
		// -------------------------------- EXPORT PRODUCT STATISTICS -----------------------------
		// ----------------------------------------------------------------------------------------




		/********** Export Product Transaction History Branch **********/
		public function export_producttransactionhistory_branch($accountID, $my_session_id, $startDate, $endDate, $locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_producttransactionhistory_branch(?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_producttransactionhistory_branch] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id, $startDate, $endDate, $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_producttransactionhistory_branch] [Param: $accountID, $my_session_id, $startDate, $endDate, $locID]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_producttransactionhistory_branch] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Product Transaction History Branch");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'producttransactionhistory_branch.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** Export Customer Product Transaction History **********/
		public function export_producttransactionhistory_customer($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_producttransactionhistory_customer(?, ?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			if (!isset($email) ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!$this->validate_email($email)) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!isset($fname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
				die($error400);
				return;
			} 

			if (!isset($lname) ) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_producttransactionhistory_customer] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_producttransactionhistory_customer] [Param: $accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			// echo "CALL export_producttransactionhistory_customer('$accountID', '$my_session_id', '$startDate', '$endDate', 
			// 	'$email', '$fname', '$lname')";
			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_producttransactionhistory_customer] [Query: $qry]");
			    die($error000);
				return;
			}  


			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0;

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Customer Product Transaction History");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'producttransactionhistory_customer.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}




		/********** Export Branch Transaction Summary Redeem **********/
		public function export_voucherTransHistory($accountID, $my_session_id, $startDate, $endDate) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL export_voucherTransHistory(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($startDate) || (!$startDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [startDate: $startDate]");
				die($error400);
				return;
			} 

			if (!isset($endDate) || (!$endDate)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [endDate: $endDate]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_voucherTransHistory] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $startDate, $endDate)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: export_voucherTransHistory] [Param: $accountID, $my_session_id, $startDate, $endDate]");
			    die($error000);
				return;
			}


			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$startDate = date('Y-m-d', strtotime($startDate));
			$endDate = date('Y-m-d', strtotime($endDate));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: export_voucherTransHistory] [Query: $qry]");
			    die($error000);
				return;
			}  

			// echo "startDate:". $startDate;
			// echo "<br>endDate:". $endDate;

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("Appsolutely Inc");
			$objPHPExcel->getProperties()->setLastModifiedBy("CodeStitch");
			$objPHPExcel->getProperties()->setTitle("User Platform");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Report");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Statistics Report");
			$objPHPExcel->getProperties()->setDescription("Statistics report for merchant");

			$objPHPExcel->getSheet(0);  
			

			$header = 'a1:h1'; 
			$style = array(
			    'font' => array('bold' => true,),
			    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
			    );
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style); 
			 

			// Get SQL statement result
			$result = $sql->get_result();
			$headerArray = array();
			$inc = 0; 

			if ($result->num_rows > 0) { 

				while ($row = $result->fetch_assoc()) {
					$headCount = count($row);
					foreach ($row as $array_key => $array_value) {
				       	$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'); 

				       	$inc++;
				       	if ($inc<=$headCount) { 
				       		array_push($headerArray, $array_key);
						} 
				    }
					array_push($output, $row);
				} 

				// Write Data to Cells
			 	$objPHPExcel->getActiveSheet()->fromArray($headerArray, ' ', 'A1');
			 	$objPHPExcel->getActiveSheet()->fromArray($output, ' ', 'A2');

				$logs->write_logs('Dashboard Export', $file_name, "Voucher Transaction History");

				if (!isset($row['result'])) {  

					$objPHPExcel->getActiveSheet()->setTitle('report'); 
					$filename = 'vouchertransactionHistory.xlsx';

					// SAVE
					$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
					$writer->save(str_replace(__FILE__,'reports/excel/'.$filename,__FILE__)); 


					return json_encode(array(array("response"=>"Success", "filename"=>$filename)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** User Download **********/
		public function add_points($accountID, $my_session_id, $memberID, $points, $transactionType) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_manual_points(?, ?, ?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('REPORTS LOG', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// echo "CALL dashboard_manual_points('$accountID', '$my_session_id', '$memberID', '$transactionType', '$transactionType', '$points' )";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_manual_points] [Query: $qry]");
			    die($error000);
				return;
			}
 


			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $memberID, $transactionType, $transactionType, $points )) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_manual_points] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$points = filter_var($points, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_manual_points] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) { 

				$logs->write_logs('Dashboard Add Points', $file_name, "[".$accountID."] added [".$points."] points");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success")));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}			
		}



		/********** Validate E-Mail Address **********/
		public function validate_email($email) {
			$isValid = true;

	        // First, we check that there's one @ symbol, and that the lengths are right
	        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	            $isValid = false;
	        }

	        // Split it into sections to make life easier
	        $email_array = explode("@", $email);
	        if (count($email_array) < 2) {
	        	$isValid = false;
	        	return $isValid;
	        }

	        $local_array = explode(".", $email_array[0]);

	        for ($i = 0; $i < sizeof($local_array); $i++) {
	            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
	                $isValid = false;
	            }
	        }

	        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	            $domain_array = explode(".", $email_array[1]);
	            if (sizeof($domain_array) < 2) {
	                $isValid = false; // Not enough parts to domain
	            }
	            for ($i = 0; $i < sizeof($domain_array); $i++) {
	                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
	                    $isValid = false;
	                }
	            }
	        }

		   	return $isValid;
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		// $file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'core.class.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>