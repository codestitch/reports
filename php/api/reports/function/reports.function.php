<?php
	/********** PHP INIT **********/
	header('Cache-Control: no-cache');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('mysql.connect_timeout','0');
	ini_set('max_execution_time', '0');
	ini_set('date.timezone', 'Asia/Manila');
 
	require_once('php/api/reports/class/reports.class.php');  
	$class = new reports(); 

	switch ($function) {

		case 'session_checker':
			$accountID = "";
			$my_session_id = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}

			echo $class->session_checker($accountID, $my_session_id);
			die();
			break;

		case 'login':
			$username = "";
			$password = "";
			
			if (isset($_POST['username']) && $_POST['username'] != NULL) {
				$username = $cipher->decrypt($_POST['username']);
				$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(username: '.$_POST['username'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['password']) && $_POST['password'] != NULL) {
				$password = $cipher->decrypt($_POST['password']);
				$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(password: '.$_POST['password'].')');
				echo $error400;
				die();
			}

			echo $class->login($username, $password);
			die();
			break;

		case 'password':
			$accountID = "";
			$my_session_id = "";
			$old_password = "";
			$new_password = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['old_password']) && $_POST['old_password'] != NULL) {
				$old_password = $cipher->decrypt($_POST['old_password']);
				$old_password = filter_var($old_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (preg_match('/^[a-zA-Z0-9]+$/', $old_password) <= 0) {
					api_error_logger('(old_password: '.$_POST['old_password'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(old_password: '.$_POST['old_password'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['new_password']) && $_POST['new_password'] != NULL) {
				$new_password = $cipher->decrypt($_POST['new_password']);
				$new_password = filter_var($new_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				
				if (preg_match('/^[a-zA-Z0-9]+$/', $new_password) <= 0) {
					api_error_logger('(new_password: '.$_POST['new_password'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(new_password: '.$_POST['new_password'].')');
				echo $error400;
				die();
			}

			echo $class->password($accountID, $my_session_id, $old_password, $new_password);
			die();
			break;

		case 'json':
			$accountID = "";
			$my_session_id = "";
			$table = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['table']) && $_POST['table'] != NULL) {
				$table = $cipher->decrypt($_POST['table']);
				$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(table: '.$_POST['table'].')');
				echo $error400;
				die();
			}

			echo $class->json($accountID, $my_session_id, $table);
			die();
			break;


		case 'get_userPlatform':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_userPlatform($accountID, $my_session_id);
			die();
			break;


		case 'get_userDownload':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_userDownload($accountID, $my_session_id);
			die();
			break; 


		case 'get_userage':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_userage($accountID, $my_session_id);
			die();
			break;


		case 'get_usergender':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_usergender($accountID, $my_session_id);
			die();
			break;


		case 'get_customerdailyRegistration':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customerdailyRegistration($accountID, $my_session_id);
			die();
			break;


		case 'get_customermonthlyBday':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customermonthlyBday($accountID, $my_session_id);
			die();
			break;


		case 'get_customerdailyBday':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customerdailyBday($accountID, $my_session_id);
			die();
			break;


		case 'get_customerdailySales':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customerdailySales($accountID, $my_session_id);
			die();
			break;


		case 'get_customerdailyRedemption':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customerdailyRedemption($accountID, $my_session_id);
			die();
			break;


		case 'get_customerdailyStatistics':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_customerdailyStatistics($accountID, $my_session_id);
			die();
			break;


		case 'get_dailyproductStatistics':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_dailyproductStatistics($accountID, $my_session_id);
			die();
			break;


		case 'get_spentdailySales':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_spentdailySales($accountID, $my_session_id);
			die();
			break;


		case 'get_spentYearlySales':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_spentYearlySales($accountID, $my_session_id);
			die();
			break;


		case 'get_spentdailyCustomer':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_spentdailyCustomer($accountID, $my_session_id);
			die();
			break;


		case 'get_spentaverageCustomer':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_spentaverageCustomer($accountID, $my_session_id);
			die();
			break;


		case 'get_totalRedeem':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_totalRedeem($accountID, $my_session_id);
			die();
			break;


		case 'get_dailyRedeem':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_dailyRedeem($accountID, $my_session_id);
			die();
			break;

			
		case 'get_dailybranchSales':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_dailybranchSales($accountID, $my_session_id);
			die();
			break;

			
		case 'get_dailybranchRedemption':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_dailybranchRedemption($accountID, $my_session_id);
			die();
			break;

			
		case 'get_dailybranchStatistics':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_dailybranchStatistics($accountID, $my_session_id);
			die();
			break;
			
		case 'get_voucherBranchRedemption':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_voucherBranchRedemption($accountID, $my_session_id);
			die();
			break;
			
		case 'get_voucherBranchDailyRedemption':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_voucherBranchDailyRedemption($accountID, $my_session_id);
			die();
			break;
			
		case 'get_voucherDailyCustomers':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			echo $class->get_voucherDailyCustomers($accountID, $my_session_id);
			die();
			break;


		case 'export_userPlatform':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_userPlatform($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;

		case 'export_registeredcustomerapp':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_registeredcustomerapp($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;
			
		case 'export_registeredcustomercard':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_registeredcustomercard($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_userDownload':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_userDownload($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_userage':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_userage($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_usergender':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_usergender($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT BRANCH SECTION -----------------------------
			// ----------------------------------------------------------------------------------------



		case 'export_dailybranchSales':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_dailybranchSales($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_dailybranchRedemption':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_dailybranchRedemption($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_dailybranchStatistics':
			$accountID = "";
			$my_session_id = "";  

			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_dailybranchStatistics($accountID, $my_session_id);
			die();
			break;


		case 'export_branchtranshistory_points':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";
			$locID = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['locID']) ) { 
				$locID = NULL; 
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_branchtranshistory_points($accountID, $my_session_id, $startDate, $endDate, $locID);
			die();
			break;


		case 'export_branchtranshistory_redeem':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			$locID = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['locID']) ) { 
				$locID = NULL; 
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_branchtranshistory_redeem($accountID, $my_session_id, $startDate, $endDate, $locID);
			die();
			break;


		case 'export_branchtranshistory_sales':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			$locID = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			
			if (isset($_POST['locID']) ) { 
				$locID = NULL; 
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_branchtranshistory_sales($accountID, $my_session_id, $startDate, $endDate, $locID);
			die();
			break;


		case 'export_branchtranssummary_points':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_branchtranssummary_points($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_branchtranssummary_redeem':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_branchtranssummary_redeem($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_branchtranssummary_sales':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_branchtranssummary_sales($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;









			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT CUSTOMER SECTION ---------------------------
			// ----------------------------------------------------------------------------------------




			
		case 'export_customerinformation':
			$accountID = "";
			$my_session_id = "";  
			$email = "";
			$fname = "";
			$lname = ""; 
			$city = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['email'])  ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname'])  ) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname: '.$_POST['fname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['lname']) ) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname: '.$_POST['lname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['city']) ) {
				$city = $cipher->decrypt($_POST['city']);
				$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(city: '.$_POST['city'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_customerinformation($accountID, $my_session_id, $email, $fname, $lname, $city);
			die();
			break;


		case 'export_customerdailyStatistics':
			$accountID = "";
			$my_session_id = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_customerdailyStatistics($accountID, $my_session_id);
			die();
			break;


		case 'export_customertranshistory_points':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";
			$email = "";
			$fname = "";
			$lname = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['email'])  ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname'])  ) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname: '.$_POST['fname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['lname']) ) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname: '.$_POST['lname'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_customertranshistory_points($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname);
			die();
			break;


		case 'export_customertranshistory_redeem':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			$email = "";
			$fname = "";
			$lname = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['email']) ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname']) ) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname: '.$_POST['fname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['lname']) ) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname: '.$_POST['lname'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_customertranshistory_redeem($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname);
			die();
			break;


		case 'export_customertranshistory_sales':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";   
			$email = "";
			$fname = "";
			$lname = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['email']) ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname'])  ) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname: '.$_POST['fname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['lname'])  ) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname: '.$_POST['lname'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_customertranshistory_sales($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname);
			die();
			break;


		case 'export_customersummary_points':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_customersummary_points($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_customersummary_redeem':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_customersummary_redeem($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_customersummary_sales':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_customersummary_sales($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;
 
 


		// ----------------------------------------------------------------------------------------
		// ------------------------------------ SPEND SECTION ---------------------------
		// ----------------------------------------------------------------------------------------



		case 'export_totalCustomerSpent':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_totalCustomerSpent($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_averageCustomerSpent':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_averageCustomerSpent($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;



			// ----------------------------------------------------------------------------------------
			// ------------------------------------ EXPORT REWARD SECTION -----------------------------
			// ----------------------------------------------------------------------------------------
 


		case 'export_redemptionBreakdown':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_redemptionBreakdown($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_promoBreakdown':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_promoBreakdown($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;





		// ----------------------------------------------------------------------------------------
		// -------------------------------- EXPORT PRODUCT STATISTICS -----------------------------
		// ----------------------------------------------------------------------------------------


		case 'export_dailyproductStatistics':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}   
 
			echo $class->export_dailyproductStatistics($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'export_producttransactionhistory_customer':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";
			$email = "";
			$fname = "";
			$lname = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['email'])  ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname']) ) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname: '.$_POST['fname'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['lname']) ) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname: '.$_POST['lname'].')');
				echo $error400;
				die();
			}
 
			echo $class->export_producttransactionhistory_customer($accountID, $my_session_id, $startDate, $endDate, $email, $fname, $lname);
			die();
			break;


		case 'export_voucherTransHistory':
			$accountID = "";
			$my_session_id = ""; 
			$startDate = ""; 
			$endDate = "";  
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['startDate']) && $_POST['startDate'] != NULL) {
				$startDate = $cipher->decrypt($_POST['startDate']);
				$startDate = filter_var($startDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startDate: '.$_POST['startDate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['endDate']) && $_POST['endDate'] != NULL) {
				$endDate = $cipher->decrypt($_POST['endDate']);
				$endDate = filter_var($endDate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(endDate: '.$_POST['endDate'].')');
				echo $error400;
				die();
			}  
 
			echo $class->export_voucherTransHistory($accountID, $my_session_id, $startDate, $endDate);
			die();
			break;


		case 'add_points':
			$accountID = "";
			$my_session_id = ""; 
			$memberID = "";
			$points = "";
			$transactionType = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['memberID']) ) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['points']) ) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['transactionType']) ) {
				$transactionType = $cipher->decrypt($_POST['transactionType']);
				$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(transactionType: '.$_POST['transactionType'].')');
				echo $error400;
				die();
			}

			echo $class->add_points($accountID, $my_session_id, $memberID, $points, $transactionType);
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