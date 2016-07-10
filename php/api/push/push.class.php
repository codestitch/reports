<?php

	class push_notification {

		public function push($pushID, $message, $type) {
			$GOOGLE_API_KEY = "AIzaSyDzg5bORM8MaZaanCYtIg8d1V5AeqIuKiY";
	        $url = 'https://android.googleapis.com/gcm/send';

	        $fields = array(
		        'content_available' => true,
		        'registration_ids' => $pushID,
		        'notification' => array(
		            'sound' => 'default',
		            'badge' => '1',
		            'body' => htmlspecialchars_decode($message, ENT_QUOTES),
		            'title' => 'Push Notification',
		            'click_action' => 'OPEN_MAIN_ACTIVITY',
		            'icon' => 'notification_icon_s'
		        ),
		        'data' => array(
		            'message' => htmlspecialchars_decode($message, ENT_QUOTES),
		            'title' => 'Push Notification'
		        ),
		        'priority' => 'high'
		    );

		    $headers = array(
		        'Authorization: key=' . $GOOGLE_API_KEY,
		        'Content-Type: application/json'
		    );

		    $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

			$result = curl_exec($ch);
	        if ($result === FALSE) {
	            die('Curl failed: ' . curl_error($ch));
	        } else {
	            return "Success";
	        }

	        curl_close($ch);

		}

	}

?>