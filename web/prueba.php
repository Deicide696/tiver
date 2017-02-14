<?php

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
$cantidad = 17544;
echo "Cantidad1: ".$cantidad."<br>";
$separamos = $cantidad%100;
$num = intval($cantidad/100);
//if($separamos < 50){
//    
//}else {
//    
//}
    echo round( 17541, -2, PHP_ROUND_HALF_UP)."<br>"; // 1.7
echo round( 1.64, 1, PHP_ROUND_HALF_UP)."<br>"; // 1.6
echo round(-1.65, 1, PHP_ROUND_HALF_UP)."<br>"; // -1.7
echo round(-1.64, 1, PHP_ROUND_HALF_UP)."<br>"; // -1.6

echo "Cantidad: ".$separamos." Numero: ".$num."<br>";

$private_key = 'k87ddruralv6l7b5gjfbq4dsgnn7tb5d:';
$public_key = 'ibran2ettm4uvas5375dhfuljeec4jc2:';

//$private_key = '4bk73tpors4das7k9a3a33ffjml7enhk:';  //  PRO




echo "Privada: ".base64_encode($private_key)."<br>";

echo "Publica 3: ".base64_encode($public_key)."<br>";

// duplicates m$ excel's ceiling function
if( !function_exists('ceiling') )
{
    function ceiling($number, $significance = 1)
    {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }
}

echo ceiling(0, 1000)."<br>";     // 0
echo ceiling(1, 1)."<br>";        // 1000
echo ceiling(1001, 1000)."<br>";  // 2000
echo ceiling(1.27, 0.05)."<br>";  // 1.30


//$d1 = date_parse ("2011-05-11 14:00:00");
//$d2 = date_parse ("2011-05-11 13:00:00");
//
//print_r($d1);
//print_r($d2);
//
//if ($d1 < $d2) {
//    echo '$d1 es menor que $d2.'."<br>";
//} else if ($d1 == $d2) {
//    echo '$d1 es igual $d2.'."<br>";
//} else {
//    echo '$d1 es mayor que $d2.'."<br>";
//}

echo password_hash("123456", PASSWORD_DEFAULT) ."<br>";
//echo var_dump("Hola",$user);
//echo "Hola";
//echo "Hola: ".Yii::$app->security->generatePasswordHash("123456").", aja<br>";

echo "Hora:" .date("H:i:s",strtotime("13:05:00"));
