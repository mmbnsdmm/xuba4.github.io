<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午5:29
 */

namespace home\models;


use common\models\db\User;
use yii\base\Model;

class FormSignup extends Model
{
    public $email;
    public $username;
    public $code;
    public $password;
    public $repassword;
    public $signup_message;

    public function attributeLabels()
    {
        return [
            'email' => "邮箱",
            'username' => "用户名",
            'password' => "密码",
            'repassword' => "确认密码",
            'code' => "验证码",
            'signup_message' => "注册信息",
        ];
    }

    public function rules()
    {
        return [
            [['email','password', 'repassword', 'code', 'signup_message'], 'trim'],
            [['email','password', 'repassword', 'code', 'signup_message'], 'required'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            [['username', 'password'], 'string', 'min' => 6, 'max' => 150],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['code', 'integer', 'min' => 100000, 'max' => 999999],
        ];
    }

    public function signUp()
    {
        $r = \Yii::$app->apiTool->post('/site/signup', ['email' => $this->email, 'username' => $this->username, 'password' => $this->password, 'code' => $this->code, 'signup_message' => $this->signup_message]);
        if ($r['code'] != 200){
            \Yii::$app->session->addFlash('error', $r['message']);
            return false;
        }
        if ($r['data']['status'] != 200){
            \Yii::$app->session->addFlash('error', $r['data']['msg']);
            return false;
        }
        \Yii::$app->session->addFlash('success', "注册成功");
        return true;
    }
}