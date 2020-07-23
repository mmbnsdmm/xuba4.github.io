<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/7/23
 * Time: 17:06
 */

namespace api\modules\test\controllers;


use wodrow\yii\rest\Controller;

class DefaultController extends Controller
{
    /**
     * test
     * @desc test
     * @param null|string $test
     * @return array
     */
    public function actionTest1($test = null)
    {
        return [
            'test' => $test,
        ];
    }
}