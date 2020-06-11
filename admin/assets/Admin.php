<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/9
 * Time: 16:40
 */

namespace admin\assets;


use common\assets\Common;
use common\assets\Md5;
use common\assets\Normalize;
use common\assets\Vue;
use dmstr\web\AdminLteAsset;
use kartik\base\AssetBundle;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\YiiAsset;

class Admin extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = "@static_root/admin";
        $this->css = [
            'site.css',
        ];
        $this->js = [
            'site.js',
        ];
        $this->depends = [
            Normalize::class,
            YiiAsset::class,
            BootstrapPluginAsset::class,
            AdminLteAsset::class,
            Md5::class,
            Vue::class,
            Common::class,
        ];
    }
}