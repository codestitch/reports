<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: core.class.php
		Description : This PHP class handles all the core related major functions.	
	**/

	class core {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		
		function __construct() {
			include_once('php/api/settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'core.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		/********** Get User Data Using QR **********/
		public function get_user_data_using_qr($qrcode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$codeType = substr($qrcode, 0, 3);

			if (!isset($qrcode) || (!$qrcode)) {
				$logs->write_logs('Get User Data Using QR', $file_name, "Bad Request - Invalid/Empty [qrcode: $qrcode]");
				die($error400);
				return;
			}

			if ($codeType == 'APP') {
				$qry = "CALL get_user_data_using_qr(?, 'APP')";
			} elseif ($codeType == 'CRD') {
				$qry = "CALL get_user_data_using_qr(?, 'CRD')";
			} else {
				$logs->write_logs('Get User Data Using QR', $file_name, "[QR Code: $qrcode]\t [Message : Invalid QR-Code] [Param: $qrcode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1998", "description"=>"Invalid QR-Code.")));
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_user_data_using_qr] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $qrcode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_user_data_using_qr] [Param: $qrcode]");
			    die($error000);
				return;
			}

			$qrcode = filter_var($qrcode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: get_user_data_using_qr] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				return json_encode(array(array("response"=>"Success", "data"=>$row)));
			} else {
				$logs->write_logs('Get User Data Using QR', $file_name, "[QR Code: $qrcode]\t [Message : No User found.] [Param: $qrcode]");
				return $error1329;
			}
		}

		/********** Fetch Complete User Data **********/
		public function complete_data($memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$profile = array();
			$campaign = array();
			$redeemable_sku = array();
			$output = array();
			$level = array();
			$qry = "CALL core_complete_data(?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Fetch Complete User Data', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_complete_data] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_complete_data] [Param: $memberID]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: core_complete_data] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if (isset($row['result'])) {
					$logs->write_logs('Fetch Complete User Data', $file_name, "[Member ID: $memberID]\t [Message : No User found.] [Param: $memberID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"0101", "description"=>"No user found.")));
				} else {
					$row['dateReg'] = date('Y-m-d', strtotime($row['dateReg']));
					$profile = $row;
				}							
			} else {
				$logs->write_logs('Fetch Complete User Data', $file_name, "[Member ID: $memberID]\t [Message : No User found.] [Param: $memberID]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0101", "description"=>"No user found.")));
			}

			$sql->close();

			$qry = "CALL my_level(?)";

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: my_level] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: my_level] [Param: $memberID]");
			    die($error000);
				return;
			}

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: my_level] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if (isset($row['result'])) {
					$logs->write_logs('Fetch Complete User Data', $file_name, "[Member ID: $memberID]\t [Message : No User found.] [Param: $memberID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"0101", "description"=>"No user found.")));
				} else {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }

				    $row['description'] = str_replace("\r\n", "<br/>", $row['description']);
				    $row['description'] = str_replace("\r", "<br/>", $row['description']);
				    $row['description'] = str_replace("\n", "<br/>", $row['description']);

					$level = $row;
				}	
			}

			$sql->close();

			// $qry = "CALL campaign_list(?)";
			$qry = "CALL fetch_json('loyaltytable')";
			
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			// if (!$sql->bind_param('s', $memberID)) {
			// 	$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: campaign_list] [Param: $memberID]");
			//     die($error000);
			// 	return;
			// }

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($campaign, $row);
				}
			}

			$sql->close();

			for ($i=0; $i<count($campaign); $i++) {
				if ($campaign[$i]['promoType'] == 'frequency') {
					$qry = "CALL sku_frequency(?, ?)";

					// Prepared statement, stage 1: prepare
					if (!($sql = $mysqli->prepare($qry))) {
						$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_frequency] [Query: $qry]");
					    die($error000);
						return;
					}

					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_frequency] [Param: ".$campaign[$i]['loyaltyID'].", $memberID]");

					if (!$sql->bind_param('ss', $campaign[$i]['loyaltyID'], $memberID)) {
						$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_frequency] [Param: ".$campaign[$i]['loyaltyID'].", $memberID]");
					    die($error000);
						return;
					}

					// Execute Prepared Statement
					if (!$sql->execute()) {
						$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: sku_frequency] [Query: $qry]");
					    die($error000);
						return;
					}

					// Get SQL statement result
					$result = $sql->get_result();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						array_push($redeemable_sku, array("loyaltyID"=>$campaign[$i]['loyaltyID'], "count"=>$row['count'], "group"=>$row['group']));
					}

					$sql->close();
				}
			}

			array_push($output, array("campaign" => $campaign));
			$profile["redeemable_sku_promo"] = $redeemable_sku;
			$profile["lastSync"] = date('M d,Y');
			// array_push($output, array("lastSync" => date('M d,Y')));
			array_push($output, array("profile" => $profile));
			array_push($output, array("level" => $level));
			return json_encode(array(array("response"=>"Success", "data"=>$output)));
		}

		/********** App Registration **********/
		public function app_registration($email, $fname, $mname, $lname, $address1, $address2, $city, $province, $zip, $dob, $gender, $mobileNum, $platform, $landlineNum, $password) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$activation = md5(uniqid(rand(), true));
			$output = array();
			$qry = "CALL registration_app(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($email) || (!$email)) {
				$logs->write_logs('App Registration', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!isset($fname) || (!$fname)) {
				$fname = NULL;
			}

			if (!isset($mname) || (!$mname)) {
				$mname = NULL;
			}

			if (!isset($lname) || (!$lname)) {
				$lname = NULL;
			}

			if (!isset($address1) || (!$address1)) {
				$address1 = NULL;
			}

			if (!isset($address2) || (!$address2)) {
				$address2 = NULL;
			}

			if (!isset($city) || (!$city)) {
				$city = NULL;
			}

			if (!isset($province) || (!$province)) {
				$province = NULL;
			}

			if (!isset($zip) || (!$zip)) {
				$zip = NULL;
			}

			if (!isset($dob) || (!$dob)) {
				$dob = NULL;
			}

			if (!isset($gender) || (!$gender)) {
				$gender = NULL;
			}

			if (!isset($mobileNum) || (!$mobileNum)) {
				$mobileNum = NULL;
			/*} else {
				$mobileNum = str_replace(' ', '', $mobileNum);
				$mobileNum = str_replace('+', '', $mobileNum);
				$mobileNum = str_replace('-', '', $mobileNum);
				
				if (!is_numeric($mobileNum)) {
		        	$mobileNum = NULL;
		        }

				$mobileNum = substr($mobileNum, -9);
				$mobileNum = "+639".$mobileNum;
				if (!$this->validate_mobile_num($mobileNum)) {
					$mobileNum = NULL;
				}*/
			}

			if (!isset($platform) || (!$platform)) {
				$logs->write_logs('App Registration', $file_name, "Bad Request - Invalid/Empty [platform: $platform]");
				die($error400);
				return;
			}

			if (!isset($landlineNum) || (!$landlineNum)) {
				$landlineNum = NULL;
			}

			if (!isset($password) || (!$password)) {
				$password = NULL;
			}

			if (!$this->validate_email($email)) {
				$logs->write_logs('App Registration', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1979", "description"=>"Invalid E-Mail Address")));
			}

			if ($this->check_registration_email($email) == "Existing") {
				$logs->write_logs('App Registration', $file_name, "Bad Request - [Message: Existing E-Mail Address] [Param: $email]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0801", "description"=>"Existing E-Mail Address")));
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: app_registration] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssssisssssss', $email, $fname, $mname, $lname, $address1, $address2, $city, $province, $zip, $dob, $gender, $mobileNum, $platform, $activation, $landlineNum, $password)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: app_registration] [Param: $email, $fname, $mname, $lname, $address1, $address2, $city, $province, $zip, $dob, $gender, $mobileNum, $platform, $activation, $landlineNum, $password]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mname = filter_var($mname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$address1 = filter_var($address1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$address2 = filter_var($address2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$province = filter_var($province, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
			$date_arr  = explode('-', $dob);
			if (count($date_arr) == 3) {
			    if (!checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {
			        $dob = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);;
			    } else {
			    	$dob = filter_var($dob, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			    }
			} else {
			    $dob = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);;
			}
			$gender = filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mobileNum = filter_var($mobileNum, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$landlineNum = filter_var($landlineNum, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: app_registration] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] != 'Failed') {
					$memberID = $row['result'];
					$password = $row['password'];
					array_push($output, array("description"=>"Please verify by clicking the activation link that has been sent to your email. Kindly check the spam folder in case it is not in your inbox."));
					$sql->close();

					if (!$fname && !$lname) {
						$recipient_name = "Registered Client";
					} else {
						$recipient_name = $fname." ".$lname;
					}

					$subject = MERCHANT.' Account Confirmation';
					// $message = '<center><img src="'.PATH.'assets/images/logo.png" width="300px" height="36px"></center>';
					$message = '<p style="font-family: Tahoma !important;">Dear, '.$recipient_name.'</p>';
					$message .= '<p style="font-family: Tahoma !important;">Thank you for registering for our '.MERCHANT.' app.</p>';
					$message .= '<p style="font-family: Tahoma !important;">Your username and password is: <br />Username: <b>'.$email.'</b><br/>Password: <b>'.$password.'</b></p>';
					$message .= '<p style="font-family: Tahoma !important;">Now, you\'re almost done! All you need to do is activate your account by clicking on the link below. Once that’s done, you can start enjoying our coffee while earning points! </p>';
					$message .= '<p style="font-family: Tahoma !important;"><br/><a href="'.PATH.'activation.php?email='.$email.'&activation='.$activation.'" style="background:#A52A2A; color:#fff; text-decoration:none;">ACTIVATE</a></p>';
					$message .= '<p style="font-family: Tahoma !important;">We\'re so excited to serve you, and we hope to see you soon!
</p>';
					$message .= '<br/>';
					$message .= '<p style="font-family: Tahoma !important;">Regards,</p>';
					$message .= '<p style="font-family: Tahoma !important;">'.MERCHANT.'</p>';

					$sent = send_mail($email, $recipient_name, $subject, $message);


					if ($sent == "Success") {
						$qry = "CALL email_sent(?)";

						// Prepared statement, stage 1: prepare
						if (!($sql = $mysqli->prepare($qry))) {
							$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: email_sent] [Query: $qry]");
						    die($error000);
							return;
						}

						if (!$sql->bind_param('s', $memberID)) {
							$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: email_sent] [Param: $memberID]");
						    die($error000);
							return;
						}
						
						// Execute Prepared Statement
						if (!$sql->execute()) {
							$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: email_sent] [Query: $qry]");
						    die($error000);
							return;
						}
					}

					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					$logs->write_logs('App Registration', $file_name, "[Message: Unable to complete registration process.] [E-Mail: $email, First Name: $fname, Middle Name: $mname, Last Name: $lname, Address 1: $address1, Address 2: $address2, City: $city, Province: $province, Zip Code: $zip, Date of Birth: $dateOfBirth, Gender: $gender, Mobile Number: $mobileNum, Platform: $platform, Landline Number: $landlineNum, Password: $password]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"0987", "description"=>"Unable to complete registration process.")));
				}			
			} else {
				$logs->write_logs('App Registration', $file_name, "[Message: Unable to complete registration process.] [E-Mail: $email, First Name: $fname, Middle Name: $mname, Last Name: $lname, Address 1: $address1, Address 2: $address2, City: $city, Province: $province, Zip Code: $zip, Date of Birth: $dateOfBirth, Gender: $gender, Mobile Number: $mobileNum, Platform: $platform, Landline Number: $landlineNum, Password: $password]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0987", "description"=>"Unable to complete registration process.")));
			}
		}

		/********** App Login **********/
		public function app_login($email, $password, $platform, $deviceID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL app_login(?, ?, ?, ?)";

			if (!isset($email) || (!$email)) {
				$logs->write_logs('App Login', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!isset($password) || (!$password)) {
				$logs->write_logs('App Login', $file_name, "Bad Request - Invalid/Empty [password: $password]");
				die($error400);
				return;
			}

			if (!isset($platform) || (!$platform)) {
				$logs->write_logs('App Login', $file_name, "Bad Request - Invalid/Empty [platform: $platform]");
				die($error400);
				return;
			}

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('App Login', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!$this->validate_email($email)) {
				$logs->write_logs('App Login', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if ($platform == 'ios') {
				$deviceID = str_replace(' ', '', $deviceID);
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: app_login] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $email, $password, $deviceID, $platform)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: app_login] [Param: $email, $password, $deviceID, $platform]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: app_login] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if (isset($row['result'])) {
					if ($row['result'] == 'Undefined') {
						$logs->write_logs('App Login', $file_name, "[Message: Undefined Registration.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"6789", "description"=>"Undefined Registration.")));
					} elseif ($row['result'] == 'Invalid') {
						$logs->write_logs('App Login', $file_name, "[Message: Either your account is inactive or does not exist.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"8901", "description"=>"Either your account is inactive or does not exist.")));
					} elseif ($row['result'] == 'Unable') {
						$logs->write_logs('App Login', $file_name, "[Message: Unable to complete login process.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete login process.")));
					} elseif ($row['result'] == 'Wrong') {
						$logs->write_logs('App Login', $file_name, "[Message: Invalid Username or Password.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"4321", "description"=>"Invalid Username or Password.")));
					} elseif ($row['result'] == 'Lock') {
						
						if ($row['reset'] == 'true') {
							$recipient_name = "";
							if (!$row['memberName']) {
								$recipient_name = MERCHANT." Member";
							} else {
								$recipient_name = $row['memberName'];
							}
							$message = '<center><img src="'.PATH.'assets/images/logo.png" width="300px" height="36px"></center>';
							$message .= '<p style="font-family: Tahoma !important;">Hi <b>'.$recipient_name.'</b>,</p>';
							$message .= '<p style="font-family: Tahoma !important;">Your account has been temporarily suspended due to the number of login attempts.</p>';
							$message .= '<p style="font-family: Tahoma !important;">We\'ve reset your password and lock your account for your security. Your updated credentials are as follows:</p>';
							$message .= '<p style="font-family: Tahoma !important;">Username: <b>'.$email.'</b></p>';
							$message .= '<p style="font-family: Tahoma !important;">Password: <b>'.$row['password'].'</b></p>';
							$message .= '<p style="font-family: Tahoma !important;">Unlock your account by clicking on the link: <a href="'.PATH.'unlock.php?email='.$email.'&activation='.$row['activation'].'" style="background:#000; color:#fff; text-decoration:none;">Unlock</a></p>';
					$message .= '<p style="font-family: Tahoma !important;">Please immediately change your password for your protection. You may visit <a href="'.MERCHANT_LINK.'" target="_blank">'.MERCHANT_WEBLABEL.'</a> to change your password.</p>';
							$message .= '<br/>';
							$message .= '<p style="font-family: Tahoma !important;">Thanks,</p>';
							$message .= '<p style="font-family: Tahoma !important;">'.MERCHANT.' Family</p>';
							send_mail($email, $recipient_name, 'Unlock your Account', $message);
						}

						$logs->write_logs('App Login', $file_name, "[Message: Account has been temporarily suspended due to numerous invalid login attempts. To unlock your account, please check your email.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"5432", "description"=>"Account has been temporarily suspended due to numerous invalid login attempts. To unlock your account, please check your email.")));
					}
				} else {
					$logs->write_logs('App Login', $file_name, "[E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]\t Status: [Success]");
					array_push($output, $row);
					$sql->close();
					return $this->complete_data($row['memberID']);
					// return json_encode(array(array("response"=>"Success", "data"=>$output)));
				}
			} else {
				$logs->write_logs('App Login', $file_name, "[Message: Either your account is inactive or does not exist.] [E-Mail: $email, Password: $password, Device ID: $deviceID, Platform: $platform]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"8901", "description"=>"Either your account is inactive or does not exist.")));
			}

			$sql->close();
			die($error000);
			return;
		}

		/********** Facebook Login **********/
		public function fb_login($email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender, $image) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL fb_login(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($email) || (!$email)) {
				$logs->write_logs('Facebook Login', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!isset($pid) || (!$pid)) {
				$logs->write_logs('Facebook Login', $file_name, "Bad Request - Invalid/Empty [pid: $pid]");
				die($error400);
				return;
			}

			if (!isset($platform) || (!$platform)) {
				$logs->write_logs('Facebook Login', $file_name, "Bad Request - Invalid/Empty [platform: $platform]");
				die($error400);
				return;
			}

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Facebook Login', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!$this->validate_email($email)) {
				$logs->write_logs('Facebook Login', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if ($platform == 'ios') {
				$deviceID = str_replace(' ', '', $deviceID);
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: fb_login] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssssss', $email, $pid, $deviceID, $platform, $fname, $mname, $lname, $dob, $gender, $image)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: fb_login] [Param: $email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender, $image]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$pid = filter_var($pid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mname = filter_var($mname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$date_arr  = explode('-', $dob);
			if (count($date_arr) == 3) {
			    if (!checkdate((int)$date_arr[1], (int)$date_arr[2], (int)$date_arr[0])) {
			        $dob = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			    } else {
			    	$dob = filter_var($dob, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			    }
			} else {
			    $dob = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			}
			$gender = strtolower(filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: fb_login] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if (isset($row['result'])) {
					$logs->write_logs('Facebook Login', $file_name, "[Message: Unable to complete login process.] [Param: $email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete login process.")));
				} else {
					$logs->write_logs('Facebook Login', $file_name, "[Param: $email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender]\t Status: [Success]");
					$sql->close();

					if (isset($row['password'])) {
						$this->send_registration_email($email, $row['password'], $row['memberID']);
					}

					$memberID = $row['memberID'];

					$arrContextOptions=array(
					    "ssl"=>array(
					        "verify_peer"=>false,
					        "verify_peer_name"=>false,
					    ),
					);

					$save_path = "pictures/".$memberID.".jpg";
					file_put_contents($save_path, file_get_contents($image, false, stream_context_create($arrContextOptions)));
					$overide_img_url = PATH."pictures/".$memberID.".jpg";
					$qry = "CALL `overide_profile_image`(?, ?)";

					if (!($sql = $mysqli->prepare($qry))) {
						$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: overide_profile_image] [Query: $qry]");
					    die($error000);
						return;
					}

					if (!$sql->bind_param('ss', $memberID, $overide_img_url)) {
						$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: overide_profile_image] [Param: $memberID, $overide_img_url]");
					    die($error000);
						return;
					}

					if (!$sql->execute()) {
						$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: overide_profile_image] [Query: $qry]");
					    die($error000);
						return;
					}

					$result = $sql->get_result();
					$sql->close();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();

						if ($row['result'] == "Success") {
							return $this->complete_data($memberID);
						} else {
							$logs->write_logs('Overide Profile Image', $file_name, "[Message: Unable to overide profile image process.] [Param: $memberID, $image]");
							$sql->close();
							return json_encode(array(array("response"=>"Error", "errorCode"=>"7891", "description"=>"Unable to overide profile image process.")));
						}
					} else {
						$logs->write_logs('Overide Profile Image', $file_name, "[Message: Unable to overide profile image process.] [Param: $memberID, $image]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"7891", "description"=>"Unable to complete login process.")));
					}

					// return $this->member_profile($row['memberID']);
				}
			} else {
				$logs->write_logs('Facebook Login', $file_name, "[Message: Unable to complete login process.] [Param: $email, $pid, $platform, $deviceID, $fname, $mname, $lname, $dob, $gender]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete login process.")));
			}

			$sql->close();
			die($error000);
			return;
		}

		/********** Update Member Profile **********/
		public function update_member_profile($memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL core_update_member_data(?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			// if (!isset($fname) || (!$fname)) {
			// 	$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [fname: $fname]");
			// 	die($error400);
			// 	return;
			// }

			// if (!isset($lname) || (!$lname)) {
			// 	$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [lname: $lname]");
			// 	die($error400);
			// 	return;
			// }

			// if (!isset($gender) || (!$gender)) {
			// 	$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [gender: $gender]");
			// 	die($error400);
			// 	return;
			// }

			// if (!isset($address1) || (!$address1)) {
			// 	$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [address1: $address1]");
			// 	die($error400);
			// 	return;
			// }

			// if (!isset($mobileNum) || (!$mobileNum)) {
			// 	$logs->write_logs('Update Member Profile', $file_name, "Bad Request - Invalid/Empty [mobileNum: $mobileNum]");
			// 	die($error400);
			// 	return;
			// }

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_update_member_data] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssssss', $memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_update_member_data] [Param: $memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$gender = strtolower(filter_var($gender, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
			$mobileNum = filter_var($mobileNum, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$dateOfBirth = filter_var($dateOfBirth, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$address1 = filter_var($address1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$drinks = filter_var($drinks, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: core_update_member_data] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == 'Success') {
					$logs->write_logs('Update Member Profile', $file_name, "[Param: $memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks]\t Status: [Success]");
					$sql->close();

					// if (isset($row['regPoint'])) {
					// 	$temp_return = $this->complete_data($memberID);

					// 	$temp_array = json_decode($temp_return);
					// 	array_push($temp_array[0]["data"], array("regPoint"=>$row['regPoint']));
					// 	return json_encode($temp_array);
					// } else {
						return $this->complete_data($memberID);
					// }
					// return json_encode(array(array("response"=>"Success")));
				} else {
					$logs->write_logs('Update Member Profile', $file_name, "[Message: Unable to complete process.] [Param: $memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete process.")));
				}
			} else {
				$logs->write_logs('Update Member Profile', $file_name, "[Message: Unable to complete process.] [Param: $memberID, $fname, $lname, $gender, $mobileNum, $dateOfBirth, $address1, $city, $drinks]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete process.")));
			}

			$sql->close();
			die($error000);
			return;
		}

		/********** Tokenizer **********/
		public function tokenizer($platform, $deviceID, $pushID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL tokenizer(?, ?, ?)";

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Tokenizer', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!isset($platform) || (!$platform)) {
				$logs->write_logs('Tokenizer', $file_name, "Bad Request - Invalid/Empty [platform: $platform]");
				die($error400);
				return;
			}

			if ($platform == 'ios') {
				$pushID = str_replace('%20', '', $pushID);
				$pushID = str_replace(' ', '', $pushID);
			}

			if (!isset($pushID) || (!$pushID)) {
				$logs->write_logs('Tokenizer', $file_name, "Bad Request - Invalid/Empty [pushID: $pushID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: tokenizer] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $platform, $deviceID, $pushID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: tokenizer] [Param: $platform, $deviceID, $pushID]");
			    die($error000);
				return;
			}

			$platform = filter_var($platform, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$pushID = filter_var($pushID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: tokenizer] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == 'Success') {					
					$logs->write_logs('Tokenizer', $file_name, "[Device ID: $deviceID, Platform: $platform, PUSH ID: $pushID]\t Status: [Success]");
					array_push($output, $row);
					$sql->close();
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} elseif ($row['result'] == 'Existing') {
					$logs->write_logs('Tokenizer', $file_name, "[Device ID: $deviceID, Platform: $platform, PUSH ID: $pushID]\t Status: [Existing]");
					array_push($output, $row);
					$sql->close();
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					$logs->write_logs('Tokenizer', $file_name, "[Message: Unable to complete tokenizing process.] [Device ID: $deviceID, Platform: $platform, PUSH ID: $pushID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete tokenizing process.")));
				}
			} else {
				$logs->write_logs('Tokenizer', $file_name, "[Message: Unable to complete tokenizing process.] [Device ID: $deviceID, Platform: $platform, PUSH ID: $pushID]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"7890", "description"=>"Unable to complete tokenizing process.")));
			}

			$sql->close();
			die($error000);
			return;
		}

		/********** Activation **********/
		public function activation($email, $activation) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Avaliable";
			$qry = "CALL activation(?, ?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: activation] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $email, $activation)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: activation] [Param: $email]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$activation = filter_var($activation, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: activation] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Success") {
					$logs->write_logs('Activation', $file_name, "[E-Mail Address: $email, Activation: $activation]\t Status: [Success]");
					$sql->close();
					return "Success";
				} else {
					$logs->write_logs('Activation', $file_name, "[Message: Unable to complete activation process.] [E-Mail Address: $email, Activation: $activation]");
					$sql->close();
					return "Failed";
				}				
			} else {
				$logs->write_logs('Activation', $file_name, "[Message: Unable to complete activation process.] [E-Mail Address: $email, Activation: $activation]");
				$sql->close();
				return "Failed";
			}
		}

		/********** Unlock **********/
		public function unlock($email, $activation) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Avaliable";
			$qry = "CALL `unlock`(?, ?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: unlock] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $email, $activation)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: unlock] [Param: $email]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$activation = filter_var($activation, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: unlock] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Success") {
					$logs->write_logs('Unlock', $file_name, "[E-Mail Address: $email, Activation: $activation]\t Status: [Success]");
					$sql->close();
					return "Success";
				} else {
					$logs->write_logs('Unlock', $file_name, "[Message: Unable to complete unlocking process.] [E-Mail Address: $email, Activation: $activation]");
					$sql->close();
					return "Failed";
				}				
			} else {
				$logs->write_logs('Unlock', $file_name, "[Message: Unable to complete unlocking process.] [E-Mail Address: $email, Activation: $activation]");
				$sql->close();
				return "Failed";
			}
		}

		/********** Check Registration E-Mail Address **********/
		public function check_registration_email($email) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Avaliable";
			$qry = "CALL check_registration_email(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_registration_email] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $email)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_registration_email] [Param: $email]");
			    die($error000);
				return;
			}

			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: check_registration_email] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if (isset($row['result'])) {
					if ($row['result'] == 'Existing') {
						$logs->write_logs('Check Registration E-Mail Address', $file_name, "E-mail Address: [$email]\t Status: [Success]\t Response : [Existing]");
						$return = "Existing";
					} elseif ($row['result'] == 'Available') {
						$logs->write_logs('Check Registration E-Mail Address', $file_name, "E-mail Address: [$email]\t Status: [Success]\t Response : [Avaliable]\t Message: [No data found.]");
						$return = "Avaliable";
					} else {
						$logs->write_logs('Check Registration E-Mail Address', $file_name, "E-mail Address: [$email]\t Status: [Success]\t Response : [Existing]");
						$return = "Existing";
					}
				} else {
					$logs->write_logs('Check Registration E-Mail Address', $file_name, "E-mail Address: [$email]\t Status: [Success]\t Response : [Existing]");
					$return = "Existing";
				}				
			} else {
				$logs->write_logs('Check Registration E-Mail Address', $file_name, "E-mail Address: [$email]\t Status: [Success]\t Response : [Existing]");
				$return = "Existing";
			}

			$sql->close();
			return $return;
		}

		/********** Validate Device ID **********/
		public function validate_device_id($deviceID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "False";
			$qry = "CALL validate_device_id(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_device_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $deviceID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_device_id] [Param: $deviceID]");
			    die($error000);
				return;
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_device_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 1) {
				$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [Multiple]");
				$return = "Multiple";
			} elseif ($result->num_rows == 1) {
				$row = $result->fetch_assoc();

				if ($row['status'] == 'active') {
					if ($row['deploy'] == 'true') {
						$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [True]");
						$return = "True";
					} else {
						$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [Undeployed]");
						$return = "Undeployed";
					}
				} else {
					$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [Inactive]");
					$return = "Inactive";
				}
			} else {
				$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [False]");
				$return = "False";
			}
			
			$sql->close();
			return $return;
		}

		/********** Validate Location ID **********/
		public function validate_location_id($locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Valid";
			$qry = "CALL validate_location_id(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_location_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_location_id] [Param: $locID]");
			    die($error000);
				return;
			}

			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_location_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$return = "Valid";
			} else {
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** Validate E-Mail Address **********/
		public function validate_email($email) {
			$isValid = true;

	        // First, we check that there's one @ symbol, and that the lengths are right
	        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	            $isValid = false;
	        }

	        // Split it into sections to make life easier
	        $email_array = explode("@", $email);
	        if (count($email_array) < 2) {
	        	$isValid = false;
	        	return $isValid;
	        }

	        $local_array = explode(".", $email_array[0]);

	        for ($i = 0; $i < sizeof($local_array); $i++) {
	            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
	                $isValid = false;
	            }
	        }

	        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	            $domain_array = explode(".", $email_array[1]);
	            if (sizeof($domain_array) < 2) {
	                $isValid = false; // Not enough parts to domain
	            }
	            for ($i = 0; $i < sizeof($domain_array); $i++) {
	                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
	                    $isValid = false;
	                }
	            }
	        }

		   	return $isValid;
		}

		/********** Validate Mobile Number **********/
		public function validate_mobile_num($mobileNum) {
			$isValid = true;
			$mobileNum = str_replace(' ', '', $mobileNum);
			$mobileNum = str_replace('+', '', $mobileNum);
			$mobileNum = str_replace('-', '', $mobileNum);

	        if (!is_numeric($mobileNum)) {
	        	$isValid = false;
	        }

	        if (strlen($mobileNum) < 11) {
	        	$isValid = false;
	        }

		   	return $isValid;
		}

		/********** Voucher List **********/
		public function voucher_list($memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL voucher_list(?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Voucher List', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: voucher_list] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: voucher_list] [Param: $memberID]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: voucher_list] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = htmlspecialchars(html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'));
				    }
					array_push($output, $row);
				}
			}

			$json_string = json_encode(array(array("response"=>"Success", "data"=>$output)));
			$json_string = str_replace('\r\n', '<br>', $json_string);
			$json_string = str_replace('\r', '<br>', $json_string);
			$json_string = str_replace('\n', '<br>', $json_string);

			return $json_string;
		}

		/********** Campaign List **********/
		public function campaign_list($memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL campaign_list(?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Campaign List', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: campaign_list] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: campaign_list] [Param: $memberID]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: campaign_list] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = htmlspecialchars(html_entity_decode($array_value, ENT_QUOTES, 'UTF-8'));
				    }
					array_push($output, $row);
				}
			}

			$json_string = json_encode(array(array("response"=>"Success", "data"=>$output)));
			$json_string = str_replace('\r\n', '<br>', $json_string);
			$json_string = str_replace('\r', '<br>', $json_string);
			$json_string = str_replace('\n', '<br>', $json_string);

			return $json_string;
		}

		/********** Send Registration Success E-Mail **********/
		public function send_registration_email($email, $password, $memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$recipient_name = "Registered Client";
			
			/*$subject = MERCHANT.' Account Details';
			$message = '<p style="font-family: Tahoma !important;">Dear, '.$recipient_name.'</p>';
			$message .= '<p style="font-family: Tahoma !important;">Thank you for registering in our '.MERCHANT.' app.</p>';
			$message .= '<p style="font-family: Tahoma !important;">Your registration is successful with username: <b>'.$email.'</b> and password: <b>'.$password.'</b></p>';
			$message .= '<p style="font-family: Tahoma !important;">You may now use your log-in information the next time you open the '.MERCHANT.' app.</p>';
			$message .= '<p style="font-family: Tahoma !important;">Have a good day!</p>';
			$message .= '<br/>';
			$message .= '<p style="font-family: Tahoma !important;">Regards,</p>';
			$message .= '<p style="font-family: Tahoma !important;">'.MERCHANT.'</p>';*/

			$subject = MERCHANT.' Account Details';
			$message = '<p style="font-family: Tahoma !important;">Dear, '.$recipient_name.'</p>';
			$message .= '<p style="font-family: Tahoma !important;">Thank you for registering for our '.MERCHANT.' app.</p>';
			$message .= '<p style="font-family: Tahoma !important;">Your username and password is: <br />Username: <b>'.$email.'</b><br/>Password: <b>'.$password.'</b></p>';
			$message .= '<p style="font-family: Tahoma !important;">Now, you\'re almost done! All you need to do is activate your account by clicking on the link below. Once that’s done, you can start enjoying our coffee while earning points! </p>';
			$message .= '<p style="font-family: Tahoma !important;"><br/><a href="'.PATH.'activation.php?email='.$email.'&activation='.$activation.'" style="background:#A52A2A; color:#fff; text-decoration:none;">ACTIVATE</a></p>';
			$message .= '<p style="font-family: Tahoma !important;">We\'re so excited to serve you, and we hope to see you soon!
</p>';
			$message .= '<br/>';
			$message .= '<p style="font-family: Tahoma !important;">Regards,</p>';
			$message .= '<p style="font-family: Tahoma !important;">'.MERCHANT.'</p>';

			$sent = send_mail($email, $recipient_name, $subject, $message);

			if ($sent == "Success") {
				$qry = "CALL email_sent(?)";

				// Prepared statement, stage 1: prepare
				if (!($sql = $mysqli->prepare($qry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: email_sent] [Query: $qry]");
				    die($error000);
					return;
				}

				if (!$sql->bind_param('s', $memberID)) {
					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: email_sent] [Param: $memberID]");
				    die($error000);
					return;
				}
				
				// Execute Prepared Statement
				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: email_sent] [Query: $qry]");
				    die($error000);
					return;
				}
			}
		}

		/********** Reserve Product **********/
		public function reserve_product($memberID, $prodID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL reserve_product(?, ?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Reserve Product', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			if (!isset($prodID) || (!$prodID)) {
				$logs->write_logs('Reserve Product', $file_name, "Bad Request - Invalid/Empty [prodID: $prodID]");
				die($error400);
				return;
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reserve_product] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $memberID, $prodID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reserve_product] [Param: $memberID, $prodID]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$prodID = filter_var($prodID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reserve_product] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Success") {
					$logs->write_logs('Reserve Product', $file_name, "[Result: ".$row['result']."] [Params: $memberID, $prodID]");
					$sql->close();
					send_mail($row['email'], $row['fname']." ".$row['lname'], "Reserve Product", "Your request has been successfully sent.");
					array_push($output, array("description"=>"Your request has been successfully sent."));
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					$logs->write_logs('Reserve Product', $file_name, "[Result: ".$row['result']."] [Message: ".$row['description'].".] [Params: $memberID, $prodID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"0987", "description"=>$row['description'])));
				}
			} else {
				$logs->write_logs('Reserve Product', $file_name, "[Result: Failed] [Message: Unable to complete reservation process.] [Params: $memberID, $prodID]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0988", "description"=>"Unable to complete reservation process.")));
			}
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		// $file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'core.class.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>