<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
    'params' => [],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    /*$config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost', '192.168.*'],
    ];*/
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost', '192.168.*'],
    ];
    $config['modules']['gii']['generators']['wodrowmodel'] = [
        'class' => \wodrow\wajaxcrud\generators\model\Generator::class,
        'showName' => "WODROW MODEL",
    ];
    $config['modules']['gii']['generators']['wodrowwajaxcrud'] = [
        'class' => \wodrow\wajaxcrud\generators\crud\Generator::class,
        'showName' => "WODROE CRUD GENERATOR",
    ];
}

return $config;