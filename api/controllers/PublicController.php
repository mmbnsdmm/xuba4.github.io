<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/9/28
 * Time: 12:04
 */

namespace api\controllers;


use common\models\db\BaseCargoType;
use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;

class PublicController extends Controller
{

    /*public function actionTest()
    {
        $yii_path = \Yii::getAlias('@project_root/yii');
        $x = shell_exec("php {$yii_path} test/test6");
        var_dump($x);
    }*/

    /**
     * 对要提交的数据进行签名
     * @desc post
     * @param string $token 用户token
     * @param json $form_params 表单数据
     * @return mixed
     * @throws
     * @return array form_params form_params
     */
    public function actionSignFormParamsForAjax($token, $form_params = null)
    {
        if (!YII_ENV_DEV){
            throw new ApiException(201909281207, "只能在测试环境下进行");
        }
        if ($form_params) {
            $form_params = json_decode($form_params, true);
        }else{
            $form_params = [];
        }
        $user = User::findIdentityByAccessToken($token);
        $form_params = \Yii::$app->api_tool->generateFormParams($user, $form_params);
        return [
            'form_params' => $form_params,
        ];
    }

    /**
     * 获取货物类型
     * @desc post
     * @return array list 货物类型列表
     */
    public function actionGetCargoTypes()
    {
        $list = BaseCargoType::find()->asArray()->all();
        return [
            'list' => $list,
        ];
    }
}