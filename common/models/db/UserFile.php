<?php

namespace common\models\db;

use Mimey\MimeTypes;
use wodrow\yii\rest\ApiException;
use wodrow\yii2wtools\tools\FileHelper;
use wodrow\yii2wtools\tools\Model;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;

/**
 * This is the model class for table "{{%user_file}}".
 *
 * @author
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property string $aburl
 * @property string $funurl
 */
class UserFile extends \common\models\db\tables\UserFile
{
    const STATUS_UPLOADED = 0;
    const STATUS_UPLOAD_FAILED = -1;
    const R_TYPE_ABSOLUTELY = 1;
    const R_TYPE_RELATIVELY = 2;
    const R_TYPE_ALIAS = 3;
    const R_TYPE_FUN = 4;
    const TEMPLATE_R_TYPE_FUN= "@USER_FILE_GET_{:id}";
    const REG_R_TYPE_FUN= "/\@USER_FILE_GET_\{(\d+)\}/";
    const REG_R_TYPE_ABSOLUTELY= "/https?:\/\/[\w|\/|\.]+/";
    const TEMPLATE_R_TYPE_FUN_FOR_ID= ":id";

    /**
     * @param $filename
     * @param $extension
     * @param $content
     * @param $tmp_file
     * @return mixed
     * @throws
     */
    public function upload($filename, $extension, $content = null, $tmp_file = null)
    {
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $user = \Yii::$app->user->identity;
        if (!$filename){
//            $filename = \Yii::$app->security->generateRandomString().".{$extension}";
            $filename = "{$y}{$m}{$d}_".\Yii::$app->security->generateRandomString().".{$extension}";
        }
//        $_path = "/user_files/{$user->id}/{$y}/{$m}/{$d}";
        $_path = "/user_files/{$user->id}";
        $user_file = new UserFile();
        $user_file->filename = $filename;
        $user_file->extension = $extension;
        $user_file->relation_path = $_path;
        $user_file->yii_alias_uploads_path = "@uploads_url";
        $user_file->yii_alias_uploads_abpath = "@uploads_aburl";
        $user_file->yii_alias_uploads_root = "@uploads_root";
        $user_file->r_type = $this->r_type;
        $user_file->created_by = $user_file->updated_by = $user->id;
        $user_file->created_at = $user_file->updated_at = YII_BT_TIME;
        $uf_root = \Yii::getAlias($user_file->yii_alias_uploads_root).$_path."/{$filename}";
        $uf_path = \Yii::getAlias($user_file->yii_alias_uploads_path).$_path."/{$filename}";
        $uf_ab_path = \Yii::getAlias($user_file->yii_alias_uploads_abpath).$_path."/{$filename}";
        $uf_alias_path = $user_file->yii_alias_uploads_abpath.$_path."/{$filename}";
        if (!is_dir(dirname($uf_root))){
            FileHelper::createDirectory(dirname($uf_root));
        }
        if ($content){
            if (file_put_contents($uf_root, $content)){
                $user_file->status = UserFile::STATUS_UPLOADED;
                if (!$user_file->save()){
                    throw new ApiException(201910291004, "数据保存失败:".Model::getModelError($user_file));
                }
            }else{
                $user_file->status = UserFile::STATUS_UPLOAD_FAILED;
                if (!$user_file->save()){
                    throw new ApiException(201910291005, "数据保存失败:".Model::getModelError($user_file));
                }
                return false;
            }
        }else{
            if ($tmp_file){
                if (!move_uploaded_file($tmp_file, $uf_root)){
                    $user_file->status = UserFile::STATUS_UPLOADED;
                    if (!$user_file->save()){
                        throw new ApiException(201910291009, "数据保存失败:".Model::getModelError($user_file));
                    }
                    throw new ApiException(201910291007, "临时文件移动失败");
                }
                $user_file->status = UserFile::STATUS_UPLOADED;
                if (!$user_file->save()){
                    throw new ApiException(201910291008, "数据保存失败:".Model::getModelError($user_file));
                }
            }else{
                throw new ApiException(201910291006, "没有找到要保存的对象");
            }
        }
        switch ($user_file->r_type){
            case self::R_TYPE_ALIAS:
                $r_path = $uf_alias_path;
                break;
            case self::R_TYPE_RELATIVELY:
                $r_path = $uf_path;
                break;
            case self::R_TYPE_ABSOLUTELY:
                $r_path = $uf_ab_path;
                break;
            case self::R_TYPE_FUN:
            default:
                $r_path = str_replace(self::TEMPLATE_R_TYPE_FUN_FOR_ID, $user_file->id, self::TEMPLATE_R_TYPE_FUN);
                break;
        }
        return $r_path;
    }

