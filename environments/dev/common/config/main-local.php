<?php
return [
    'components' => [
        'db' => [
            'class' => "yii\db\Connection",
//            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname=_inityii",
//            'username' => "",
//            'password' => "",
            'charset' => "utf8mb4",
            'masterConfig' => [
                'username' => '',
                'password' => '',
            ],
            'masters' => [
                ['dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=_inityii'],
            ],
            'slaveConfig' => [
                'username' => '',
                'password' => '',
            ],
            'slaves' => [
                ['dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=_inityii'],
            ],
        ],
        'dbXuba3' => [
            'class' => "yii\db\Connection",
            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname=_xuba3",
            'username' => "",
            'password' => "",
            'charset' => "utf8mb4",
        ],
        'dbXuba2' => [
            'class' => "yii\db\Connection",
            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname=_xuba_2",
            'username' => "",
            'password' => "",
            'charset' => "utf8mb4",
        ],
        'elasticsearch' => [
            'class' => 'yii\lasticsearch\Connection',
            'node' => [
                ['http_address' => 'http://192.168.1.1:9200'],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,    //这里一定要改成false，不然邮件不会发送
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.2980.com',
                'username' => 'wodrow@2980.com',
                'password' => '',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['wodrow@2980.com'=>'wodrow@2980']
            ],
        ],
        'apiTool' => [
            'class' => \common\components\ApiTool::class,
            'baseUri' => YII_BASE_URL,
            'apiUri' => "/api",
        ],
        'uniApp' => [
            'class' => \common\components\UniApp::class,
        ],
    ],
    'aliases' => [
        '@bower' => "@vendor/bower",
        '@npm'   => "@vendor/npm",
//        '@npm'   => "@vendor/npm-asset",
    ],
    'params' => [
        'adminEmail' => "",
        'adminMobile' => "",
        'adminQQ' => "",
        'adminWX' => "",
        'quenYiiTaskCacheKey' => "quenYiiTaskCacheKey",
        'yiichina-username' => "",
        'yiichina-password' => "",
        'adminRoleOrdinaryUserName' => "普通用户",
        'adminRoleAdminUserName' => "管理员",
        'apiAdminUserIds' => [],
        'console-username' => "",
        'console-password' => "",
        'qqqs' => [],
        'qlProxy' => "",
    ],
];
