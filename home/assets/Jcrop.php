<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-27
 * Time: 下午12:32
 */

namespace home\assets;


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