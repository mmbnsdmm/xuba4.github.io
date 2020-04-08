<?php
$basePath = dirname(__DIR__);
$config = [
    'id' => YII_APP_ID,
    'basePath' => $basePath,
    'name' => "api",
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'route/api',
    'bootstrap' => [],
    'components' => [
        'user' => [
            'class' => \wodrow\yii2wtools\rewrite\yii2web\User::class,
            'identityClass' => \common\models\db\User::class,
        ],
        'urlManager' => [
            'rules' => [
                '/' => 'route/api',
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => \api\modules\user\UserModule::class,
        ],
    ],
    'as token check' => [
        'class' => \api\behaviors\TokenCheck::class,
        'except' => [
            'site/*',
            'gii/*',
            'debug/*',
            'route/*',
            'public/*',
        ],
    ],
];

$config = yii\helpers\ArrayHelper::merge(
    $config,
    \wodrow\yii\rest\Controller::getConfig()
);

return $config;