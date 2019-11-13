<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-13
 * Time: 上午9:43
 */

namespace home\modules\user\controllers;


use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}