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
     * @return int is_ok 是否发送成功
     * @return string msg
     * @throws
     */
    public function actionSendEmailCode($email, $typeKey)
    {
        $r = $this->return;
        $rules = [
            ['email', 'string', 'max' => 150],
            ['email', 'email'],
        ];
        switch ($typeKey){
            case LogEmailSendCode::TYPE_SIGNUP:
                $rules[] = ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => "email"];
                break;
            case LogEmailSendCode::TYPE_LOGIN:
            case LogEmailSendCode::TYPE_RESET_PASSWORD:
            $rules[] = ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => "email"];
                break;
            default:
                throw new ApiException(201911121120, "验证码类型未设置");
                break;
        }
        $model = DynamicModel::validateData(['email' => $email], $rules);
        if (!$model->validate()){
            $r['is_ok'] = 0;
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
     * @throws
     * @return array
     * @return int is_ok 是否注册成功0:失败;1:成功
     * @return string msg 提示信息
     */
    public function actionSignup($username, $email, $password, $code)
    {
        $r = $this->return;
        $rules = [
            [['username', 'password'], 'string', 'min' => 6, 'max' => 150],
        ];
        $model = DynamicModel::validateData(['password' => $password], $rules);
        if (!$model->validate()){
            $r['is_ok'] = 0;
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
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->status = User::STATUS_ACTIVE;
            $user->created_at = YII_BT_TIME;
            $user->setToken();
            if (!$user->save()) {
                throw new ApiException(201909231313, "注册失败:" . Model::getModelError($user));
            }
            $admin_role_ordinary_user_name = \Yii::$app->params['admin_role_ordinary_user_name'];
            if (!AdminAuthAssignment::findOne(['item_name' => $admin_role_ordinary_user_name, 'user_id' => $user->id])) {
                if (!AdminAuthItem::findOne(['name' => $admin_role_ordinary_user_name, 'type' => 1])) {
                    throw new ApiException(201911081011, "没有找到{$admin_role_ordinary_user_name}的角色");
                }
                $adminAuthAssignment = new AdminAuthAssignment();
                $adminAuthAssignment->item_name = $admin_role_ordinary_user_name;
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
        $r['is_ok'] = 1;
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
     * @return int is_ok 是否登录成功0:失败;1:成功
     * @return string msg 提示信息
     * @return object user 用户信息示例 {'token': "令牌", 'key': "秘钥， 不要泄露", 'username': "用户名", 'email': "邮箱", 'amount': "余额", 'frozen': "冻结资金", 'deposit': 保证金"}
     *
     */
    public function actionLogin($username, $password)
    {
        $log = new LogUserLogin();
        $log->from_app = LogUserLogin::FROM_APP_API;
        $log->created_at = YII_BT_TIME;
        $log->param_username = $username;
        $user = User::findOne(['username' => $username]);
        if (!$user || !$user->validatepassword($password)){
            $this->return['msg'] = "用户名或密码错误";
            $log->is_login = LogUserLogin::IS_LOGIN_N;
            if (!$log->save()){
                throw new ApiException(201911121530, "登录日志保存失败");
            }
            return $this->return;
        }
        $log->created_by = $user->id;
        switch ($user->status){
            case User::STATUS_ACTIVE:
                $this->return['is_ok'] = 1;
                $this->return['msg'] = "登录成功";
                break;
            default:
                $this->return['msg'] = "用户状态异常";
                $log->is_login = LogUserLogin::IS_LOGIN_N;
                if (!$log->save()){
                    throw new ApiException(201911121531, "登录日志保存失败");
                }
                return $this->return;
                break;
        }
        $this->return['user'] = \Yii::$app->apiTool->authReturn($user);
        $log->is_login = LogUserLogin::IS_LOGIN_Y;
        $log->from_ip = \Yii::$app->request->remoteIP;
        if (!$log->save()){
            throw new ApiException(201911121532, "登录日志保存失败");
        }
        return $this->return;
    }

    /**
     * 重置密码
     * @desc post
     * @param string $email
     * @param string $password 新密码
     * @param string $code 验证码
     * @return array
     * @return int is_ok 是否重置成功0:失败;1:成功
     * @return string msg 提示信息
     * @throws
     */
    public function actionResetPassword($email, $password, $code)
    {
        $r = $this->return;
        $rules = [
            ['password', 'string', 'min' => 6, 'max' => 150],
        ];
        $model = DynamicModel::validateData(['password' => $password], $rules);
        if (!$model->validate()){
            $r['is_ok'] = 0;
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
        $r['is_ok'] = 1;
        $r['msg'] = "密码重置成功";
        return $r;
    }
}