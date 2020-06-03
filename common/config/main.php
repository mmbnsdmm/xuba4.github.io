<?php
$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\DbTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'bootstrap' => ['log'],
    'params' => [
        'icon-framework' => \kartik\icons\Icon::FA,
    ],
];
$_urlManagers = require(Yii::getAlias('@common/config/_urlManagers.php'));
foreach ($_urlManagers as $k => $v) {
    $config['components'][$k] = $v;
}
return $config;
