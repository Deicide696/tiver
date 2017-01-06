<?php

namespace app\components;

use Yii;
use yii\base\Component;

class PushNotifiersFCM extends Component {


   public function sendNotificationFCM_Expert($titulo, $mensaje, $data, $tokens = null) {

        $notification ['title'] = $titulo;
        $notification ['body'] = $mensaje;

        // Set FCM post variables (device IDs and push payload)
        $post = array(
            'registration_ids' => $tokens,
            'notification' => $notification,
            'data' => $data 
        );
        // Set CURL request headers (authentication and type)
        $headers = array(
            'Authorization: key=' . Yii::$app->params ['fcm_api_key_especialista'],
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Yii::$app->params ['fcm_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_exec($ch);
        
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return true;
   }
}
