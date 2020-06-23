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
     * 服务器配置
     * @desc post
     * @return array
     * @return string adminEmail
     * @return string diskFreeSpace
     */
    public function actionGetServerData()
    {
        $params = \Yii::$app->params;
        $diskFreeSpace = disk_free_space(\Yii::getAlias('@uploads_root'))/1024/1024/1024;
        $diskFreeSpace = round($diskFreeSpace, 2);
        $diskFreeSpace = $diskFreeSpace."GB";
        $this->data['status'] = 200;
        $this->data['msg'] = "正常";
        $this->data['serverData'] = [
            'adminEmail' => $params['adminEmail'],
            'diskFreeSpace' => $diskFreeSpace,
            'qqqs' => $params['qqqs'],
        ];
        return $this->data;
    }
}