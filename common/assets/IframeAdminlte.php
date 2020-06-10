<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/10
 * Time: 15:36
 */

namespace common\assets;


use kartik\base\AssetBundle;
use yii\bootstrap\BootstrapPluginAsset;

class IframeAdminlte extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = "@static_root/iframe-adminlte";
        $this->css = [
            YII_ENV_DEV?'dist/css/font-awesome.css':'dist/css/font-awesome.min.css',
            YII_ENV_DEV?'dist/css/ionicons.css':'dist/css/ionicons.min.css',
            YII_ENV_DEV?'dist/css/AdminLTE.css':'dist/css/AdminLTE.min.css',
            YII_ENV_DEV?'dist/css/skins/all-skins.css':'dist/css/skins/all-skins.min.css',
        ];
        $this->js = [
            YII_ENV_DEV?'dist/js/app.js':'dist/js/app.min.js',
            YII_ENV_DEV?'dist/js/app_iframe.js':'dist/js/app_iframe.min.js',
            'dist/js/demo.js',
            'plugins/fastclick/fastclick.js',
            'plugins/slimScroll/jquery.slimscroll.min.js',
        ];
        $this->depends = [
            BootstrapPluginAsset::class,
        ];
    }
}