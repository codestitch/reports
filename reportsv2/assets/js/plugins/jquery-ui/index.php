<?php

	include_once('../../../../../php/api/logs/logs.class.php');
	$logs = NEW logs();
	$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
	$logs->write_logs('Invalid Access', $file_name, "Illegal access attempt.");
	header("Location: ".REDIRECT);

?>