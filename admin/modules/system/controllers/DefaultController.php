<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/11
 * Time: 16:55
 */

namespace admin\modules\system\controllers;


use common\components\Tools;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionInfo()
    {
        // 禁用函数
        $disableFunctions = ini_get('disable_functions');
        $disableFunctions = !empty($disableFunctions) ? explode(',', $disableFunctions) : '未禁用';
        // 附件大小
        $attachmentDir = \Yii::getAlias('@storage_root/uploads');
        $attachmentSize = Tools::getDirSize($attachmentDir);

        $models = \Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $this->render('info', [
            'mysql_size' => $mysqlSize,
            'attachment_dir' => $attachmentDir,
            'attachment_size' => $attachmentSize ?? 0,
            'disable_functions' => $disableFunctions,
        ]);
    }
}