    /**
     * @param null $base64
     * @param null $url
     * @param int $url_file_download
     * @return array
     * @throws
     */
    public function fileSave($base64 = null, $base64s = null, $url = null, $urls = null, $url_file_download = 0)
    {
        $r = [
            'status' => 0,
            'msg' => "失败",
        ];
        $rurl = [];
        if ($base64){
            $rurl[] = $this->_fileSaveBase64($base64);
        }elseif($base64s){
            foreach ($base64s as $k => $v){
                $rurl[] = $this->_fileSaveBase64($v);
            }
        }else{
            if ($url){
                $rurl[] = $this->_fileSaveUrl($url, $url_file_download);
            }elseif($urls){
                foreach ($urls as $k => $v){
                    $rurl[] = $this->_fileSaveUrl($v, $url_file_download);
                }
            }else{
                if ($_FILES){
                    if (!isset($_FILES['ufile'])){
                        $r['msg'] = "表单字段必须为ufile或ufile[]";
                        return $r;
                    }
                    $ufile = $_FILES['ufile'];
                    $ufiles = [];
                    if (is_array($ufile['name'])){
                        $total = count($ufile['name']);
                        $keys = array_keys($ufile);
                        for ($i = 0; $i < $total; $i++){
                            $x = [];
                            foreach ($keys as $k => $v){
                                $x[$v] = $ufile[$v][$i];
                            }
                            $ufiles[] = $x;
                        }
                    }else{
                        $ufiles[] = $ufile;
                    }
                    foreach ($ufiles as $k => $v){
                        $rurl[] = $this->upload(null, substr(strrchr($v['name'], '.'), 1), null, $v['tmp_name']);
                    }
                }else{
                    $r['msg'] = "url,urls,base64,base64s,表单上传必须选其一";
                    return $r;
                }
            }
        }
        $r['status'] = 200;
        $r['msg'] = "成功";
        $r['urls'] = $rurl;
        return $r;
    }

    /**
     * @param $base64
     * @return
     * @throws
     */
    protected function _fileSaveBase64($base64)
    {
        $mimes = new MimeTypes();
        $mime_type = mime_content_type($base64);
        $match = preg_match('/^(data:\s*(\w+)\/([\w|-]+);base64,)/', $base64, $result);
        if(!$match){
            $r['msg'] = "信息匹配失败";
            return $r;
        }
        $extension = $mimes->getExtension($mime_type);
        $x= str_replace($result[1], '', $base64);
        $content = base64_decode($x);
        $r = $this->upload(null, $extension, $content);
        return $r;
    }

    /**
     * @param $url
     * @param int $url_file_download
     * @return mixed
     * @throws
     */
    protected function _fileSaveUrl($url, $url_file_download = 0)
    {
        if ($url_file_download){
            $extension = substr(strrchr($url, '.'), 1);
            $content = file_get_contents($url);
            $r = $this->upload(null, $extension, $content);
        }else{
            $r = $url;
        }
        return $r;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
                'updatedByAttribute' => false,
            ],
            /*'uuid' => [
                'class' => Uuid::class,
                'column' => false,
            ],*/
        ]);
    }

    public function rules()
    {
        $rules = parent::rules();
        /*foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }*/
        return ArrayHelper::merge($rules, []);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return ArrayHelper::merge($attributeLabels, []);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return bool|string
     */
    public function getAburl()
    {
        return Yii::getAlias("{$this->yii_alias_uploads_abpath}{$this->relation_path}/{$this->filename}");
    }

    /**
     * @return bool|string
     */
    public function getFunurl()
    {
        return str_replace(self::TEMPLATE_R_TYPE_FUN_FOR_ID, $this->id, self::TEMPLATE_R_TYPE_FUN);
    }

    public static function encodeContent($content)
    {
        $content = preg_replace_callback(self::REG_R_TYPE_ABSOLUTELY, function ($matches){
            $url = $matches[0];
            $filename = basename($url);
            $userFile = UserFile::findOne(['filename' => $filename]);
            return $userFile?$userFile->funurl:$url;
        }, $content);
        return $content;
    }

    public static function decodeContent($content)
    {
        $content = preg_replace_callback(self::REG_R_TYPE_FUN, function ($matches){
            $userFile = UserFile::findOne($matches[1]);
            return $userFile->aburl;
        }, $content);
        return $content;
    }
}
