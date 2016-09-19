<?php
namespace app\tasks;

date_default_timezone_set('America/Bogota');

use Yii;
//use app\models\GcmToken;


print date ('Y-m-d H:i:s').PHP_EOL;
 //exit();


//$gcm_tokens = GcmToken::find ()->select ( [ 'one_signal_token' ] )->where ( [ "user_id" => $argv[1] ] )->all ();
//foreach ( $gcm_tokens as $gcm_token ) {
//	$tokens [] = $gcm_token->one_signal_token;
//}
 $tokens [] = "a3f987f5-97fb-42ab-855c-33c012c071df";

Yii::$app->PushNotifier->sendNotificationUserOS ( "Servicio finalizado", "El servicio ha sido cobrado", $data, $tokens );

?>