<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/14
 * Time: 13:30
 */

namespace api\modules\user\controllers;


use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;

class ProfileController extends Controller
{
    /**
     * 获取用户信息
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否获取成功
     * @return string msg
     * @return object user 用户信息
     */
    public function actionInfo($id)
    {
        $u = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
        $user = \Yii::$app->apiTool->authReturn($u);
        $user['isYourAttention'] = $u->isYourAttention;
        return $this->success("获取成功", ['user' => $user]);
    }

    /**
     * 关注
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     * @throws
     */
    public function actionAttention($id)
    {
        $u = $this->_getModel($id);
        $u->attention();
        $user = \Yii::$app->apiTool->authReturn($u);
        $user['isYourAttention'] = $u->isYourAttention;
        return $this->success("关注成功", ['user' => $user]);
    }

    /**
     * 取消踩一踩
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     * @throws
     */
    public function actionUnAttention($id)
    {
        $u = $this->_getModel($id);
        $u->unAttention();
        $user = \Yii::$app->apiTool->authReturn($u);
        $user['isYourAttention'] = $u->isYourAttention;
        return $this->success("取消关注成功", ['user' => $user]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws
     */
    private function _getModel($id)
    {
        $model = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
        if (!$model){
            throw new ApiException(202008201024, "没有找到用户或用户已删除");
        }
        return $model;
    }
}