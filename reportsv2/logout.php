<?php

	session_start();
	unset($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID']);
	unset($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION']);
	$_SESSION = array();
	session_unset();
	session_destroy();
	header('location: login.php');

?>