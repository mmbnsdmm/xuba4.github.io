<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/1
 * Time: 17:28
 */

namespace common\assets;


use kartik\base\AssetBundle;

class Md5 extends AssetBundle
{
//    public $sourcePath = '@static_root/md5';

    public $js = [
//        'md5.min.js',
        'https://cdn.bootcdn.net/ajax/libs/blueimp-md5/2.16.0/js/md5.min.js',
    ];
}