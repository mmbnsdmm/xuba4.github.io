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
                'TypeDesc' => LogEmailSendCode::instance()->typeDesc
            ],
        ];
        $this->data['enums'] = $enums;
        $this->data['status'] = 200;
        $this->data['msg'] = "获取成功";
        return $this->data;
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
            "apiDomain" => \Yii::$app->apiTool->getFullUrl(),
        ];
    }

    /**
     * app最新版本
     * @desc post
     * @return array
     * @return string lastVersion
     * @return string forceUpdate
     * @return string updateLog
     * @return string appDownloadUrl
     */
    public function actionGetLastApp()
    {
        return [
            "noticeMsg" => \Yii::$app->params['appNoticeMsg'],
            "lastVersion" => \Yii::$app->params['appLastVersion'],
            "forceUpdate" => \Yii::$app->params['appForceUpdate'],
            "updateLog" => \Yii::$app->params['appUpdateLog'],
            "androidAppDownloadUrl" => \Yii::$app->request->hostInfo.\Yii::$app->params['androidAppDownloadPath'],
            "iosAppDownloadUrl" => \Yii::$app->request->hostInfo.\Yii::$app->params['iosAppDownloadPath'],
            "webAppUrl" => \Yii::$app->params['webAppUrl'],
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
        $diskFreeSpace = substr(disk_free_space(\Yii::$app->params['useDisk'])/1024/1024/1024, 0, 4)."GB";
        return [
            'serverData' => [
                'adminEmail' => $params['adminEmail'],
                'diskFreeSpace' => $diskFreeSpace,
                'qqqs' => $params['qqqs'],
            ],
        ];
    }
}