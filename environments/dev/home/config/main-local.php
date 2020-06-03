<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'session' => [
            'name' => "ans-home",
            'timeout' => 86400*365,
            'cookieParams' => [
                'lifetime' => 86400*365,
                'httponly' => true,
            ],
        ],
    ],
    'params' => [],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost', '192.168.*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost', '192.168.*'],
    ];
    /*$config['modules']['gii']['generators']['wodrowmodel'] = [
        'class' => \wodrow\wajaxcrud\generators\model\Generator::class,
        'showName' => "YOUR MODEL GENERATOR",
    ];
    $config['modules']['gii']['generators']['wodrowwajaxcrud'] = [
        'class' => \wodrow\wajaxcrud\generators\crud\Generator::class,
        'showName' => "YOUR AJAX CRUD GENERATOR",
    ];*/
}

return $config;