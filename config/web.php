<?php
$params = require (__DIR__ . '/params.php');

$config = [ 
		'id' => 'basic',
		'basePath' => dirname ( __DIR__ ),
		'language' => 'es',	//
		'timeZone' => 'America/Bogota',
		'bootstrap' => [ 
				'log' 
		],
		'modules' => [
				'gii' => 'yii\gii\Module',
				'gridview' => [
						'class' => '\kartik\grid\Module',
				],
		],
		
	
		'components' => [ 
				'TPaga' => [ 
						'class' => 'app\components\TPaga' 
				],
				'PushNotifier' => [ 
						'class' => 'app\components\PushNotifier' 
				],
				
				'mailer' => [ 
						'class' => 'yii\swiftmailer\Mailer',
						// 'useFileTransport' => false,
						'transport' => [ 
								'class' => 'Swift_SmtpTransport',
								'host' => 'smtp.gmail.com',
								'username' => 'tiver@zugartek.com',
								'password' => 'Tiver525',
								'port' => '587',
								'encryption' => 'tls' 
						] 
				],
				'request' => [
						// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
						'cookieValidationKey' => 'SaYT_IICQ1g6gMKKIYhU22pZnh4Pb8Mx' 
				],
				'cache' => [ 
						'class' => 'yii\caching\FileCache' 
				],
				'user' => [ 
						'identityClass' => 'app\models\User',
						'enableAutoLogin' => true 
				],
				'errorHandler' => [ 
						'errorAction' => 'site/error' 
				],
				
				'log' => [ 
						'traceLevel' => YII_DEBUG ? 3 : 0,
						'targets' => [ 
								[ 
										'class' => 'yii\log\FileTarget',
										'levels' => [ 
												'error',
												'warning' 
										] 
								] 
						] 
				],
				'urlManager' => [ 
						'class' => 'yii\web\UrlManager',
						// Disable index.php
						'showScriptName' => false,
						// Disable r= routes
						'enablePrettyUrl' => true,
						'rules' => array (
								'<controller:\w+>/<id:\d+>' => '<controller>/view',
								'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
								'<controller:\w+>/<action:\w+>' => '<controller>/<action>' 
						) 
				],
				// Moneda en COP
				'formatter' => [ 
						'class' => 'yii\i18n\Formatter',
						// 'thousandSeparator' => '.',
						// 'decimalSeparator' => ',',
						// 'currencyCode' => 'COP',
						'defaultTimeZone' => 'America/Bogota',
						'timeZone' => 'America/Bogota',
						'locale' => 'es-CO' 
				],
				'assetManager' => [
						'bundles' => [
								'dosamigos\google\maps\MapAsset' => [
										'options' => [
												'key' => 'AIzaSyD6sHf-DQOt0l-TlMv8B0IhW9BzHTOSXT8',
												'language' => 'es',
												'version' => '3.1.18'
										]
								]
						]
				],
				
				'db' => require (__DIR__ . '/db.php') 
		],
		'params' => $params 
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config ['bootstrap'] [] = 'debug';
	$config ['modules'] ['debug'] = [ 
			'class' => 'yii\debug\Module' 
	];
	
	$config ['bootstrap'] [] = 'gii';
	$config ['modules'] ['gii'] = [ 
			'class' => 'yii\gii\Module' 
	];
}

return $config;
