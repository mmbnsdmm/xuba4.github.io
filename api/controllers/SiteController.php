<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace api\controllers;

use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;

class SiteController extends Controller
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