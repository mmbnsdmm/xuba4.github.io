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
    public function actionWoPersonAliPayNotify($params, $t10, $nonce, $sign)
    {
        $salt = '123456';
        $_sign = $params.$t10.$nonce.$salt;
        // $x = md5("%7B%22pkg%22%3A%22com.eg.android.AlipayGphone%22%2C%22content%22%3A%22%5B12%E6%9D%A1%5Dwww%22%2C%22notify_time%22%3A%222022-06-29%2011%3A58%3A44%22%2C%22title%22%3A%22wodrow%22%7D1656475124rand_1656475124239860638123456");
        return $this->success("success", ['x' => $_sign]);
    }
}