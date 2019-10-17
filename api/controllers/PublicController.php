<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/9/28
 * Time: 12:04
 */

namespace api\controllers;

use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;

class PublicController extends Controller
{
    /**
     * 测试
     * @desc psot
     * @return array
     * @return string str
     * @return array list
     */
    public function actionTest()
    {
        return [
            'str' => "test",
            'list' => [
                ['k' => "v"],
            ],
        ];
    }
}