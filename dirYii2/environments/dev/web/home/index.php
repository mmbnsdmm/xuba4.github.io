<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_APP_ID') or define('YII_APP_ID', 'home');

require(__DIR__ . '/../../vendor/autoload.php');
require (__DIR__ . '/../../Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../'.YII_APP_ID.'/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../../'.YII_APP_ID.'/config/main.php'),
    require(__DIR__ . '/../../'.YII_APP_ID.'/config/main-local.php')
);

(new yii\web\Application($config))->run();
