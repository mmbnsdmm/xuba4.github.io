<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/4
 * Time: 15:00
 */

namespace api\modules\user\controllers;


use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class CenterController extends Controller
{
    /**
     * 修改密码
     * @desc post
     * @param string $oldPassword
     * @param string $newPassword
     * @return array
     * @return int is_ok 是否发送成功
     * @return string msg
     */
    public function actionUpdatePassword($oldPassword, $newPassword)
    {
        $user = \Yii::$app->user->identity;
        if (!$user->validatepassword($oldPassword)){
            return $this->error("老密码错误");
        }
        $user->setPassword($newPassword);
        if (!$user->save()){
            throw new ApiException(202008041504, "修改密码失败:".Model::getModelError($user));
        }
        return $this->success("修改成功");
    }

    /**
     * 获取用户信息
     * @desc post
     * @return array
     * @return int is_ok 是否成功
     * @return string msg
     * @return object user 用户信息
     */
    public function actionUserInfo()
    {
        $user = \Yii::$app->user->identity;
        $user = \Yii::$app->apiTool->authReturn($user);
        $this->data['status'] = 200;
        $this->data['msg'] = "成功";
        $this->data['user'] = $user;
        return $this->data;
    }
}