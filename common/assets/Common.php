<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 11:12
 */

namespace common\assets;


use kartik\base\AssetBundle;

class Common extends AssetBundle
{
    public $sourcePath = "@static_root";

    public $js = [
        'common.js',
    ];
}