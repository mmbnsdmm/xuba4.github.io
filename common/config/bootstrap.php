<?php
define("YII_BT_TIME", time());
define("YII_BT_DATE", date("Y-m-d", YII_BT_TIME));
define("YII_BT_DATETIME", date("Y-m-d H:i:s", YII_BT_TIME));
define("YII_BT_MTIME", microtime(true));
define("YII_PROJECT_ROOT", dirname(dirname(__DIR__))); // 根目录

if (YII_ENV_DEV) {
    error_reporting(E_ALL);
}

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

$BASE_URL = $_ENV['BASE_URL'];
if (Yii::$app instanceof \yii\web\Application){
    $BASE_URL = $_SERVER['HTTP_HOST'];
}

define("YII_BASE_URL", $BASE_URL);

$yiiApps = ['console', 'api', 'home', 'admin'];
Yii::setAlias('@common', YII_PROJECT_ROOT . '/common');
foreach ($yiiApps as $k => $v){
    Yii::setAlias("@{$v}", YII_PROJECT_ROOT . "/{$v}");
}
Yii::setAlias('@wroot', YII_PROJECT_ROOT . '/web');
Yii::setAlias('@wurl', '/');
Yii::setAlias('@waburl', YII_BASE_URL);
$webDirs = ['static', 'others'];
foreach ($webDirs as $k => $v) {
    Yii::setAlias("@{$v}_root", YII_PROJECT_ROOT . "/web/{$v}");
    Yii::setAlias("@{$v}_url", "/{$v}");
    Yii::setAlias("@{$v}_aburl", YII_BASE_URL . "/{$v}");
}
if (YII_ENV_DEV) {
    Yii::setAlias('@storage_root', YII_PROJECT_ROOT . '/web/storage/dev');
    Yii::setAlias('@storage_url', '/storage/dev');
    Yii::setAlias('@storage_aburl', YII_BASE_URL . '/storage/dev');
}else{
    Yii::setAlias('@storage_root', YII_PROJECT_ROOT . '/web/storage/prod');
    Yii::setAlias('@storage_url', '/storage/prod');
    Yii::setAlias('@storage_aburl', YII_BASE_URL . '/storage/prod');
}
if (!is_dir(Yii::getAlias('@storage_root'))){
    \wodrow\yii2wtools\tools\FileHelper::createDirectory(Yii::getAlias('@storage_root'));
}
$storageDirs = ['uploads', 'tmp', 'bin', 'dbbackup', 'yiiconfigbackup'];
foreach ($storageDirs as $k => $v) {
    Yii::setAlias("@{$v}_root", Yii::getAlias("@storage_root/{$v}"));
    Yii::setAlias("@{$v}_url", Yii::getAlias("@storage_url/{$v}"));
    Yii::setAlias("@{$v}_aburl", Yii::getAlias("@storage_aburl/{$v}"));
    if (!is_dir(Yii::getAlias("@{$v}_root"))){
        \wodrow\yii2wtools\tools\FileHelper::createDirectory(Yii::getAlias("@{$v}_root"));
    }
}

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

Yii::$container->set(\yii\data\Pagination::class, [
    'defaultPageSize' => 10,
]);
