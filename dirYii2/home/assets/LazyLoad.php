<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-2-15
 * Time: 下午5:38
 */

namespace home\assets;


use yii\web\AssetBundle;
use yii\web\YiiAsset;

class LazyLoad extends AssetBundle
{
    public $js = [
        'https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js',
    ];

    public function init()
    {
        $this->depends = [
            YiiAsset::className(),
        ];
    }
}