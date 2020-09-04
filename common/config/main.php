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
        /*'cdn' => [
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
                    'class' => \yii\bootstrap\BootstrapAsset::class,
                    'css' => [
                        "https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css",
                    ],
                ],
//                [
//                    'class' => \dmstr\web\AdminLteAsset::class,
//                    'css' => [
//                        "https://cdn.bootcdn.net/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css",
//                    ],
//                    'js' => [
//                        "https://cdn.bootcdn.net/ajax/libs/admin-lte/2.4.18/js/adminlte.min.js"
//                    ],
//                ],
                [
                    'class' => \rmrevin\yii\fontawesome\AssetBundle::class,
                    'css' => [
                        'https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css',
                    ],
                ],
            ],
        ],*/
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
