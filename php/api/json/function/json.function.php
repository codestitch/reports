<?php

	require_once('php/api/json/class/json.class.php');

	$class = new json();

	switch ($function) {

		case 'fetch':
			if (isset($_GET['table']) && $_GET['table'] != NULL) {
				$table = $cipher->decrypt($_GET['table']);
				$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				echo $class->fetch($table);
				die();
			} else {
				api_error_logger('(table: '.$_GET['table'].')');
				echo $error400;
				die();
			}

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