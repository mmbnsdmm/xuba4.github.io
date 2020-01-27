<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午10:55
 */

namespace console\controllers;


use wodrow\yii2wtools\tools\Tools;
use yii\console\Controller;

class TestController extends Controller
{
    /**
     * php yii test/test
     */
    public function actionTest()
    {
        var_dump(YII_BT_TIME + 86400*30);
        Tools::log(YII_BT_TIME);
    }
}