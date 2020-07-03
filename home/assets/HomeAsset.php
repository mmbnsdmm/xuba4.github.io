<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午4:14
 */

namespace home\assets;


use common\assets\Common;
use common\assets\Md5;
use common\assets\Normalize;
use common\assets\Vue;
use kartik\base\AssetBundle;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\YiiAsset;

class HomeAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = "@static_root/home";
        $this->css = [
            'site.css',
        ];
        $this->js = [
            'site.js',
        ];
        $this->depends = [
            Normalize::class,
            YiiAsset::class,
            Md5::class,
            Vue::class,
            BootstrapPluginAsset::class,
            Common::class,
        ];
    }
}