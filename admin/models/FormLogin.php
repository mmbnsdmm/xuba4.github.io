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
    public $username;
    public $password;
    public $code;

    public function attributeLabels()
    {
        return [
            'username' => "用户名",
            'password' => "密码",
            'code' => "验证码",
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password', 'code'], 'trim'],
            [['username', 'password', 'code'], 'required'],
            ['password', 'validatePassword'],
            ['code', 'captcha'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['username' => $this->username]);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
            if ($user->status != User::STATUS_ACTIVE){
                $this->addError($attribute, '用户未激活');
            }
        }
    }

    public function login()
    {
        $user = User::findOne(['username' => $this->username]);
        if ($user){
            return \Yii::$app->user->login($user, 3600*2);
        }else{
            return null;
        }
    }
}