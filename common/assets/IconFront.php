<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/5/25
 * Time: 11:16
 */

namespace common\assets;


use kartik\base\AssetBundle;

class IconFront extends AssetBundle
{
    public function init()
    {
        $this->css = [
            '//at.alicdn.com/t/font_1840034_auy7ebe4vs.css',
        ];
        /*$this->js = [
            'icons.js',
        ];*/
    }
}