<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/1
 * Time: 16:41
 */

namespace home\assets;


use kartik\base\AssetBundle;

class Md5 extends AssetBundle
{
    public $sourcePath = '@static_root/md5';

    public $js = [
        'md5.min.js',
    ];
}