<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 17:27
 */

namespace common\assets;


use kartik\base\AssetBundle;

class Jcrop extends AssetBundle
{
    public $sourcePath = '@static_root/jcrop';

    public $css = [
        "jcrop.css",
    ];

    public $js = [
        "jcrop.js",
    ];
}