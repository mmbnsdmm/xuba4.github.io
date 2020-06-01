<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/5/29
 * Time: 10:27
 */

namespace common\assets;


use kartik\base\AssetBundle;

class Notify extends AssetBundle
{
    public $sourcePath = '@static_root/notify';

    public $js = [
        "bootstrap-notify.min.js",
    ];
}