<?php
return [
    'components' => [
        'db' => [
            'class' => "yii\db\Connection",
            'dsn' => "mysql:host=127.0.0.1;dbname=",
            'username' => "",
            'password' => "",
            'charset' => "utf8mb4",
        ],
    ],
    'aliases' => [
        '@bower' => "@vendor/bower",
//        '@npm'   => "@vendor/npm-asset",
    ],
    'params' => [
        'adminEmail' => "",
        'adminMobile' => "",
        'quene_yii_task_cache_key' => "quene_yii_task_cache_key",
        'yiichina-username' => "",
        'yiichina-password' => "",
    ],
];
