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
    /**
     * gogo支付notify
     */
    public function actionGogozhifuNotify()
    {
        $this->onlyDataOut = true;
        Tools::yiiLog($_GET);
        Tools::yiiLog($_POST);
        return "success";
    }

    /**
     * gogo支付return
     */
    public function actionGogozhifuReturn()
    {
        $this->onlyDataOut = true;
        Tools::yiiLog($_GET);
        Tools::yiiLog($_POST);
        return "success";
    }

    /**
     * 我的个人支付宝通知推送
     */
    public function actionWoPersonAliPayNotify($eStr, $sign)
    {
        return $this->success("succcess", [
            'eStr' => $eStr,
            'sign' => $sign,
        ]);
    }
}