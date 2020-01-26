<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-27
 * Time: 下午2:19
 */

namespace home\assets;


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