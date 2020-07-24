<?php
return [
    'components' => [
        'db' => [
            'class' => "yii\db\Connection",
            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname=_inityii",
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
            'baseUri' => getenv("BASE_URl"),
            'apiUri' => "/api",
        ],
        'vueApp' => [
            'class' => \common\components\VueApp::class,
        ],
        'uniApp' => [
            'class' => \common\components\UniApp::class,
        ],
        'sftpFileProd' => [
            'class' => \creocoder\flysystem\SftpFilesystem::class,
            'host' => '',
            'port' => 22,
            'username' => '',
            'password' => '',
            'root' => '',
        ],
        'sftpFileDev' => [
            'class' => \creocoder\flysystem\SftpFilesystem::class,
            'host' => '',
            'port' => 22,
            'username' => '',
            'password' => '',
            'root' => '',
        ],
        'sftpFileBackup' => [
            'class' => \creocoder\flysystem\SftpFilesystem::class,
            'host' => '',
            'port' => 22,
            'username' => '',
            'password' => '',
            'root' => '',
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
        'quenYiiTaskCacheKey' => "quenYiiTaskCacheKey",
        'yiichina-username' => "",
        'yiichina-password' => "",
        'adminRoleOrdinaryUserName' => "普通用户",
        'adminRoleAdminUserName' => "管理员",
        'console-username' => "",
        'console-password' => "",
        'qqqs' => [],
    ],
];
