<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午10:55
 */

namespace console\controllers;


use common\models\db\BaseArea;
use common\models\db\LogRealnameValidate;
use common\models\db\QueneYiiTask;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionTest6()
    {
        var_dump(YII_BT_TIME + 86400*30);
    }
}