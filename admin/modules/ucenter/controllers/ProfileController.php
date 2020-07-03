<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/12
 * Time: 9:36
 */

namespace admin\modules\ucenter\controllers;


use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}