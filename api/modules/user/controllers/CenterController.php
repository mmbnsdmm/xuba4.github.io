<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/4
 * Time: 15:00
 */

namespace api\modules\user\controllers;


use common\models\db\UserTag;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Model;

class CenterController extends Controller
{
    /**
     * 修改密码
     * @desc post
     * @param string $oldPassword
     * @param string $newPassword
     * @return array
     * @return int status 是否成功
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
     * 修改头像
     * @desc post
     * @param string $newPassword
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionUpdateAvatar($newAvatarUrl)
    {
        $user = \Yii::$app->user->identity;
        $user->avatar = $newAvatarUrl;
        if (!$user->save()){
            throw new ApiException(202008041504, "修改密码失败:".Model::getModelError($user));
        }
        return $this->success("修改成功");
    }

    /**
     * 修改联系方式
     * @desc post
     * @param string $mobile
     * @param string $weixin
     * @param string $qq
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionUpdateContact($mobile = null, $weixin = null, $qq = null)
    {
        $user = \Yii::$app->user->identity;
        $user->mobile = $mobile;
        $user->weixin = $weixin;
        $user->qq = $qq;
        if (!$user->save()){
            throw new ApiException(202008171416, "修改联系方式失败:".Model::getModelError($user));
        }
        return $this->success("修改成功");
    }

    /**
     * 修改打赏二维码
     * @desc post
     * @param string $alipay_exceptional_code 支付宝打赏二维码
     * @param string $alipay_exceptional_url 支付宝打赏二维码链接
     * @param string $weixin_exceptional_code 微信打赏二维码
     * @param string $weixin_exceptional_url 微信打赏二维码链接
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionUpdateExceptionalCode($alipay_exceptional_code = null, $alipay_exceptional_url = null, $weixin_exceptional_code = null, $weixin_exceptional_url = null)
    {
        $user = \Yii::$app->user->identity;
        $user->alipay_exceptional_code = $alipay_exceptional_code;
        $user->alipay_exceptional_url = $alipay_exceptional_url;
        $user->weixin_exceptional_code = $weixin_exceptional_code;
        $user->weixin_exceptional_url = $weixin_exceptional_url;
        if (!$user->save()){
            throw new ApiException(202008171454, "修改打赏二维码失败:".Model::getModelError($user));
        }
        return $this->success("修改成功");
    }

    /**
     * 获取用户信息
     * @desc post
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     */
    public function actionUserInfo()
    {
        $user = \Yii::$app->user->identity;
        $this->data['status'] = 200;
        $this->data['msg'] = "成功";
        $this->data['user'] = $user->profile;
        return $this->data;
    }

    /**
     * 获取用户圈子
     * @desc post
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return array utags 用户圈子
     * @return array tagIds 用户圈子id
     * @return object tagMap 用户圈子
     */
    public function actionGetMyTags()
    {
        $user = \Yii::$app->user->identity;
        $query = UserTag::find()->where(['created_by' => $user->id]);
        $utags = $query->all();
        $tagMap = ArrayHelper::map($utags, 'tag_id', "tag_name");
        $tagIds = [];
        foreach ($utags as $k => $v) {
            $tagIds[] = $v->tag_id;
        }
        return $this->success("获取成功", ['utags' => $utags, 'tagIds' => $tagIds, 'tagMap' => $tagMap]);
    }
}