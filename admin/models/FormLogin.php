<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-4-30
 * Time: 上午8:48
 */

namespace admin\models;


use common\models\db\User;
use yii\base\Model;

class FormLogin extends Model
{
    public $account;
    public $password;
    public $code;

    public function attributeLabels()
    {
        return [
            'account' => "账号",
            'password' => "密码",
            'code' => "验证码",
        ];
    }

    public function rules()
    {
        return [
            [['account', 'password', 'code'], 'trim'],
            [['account', 'password', 'code'], 'required'],
            ['account', 'ruleCheckAccount'],
            ['password', 'validatePassword'],
            ['code', 'captcha'],
        ];
    }

    public function gCacheUser()
    {
        $user = \Yii::$app->session->get('FormLogin_cacheUser');
        if (!$user){
            $user = User::find()->where(['or', ['mobile' => $this->account], ['idcard' => $this->account]])->one();
            \Yii::$app->session->set('FormLogin_cacheUser', $user);
        }
        return $user;
    }

    public function ruleCheckAccount($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->gCacheUser();
            if (!$user) {
                $this->addError($attribute, '用户不存在');
            }
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->gCacheUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    public function login()
    {
        $user = $this->gCacheUser();
        if ($user){
            return \Yii::$app->user->login($user, 3600*2);
        }else{
            return null;
        }
    }
}