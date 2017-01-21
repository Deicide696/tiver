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

$private_key = 'k87ddruralv6l7b5gjfbq4dsgnn7tb5d:';
$public_key = 'ibran2ettm4uvas5375dhfuljeec4jc2:';
echo "Privada: ".base64_encode($private_key)."<br>";

echo "Publica: ".base64_encode($public_key);