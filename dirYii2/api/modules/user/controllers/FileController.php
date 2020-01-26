<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/24
 * Time: 22:43
 */

namespace api\modules\user\controllers;


use common\models\db\UserFile;
use Mimey\MimeTypes;
use wodrow\yii\rest\Controller;

class FileController extends Controller
{
    /**
     * 文件上传
     * @desc post url,base64,表单上传必须选其一,如果表单上传，字段必须为ufile或者ufile[]
     * @param string $url
     * @param base64 $base64
     * @param int $url_file_download url文件是否保存到图床 1:保存到图床;0:不保存,用原有url
     * @return array
     * @return int is_ok 是否成功
     * @return string msg 返回信息
     * @return array urls 成功返回的所有文件链接
     * @throws
     */
    public function actionUpload($base64 = null, $url = null, $filename = null, $url_file_download = 1)
    {
        return \Yii::$app->apiTool->fileSave($base64, $url, $filename, $url_file_download);
    }
}