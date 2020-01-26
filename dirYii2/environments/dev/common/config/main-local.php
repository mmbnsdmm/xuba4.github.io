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
            'base_uri' => "http://",
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
        'quene_yii_task_cache_key' => "quene_yii_task_cache_key",
        'yiichina-username' => "",
        'yiichina-password' => "",
        'admin_role_ordinary_user_name' => "普通用户",
    ],
];
