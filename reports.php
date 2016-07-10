<?php

	/********** PHP INIT **********/
	// header('Access-Control-Allow-Origin: http://58.69.142.44/');
	header('Cache-Control: no-cache');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('mysql.connect_timeout','0');
	ini_set('max_execution_time', '0');
	ini_set('date.timezone', 'Asia/Manila');

	$error000 = json_encode(array(array("response"=>"Error", "errorCode"=>"000", "description"=>"Unable to complete process.")));
	$error400 = json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
	$error1329 = json_encode(array(array("response"=>"Error", "errorCode"=>"1329", "description"=>"No Data Found.")));

	if ((!isset($_POST['oauth']) )|| (!$_POST['oauth'])) {
		echo $error400;
		api_error_logger('oauth');
		die();
		return;
	}

	if ((!isset($_POST['token']) )|| (!$_POST['token'])) {
		echo $error400;
		api_error_logger('token');
		die();
		return;
	}

	require_once('php/api/cipher/cipher.class.php');
	$cipher = NEW cipher($_POST['oauth'], $_POST['token']);

	if ((!isset($_GET['function'])) || (!$_GET['function'])) {
		api_error_logger('function');
		echo $error400;
		die();
		return;
	} else {
		$function = $cipher->decrypt($_GET['function']);
		$function = filter_var($function, FILTER_SANITIZE_URL);
	}

	include_once('php/api/reports/function/reports.function.php');

	function api_error_logger($param) {
		include_once('php/api/logs/logs.class.php');
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', $file_name, "Illegal access attempt.\t [Param: $param]");
	}

?>