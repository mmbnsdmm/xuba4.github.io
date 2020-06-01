<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 17:27
 */

namespace common\assets;


use kartik\base\AssetBundle;

class CropperJs extends AssetBundle
{
    public $sourcePath = '@static_root/cropper';

    public $css = [
        "cropper.min.css",
        "offical-theme.css",
    ];

    public $js = [
        "cropper.min.js",
    ];
}