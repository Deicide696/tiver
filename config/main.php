<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'bootstrap' => ['log'],

    'modules' => [],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'email-smtp.us-east-1.amazonaws.com',
                'username' => 'AKIAIUIJTAKXJY3YMXJA',
                'password' => 'AqkkVlnbpVEa5fX3diCFjdQFMTPScWwWU1A+cEilswYm',
                'port' => '25',
                'encryption' => 'tls',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\SarUser',
            'enableAutoLogin' => true,
        	'passwordResetTokenExpire'=>1000000,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'urlManager' => [
//            'showScriptName' => false,
//            'enablePrettyUrl' => true,
//        ],
//        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
//            'cookieValidationKey' => 'hUEObPZQd7fGg6TteUjvd1ONplZG2GAe',
//        ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//        ],
    ],
    'params' => $params,
];
