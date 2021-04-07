<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/24
 * Time: 22:43
 */

namespace api\modules\user\controllers;


use common\components\Tools;
use common\models\db\UserFile;
use wodrow\yii\rest\Controller;

class FileController extends Controller
{
    /**
     * 文件上传
     * @desc post url,urls,base64,base64s,表单上传必须选其一,如果表单上传，字段必须为ufile或者ufile[]
     * @param string $url
     * @param string $urls json数组格式:['url1', 'url2']
     * @param string $base64
     * @param string $base64s json数组格式:['base64', 'base64']
     * @param string $ufile 表单上传必须有这个字段
     * @param int $url_file_download url文件是否保存到图床 1:保存到图床;0:不保存,用原有url
     * @param int $r_type 返回图片类型
     * @return array
     * @return int status 是否成功
     * @return string msg 返回信息
     * @return array urls 成功返回的所有文件链接
     * @throws
     */
    public function actionUpload($base64 = null, $base64s = null, $url = null, $urls = null, $url_file_download = 1, $r_type = UserFile::R_TYPE_ABSOLUTELY)
    {
        if ($base64s){
            $base64s = json_decode($base64s, true);
        }
        if ($urls){
            $urls = json_decode($urls, true);
        }
        $user_file = new UserFile();
        $user_file->r_type = $r_type;
        if (!$url_file_download)$user_file->r_type = UserFile::R_TYPE_ABSOLUTELY;
        $r =  $user_file->fileSave($base64, $base64s, $url, $urls, $url_file_download);
        return $r;
    }
}