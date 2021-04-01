<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php'
);

return [
    'id' => YII_APP_ID,
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => \common\models\db\User::class,
            'isInConsole' => true,
            'enableSession' => false,
        ],
    ],
    'as console user login' => [
        'class' => \console\behaviors\ConsoleUserLogin::class,
        'only' => [
            'test/test1',
            'job/ct',
        ],
    ],
    'params' => $params,
];
