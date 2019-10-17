<?php
define("YII_BT_TIME", time());
$_project_root = dirname(dirname(__DIR__));
Yii::setAlias('@project_root', $_project_root); // 根目录
Yii::setAlias('@common', $_project_root . '/common');
Yii::setAlias('@console', $_project_root . '/console');
Yii::setAlias('@api', $_project_root . '/api');
Yii::setAlias('@home', $_project_root . '/home');

Yii::setAlias('@wroot', $_project_root . '/web');
Yii::setAlias('@wurl', '/');
Yii::setAlias('@static_root', $_project_root . '/web/static');
Yii::setAlias('@static_url', '/static');
Yii::setAlias('@storage_root', $_project_root . '/web/storage');
Yii::setAlias('@storage_url', '/storage');
if(YII_ENV_DEV){
    Yii::setAlias('@uploads_root', $_project_root . '/web/storage/uploads/dev');
    Yii::setAlias('@uploads_url', '/storage/uploads/dev');
}else{
    Yii::setAlias('@uploads_root', $_project_root . '/web/storage/uploads/prod');
    Yii::setAlias('@uploads_url', '/storage/uploads/prod');
}
Yii::setAlias('@tmp_root', $_project_root . '/web/storage/tmp');
Yii::setAlias('@tmp_url', '/storage/tmp');

Yii::$container->set(\kartik\icons\FontAwesomeAsset::class, [
    'js' => [],
    'css' => [
        'https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css',
    ],
]);
