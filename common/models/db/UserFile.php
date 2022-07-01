<?php

namespace common\models\db;

use Mimey\MimeTypes;
use QL\QueryList;
use wodrow\yii\rest\ApiException;
use wodrow\yii2wtools\tools\FileHelper;
use wodrow\yii2wtools\tools\Model;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;
use wodrow\yii2wtools\tools\ArrayHelper as ToolsArrayHelper;

/**
 * This is the model class for table "{{%user_file}}".
 *
 * @author
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property string $root
 * @property string $url
 * @property string $aburl
 * @property string $aliasurl
 * @property string $funurl
 * @property string $rTypeResult
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
//    const REG_R_TYPE_ABSOLUTELY= "/https?:\/\/([\w|\/|\.|\-|:]+)/";
    const REG_R_TYPE_ABSOLUTELY= "/(https?\:\/\/[\w|\.|\-|\:?]+)([\w|\/|\\\|\.|\-]+)/";
    const TEMPLATE_R_TYPE_FUN_FOR_ID= ":id";

    public function generateFilename()
    {
        $Y = date("Y");
        $m = date("m");
        $d = date("d");
        $H = date("H");
        $i = date("i");
        $s = date("s");
        $this->filename = "{$Y}{$m}{$d}_{$H}{$i}{$s}_".\Yii::$app->security->generateRandomString().".{$this->extension}";
        if (self::findOne(['filename' => $this->filename])){
            $this->generateFilename();
        }
    }

    /**
     * @param $content
     * @param $tmp_file
     * @return mixed
     * @throws
     */
    public function upload($content = null, $tmp_file = null)
    {
        $user = \Yii::$app->user->identity;
        $_path = "/user_files/{$user->id}";
        $extension = $this->extension;
        list($ext, $query) = ToolsArrayHelper::str2arr($extension, "?");
        $userFile = clone $this;
        $userFile->extension = $ext;
        $userFile->generateFilename();
        $userFile->relation_path = $_path;
        $userFile->yii_alias_uploads_path = "@uploads_url";
        $userFile->yii_alias_uploads_abpath = "@uploads_aburl";
        $userFile->yii_alias_uploads_root = "@uploads_root";
        $userFile->created_by = $userFile->updated_by = $user->id;
        $userFile->created_at = $userFile->updated_at = YII_BT_TIME;
        $uf_root = \Yii::getAlias($userFile->yii_alias_uploads_root).$_path."/{$userFile->filename}";
        if (!is_dir(dirname($uf_root))){
            FileHelper::createDirectory(dirname($uf_root));
        }
        if ($content){
            if (file_put_contents($uf_root, $content)){
                $userFile->status = UserFile::STATUS_UPLOADED;
                if (!$userFile->save()){
                    throw new ApiException(201910291004, "数据保存失败:".Model::getModelError($userFile));
                }
            }else{
                $userFile->status = UserFile::STATUS_UPLOAD_FAILED;
                if (!$userFile->save()){
                    throw new ApiException(201910291005, "数据保存失败:".Model::getModelError($userFile));
                }
                return false;
            }
        }else{
            if ($tmp_file){
                if (!move_uploaded_file($tmp_file, $uf_root)){
                    $userFile->status = UserFile::STATUS_UPLOADED;
                    if (!$userFile->save()){
                        throw new ApiException(201910291009, "数据保存失败:".Model::getModelError($userFile));
                    }
                    throw new ApiException(201910291007, "临时文件移动失败");
                }
                $userFile->status = UserFile::STATUS_UPLOADED;
                if (!$userFile->save()){
                    throw new ApiException(201910291008, "数据保存失败:".Model::getModelError($userFile));
                }
            }else{
                throw new ApiException(201910291006, "没有找到要保存的对象");
            }
        }
        return $userFile->rTypeResult;
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
        $rurls = [];
        if ($base64){
            $rurls[] = $this->_fileSaveBase64($base64);
        }elseif($base64s){
            foreach ($base64s as $k => $v){
                $rurls[] = $this->_fileSaveBase64($v);
            }
        }else{
            if ($url){
                $rurls[] = $this->_fileSaveUrl($url, $url_file_download);
            }elseif($urls){
                foreach ($urls as $k => $v){
                    $rurls[] = $this->_fileSaveUrl($v, $url_file_download);
                }
            }else{
                if ($_FILES){
                    if (!isset($_FILES['ufile'])){
                        throw new Exception("表单字段必须为ufile或ufile[]");
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
                        $this->extension = substr(strrchr($v['name'], '.'), 1);
                        $rurls[] = $this->upload(null, $v['tmp_name']);
                    }
                }else{
                    throw new Exception("url,urls,base64,base64s,表单上传必须选其一");
                }
            }
        }
        $r['status'] = 200;
        $r['msg'] = "成功";
        $r['urls'] = $rurls;
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
        $this->extension = $mimes->getExtension($mime_type);
        $x= str_replace($result[1], '', $base64);
        $content = base64_decode($x);
        $r = $this->upload($content);
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
            $userFile = UserFile::findOne(['original_url' => $url]);
            if ($userFile){
                $userFile->r_type = $this->r_type;
                $r = $userFile->rTypeResult;
            }else{
                $this->r_type = UserFile::R_TYPE_ABSOLUTELY;
                $this->original_url = $url;
                $this->extension = substr(strrchr($url, '.'), 1);
                $content = file_get_contents($url);
                $r = $this->upload($content);
            }
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
    public function getRoot()
    {
        return Yii::getAlias("{$this->yii_alias_uploads_root}{$this->relation_path}/{$this->filename}");
    }

    /**
     * @return bool|string
     */
    public function getUrl()
    {
        return Yii::getAlias("{$this->yii_alias_uploads_path}{$this->relation_path}/{$this->filename}");
    }

    /**
     * @return bool|string
     */
    public function getAburl()
    {
        return Yii::getAlias($this->aliasurl);
    }

    /**
     * @return bool|string
     */
    public function getAliasurl()
    {
        return "{$this->yii_alias_uploads_abpath}{$this->relation_path}/{$this->filename}";
    }

    /**
     * @return bool|string
     */
    public function getFunurl()
    {
        return str_replace(self::TEMPLATE_R_TYPE_FUN_FOR_ID, $this->id, self::TEMPLATE_R_TYPE_FUN);
    }

    /**
     * @return bool|string
     */
    public function getRTypeResult()
    {
        switch ($this->r_type){
            case self::R_TYPE_ALIAS:
                $r_path = $this->aliasurl;
                break;
            case self::R_TYPE_RELATIVELY:
                $r_path = $this->url;
                break;
            case self::R_TYPE_ABSOLUTELY:
                $r_path = $this->aburl;
                break;
            case self::R_TYPE_FUN:
            default:
                $r_path = $this->funurl;
                break;
        }
        return $r_path;
    }

    public static function encodeContent($content)
    {
        if ($content){
            $content = preg_replace_callback(self::REG_R_TYPE_ABSOLUTELY, function ($matches){
                $url = $matches[0];
                $domain = $matches[1];
                if ($domain){
                    $ips = ["120.92.150.43", "49.235.220.19", "121.37.179.86"];
                    $pp = false;
                    foreach ($ips as $k1 => $v1) {
                        if(strpos($domain, $v1) === false){}else{
                            $pp = true;
                            break;
                        }
                    }
                    if ($pp){}else{
                        return $url;
                    }
                }
                $filename = basename($url);
                $userFile = UserFile::findOne(['filename' => $filename]);
                return $userFile?$userFile->funurl:$url;
            }, $content);
        }
        return $content;
    }

    public static function decodeContent($content)
    {
        if ($content){
            $content = preg_replace_callback(self::REG_R_TYPE_FUN, function ($matches){
                $userFile = UserFile::findOne($matches[1]);
                return $userFile->aburl;
            }, $content);
            $content = preg_replace_callback("/\@static_aburl\/avatars\/{(\d+)}\./", function ($matches){
                $userFile = UserFile::findOne($matches[1]);
                return $userFile->aburl;
            }, $content);
        }
        return $content;
    }

    /**
     * @param $path
     * @return null|static
     */
    public static function findByPath($path)
    {
        $filename = basename($path);
        $userFile = static::findOne(['filename' => $filename]);
        return $userFile;
    }
}
