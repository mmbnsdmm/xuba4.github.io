<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 17:28
 */

namespace common\assets;


use kartik\base\AssetBundle;

class LazyLoad extends AssetBundle
{
    public $js = [
        'https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js',
    ];
}