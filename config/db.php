<?php
//LOCAL
/*
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=tiver',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
];
*/
//DEV
return [
		'class' => 'yii\db\Connection',
		//'dsn' => 'mysql:host=tiver.c0hs9r9wuhtq.us-west-2.rds.amazonaws.com;dbname=tiver',
		'dsn' => 'mysql:host=tiver.cqjbs2tah4ps.us-east-1.rds.amazonaws.com;dbname=tiver',
		'username' => 'root',
		'password' => 'tiverroot',
		'charset' => 'utf8',
];