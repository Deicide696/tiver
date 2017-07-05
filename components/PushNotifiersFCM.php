<?php

namespace app\components;

use Yii;
use yii\base\Component;

class PushNotifiersFCM extends Component {
	public function sendNotification( $titulo, $mensaje, $tokens, $data = null) {
		$notification ['title'] = $titulo;
		$notification ['body'] = $mensaje;
		$notification ['priority'] = 'high';
		$notification ['sound'] = 'default';
		$notification ['notification'] = $titulo;
		
		// Set FCM post variables (device IDs and push payload)
		if (is_array ( $tokens ))
			$post = array (
					'registration_ids' => $tokens,
					//'notification' => $notification,
					'data' => $notification 
			);
		else
			$post = array (
					'to' => $tokens,
					//'notification' => $notification,
					'data' => $notification 
			);
			
			// Set CURL request headers (authentication and type)
		
		$headers = array (
				'Authorization: key=' . Yii::$app->params ['fcm_key'],
				'Content-Type: application/json' 
		);
		return $this->sendCurl ( $headers, $post );
	}

	function sendCurl($headers, $post) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, Yii::$app->params ['fcm_url'] );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HEADER, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $post ) );

		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );

		$content = curl_exec ( $ch );
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		/*var_dump ( $headers );
		echo '<br><br>';
		var_dump ( $post );
		echo '<br><br>';
		var_dump ( curl_error ( $ch ) );
		echo '<br><br>';
		var_dump ( curl_errno ( $ch ) );
		echo '<br><br>';
		var_dump ($httpcode);
		echo '<br><br>';
		var_dump ( $content );
		exit ();*/

		if (curl_errno ( $ch ) || $httpcode!=200) {

			return false;
		}

		curl_close ( $ch );

		return true;
	}

	public function sendNotificationTopic( $titulo, $mensaje, $topic, $data = null) {
		$notification ['title'] = $titulo;
		$notification ['body'] = $mensaje;
		$notification ['priority'] = 'high';
		$notification ['sound'] = 'default';
		$notification ['notification'] = $titulo;

		// Set FCM post variables (device IDs and push payload)

		$post = array (
				'to' => "/topics/$topic",
				//'notification' => $notification,
				'data' => $notification
		);

		// Set CURL request headers (authentication and type)

		$headers = array (
				'Authorization: key=' . Yii::$app->params ['fcm_key'],
				'Content-Type: application/json'
		);
		return $this->sendCurl ( $headers, $post );
	}
}
