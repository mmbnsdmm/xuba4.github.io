<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午10:55
 */

namespace console\controllers;


use QL\QueryList;
use wodrow\yii2wtools\tools\Tools;
use yii\console\Controller;

class TestController extends Controller
{
    /**
     * php yii test/test
     */
    public function actionTest()
    {
        var_dump(YII_APP_ID);
        var_dump(YII_BT_TIME + 86400*30);
        Tools::log(YII_BT_TIME);
    }

    /**
     * php yii test/test1
     */
    public function actionTest1()
    {
        $data = QueryList::getInstance()->get("https://packagist.org/packages/jaeger/querylist#V4.1.1")->rules([
            'version' => ['li.version>a', 'text'],
            'href' => ['li.version>a', 'href'],
        ])->queryData();
        var_dump($data);
        var_dump(\Yii::$app->user->identity->toArray());
    }
}