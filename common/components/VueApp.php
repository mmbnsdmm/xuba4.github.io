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
    public $qqqs = [
        ['qqq' => "test", 'isFull' => true, 'canJoin' => false, 'checkCode' => "15xag4ae"],
        ['qqq' => "test1", 'isFull' => false, 'canJoin' => true, 'checkCode' => "gasd451h"],
    ];
    public $sysInfo = [];
    public $howToUse = [];
    public $warnings = [];

    public function init()
    {
        if (!$this->apiUrl){
            $this->apiUrl = \Yii::$app->apiTool->getFullUrl();
        }
        $diskFreeSpace = disk_free_space(\Yii::getAlias('@uploads_root'))/1024/1024/1024;
        $diskFreeSpace = round($diskFreeSpace, 2);
        $diskFreeSpace = $diskFreeSpace."GB";
        $this->sysInfo[] = ['i' => 1, 'k' => "服务器剩余容量", 'v' => "$diskFreeSpace"];
    }
}