<?php
$basePath = dirname(__DIR__);
$config = [
    'id' => YII_APP_ID,
    'basePath' => $basePath,
    'name' => "api",
    'controllerNamespace' => 'api\controllers',
//    'defaultRoute' => 'route/api',
    'bootstrap' => [],
    'components' => [
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => \common\models\db\User::class,
        ],
    ],
    'modules' => [
        'user' => \api\modules\user\UserModule::class,
        'tag' => \api\modules\tag\TagModule::class,
        'article' => \api\modules\article\ArticleModule::class,
        'message' => \api\modules\message\MessageModule::class,
        'search' => \api\modules\search\SearchModule::class,
        'test' => \api\modules\test\TestModule::class,
    ],
    'as token check' => [
        'class' => \api\behaviors\TokenCheck::class,
        'except' => [
            'site/*',
            'gii/*',
            'debug/*',
            'route/*',
            'public/*',
            'notify/*',
        ],
    ],
];

$config = yii\helpers\ArrayHelper::merge(
    $config,
    \wodrow\yii\rest\Controller::getConfig()
);

$_urlManagers = require(Yii::getAlias('@common/config/_urlManagers.php'));
$config['components']['urlManager'] = $_urlManagers['urlManagerApi'];

return $config;