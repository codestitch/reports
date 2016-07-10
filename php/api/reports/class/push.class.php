<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: push.class.php
		Description : This PHP class handles all the push related major functions.	
	**/

	class push {

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
			self::$tmp_file_name = 'push.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		/********** Send Push Notification **********/
		public function send_push($message, $type){
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL send_push()";

			if (!isset($message) || (!$message)) {
				$logs->write_logs('Send Push Notification', $file_name, "Bad Request - Invalid/Empty [message: $message]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: send_push] [Query: $qry]");
			    die($error000);
				return;
			}

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: send_push] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$android_GCM_regID = array();
				$ios_deviceID = array();

				while ($row = $result->fetch_assoc()) {
					if (strtolower($row['platform']) == 'android') {
						array_push($android_GCM_regID, $row['gcmID']);
					} elseif (strtolower($row['platform']) == 'ios') {
						array_push($ios_deviceID, $row['deviceID']);
					}				
				}

				$gcm_chunk = array_chunk($android_GCM_regID, 1000);

				for ($i=0; $i<count($gcm_chunk); $i++) {
					$push->android($gcm_chunk[$i], $message, $type);
				}

				echo $push->ios($ios_deviceID, $message, CERTIFICATE, ENVIRONMENT, $type);
			}

			$sql->close();
			echo "Success";
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