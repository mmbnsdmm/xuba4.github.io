<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/14
 * Time: 13:30
 */

namespace api\modules\user\controllers;


use common\models\db\User;
use wodrow\yii\rest\Controller;

class ProfileController extends Controller
{
    /**
     * 获取用户信息
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     */
    public function actionInfo($id)
    {
        $user = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
        $user = \Yii::$app->apiTool->authReturn($user);
        $this->data['status'] = 200;
        $this->data['msg'] = "成功";
        $this->data['user'] = $user;
        return $this->data;
    }
}