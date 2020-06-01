<?php
namespace console\behaviors;


use common\models\db\User;
use yii\base\ActionFilter;
use yii\console\Exception;

class ConsoleUserLogin extends ActionFilter
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws
     */
    public function beforeAction($action)
    {
        $user = User::find()->where(['username' => \Yii::$app->params['console-username']])->one();
        if (!$user){
            throw new Exception("没有找到用户:".\Yii::$app->params['console-username']);
        }
        if (!$user->validatepassword(\Yii::$app->params['console-password'])){
            throw new Exception("console-password错误");
        }
        if ($user->status != User::STATUS_ACTIVE){
            throw new Exception("用户非激活状态");
        }
        \Yii::$app->user->login($user);
        return parent::beforeAction($action);
    }
}