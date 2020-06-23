<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/23
 * Time: 14:22
 */

namespace common\components;


use yii\base\Component;

class VueApp extends Component
{
    public $apiUrl;
    public $lastVersion;
    public $noticeMsg;
    public $forceUpdate = false;
    public $updateLog;
    public $androidAppDownloadUrl;
    public $iosAppDownloadUrl;
    public $webAppUrl;

    public function init()
    {
        if (!$this->apiUrl){
            $this->apiUrl = \Yii::$app->apiTool->getFullUrl();
        }
    }
}