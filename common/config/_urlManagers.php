<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/5/19
 * Time: 9:24
 */

$urlManagers = [
    'urlManagerHome' => [
        'class' => \yii\web\UrlManager::class,
//        'baseUrl' => YII_BASE_URL."/home",
        'baseUrl' => YII_BASE_URL,
        'hostInfo' => YII_BASE_URL,
        'enablePrettyUrl' => true,
        'showScriptName' => false,
//        'enableStrictParsing' => true,
    ],
    'urlManagerAdmin' => [
        'class' => \yii\web\UrlManager::class,
        'baseUrl' => YII_BASE_URL."/admin",
        'hostInfo' => YII_BASE_URL,
        'enablePrettyUrl' => true,
        'showScriptName' => false,
//        'enableStrictParsing' => true,
    ],
];

$_apiConfig = \wodrow\yii\rest\Controller::getConfig();
$urlManagers['urlManagerApi'] = $_apiConfig['components']['urlManager'];
$urlManagers['urlManagerApi']['class'] = \yii\web\UrlManager::class;
$urlManagers['urlManagerApi']['baseUrl'] = YII_BASE_URL."/api";
$urlManagers['urlManagerApi']['hostInfo'] = YII_BASE_URL;
//$urlManagers['urlManagerApi']['rules']['/'] = 'route/api';

return $urlManagers;