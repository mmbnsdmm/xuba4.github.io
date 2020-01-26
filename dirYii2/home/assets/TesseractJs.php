<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/9/28
 * Time: 18:44
 */

namespace home\assets;


use kartik\base\AssetBundle;

class TesseractJs extends AssetBundle
{
    public $sourcePath = "@static_root/tesseractJs";

    public $js = [
        'tesseract.min.js',
    ];
}