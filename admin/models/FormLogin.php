<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-4-30
 * Time: 上午8:48
 */

namespace admin\models;


use common\models\db\LogUserLogin;
use common\models\db\User;
use yii\base\Exception;
use yii\base\Model;

class FormLogin extends Model
{
    public $username;
    public $password;
    public $code;
    /**
     * @var bool
     */
    public $rememberMe = true;

    public function attributeLabels()
    {
        return [
            'username' => "用户名",
            'password' => "密码",
            'code' => "验证码",
            'rememberMe' => '记住我',
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password', 'code'], 'trim'],
            [['username', 'password', 'code'], 'required'],
            ['password', 'validatePassword'],
            ['code', 'captcha'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['username' => $this->username]);
            if (!$user){
                $this->addError($attribute, '用户名或密码错误');
            }else{
                if (!$user->validatePassword($this->password)) {
                    $this->addError($attribute, '用户名或密码错误');
                }
                if ($user->status != User::STATUS_ACTIVE){
                    $this->addError($attribute, '用户未激活');
                }
            }
        }
    }

    /**
     * @return int
     * @throws
     */
    public function login()
    {
        $log = new LogUserLogin();
        $log->from_app = YII_APP_ID;
        $log->created_at = YII_BT_TIME;
        $log->param_username = $this->username;
        $log->from_ip = \Yii::$app->request->remoteIP;
        $user = User::findOne(['username' => $this->username]);
        if ($user) {
            if (\Yii::$app->user->login($user, $this->rememberMe?3600*24*30:0)) {
                $log->is_login = LogUserLogin::IS_LOGIN_Y;
                $log->created_by = $user->id;
            }
        } else {
            $log->is_login = LogUserLogin::IS_LOGIN_N;
        }
        if (!$log->save()) {
            throw new Exception("登录日志保存异常:" . \wodrow\yii2wtools\tools\Model::getModelError($log));
        }
        return $log->is_login;
    }
}