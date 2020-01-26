<?php
define("YII_BT_TIME", time());
define("YII_PROJECT_ROOT", dirname(dirname(__DIR__))); // 根目录
Yii::setAlias('@common', YII_PROJECT_ROOT . '/common');
Yii::setAlias('@console', YII_PROJECT_ROOT . '/console');
Yii::setAlias('@api', YII_PROJECT_ROOT . '/api');
Yii::setAlias('@home', YII_PROJECT_ROOT . '/home');
Yii::setAlias('@admin', YII_PROJECT_ROOT . '/admin');

Yii::setAlias('@wroot', YII_PROJECT_ROOT . '/web');
Yii::setAlias('@wurl', '/');
Yii::setAlias('@static_root', YII_PROJECT_ROOT . '/web/static');
Yii::setAlias('@static_url', '/static');
Yii::setAlias('@storage_root', YII_PROJECT_ROOT . '/web/storage');
Yii::setAlias('@storage_url', '/storage');
if(YII_ENV_DEV){
    Yii::setAlias('@uploads_root', YII_PROJECT_ROOT . '/web/storage/uploads/dev');
    Yii::setAlias('@uploads_url', '/storage/uploads/dev');
}else{
    Yii::setAlias('@uploads_root', YII_PROJECT_ROOT . '/web/storage/uploads/prod');
    Yii::setAlias('@uploads_url', '/storage/uploads/prod');
}
Yii::setAlias('@tmp_root', YII_PROJECT_ROOT . '/web/storage/tmp');
Yii::setAlias('@tmp_url', '/storage/tmp');

Yii::$container->set(\kartik\icons\FontAwesomeAsset::class, [
    'js' => [],
    'css' => [
        'https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css',
    ],
]);

Yii::$container->set(\yii\widgets\LinkPager::class, [
    'maxButtonCount' => 9,
    'firstPageLabel' => '首页',
    'lastPageLabel' => '末页',
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页',
]);
