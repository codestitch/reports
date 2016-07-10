<?php

	require_once('php/api/dashboard/class/push.class.php');

	$class = new push();

	switch ($function) {

		case 'send_push':
			$message = "";
			$type = "";
			
			if (isset($_POST['message']) && $_POST['message'] != NULL) {
				$message = $cipher->decrypt($_POST['message']);
				$message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(message: '.$_POST['message'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['type']) && $_POST['type'] != NULL) {
				$type = $cipher->decrypt($_POST['type']);
				$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(type: '.$_POST['type'].')');
				echo $error400;
				die();
			}

			echo $class->send_push($message, $type);
			die();
			break;
		
		default:
			echo $error400;
			die();
			break;
	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', $file_name, 'Illegal access attempt.');
		die('Access denied'); 
	}

?>