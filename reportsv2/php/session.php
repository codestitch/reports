<?php

	session_start();

	if (!$_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID']) {
		echo "EXPIRED";
	} else {
		echo "OK";
	}

?>