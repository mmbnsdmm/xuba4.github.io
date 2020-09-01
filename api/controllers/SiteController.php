<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace api\controllers;

use common\models\db\AdminAuthAssignment;
use common\models\db\AdminAuthItem;
use common\models\db\LogEmailSendCode;
use common\models\db\LogUserLogin;
use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;
use yii\base\DynamicModel;
use yii\base\Exception;

class SiteController extends Controller
{
    /**
     * 发送邮箱验证码
     * @desc psot
     * @param string $email
     * @param string $typeKey 注册:1;登录:2;重置密码:3
     * @return array
     * @return int status 是否发送成功
     * @return string msg
     * @throws
     */
    public function actionSendEmailCode($email, $typeKey)
    {
        $r = $this->data;
        $rules = [
            ['email', 'trim'],
            ['email', 'string', 'max' => 150],
            ['email', 'email'],
        ];
        switch ($typeKey){
            case LogEmailSendCode::TYPE_SIGNUP:
                $rules[] = ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => "email", 'message' => "邮箱已存在"];
                break;
            case LogEmailSendCode::TYPE_LOGIN:
            case LogEmailSendCode::TYPE_RESET_PASSWORD:
            $rules[] = ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => "email", 'message' => "邮箱不存在"];
                break;
            default:
                throw new ApiException(201911121120, "验证码类型未设置");
                break;
        }
        $model = DynamicModel::validateData(['email' => $email], $rules);
        if (!$model->validate()){
            $r['status'] = 0;
            $r['msg'] = Model::getModelError($model);
            return $r;
        }
        $r = \Yii::$app->apiTool->sendEmailCode($email, $typeKey, 2);
        return $r;
    }

    /**
     * 用户注册
     * @desc post
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $code 详见发送验证码
     * @param string $signup_message 注册信息
     * @throws
     * @return array
     * @return int status 是否注册成功0:失败;1:成功
     * @return string msg 提示信息
     */
    public function actionSignup($username, $email, $password, $code, $signup_message)
    {
        $r = $this->data;
        $rules = [
            [['username', 'password', 'signup_message'], 'trim'],
            [['username', 'password', 'signup_message'], 'string', 'min' => 6, 'max' => 150],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
        ];
        $model = DynamicModel::validateData(['username' => $username, 'password' => $password, 'signup_message' => $signup_message], $rules);
        if (!$model->validate()){
            $r['status'] = 0;
            $r['msg'] = Model::getModelError($model);
            return $r;
        }
        if (User::findOne(['username' => $username])){
            $r['msg'] = "用户名已注册";
            return $r;
        }
        if (User::findOne(['email' => $email])){
            $r['msg'] = "此邮箱已注册";
            return $r;
        }
        if (!\Yii::$app->apiTool->validateEmailCode($email, LogEmailSendCode::TYPE_SIGNUP, $code)){
            $r['msg'] = "验证码错误或失效";
            return $r;
        }
        $trans = \Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->setPassword($password);
            $user->signup_message = $signup_message;
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->status = User::STATUS_ACTIVE;
            $user->created_at = YII_BT_TIME;
            $user->setToken();
            if (!$user->save()) {
                throw new ApiException(201909231313, "注册失败:" . Model::getModelError($user));
            }else{
                $user->generateAvatar(true, true);
                $user->save();
            }
            $adminRoleOrdinaryUserName = \Yii::$app->params['adminRoleOrdinaryUserName'];
            if (!AdminAuthAssignment::findOne(['item_name' => $adminRoleOrdinaryUserName, 'user_id' => $user->id])) {
                if (!AdminAuthItem::findOne(['name' => $adminRoleOrdinaryUserName, 'type' => 1])) {
                    throw new ApiException(201911081011, "没有找到{$adminRoleOrdinaryUserName}的角色");
                }
                $adminAuthAssignment = new AdminAuthAssignment();
                $adminAuthAssignment->item_name = $adminRoleOrdinaryUserName;
                $adminAuthAssignment->user_id = $user->id;
                $adminAuthAssignment->created_at = YII_BT_TIME;
                if (!$adminAuthAssignment->save()) {
                    throw new ApiException(201911081012, "角色分配失败:" . Model::getModelError($adminAuthAssignment));
                }
            }
            $trans->commit();
        }catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
        $r['status'] = 200;
        $r['msg'] = "注册成功";
        return $r;
    }

    /**
     * 用户登录
     * @desc post
     * @param string $username
     * @param string $password
     * @throws
     * @return array
     * @return int status 是否登录成功0:失败;200:成功
     * @return string msg 提示信息
     * @return object user 用户信息示例 {'token': "令牌", 'key': "秘钥， 不要泄露", 'username': "用户名", 'email': "邮箱", 'amount': "余额", 'frozen': "冻结资金", 'deposit': 保证金"}
     *
     */
    public function actionLogin($username, $password)
    {
        $log = new LogUserLogin();
        $log->from_app = YII_APP_ID;
        $log->created_at = YII_BT_TIME;
        $log->param_username = $username;
        $user = User::findOne(['username' => $username]);
        if (!$user || !$user->validatepassword($password)){
            $this->data['msg'] = "用户名或密码错误";
            $log->is_login = LogUserLogin::IS_LOGIN_N;
            if (!$log->save()){
                throw new ApiException(201911121530, "登录日志保存失败");
            }
            return $this->data;
        }
        $log->created_by = $user->id;
        switch ($user->status){
            case User::STATUS_ACTIVE:
                $this->data['status'] = 200;
                $this->data['msg'] = "登录成功";
                break;
            default:
                $this->data['msg'] = "用户状态异常";
                $log->is_login = LogUserLogin::IS_LOGIN_N;
                if (!$log->save()){
                    throw new ApiException(201911121531, "登录日志保存失败");
                }
                return $this->data;
                break;
        }
        $this->data['user'] = $user->profile;
        $log->is_login = LogUserLogin::IS_LOGIN_Y;
        $log->from_ip = \Yii::$app->request->remoteIP;
        if (!$log->save()){
            throw new ApiException(201911121532, "登录日志保存失败");
        }
        return $this->data;
    }

    /**
     * 邮箱登录
     * @desc post
     * @param string $email
     * @param string $code
     * @throws
     * @return array
     * @return int status 是否登录成功0:失败;1:成功
     * @return string msg 提示信息
     * @return object user 用户信息示例 {'id': id, 'token': "令牌", 'key': "秘钥， 不要泄露", 'username': "用户名", 'email': "邮箱", 'amount': "余额", 'frozen': "冻结资金", 'availableAmount' => "可用余额", 'deposit': 保证金", 'level': 级别", 'integral': 积分", 'uclass' => "", 'alipay_income_image' => "", 'weixin_income_image' => "", 'qq' => "QQ号", 'weiixn' => "微信号"}
     *
     */
    public function actionLoginByEmail($email, $code)
    {
        $log = new LogUserLogin();
        $log->from_app = YII_APP_ID;
        $log->created_at = YII_BT_TIME;
        $log->param_email = $email;
        if (!\Yii::$app->apiTool->validateEmailCode($email, LogEmailSendCode::TYPE_LOGIN, $code)){
            $r['msg'] = "验证码错误或失效";
            return $r;
        }
        $user = User::findOne(['email' => $email]);
        if (!$user){
            $this->data['msg'] = "邮箱未找到";
            $log->is_login = LogUserLogin::IS_LOGIN_N;
            if (!$log->save()){
                throw new ApiException(201911270928, "登录日志保存失败");
            }
            return $this->return;
        }
        $log->created_by = $user->id;
        switch ($user->status){
            case User::STATUS_ACTIVE:
                $this->data['status'] = 200;
                $this->data['msg'] = "登录成功";
                break;
            default:
                $this->data['msg'] = "用户状态异常";
                $log->is_login = LogUserLogin::IS_LOGIN_N;
                if (!$log->save()){
                    throw new ApiException(201911270929, "登录日志保存失败");
                }
                return $this->data;
                break;
        }
        $this->data['user'] = $user->profile;
        $log->is_login = LogUserLogin::IS_LOGIN_Y;
        $log->from_ip = \Yii::$app->request->remoteIP;
        if (!$log->save()){
            throw new ApiException(201911121532, "登录日志保存失败");
        }
        return $this->data;
    }

    /**
     * 重置密码
     * @desc post
     * @param string $email
     * @param string $password 新密码
     * @param string $code 验证码
     * @return array
     * @return int status 是否重置成功0:失败;200:成功
     * @return string msg 提示信息
     * @throws
     */
    public function actionResetPassword($email, $password, $code)
    {
        $r = $this->data;
        $rules = [
            ['password', 'string', 'min' => 6, 'max' => 150],
        ];
        $model = DynamicModel::validateData(['password' => $password], $rules);
        if (!$model->validate()){
            $r['status'] = 0;
            $r['msg'] = Model::getModelError($model);
            return $r;
        }
        $user = User::findOne(['email' => $email]);
        if (!$user){
            $r['msg'] = "未找到用户邮箱";
            return $r;
        }
        if ($user->status != User::STATUS_ACTIVE){
            $r['msg'] = "用户非正常状态";
            return $r;
        }
        if (!\Yii::$app->apiTool->validateEmailCode($email, LogEmailSendCode::TYPE_RESET_PASSWORD, $code)){
            $r['msg'] = "验证码错误或失效";
            return $r;
        }
        $user->setPassword($password);
        if (!$user->save()){
            throw new ApiException(201911121541, Model::getModelError($user));
        }
        $r['status'] = 200;
        $r['msg'] = "密码重置成功";
        return $r;
    }
}