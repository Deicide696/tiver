<?php

namespace app\components;

use Yii;
use yii\base\Component;

class PushNotifiersFireBase extends Component {
    
    private function sendMessage($fields, $api_key) {
        
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json;',
            'Authorization: key=' . $api_key
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function sendNotificationUserOS($titulo, $mensaje, $data, $ids = null) {
    }
    
}
