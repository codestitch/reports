<?php 
	/********** PHP INIT **********/
	header('Access-Control-Allow-Origin: *');  
	header('Cache-Control: no-cache');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('mysql.connect_timeout','0');
	ini_set('max_execution_time', '0');
	ini_set('date.timezone', 'Asia/Manila'); 
 

	/********** MySQLi Config **********/
	$mysqli = new mysqli("localhost", "root", "28rskad08dwR", "work_bos");
	$sql = "";

	/********** Parameters **********/
	if ((!isset($_GET['function'])) || (!$_GET['function'])) {
		echo "error";
		return;
	} else {
		$function = $_GET['function'];
		$function = filter_var($function, FILTER_SANITIZE_URL);
	} 

	if($mysqli->connect_errno > 0){
	    die('Unable to connect to database [' . $mysqli->connect_error . ']');
	}
 
	

	$output = array();

	switch ($function) {

	 	case 'add_points':
			$memberID = $_GET['memberID'];
			$transactionType = $_GET['transactionType'];
			$points = $_GET['points']; 
			
			$earnId = "EARN".randomizer(12);

			// Query 1
			$presql = "SELECT `email` FROM `memberstable` WHERE BINARY `memberID` = '".$memberID."' LIMIT 1;"; 

			if(!$result = $mysqli->query($presql)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$row = $result->fetch_assoc(); 
			$email = $row['email'];  
 

			// Query 2
			$insertqry = "INSERT INTO `earntable` (`earnID`, `memberID`, `transactionType`, `earnType`, `points`, `dateAdded`, `dateModified`, `email`) 
						VALUES ('".$earnId."', '".$memberID."', '".$transactionType."', '".$transactionType."', '".$points."', NOW(), NOW(), '".$email."')";

			if ($mysqli->query($insertqry) === TRUE) {
			   $sqlresult = "Success";
			} else {
			    echo "Error: " . $insertqry . "<br>" . $mysqli->error;
			}


			if ($sqlresult == "Success") {
				
				// Query 3
				$curptssql = "SELECT IF(`totalPoints` IS NULL, '0', `totalPoints`) as points FROM `memberstable` WHERE BINARY `memberID` = '".$memberID."' ";
				if(!$result3 = $mysqli->query($curptssql)){
				    die('There was an error running the query [' . $mysqli->error . ']');
				}
				$row3 = $result3->fetch_assoc(); 
				$currentPoints = $row3['points'];
				// echo "\n\r1".$currentPoints." | ";

				// Query 4
				$earnsql = "SELECT IF(SUM(`points`) IS NULL, 0, SUM(`points`)) AS `earpoints` FROM `earntable` WHERE `memberID` = '".$memberID."' ";
				if(!$result4 = $mysqli->query($earnsql)){
				    die('There was an error running the query [' . $mysqli->error . ']');
				}
				$row4 = $result4->fetch_assoc(); 
				$totalearn = $row4['earpoints']; 
				// echo "\n\r2".$totalearn." | ";

				// Query 5
				$redeemsql = "SELECT IF(SUM(`points`) IS NULL, 0, SUM(`points`)) AS `redeempoints` FROM `redeemtable` WHERE `memberID` = '".$memberID."' ";
				if(!$result5 = $mysqli->query($redeemsql)){
				    die('There was an error running the query [' . $mysqli->error . ']');
				}
				$row5 = $result5->fetch_assoc(); 
				$totalredeem = $row5['redeempoints']; 
				// echo "\n\r3".$totalearn." | ";

				$subtotal = $totalearn - $totalredeem;

				if ($subtotal != $currentPoints) {	

					$lastsql = "UPDATE `memberstable` SET `totalPoints` = ".$subtotal.", `accumulatedPoints` = ".$totalearn.", `dateModified` = NOW() WHERE BINARY `memberID` = '".$memberID."' LIMIT 1";
					
					if ($mysqli->query($lastsql) === TRUE) {
					  // echo "Success";
						echo json_encode(array(array("response"=>"Success")));
					} else {
					    echo "Error: " . $lastsql . "<br>" . $mysqli->error;
					}

				}

			}


			break;

		/********** SPECIAL REPORTS **********/

		case 'get_userinformation' :
			$email = $_GET['email'];
			$favoriteDrink = ($_GET['favoriteDrink'] == "") ? "" : $_GET['favoriteDrink'];
			$gender = $_GET['gender'];
			$birthday = $_GET['birthday'];
			$startAge = $_GET['startAge'];
			$endAge = $_GET['endAge'];

			$startDate = $_GET['startDate'];
			$endDate = $_GET['endDate'];

			$filteredqry = "SELECT email, memberID, image, lname, fname, mname, CONCAT(fname, ' ', lname) as name, address1, DATE_FORMAT(dateofbirth, '%d-%M-%Y') as 'dateofbirth', gender, mobileNum, drinks, accumulatedPoints, totalPoints from memberstable where ";
			$allquery = "SELECT * from memberstable"; 
			$hasinitfilter = false; 

			if ($email != "") {
				$filteredqry .= " `email` LIKE '%" . $email. "%'";
				$hasinitfilter = true;
			}
			
			if ($favoriteDrink != "" && !$hasinitfilter) {
				$filteredqry .=  " `drinks` = '" . $favoriteDrink. "'";
				$hasinitfilter = true;
			}
			else if ($favoriteDrink != "" && $hasinitfilter){  
				$filteredqry .= " AND `drinks` = '" . $favoriteDrink. "'";
			}

			if ($gender != "" && !$hasinitfilter) {
				$filteredqry .=  " `gender` = '" . $gender . "'";
				$hasinitfilter = true;
			}
			else if ($gender != "" && $hasinitfilter) { 	  
				$filteredqry .= " AND `gender` = '" . $gender . "'";
			}

			if ($birthday != "" && !$hasinitfilter) {
				$filteredqry .=  " DATE_FORMAT(dateOfBirth, '%c') = " .$birthday;
				$hasinitfilter = true;
			}
			else if ($birthday != "" && $hasinitfilter){  
				$filteredqry .= " AND DATE_FORMAT(dateOfBirth, '%c') = " .$birthday;
			} 

			if ($startAge != "" && !$hasinitfilter) {
				$filteredqry .=  " CAST(DATEDIFF(NOW(), DATE(`dateOfBirth`)) / 365.25 AS UNSIGNED) > " . $startAge . " AND CAST(DATEDIFF(NOW(), DATE(`dateOfBirth`)) / 365.25 AS UNSIGNED) < " . $endAge;
				$hasinitfilter = true;
			}
			else if ($startAge != "" && $hasinitfilter){  
				$filteredqry .=  " AND CAST(DATEDIFF(NOW(), DATE(`dateOfBirth`)) / 365.25 AS UNSIGNED) > " . $startAge . " AND CAST(DATEDIFF(NOW(), DATE(`dateOfBirth`)) / 365.25 AS UNSIGNED) < " . $endAge;
			}

			if ($startDate != "" && !$hasinitfilter) {
				$filteredqry .=  " DATE_FORMAT(dateReg, '%Y/%m/%d') >= '".$startDate."' AND DATE_FORMAT(dateReg, '%Y/%m/%d') <= '".$endDate."' ";
				$hasinitfilter = true;
			}
			else if ($startDate != "" && $hasinitfilter){  
				$filteredqry .=  " AND DATE_FORMAT(dateReg, '%Y/%m/%d') >= '".$startDate."' AND DATE_FORMAT(dateReg, '%Y/%m/%d') <= '".$endDate."' ";
			}


			if ($email == "" && $favoriteDrink == "" && $gender == "" && $birthday == "" && $startAge == "" && $startDate == "") {
				$sql = $allquery;
			}
			else{
				$sql = $filteredqry; 
			}   

 			break;

 		case 'get_customerHistoryTransactions':
			$memberID = $_GET['memberID'];
 			
 			$sql = "SELECT e.transactiontype as 'type', e.memberid as 'memberID', e.transactionid as 'transactionID', 
						IFNULL(concat(m.fname, ' ', m.lname),e.email) as 'name', locname as 'branch', dateadded as 'date', amount as 'amount', points AS 'points'
						from earntable e inner join memberstable m on e.memberid = m.memberid
						where e.amount > 0 and e.memberid = '".$memberID."' ";

 			break;

 		case 'get_customerProfileDetail':
			$email = $_GET['email'];
 			
 			$sql = "SELECT memberID, image, email, concat(fname, ' ', lname) as name, address1 as address, DATE_FORMAT(dateofbirth, '%d-%M-%Y') as 'dateofbirth' , gender as gender, 
					mobilenum as mobile, drinks as drink, totalpoints as 'points', accumulatedpoints as 'totalpoints', profileStatus
					from memberstable where `email` = '" . $email. "'";

 			break; 


		/********** JSON **********/
 		case 'getLocation':

			$sql = "SELECT * from loctable ORDER BY locName ASC";  

			break;
	}

	if ($function != "add_points") {
		
		if ($sql != "") {

			if(!$result = $mysqli->query($sql)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    } 
					array_push($output, $row);
				}  
				
				echo json_encode(array(array("response"=>"Success", "data"=>$output)));
				
			} else { 
				echo json_encode(array(array("response"=>"Empty")));
			}		

			$mysqli->close();
		}
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