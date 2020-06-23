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
     * 获取枚举信息
     * @desc post
     * @return array
     * @return int status
     * @return string msg
     * @return object enums
     * @return object enums.logEmailSendCode
     * @return array enums.logEmailSendCode.TypeDesc 验证码类型
     */
    public function actionGetEnums()
    {
        $enums = [
            'logEmailSendCode' => [
                'TypeDesc' => LogEmailSendCode::instance()->typeDesc,
            ],
        ];
        $data = $this->data;
        $data['status'] = 200;
        $data['msg'] = "获取成功";
        $data['enums'] = $enums;
        return $data;
    }

    /**
     * app配置
     * @desc post
     * @return array
     * @return string apiDomain
     */
    public function actionGetAppConfig()
    {
        $this->onlyDataOut = true;
        return [
            "apiUrl" => \Yii::$app->vueApp->apiUrl,
        ];
    }

    /**
     * 最新版本VueApp
     * @desc post
     * @return array
     * @return string lastVersion
     * @return string forceUpdate
     * @return string updateLog
     * @return string appDownloadUrl
     */
    public function actionGetLastVueApp()
    {
        return [
            "noticeMsg" => \Yii::$app->vueApp->noticeMsg,
            "lastVersion" => \Yii::$app->vueApp->lastVersion,
            "forceUpdate" => \Yii::$app->vueApp->forceUpdate,
            "updateLog" => \Yii::$app->vueApp->updateLog,
            "androidAppDownloadUrl" => \Yii::$app->vueApp->androidAppDownloadUrl,
            "iosAppDownloadUrl" => \Yii::$app->vueApp->iosAppDownloadUrl,
            "webAppUrl" => \Yii::$app->vueApp->webAppUrl,
        ];
    }

    /**
     * 获取信息
     * @desc post
     * @return array
     * @return object datas
     * @return string datas.adminEmail
     * @return array datas.sysInfo
     * @return array datas.qqqs
     */
    public function actionGetDatas()
    {
        $params = \Yii::$app->params;
        $this->data['status'] = 200;
        $this->data['msg'] = "正常";
        $this->data['datas'] = [];
        $this->data['datas']['adminEmail'] = $params['adminEmail'];
        $this->data['datas']['sysInfo'] = \Yii::$app->vueApp->sysInfo;
        $this->data['datas']['howToUse'] = \Yii::$app->vueApp->howToUse;
        $this->data['datas']['warnings'] = \Yii::$app->vueApp->warnings;
        $this->data['datas']['qqqs'] = \Yii::$app->vueApp->qqqs;
        return $this->data;
    }
}