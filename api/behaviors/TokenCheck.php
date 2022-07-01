<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-24
 * Time: 上午9:34
 */

namespace api\behaviors;


use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\base\ActionFilter;

class TokenCheck extends ActionFilter
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws
     */
    public function beforeAction($action)
    {
        $params = \Yii::$app->request->post();
        if (isset($params['key'])){
            throw new ApiException(201808161401, '请不要传key，会有安全隐患');
        }
        if (!isset($params['token'])) {
            throw new ApiException(201808161402, '请求必须有认证口令');
        }
        if (strlen($params['token']) != 32) {
            throw new ApiException(201808161403, '认证口令长度错误');
        }else{
            $user = User::findIdentityByAccessToken($params['token']);
        }
        if (!$user){
            throw new ApiException(201808161455, "没有找到用户,请重新登陆");
        }
        if (1){
            if (!isset($params['timestamp'])) {
                throw new ApiException(201808161406, '请求必须有当前操作时间戳');
            }
            if (strlen($params['timestamp']) != 10) {
                throw new ApiException(201808161407, '时间戳长度必须为10位秒数');
            }
            if (abs(substr($params['timestamp'], 0, 10) - YII_BT_TIME) > 600){
                throw new ApiException(201808161408, '操作超时或时间异常，请检查设备时间');
            }
            if (!isset($params['nonce'])) {
                throw new ApiException(201808161409, '请求必须设置随机数');
            }
            if (strlen($params['nonce']) < 3) {
                throw new ApiException(201808161411, '随机数必须4位以上');
            }
            $nonce_key = 'nonceUser'.$user->id;
            $tmp_nonces = is_array(\Yii::$app->cache->get($nonce_key))?\Yii::$app->cache->get($nonce_key):[];
            if (!in_array($params['nonce'], $tmp_nonces)){
                $tmp_nonces[] = $params['nonce'];
                if (count($tmp_nonces) > 2000) {
                    unset($tmp_nonces[0]);
                }
                \Yii::$app->cache->set($nonce_key, $tmp_nonces, 3600);
            }else{
                throw new ApiException(201808161412, '请求随机数重复');
            }
            if (!isset($params['sign'])) {
                throw new ApiException(201808161404, '请求必须有请求签名');
            }
            if (strlen($params['sign'])!=32) {
                throw new ApiException(201808161405, '请求签名长度错误');
            }
            $p = $params;
            $p['key'] = $user->key;
            ksort($p);
            unset($p['sign']);
            $p_arr = [];
            foreach ($p as $k => $v) {
                $p_arr[] = $k."=".$v;
            }
            $p_str = ArrayHelper::arr2str($p_arr, '&');
            $_sign = md5($p_str);
            if ($_sign != $params['sign']){
                throw new ApiException(201808161457, "验签失败");
            }
        }
        if ($user->status != User::STATUS_ACTIVE){
            throw new ApiException(201808161456, "用户状态异常,你是否被禁了");
        }
        \Yii::$app->user->login($user);
        return parent::beforeAction($action);
    }
}