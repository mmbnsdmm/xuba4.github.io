<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 17:29
 */

namespace common\assets;


use kartik\base\AssetBundle;

class TesseractJs extends AssetBundle
{
    public $sourcePath = "@static_root/tesseractJs";

    public $js = [
        'tesseract.min.js',
    ];
}