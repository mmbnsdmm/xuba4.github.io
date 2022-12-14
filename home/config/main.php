<?php
$basePath = dirname(__DIR__);
$config = [
    'id' => YII_APP_ID,
    'basePath' => $basePath,
    'name' => "home",
    'controllerNamespace' => 'home\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-home',
        ],
        'assetManager' => [
            'forceCopy' => YII_ENV == 'dev' ? true : false,
            'linkAssets' => YII_ENV == 'dev' ? true : false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => \common\models\db\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-home', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'modules' => [
        'user' => \home\modules\user\UserModule::class,
    ],
    'as check status' => [
        'class' => \home\behaviors\AccessCheck::class,
        'except' => ['site/*', 'debug/*','gii/*','public/*',],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => [],
];
$_urlManagers = require(Yii::getAlias('@common/config/_urlManagers.php'));
$config['components']['urlManager'] = $_urlManagers['urlManagerHome'];
return $config;