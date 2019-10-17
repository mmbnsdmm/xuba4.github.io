<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-30
 * Time: 上午11:32
 */

namespace home\controllers;


use yii\web\Controller;

class PublicController extends Controller
{
    public function actionHowToUseApi()
    {
        return $this->render('how-to-use-api');
    }
}