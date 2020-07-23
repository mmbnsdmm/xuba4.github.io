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
            "https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",
//            YII_ENV_DEV?'dist/css/font-awesome.css':'dist/css/font-awesome.min.css',
            "https://cdn.bootcdn.net/ajax/libs/ionicons/2.0.0/css/ionicons.min.css",
//            YII_ENV_DEV?'dist/css/ionicons.css':'dist/css/ionicons.min.css',
//            "https://cdn.bootcdn.net/ajax/libs/admin-lte/2.3.8/css/AdminLTE.min.css",
            YII_ENV_DEV?'dist/css/AdminLTE.css':'dist/css/AdminLTE.min.css',
            "https://cdn.bootcdn.net/ajax/libs/admin-lte/2.3.8/css/skins/_all-skins.min.css",
//            YII_ENV_DEV?'dist/css/skins/all-skins.css':'dist/css/skins/all-skins.min.css',
        ];
        /*$this->js = [
            YII_ENV_DEV?'dist/js/app.js':'dist/js/app.min.js',
//            YII_ENV_DEV?'dist/js/app_iframe.js':'dist/js/app_iframe.min.js',
            YII_ENV_DEV?'dist/js/app_iframe.js':'dist/js/app_iframe.js',
//            'dist/js/demo.js',
            'plugins/fastclick/fastclick.js',
            'plugins/slimScroll/jquery.slimscroll.min.js',
        ];*/
        $this->js = [
            YII_ENV_DEV?'dist/js/app.js':'dist/js/app.min.js',
            'dist/js/app_iframe.js',
//            YII_ENV_DEV?'dist/js/app_iframe.js':'dist/js/app_iframe.min.js',
            "https://cdn.bootcdn.net/ajax/libs/fastclick/1.0.6/fastclick.min.js",
            "https://cdn.bootcdn.net/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js",
        ];
        $this->depends = [
            BootstrapPluginAsset::class,
        ];
    }
}