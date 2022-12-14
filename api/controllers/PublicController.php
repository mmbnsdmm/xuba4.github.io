<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/9/28
 * Time: 12:04
 */

namespace api\controllers;

use common\models\db\AppVersion;
use common\models\db\Article;
use common\models\db\LogEmailSendCode;
use common\models\db\SearchIndex;
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
     * @return object enums.appVersion APP
     * @return array enums.appVersion.TypeDesc 版本app类型
     * @return array enums.article 文章
     * @return array enums.article.statusDesc 状态类型
     * @return array enums.article.isBoutiqueDesc 是否精品
     * @return array enums.searchIndex 搜索索引
     * @return array enums.searchIndex.typeDesc 类型
     */
    public function actionGetEnums()
    {
        $enums = [
            'logEmailSendCode' => [
                'typeDesc' => LogEmailSendCode::instance()->typeDesc,
            ],
            'appVersion' => [
                'typeDesc' => AppVersion::instance()->typeDesc,
            ],
            'article' => [
                'statusDesc' => Article::instance()->statusDesc,
                'isBoutiqueDesc' => Article::instance()->isBoutiqueDesc,
                'createTypeDesc' => Article::instance()->createTypeDesc,
            ],
            'searchIndex' => [
                'typeDesc' => SearchIndex::instance()->typeDesc,
            ],
        ];
        return $this->success("获取成功", ['enums' => $enums]);
    }

    /**
     * uniApp配置
     * @desc post
     * @return array
     * @return string apiUrl
     */
    public function actionGetApiUrl()
    {
        $this->onlyDataOut = true;
        return [
            "apiUrl" => \Yii::$app->uniApp->apiUrl,
        ];
    }

    /**
     * 最新版本VueApp
     * @desc post
     * @param int $type
     * @return array
     * @return int status
     * @return string msg
     * @return object model
     * @return int model.type app类型
     * @return int model.v_id 版本号
     * @return int model.version 版本
     * @return int model.is_force_update 是否强制更新
     * @return int model.pkg_url 安装包
     * @return int model.wgt_url 升级包
     * @return int model.desc 说明
     */
    public function actionGetLastVueApp($type)
    {
        $model = AppVersion::find()
            ->where(['type' => $type])
            ->andWhere(['status' => AppVersion::STATUS_ACTIVE])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        $data = $this->data;
        $data['status'] = 200;
        $data['msg'] = "获取成功";
        $data['model'] = $model;
        return $data;
    }

    /**
     * 获取信息
     * @desc post
     * @return array
     * @return int status
     * @return string msg
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
        $this->data['datas']['adminQQ'] = $params['adminQQ'];
        $this->data['datas']['adminWX'] = $params['adminWX'];
        $this->data['datas']['apiAdminUserIds'] = $params['apiAdminUserIds'];
        $this->data['datas']['appUrl'] = \Yii::$app->uniApp->appUrl;
        $this->data['datas']['sysInfo'] = \Yii::$app->uniApp->sysInfo;
        $this->data['datas']['howToUse'] = \Yii::$app->uniApp->howToUse;
        $this->data['datas']['warnings'] = \Yii::$app->uniApp->warnings;
        $this->data['datas']['qqqs'] = \Yii::$app->uniApp->qqqs;
        $this->data['datas']['searchAllKeyword'] = \Yii::$app->uniApp->searchAllKeyword;
        return $this->data;
    }
}