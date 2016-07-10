<?php
	/********** PHP INIT **********/
	header('Cache-Control: no-cache');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('mysql.connect_timeout','0');
	ini_set('max_execution_time', '0');
	ini_set('date.timezone', 'Asia/Manila');
	
	require_once('../../php/api/cipher/cipher.class.php');
	require_once('../../php/api/settings/config.php');
	// $path = PATH."reports.php"; 
	$path = str_replace("/reports/php", "", PATH)."reports.php"; 
	

	if ((!isset($_POST['function'])) || (!$_POST['function'])) {
		echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
		return;
		die();
	}

	$function = $_POST['function'];
	$output = array();
	$key = randomizer(32);
	$iv = randomizer(16);

	$cipher = NEW cipher($key, $iv);

	switch ($function) { 

		case 'login':
			$username = "";
			$password = "";

			if ((!isset($_POST['username'])) || (!$_POST['username'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
				return;
				die();
			} else {
				$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$username = $cipher->encrypt($username);
			}

			if ((!isset($_POST['password'])) || (!$_POST['password'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
				return;
				die();
			} else {
				$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$password = $cipher->encrypt($password);
			}

			$function = $cipher->encrypt("login");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&username=".urlencode($username)."&password=".urlencode($password);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			$output = json_decode($server_output);

			if (($output[0]->response) == "Success") {
				session_start();
				$_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'] = $output[0]->data->accountID;
				$_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'] = $output[0]->data->loginSession;
				$_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ROLE'] = $output[0]->data->role; 
				echo $output[0]->response;
			} else {
				if(!$output[0]->response) {
					echo $server_output;
				} else {
					echo $output[0]->response;
				}
			}

			break;

		case 'password':
			$old_password = "";
			$new_password = "";

			if ((!isset($_POST['old_password'])) || (!$_POST['old_password'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$old_password = filter_var($_POST['old_password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (preg_match('/^[a-zA-Z0-9]+$/', $old_password) <= 0) {
					echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
					return;
					die();
				}

				$old_password = $cipher->encrypt($old_password);
			}

			if ((!isset($_POST['new_password'])) || (!$_POST['new_password'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (preg_match('/^[a-zA-Z0-9]+$/', $new_password) <= 0) {
					echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
					return;
					die();
				}

				$new_password = $cipher->encrypt($new_password);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("password");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id)."&old_password=".urlencode($old_password)."&new_password=".urlencode($new_password);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'json':
			$table = "";

			if ((!isset($_POST['table'])) || (!$_POST['table'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$table = filter_var($_POST['table'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$table = $cipher->encrypt($table);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("json");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id)."&table=".urlencode($table);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'get_userPlatform':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_userPlatform");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'get_userDownload':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_userDownload");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'get_userage':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_userage");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_usergender':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_usergender");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customerdailyRegistration':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customerdailyRegistration");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customermonthlyBday':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customermonthlyBday");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customerdailyBday':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customerdailyBday");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customerdailySales':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customerdailySales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customerdailyRedemption':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customerdailyRedemption");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_customerdailyStatistics':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_customerdailyStatistics");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'get_spentdailySales':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_spentdailySales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;

		case 'get_spentYearlySales':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_spentYearlySales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;
			

		case 'get_spentdailyCustomer':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_spentdailyCustomer");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;
			

		case 'get_spentaverageCustomer':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_spentaverageCustomer");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;



		case 'get_dailyproductStatistics':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_dailyproductStatistics");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'get_totalRedeem':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_totalRedeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'get_dailyRedeem':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_dailyRedeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'get_dailybranchSales':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_dailybranchSales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'get_dailybranchRedemption':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_dailybranchRedemption");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'get_dailybranchStatistics':

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_dailybranchStatistics");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

 

		case 'get_voucherBranchRedemption': 
			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_voucherBranchRedemption");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'get_voucherBranchDailyRedemption': 
			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_voucherBranchDailyRedemption");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'get_voucherDailyCustomers': 
			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("get_voucherDailyCustomers");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 



		
		case 'export_registeredcustomerapp': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_registeredcustomerapp");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'export_registeredcustomercard': 
			$startDate = "";
			$endDate = ""; 
 
			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request stardate.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request enddate.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_registeredcustomercard");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 

		case 'export_userPlatform': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_userPlatform");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_userDownload': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_userDownload");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_userage': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_userage");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_usergender': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_usergender");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT BRANCH SECTION -----------------------------
			// ----------------------------------------------------------------------------------------


		case 'export_dailybranchSales': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_dailybranchSales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_dailybranchRedemption': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_dailybranchRedemption");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_dailybranchStatistics':   

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_dailybranchStatistics");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranshistory_points': 
			$startDate = "";
			$endDate = ""; 
			$locID = "";

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request startDate.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request endDate.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			} 

			if ((!isset($_POST['locID'])) || (!$_POST['locID'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username locID.")));
				return;
				die();
			} else {
				$locID = filter_var($_POST['locID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$locID = $cipher->encrypt($locID);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranshistory_points");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&locID=".urlencode($locID);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranshistory_redeem': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			if ((!isset($_POST['locID'])) || (!$_POST['locID'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username locID.")));
				return;
				die();
			} else {
				$locID = filter_var($_POST['locID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$locID = $cipher->encrypt($locID);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranshistory_redeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&locID=".urlencode($locID);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranshistory_sales': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			if ((!isset($_POST['locID'])) || (!$_POST['locID'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username locID.")));
				return;
				die();
			} else {
				$locID = filter_var($_POST['locID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$locID = $cipher->encrypt($locID);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranshistory_sales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&locID=".urlencode($locID);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranssummary_points': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranssummary_points");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranssummary_redeem': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranssummary_redeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_branchtranssummary_sales': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_branchtranssummary_sales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;








			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT CUSTOMER SECTION ---------------------------
			// ----------------------------------------------------------------------------------------




		case 'export_customerinformation':  
			$email = "";
			$fname = "";
			$lname = "";
			$city = ""; 

			if ((!isset($_POST['email'])) ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$email = strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
				$email = $cipher->encrypt($email);
			}

			if ((!isset($_POST['fname'])) ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username fname.")));
				return;
				die();
			} else {
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$fname = $cipher->encrypt($fname);
			}

			if ((!isset($_POST['lname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username lname.")));
				return;
				die();
			} else {
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$lname = $cipher->encrypt($lname);
			}

			if ((!isset($_POST['city']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username city.")));
				return;
				die();
			} else {
				$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$city = $cipher->encrypt($city);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customerinformation");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id)."&email=".urlencode($email)."&fname=".urlencode($fname)."&lname=".urlencode($lname)."&city=".urlencode($city);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 



		case 'export_customerdailyStatistics':   
			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customerdailyStatistics");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customertranshistory_points': 
			$startDate = "";
			$endDate = ""; 
			$email = "";
			$fname = "";
			$lname = "";

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			} 

			if ((!isset($_POST['email'])) ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$email = strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
				$email = $cipher->encrypt($email);
			}

			if ((!isset($_POST['fname'])) ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username fname.")));
				return;
				die();
			} else {
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$fname = $cipher->encrypt($fname);
			}

			if ((!isset($_POST['lname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username lname.")));
				return;
				die();
			} else {
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$lname = $cipher->encrypt($lname);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customertranshistory_points");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&email=".urlencode($email)."&fname=".urlencode($fname)."&lname=".urlencode($lname);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customertranshistory_redeem': 
			$startDate = "";
			$endDate = ""; 
			$email = "";
			$fname = "";
			$lname = "";

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			if ((!isset($_POST['email']))  ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$email = strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
				$email = $cipher->encrypt($email);
			}

			if ((!isset($_POST['fname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username fname.")));
				return;
				die();
			} else {
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$fname = $cipher->encrypt($fname);
			}

			if ((!isset($_POST['lname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username lname.")));
				return;
				die();
			} else {
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$lname = $cipher->encrypt($lname);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customertranshistory_redeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&email=".urlencode($email)."&fname=".urlencode($fname)."&lname=".urlencode($lname);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customertranshistory_sales': 
			$startDate = "";
			$endDate = ""; 
			$email = "";
			$fname = "";
			$lname = "";

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			if ((!isset($_POST['email']))  ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$email = strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
				$email = $cipher->encrypt($email);
			}

			if ((!isset($_POST['fname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username fname.")));
				return;
				die();
			} else {
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$fname = $cipher->encrypt($fname);
			}

			if ((!isset($_POST['lname']))  ) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username lname.")));
				return;
				die();
			} else {
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$lname = $cipher->encrypt($lname);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customertranshistory_sales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&email=".urlencode($email)."&fname=".urlencode($fname)."&lname=".urlencode($lname);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customersummary_points': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customersummary_points");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customersummary_redeem': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customersummary_redeem");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'export_customersummary_sales': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customersummary_sales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_customersummary_sales': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_customersummary_sales");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_totalCustomerSpent': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_totalCustomerSpent");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_averageCustomerSpent': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_averageCustomerSpent");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;




			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT REWARD SECTION -----------------------------
			// ----------------------------------------------------------------------------------------




		case 'export_redemptionBreakdown': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_redemptionBreakdown");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_promoBreakdown': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_promoBreakdown");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;




		// ----------------------------------------------------------------------------------------
		// -------------------------------- EXPORT PRODUCT STATISTICS -----------------------------
		// ----------------------------------------------------------------------------------------




		case 'export_producttransactionhistory_branch': 
			$startDate = "";
			$endDate = ""; 
			$locID = "";

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			if ((!isset($_POST['locID'])) || (!$_POST['locID'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username locID.")));
				return;
				die();
			} else {
				$locID = filter_var($_POST['locID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$locID = $cipher->encrypt($locID);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_producttransactionhistory_branch");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&locID=".urlencode($locID);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_producttransactionhistory_customer': 
			$startDate = "";
			$endDate = ""; 
			$email = "";
			$fname = "";
			$lname = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			} 

			if ((!isset($_POST['email'])) || (!$_POST['email'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$email = strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
				$email = $cipher->encrypt($email);
			}

			if ((!isset($_POST['fname'])) || (!$_POST['fname'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username fname.")));
				return;
				die();
			} else {
				$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$fname = $cipher->encrypt($fname);
			}

			if ((!isset($_POST['lname'])) || (!$_POST['lname'])) {
				echo json_encode(array(array("response"=>"Error", "description"=>"Invalid Username lname.")));
				return;
				die();
			} else {
				$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$lname = $cipher->encrypt($lname);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_producttransactionhistory_customer");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate)."&email=".urlencode($email)."&fname=".urlencode($fname)."&lname=".urlencode($lname);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		case 'export_voucherTransHistory': 
			$startDate = "";
			$endDate = ""; 

			if ((!isset($_POST['startDate']) )|| (!$_POST['startDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$startDate = date('Y-m-d', strtotime($startDate));
				$startDate = $cipher->encrypt($startDate);
			} 

			if ((!isset($_POST['endDate']) )|| (!$_POST['endDate'])) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
				return;
				die();
			} else {
				$endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$endDate = date('Y-m-d', strtotime($endDate));
				$endDate = $cipher->encrypt($endDate);
			}

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("export_voucherTransHistory");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id).
			"&startDate=".urlencode($startDate)."&endDate=".urlencode($endDate);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break; 


		case 'add_points': 
			$memberID = "";
			$points = "";  
			$transactionType = "";  


			if ((!isset($_POST['memberID']))  ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request cat.")));
				return;
				die();
			} else {
				$memberID = filter_var($_POST['memberID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$memberID = $cipher->encrypt($memberID);
			}

			if ((!isset($_POST['points']))  ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request points.")));
				return;
				die();
			} else {
				$points = filter_var($_POST['points'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$points = $cipher->encrypt($points);
			}

			if ((!isset($_POST['transactionType']))  ) {
				echo json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request transactionType.")));
				return;
				die();
			} else {
				$transactionType = filter_var($_POST['transactionType'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$transactionType = $cipher->encrypt($transactionType);
			}
 

			session_start();
			$accountID = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_ID'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($_SESSION[MERCHANT_APPNAME.'_DASHBOARD_ACCOUNT_SESSION'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$accountID = $cipher->encrypt($accountID);
			$my_session_id = $cipher->encrypt($my_session_id);

			$function = $cipher->encrypt("add_points");
			$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id)."&memberID=".urlencode($memberID)."&points=".urlencode($points)."&transactionType=".urlencode($transactionType);
			$url = $path."?function=".urlencode($function);
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_POST, 1);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($cURL);
			curl_close ($cURL);
			echo $server_output;
			break;


		default:
			# code...
			break;
	}

	/********** Randomizer **********/
	function randomizer($len, $norepeat = true) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $max = strlen($chars) - 1;

	    if ($norepeat && $len > $max + 1) {
	        throw new Exception("Non repetitive random string can't be longer than charset");
	    }

	    $rand_chars = array();

	    while ($len) {
	        $picked = $chars[mt_rand(0, $max)];

	        if ($norepeat) {
	            if (!array_key_exists($picked, $rand_chars)) {
	                $rand_chars[$picked] = true;
	                $len--;
	            }
	        }
	        else {
	            $rand_chars[] = $picked;
	            $len--;
	        }
	    }

	    return implode('', $norepeat ? array_keys($rand_chars) : $rand_chars);   
	}
	

?>