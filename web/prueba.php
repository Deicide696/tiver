
<?php
namespace yii;
//use Yii;

 Yii::info('script', 'application');
Yii::trace("Hola mundo");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//$val = 33024.25;
//$resul = $val/1000 ;
//var_dump("Result: ", $resul,"Round: ",ceil($resul, 0, PHP_ROUND_HALF_UP), " X 1000: ", $resul*1000);

//$int = 5.45;
//$int_as_string = (int) $int;
//echo $int . ' is a '. gettype($int) . "\n"; 
//echo $int_as_string . ' is a '. gettype($int_as_string) . "\n";

//echo var_dump(Yii::getAlias("@app"));

// Sandbox //

//$private_key = 'k87ddruralv6l7b5gjfbq4dsgnn7tb5d:';
//$public_key = 'ibran2ettm4uvas5375dhfuljeec4jc2:';

// Produc  //

$private_key = '4bk73tpors4das7k9a3a33ffjml7enhk:';
$public_key = 'el2atmrfmmce4u3ue2ge9qpvf82jg5fu:';
echo "Privada PRO: ".base64_encode($private_key)."<br>";

echo "Publica: ".base64_encode($public_key);


//$url = Yii::$app->params ['path_scripts'];
$id_serv = 934;
$date_new = '2017-02-03';
$time_new = '20:30:00';

$url = 'C:/xampp/htdocs/tiver.backend/web';
echo $url;
$log = $url . "/logs/$id_serv.txt";
$script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date_new . '" "' . $time_new . '"';
// $url="/var/www/html/tiver/web/log_date.txt";
//exec("(sleep 180; $script > $log) > /dev/null 2>&1 &");

