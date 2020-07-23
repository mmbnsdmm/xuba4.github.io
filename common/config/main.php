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
        'cdn' => [
            'class' => \yiizh\cdn\CDN::class,
            'assets' => [
                [
                    'class' => \yii\web\JqueryAsset::class,
                    'js' => [
                        "https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js",
                    ],
                ],
                [
                    'class' => \yii\bootstrap\BootstrapPluginAsset::class,
                    'js' => [
                        "https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js",
                    ],
                    'css' => [
                        "https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css",
                    ],
                ],
                [
                    'class' => \common\assets\Vue::class,
                    'js' => [
                        "https://cdn.bootcdn.net/ajax/libs/vue/2.6.11/vue.min.js",
                    ],
                ],
            ],
        ],
    ],
    'bootstrap' => ['log', 'cdn'],
    'params' => [
        'icon-framework' => \kartik\icons\Icon::FA,
    ],
];
$_urlManagers = require(Yii::getAlias('@common/config/_urlManagers.php'));
foreach ($_urlManagers as $k => $v) {
    $config['components'][$k] = $v;
}
return $config;
