<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace api\controllers;


use common\data\Enum;
use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class SiteController extends Controller
{
    /**
     * 发送注册验证码
     * @desc post
     * @param string $mobile
     * @throws
     * @return array
     * @return int is_send_success 0:失败;1:成功
     */
    public function actionSendSignupCode($mobile)
    {
        if (User::findOne(['mobile' => $mobile])){
            throw new ApiException(201907231319, "此手机已注册");
        }
        $key = Enum::SMS_KEY_SIGNUP.$mobile;
        if (YII_ENV_DEV)\Yii::$app->cache->delete($key);
        $code = \Yii::$app->cache->get($key);
        if (!$code){
            $code = rand(1000, 9999);
            \Yii::$app->cache->set($key, $code, 120);
        }else{
            throw new ApiException(201907231318, "2分钟之内相同手机号不能重复发送注册验证码");
        }
        if (!\Yii::$app->api_tool->sendSmsCode($mobile, $code)){
            throw new ApiException(201907281524, "注册验证码发送失败");
        }
        return [
            'is_send' => 1,
        ];
    }

    /**
     * 用户注册
     * @desc post
     * @param string $mobile
     * @param string $password
     * @param string $code 详见发送注册验证码
     * @throws
     * @return array
     * @return string token
     * @return string key 秘钥，不要泄露
     * @return string avatar 头像
     * @return string idcard 身份证号
     * @return string realname 真实姓名
     * @return int is_courier 是否快递员
     * @return int dot_belong 所属网点id
     * @return float amount 余额
     * @return float frozen 冻结资金
     * @return float deposit 保证金
     */
    public function actionSignup($mobile, $password, $code)
    {
        if (User::findOne(['mobile' => $mobile])){
            throw new ApiException(201907231320, "此手机已注册");
        }
        $key = Enum::SMS_KEY_SIGNUP.$mobile;
        if (\Yii::$app->cache->get($key) != $code){
            throw new ApiException(201907231156, "验证码错误");
        }
        $user = new User();
        $user->mobile = $mobile;
        $user->setPassword($password);
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_ACTIVE;
        $user->created_at = YII_BT_TIME;
        $user->setToken();
        if (!$user->save()){
            throw new ApiException(201909231313, "注册失败:".Model::getModelError($user));
        }
        return \Yii::$app->api_tool->AuthReturn($user);
    }

    /**
     * 用户账号登录
     * @desc post
     * @param string $account 11位手机号或者15或18位身份证号
     * @param string $password
     * @throws
     * @return array
     * @return string token
     * @return string key 秘钥，不要泄露
     * @return string avatar 头像
     * @return string idcard 身份证号
     * @return string realname 真实姓名
     * @return int is_courier 是否快递员
     * @return int dot_belong 所属网点id
     * @return float amount 余额
     * @return float frozen 冻结资金
     * @return float deposit 保证金
     */
    public function actionLogin1($account, $password)
    {
        switch (strlen($account)){
            case 11:
                $type = "mobile";
                break;
            case 15:
                $type = "idcard";
                break;
            case 18:
                $type = "idcard";
                break;
            default:
                throw new ApiException(201907231321, "必须为手机号或身份证号");
                break;
        }
        $user = User::findOne([$type => $account]);
        if (!$user){
            throw new ApiException(201907231322, "未找到账号，请注册");
        }
        if (!$user->validatepassword($password)){
            throw new ApiException(201907231715, "密码错误");
        }
        return \Yii::$app->api_tool->AuthReturn($user);
    }

    /**
     * 手机快速登录发送验证码
     * @desc post
     * @param string $mobile
     * @throws
     * @return array
     * @return int is_send_success 0:失败;1:成功
     */
    public function actionSendLoginCode($mobile)
    {
        if (!User::findOne(['mobile' => $mobile])){
            throw new ApiException(201907231341, "此手机未注册");
        }
        $key = Enum::SMS_KEY_Login.$mobile;
        if (YII_ENV_DEV)\Yii::$app->cache->delete($key);
        $code = \Yii::$app->cache->get($key);
        if (!$code){
            $code = rand(1000, 9999);
            \Yii::$app->cache->set($key, $code, 120);
        }else{
            throw new ApiException(201907231317, "2分钟之内相同手机号不能重复发送登录验证码");
        }
        if (!\Yii::$app->api_tool->sendSmsCode($mobile, $code)){
            throw new ApiException(201907281525, "登录验证码发送失败");
        }
        return [
            'is_send' => 1,
        ];
    }

    /**
     * 手机号快速登录
     * @desc post
     * @param string $mobile
     * @param string $code 详见手机快速登录发送验证码
     * @throws
     * @return array
     * @return string token
     * @return string key 秘钥，不要泄露
     * @return string avatar 头像
     * @return string idcard 身份证号
     * @return string realname 真实姓名
     * @return int is_courier 是否快递员
     * @return int dot_belong 所属网点id
     * @return float amount 余额
     * @return float frozen 冻结资金
     * @return float deposit 保证金
     */
    public function actionLogin2($mobile, $code)
    {
        $key = Enum::SMS_KEY_Login.$mobile;
        if ($code != \Yii::$app->cache->get($key)){
            throw new ApiException(201907231343, "验证码错误");
        }
        $user = User::findOne(['mobile' => $mobile]);
        if (!$user){
            throw new ApiException(201907231344, "此手机未注册");
        }
        return \Yii::$app->api_tool->AuthReturn($user);
    }

    /**
     * 重置密码发送验证码
     * @desc post
     * @param string $mobile
     * @throws
     * @return array
     * @return int is_send_success 0:失败;1:成功
     */
    public function actionSendResetPasswordCode($mobile)
    {
        if (!User::findOne(['mobile' => $mobile])){
            throw new ApiException(201907231709, "此手机未注册");
        }
        $key = Enum::SMS_KEY_RESET_PASSWORD.$mobile;
        if (YII_ENV_DEV)\Yii::$app->cache->delete($key);
        $code = \Yii::$app->cache->get($key);
        if (!$code){
            $code = rand(1000, 9999);
            \Yii::$app->cache->set($key, $code, 120);
        }else{
            throw new ApiException(201907231316, "2分钟之内相同手机号不能重复发送重置密码验证码");
        }
        if (!\Yii::$app->api_tool->sendSmsCode($mobile, $code)){
            throw new ApiException(201907281526, "重置密码验证码发送失败");
        }
        return [
            'is_send' => 1,
        ];
    }

    /**
     * 重置密码
     * @desc post
     * @param string $mobile
     * @param string $new_password
     * @param string $code
     * @throws
     * @return array
     * @return string token
     * @return string key 秘钥，不要泄露
     */
    public function actionResetPassword($mobile, $new_password, $code)
    {
        $key = Enum::SMS_KEY_RESET_PASSWORD.$mobile;
        if ($code != \Yii::$app->cache->get($key)){
            throw new ApiException(201907231711, "验证码错误");
        }
        $user = User::findOne(['mobile' => $mobile]);
        if (!$user){
            throw new ApiException(201907231710, "此手机未注册");
        }
        $user->setPassword($new_password);
        if (!$user->save()){
            throw new ApiException(201909231716, "重置失败:".Model::getModelError($user));
        }
        return [
            'token' => $user->token,
            'key' => $user->key,
        ];
    }
}