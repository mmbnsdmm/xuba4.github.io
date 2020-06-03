<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/9/28
 * Time: 12:04
 */

namespace api\controllers;

use common\models\db\LogEmailSendCode;
use wodrow\yii\rest\Controller;

class PublicController extends Controller
{
    /**
     * 获取邮箱验证码类型列表
     * @desc psot
     * @return array
     * @return array types 验证码类型数组
     */
    public function actionGetEmailSendCodeTypes()
    {
        $types = LogEmailSendCode::getTypes();
        return [
            'types' => $types,
        ];
    }
}