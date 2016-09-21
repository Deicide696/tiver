<?php

namespace app\components;

use Yii;
use yii\base\Component;

class PushNotifier extends Component {
	private function sendMessage($fields, $api_key) {
		$fields = json_encode ( $fields );
		// print("\nJSON sent:\n");
		// print($fields);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications" );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json',
				'Authorization: Basic ' . $api_key 
		) );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $response;
	}
	public function sendNotificationUserOS($titulo, $mensaje, $data, $ids = null,$firebase=Yii::$app->params ['send_firebase']) {
		$data ['title'] = $titulo;
		$data ['alert'] = $mensaje;
		
		if($firebase){
			$this->sendNotificationUser($data, $ids);
		}
		
		$content = array (
				"es" => $mensaje,
				"en" => $mensaje 
		);
		$heading = array (
				"es" => $titulo,
				"en" => $titulo 
		);
		
		
		
		$time_wait = 20000;
		if (isset ( $data ['time_wait'] ))
			$time_wait = $data ['time_wait'];
		
		if ($ids == null)
			$fields = array (
					'app_id' => Yii::$app->params ['os_id_user'],
					'included_segments' => array (
							'All' 
					),
					'data' => $data,
					//'contents' => $content,
					//'headings' => $heading,
					'android_background_data' => true,
					'ttl'=>$time_wait,
			);
		else
			$fields = array (
					'app_id' => Yii::$app->params ['os_id_user'],
					'include_player_ids' => $ids,
					'data' => $data,
					//'contents' => $content,
					//'headings' => $heading,
					'android_background_data' => true,
					'ttl'=>$time_wait,
			);
		
		$response = $this->sendMessage ( $fields, Yii::$app->params ['os_api_key_user'] );
		$return ["allresponses"] = $response;
		$return = json_encode ( $return );
		
		// print("\n\nJSON received:\n");
		// print($return);
		// print("\n");
	}
	public function sendNotificationExpertOS($titulo, $mensaje, $data, $ids = null, $firebase=Yii::$app->params ['send_firebase']) {
		
		
		$data ['title'] = $titulo;
		$data ['alert'] = $mensaje;
		// print_r($data);
		
		if($firebase){
			$this->sendNotificationExpert($data, $ids);
		}
		
		$content = array (
				"es" => $mensaje,
				"en" => $mensaje 
		);
		$heading = array (
				"es" => $titulo,
				"en" => $titulo 
		);
		
		$time_wait = 20000;
		if (isset ( $data ['time_wait'] ))
			$time_wait = $data ['time_wait'];
		
		if ($ids == null)
			$fields = array (
					'app_id' => Yii::$app->params ['os_id_expert'],
					'included_segments' => array (
							'All' 
					),
					'data' => $data,
					// 'contents' => $content,
					//'headings' => $heading,
					'ttl'=>$time_wait,
					'android_background_data' => true 
			);
		else
			$fields = array (
					'app_id' => Yii::$app->params ['os_id_expert'],
					'include_player_ids' => $ids,
					'data' => $data,
					 //'contents' => $content,
					//'headings' => $heading,
					'ttl'=>$time_wait,
					'android_background_data' => true 
			);
		
		$response = $this->sendMessage ( $fields, Yii::$app->params ['os_api_key_expert'] );
		// print_r($response);
		$return ["allresponses"] = $response;
		$return = json_encode ( $return );
		
		// print("\n\nJSON received:\n");
		// print($return);
		// print("\n");
	}
	public function sendNotificationUser($data, $ids) {
		
		// Set GCM post variables (device IDs and push payload)
		$post = array (
				'registration_ids' => $ids,
				'notification' => $data 
		);
		// Set CURL request headers (authentication and type)
		$headers = array (
				'Authorization: key=' . Yii::$app->params ['gcm_api_key_user'],
				'Content-Type: application/json' 
		);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, Yii::$app->params ['gcm_url'] );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $post ) );
		
		$result = curl_exec ( $ch );
		
		if (curl_errno ( $ch )) {
			return false;
		}
		
		curl_close ( $ch );
		
		return true;
	}
	public function sendNotificationExpert($data, $ids) 

	{
		
		// Set GCM post variables (device IDs and push payload)
		$post = array (
				'registration_ids' => $ids,
				'notification' => $data 
		);
		// Set CURL request headers (authentication and type)
		$headers = array (
				'Authorization: key=' . Yii::$app->params ['gcm_api_key_especialista'],
				'Content-Type: application/json' 
		);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, Yii::$app->params ['gcm_url'] );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $post ) );
		
		$result = curl_exec ( $ch );
		
		if (curl_errno ( $ch )) {
			return false;
		}
		
		curl_close ( $ch );
		
		return true;
	}
}