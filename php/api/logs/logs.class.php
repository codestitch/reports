<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: logs.class.php
		Description : This PHP class handles the creation of logs.	
	**/

	/********** Redirection Address **********/
	define('REDIRECT', 'http://appsolutely.ph');

	class logs {

		public function write_logs($function, $file_name, $message) {
			$path = realpath(dirname(__FILE__)) . "/dump/" . date('Y-m-d');
			$file = $path . "/" . $function . ".txt";

			if (!file_exists($path)) {
			    mkdir($path, 0777, true);
			}

			if (!file_exists($file)) {
				fopen($file, "w");
			}
			
			$fullmsg = "[" . date("D M d H:i:s Y") . "]\t[client: " . $this->get_client_ip() . "] [file: " . $file_name . "] - " . $message . "\r\n";
			$fhandler = fopen($file, "a+");
			fwrite($fhandler, $fullmsg);
			fclose($fhandler);
			return;
		}

		public function get_client_ip() {
		    $ipaddress = '';

		    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		    	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    	$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    } elseif(isset($_SERVER['HTTP_X_FORWARDED'])) {
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		    } elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
		    	$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
		    	$ipaddress = $_SERVER['HTTP_FORWARDED'];
		    } elseif(isset($_SERVER['REMOTE_ADDR'])) {
				$ipaddress = $_SERVER['REMOTE_ADDR'];
		    } else {
		    	$ipaddress = 'UNKNOWN';
		    }
		        
		    return $ipaddress;
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', $file_name, 'Illegal access attempt.');
		die('Access denied'); 
	}

?>