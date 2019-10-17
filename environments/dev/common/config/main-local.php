<?php
return [
    'components' => [
        'db' => [
            'class' => "yii\db\Connection",
            'dsn' => "mysql:host=101.37.157.112;dbname=",
            'username' => "",
            'password' => "",
            'charset' => "utf8mb4",
        ],
        'authClientCollection' => [
            'class' => "yii\authclient\Collection",
            'clients' => [
                'weixin' => [
                    'class' => "xj\oauth\WeixinAuth",
                    'clientId' => "",
                    'clientSecret' => "",
                ],
            ],
        ],
        'api_tool' => [
            'class' => \common\components\ApiTool::class,
            'base_uri' => "",
            'sms_app_id' => "", //腾讯sms
            'sms_app_key' => "",
            'sms_template_id' => "",
            'sms_sign' => "",
            'bd_ak' => "", // 百度地图ak
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
