<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-26
 * Time: 下午3:24
 */

namespace home\models;


use common\models\db\LogEmailSendCode;
use common\models\db\User;
use yii\base\Model;

class FormResetPassword extends Model
{
    public $email;
    public $code;
    public $password;
    public $repassword;

    public function attributeLabels()
    {
        return [
            'email' => "邮箱",
            'password' => "密码",
            'repassword' => "确认密码",
            'code' => "验证码",
        ];
    }

    public function rules()
    {
        return [
            [['email','password', 'repassword', 'code'], 'trim'],
            [['email','password', 'repassword', 'code'], 'required'],
            ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['code', 'string', 'min' => 32, 'max' => 32],
        ];
    }

    public function resetPassword()
    {
        $r = \Yii::$app->apiTool->post('site/reset-password', ['email' => $this->email, 'password' => $this->password, 'code' => $this->code]);
        if ($r['code'] != 200){
            \Yii::$app->session->addFlash('error', $r['message']);
            return false;
        }
        if ($r['data']['status'] != 200){
            \Yii::$app->session->addFlash('error', $r['data']['msg']);
            return false;
        }
        \Yii::$app->session->addFlash('success', "重置密码成功");
        return true;
    }
}