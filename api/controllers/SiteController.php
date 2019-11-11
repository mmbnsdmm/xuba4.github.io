<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace api\controllers;

use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class SiteController extends Controller
{
    /**
     * 测试
     * @desc psot
     * @return array
     * @return string str
     * @return array list
     */
    public function actionTest()
    {
        return [
            'str' => "test",
            'list' => [
                ['k' => "v"],
            ],
        ];
    }

    /**
     * 用户注册
     * @desc post
     * @param string $email
     * @param string $password
     * @param string $code 详见发送验证码
     * @throws
     * @return array
     * @return int is_ok 是否注册成功0:失败;1:成功
     * @return string msg 提示信息
     * @return object user 用户信息示例 {'token': "令牌",'key': "秘钥，不要泄露",'email': "邮箱",'avatar': "头像",'idcard': "身份证号",'realname': "真实姓名",'is_courier': "是否快递员",'dot_belong': "所属网点id",'amount': "余额",'frozen': "冻结资金",'deposit': 保证金"}
     *
     */
    public function actionSignup($email, $password, $code)
    {
        $r = $this->return;
        if (User::findOne(['mobile' => $email])){
            $r['msg'] = "此邮箱已注册";
            return $r;
        }
        if (!\Yii::$app->apiTool->validateSmsCode($email, Enum::SMS_CODE_KEY_SIGNUP, $code)){
            $r['msg'] = "验证码错误或失效";
            return $r;
        }
        $trans = \Yii::$app->db->beginTransaction();
        try {
            $user = new User();
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
        $user = \Yii::$app->apiTool->AuthReturn($user);
        $r['is_joined'] = 1;
        $r['msg'] = "注册成功";
        $r['user'] = $user;
        return $r;
    }
}