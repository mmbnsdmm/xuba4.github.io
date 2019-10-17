<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-26
 * Time: 下午4:52
 */

namespace home\behaviors;


use common\models\db\User;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class AccessCheck extends AccessControl
{
    public function beforeAction($action)
    {
//        $user = \Yii::$app->user->identity;
//        if ($user->status != User::STATUS_ACTIVE){
//            throw new ForbiddenHttpException("非正常用户");
//        }
        return parent::beforeAction($action);
    }
}