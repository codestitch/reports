<?php

	require_once('php/api/core/class/core.class.php');

	$class = new core();

	switch ($function) {

		case 'get_user_data_using_qr':
			$qrcode = "";

			if (isset($_GET['qrcode']) && $_GET['qrcode'] != NULL) {
				$qrcode = $cipher->decrypt($_GET['qrcode']);
				$qrcode = filter_var($qrcode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(qrcode: '.$_GET['qrcode'].')');
				echo $error400;
				die();
			}

			echo $class->get_user_data_using_qr($qrcode);
			die();
			break;
			
		case 'complete_data':
			$memberID = "";
			
			if (isset($_GET['memberID']) && $_GET['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_GET['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_GET['memberID'].')');
				echo $error400;
				die();
			}

			echo $class->complete_data($memberID);
			die();
			break;

		case 'app_registration':
			$email = "";
			$fname = "";
			$mname = "";
			$lname = "";
			$address1 = "";
			$address2 = "";
			$city = "";
			$province = "";
			$zip = "";
			$dob = "";
			$gender = "";
			$mobileNum = "";
			$platform = "";
			$landlineNum = "";
			$password = "";

			if (isset($_POST['email']) && $_POST['email'] != NULL) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname']) && $_POST['fname'] != NULL) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$fname = NULL;
			}

			if (isset($_POST['mname']) && $_POST['mname'] != NULL) {
				$mname = $cipher->decrypt($_POST['mname']);
				$mname = filter_var($mname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$mname = NULL;
			}

			if (isset($_POST['lname']) && $_POST['lname'] != NULL) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$lname = NULL;
			}

			if (isset($_POST['address1']) && $_POST['address1'] != NULL) {
				$address1 = $cipher->decrypt($_POST['address1']);
				$address1 = filter_var($address1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$address1 = NULL;
			}

			if (isset($_POST['address2']) && $_POST['address2'] != NULL) {
				$address2 = $cipher->decrypt($_POST['address2']);
				$address2 = filter_var($address2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$address2 = NULL;
			}

			if (isset($_POST['city']) && $_POST['city'] != NULL) {
				$city = $cipher->decrypt($_POST['city']);
				$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$city = NULL;
			}

			if (isset($_POST['province']) && $_POST['province'] != NULL) {
				$province = $cipher->decrypt($_POST['province']);
				$province = filter_var($province, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$province = NULL;
			}

			if (isset($_POST['zip']) && $_POST['zip'] != NULL) {
				$zip = $cipher->decrypt($_POST['zip']);
				$zip = filter_var($zip, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$zip = NULL;
			}
			
			if (isset($_POST['dob']) && $_POST['dob'] != NULL) {
				$dob = $cipher->decrypt($_POST['dob']);
				$date_arr  = explode('-', $dob);
				if (count($date_arr) == 3) {
				    if (!checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {
				        $dob = NULL;
				    }
				} else {
				    $dob = NULL;
				}
			} else {
				$dob = NULL;
			}
			
			if (isset($_POST['gender']) && $_POST['gender'] != NULL) {
				$gender = $cipher->decrypt($_POST['gender']);
				$gender = strtolower(filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
			} else {
				$gender = NULL;
			}

			if (isset($_POST['mobileNum']) && $_POST['mobileNum'] != NULL) {
				$mobileNum = $cipher->decrypt($_POST['mobileNum']);
				$mobileNum = filter_var($mobileNum, FILTER_SANITIZE_NUMBER_INT);
			} else {
				$mobileNum = NULL;
			}

			if (isset($_POST['platform']) && $_POST['platform'] != NULL) {
				$platform = $cipher->decrypt($_POST['platform']);
				$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(platform: '.$_POST['platform'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['landlineNum']) && $_POST['landlineNum'] != NULL) {
				$landlineNum = $cipher->decrypt($_POST['landlineNum']);
				$landlineNum = filter_var($landlineNum, FILTER_SANITIZE_NUMBER_INT);
			} else {
				$landlineNum = NULL;
			}
			

			if (isset($_POST['password']) && $_POST['password'] != NULL) {

				$password = $cipher->decrypt($_POST['password']);
				$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				// if(!preg_match('~^[a-z0-9]*[0-9][a-z0-9]*$~i', $password))
				// {
				// 	api_error_logger('(Invalid Password: '.$password.')');
				//     echo $error9727;
				// 	die();
				// }

			} else {
				$password = NULL;
				api_error_logger('(password: '.$_POST['password'].')');
				echo $error000;
				die();
			}

			echo $class->app_registration($email, $fname, $mname, $lname, $address1, $address2, $city, $province, $zip, $dob, $gender, $mobileNum, $platform, $landlineNum, $password);
			die();
			break;

		case 'app_login':
			$email = "";
			$password = "";
			$platform = "";
			$deviceID = "";

			if (isset($_POST['email']) && $_POST['email'] != NULL) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
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

			if (isset($_POST['platform']) && $_POST['platform'] != NULL) {
				$platform = $cipher->decrypt($_POST['platform']);
				$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(platform: '.$_POST['platform'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}

			echo $class->app_login($email, $password, $platform, $deviceID);
			die();
			break;

		case 'fb_login':
			$email = "";
			$pid = "";
			$platform = "";
			$deviceID = "";
			$fname = "";
			$mname = "";
			$lname = "";
			$dob = "";
			$gender = "";
			$image = "";

			if (isset($_POST['email']) && $_POST['email'] != NULL) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['pid']) && $_POST['pid'] != NULL) {
				$pid = $cipher->decrypt($_POST['pid']);
				$pid = filter_var($pid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(pid: '.$_POST['pid'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['platform']) && $_POST['platform'] != NULL) {
				$platform = $cipher->decrypt($_POST['platform']);
				$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(platform: '.$_POST['platform'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname']) && $_POST['fname'] != NULL) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$fname = NULL;
			}

			if (isset($_POST['mname']) && $_POST['mname'] != NULL) {
				$mname = $cipher->decrypt($_POST['mname']);
				$mname = filter_var($mname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$mname = NULL;
			}

			if (isset($_POST['lname']) && $_POST['lname'] != NULL) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$lname = NULL;
			}
			
			if (isset($_POST['dob']) && $_POST['dob'] != NULL) {
				$dob = $cipher->decrypt($_POST['dob']);
				$date_arr  = explode('-', $dob);
				if (count($date_arr) == 3) {
				    if (!checkdate((int)$date_arr[1], (int)$date_arr[2], (int)$date_arr[0])) {
				        $dob = NULL;
				    }
				} else {
				    $dob = NULL;
				}
			} else {
				$dob = NULL;
			}
			
			if (isset($_POST['gender']) && $_POST['gender'] != NULL) {
				$gender = $cipher->decrypt($_POST['gender']);
				$gender = strtolower(filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
			} else {
				$gender = NULL;
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$image = NULL;
			}

			echo $class->fb_login($email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender, $image);
			die();
			break;

		case 'update_member_profile':
			$memberID = "";
			$fname = "";
			$lname = "";
			$gender = "";
			$mobileNum = "";
			$dateOfBirth = "";
			$address1 = "";
			$city = "";
			$drinks = "";

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['fname']) && $_POST['fname'] != NULL) {
				$fname = $cipher->decrypt($_POST['fname']);
				$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$fname = NULL;
			}
			
			if (isset($_POST['lname']) && $_POST['lname'] != NULL) {
				$lname = $cipher->decrypt($_POST['lname']);
				$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$lname = NULL;
			}

			if (isset($_POST['gender']) && $_POST['gender'] != NULL) {
				$gender = $cipher->decrypt($_POST['gender']);
				$gender = filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$gender = NULL;
			}
			
			if (isset($_POST['mobileNum']) && $_POST['mobileNum'] != NULL) {
				$mobileNum = $cipher->decrypt($_POST['mobileNum']);
				$mobileNum = filter_var($mobileNum, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$mobileNum = NULL;
			}
			
			if (isset($_POST['dob']) && $_POST['dob'] != NULL) {
				$dateOfBirth = $cipher->decrypt($_POST['dob']);
				$date_arr  = explode('-', $dateOfBirth);
				if (count($date_arr) == 3) {
				    if (!checkdate((int)$date_arr[1], (int)$date_arr[2], (int)$date_arr[0])) {
				        $dateOfBirth = NULL;
				    }
				} else {
				    $dateOfBirth = NULL;
				}
			} else {
				$dateOfBirth = NULL;
			}

			if (isset($_POST['address1']) && $_POST['address1'] != NULL) {
				$address1 = $cipher->decrypt($_POST['address1']);
				$address1 = filter_var($address1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$address1 = NULL;
			}

			if (isset($_POST['city']) && $_POST['city'] != NULL) {
				$city = $cipher->decrypt($_POST['city']);
				$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$city = NULL;
			}

			if (isset($_POST['drinks']) && $_POST['drinks'] != NULL) {
				$drinks = $cipher->decrypt($_POST['drinks']);
				$drinks = filter_var($drinks, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$drinks = NULL;
			}

			echo $class->update_member_profile($memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks);
			die();
			break;

		case 'tokenizer':
			$platform = "";
			$deviceID = "";
			$pushID = "";

			if (isset($_POST['platform']) && $_POST['platform'] != NULL) {
				$platform = $cipher->decrypt($_POST['platform']);
				$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(platform: '.$_POST['platform'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['pushID']) && $_POST['pushID'] != NULL) {
				$pushID = $cipher->decrypt($_POST['pushID']);
				$pushID = filter_var($pushID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$pushID = NULL;
			}

			echo $class->tokenizer($platform, $deviceID, $pushID);
			die();
			break;

		case 'activation':
			$email = "";
			$activation = "";
			
			if (isset($_POST['email']) && $_POST['email'] != NULL) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['activation']) && $_POST['activation'] != NULL) {
				$activation = $cipher->decrypt($_POST['activation']);
				$activation = filter_var($activation, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(activation: '.$_POST['activation'].')');
				echo $error400;
				die();
			}

			echo $class->activation($email, $activation);
			die();
			break;

		case 'unlock':
			$email = "";
			$activation = "";
			
			if (isset($_POST['email']) && $_POST['email'] != NULL) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['activation']) && $_POST['activation'] != NULL) {
				$activation = $cipher->decrypt($_POST['activation']);
				$activation = filter_var($activation, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(activation: '.$_POST['activation'].')');
				echo $error400;
				die();
			}

			echo $class->unlock($email, $activation);
			die();
			break;

		case 'voucher_list':
			$memberID = "";

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			echo $class->voucher_list($memberID);
			die();
			break;

		case 'campaign_list':
			$memberID = "";

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			echo $class->campaign_list($memberID);
			die();
			break;

		case 'reserve_product':
			$memberID = "";
			$prodID = "";

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['prodID']) && $_POST['prodID'] != NULL) {
				$prodID = $cipher->decrypt($_POST['prodID']);
				$prodID = filter_var($prodID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(prodID: '.$_POST['prodID'].')');
				echo $error400;
				die();
			}

			echo $class->reserve_product($memberID, $prodID);
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