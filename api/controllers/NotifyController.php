<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/23
 * Time: 11:27
 */

namespace api\controllers;


use common\components\Tools;
use wodrow\yii\rest\Controller;

class NotifyController extends Controller
{
    public function actionGogozhifuNotify()
    {
        $this->onlyDataOut = true;
        Tools::yiiLog($_GET);
        Tools::yiiLog($_POST);
        return "success";
    }

    public function actionGogozhifuReturn()
    {
        $this->onlyDataOut = true;
        Tools::yiiLog($_GET);
        Tools::yiiLog($_POST);
        return "success";
    }
}