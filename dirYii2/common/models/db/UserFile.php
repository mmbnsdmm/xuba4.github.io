<?php

namespace common\models\db;

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
 */
class UserFile extends \common\models\db\tables\UserFile
{
    const STATUS_UPLOADED = 0;
    const STATUS_UPLOAD_FAILED = -1;

    /**
     * @param $filename
     * @param $extension
     * @param $content
     * @param $tmp_file
     * @return mixed
     * @throws
     */
    public static function upload($filename, $extension, $content = null, $tmp_file = null)
    {
        if (!$filename){
            $filename = \Yii::$app->security->generateRandomString().".{$extension}";
        }
        $user = \Yii::$app->user->identity;
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $_path = "/user_files/{$user->id}/{$y}/{$m}/{$d}";
        $user_file = new UserFile();
        $user_file->filename = $filename;
        $user_file->extension = $extension;
        $user_file->relation_path = $_path;
        $user_file->yii_alias_uploads_path = "@uploads_url";
        $user_file->yii_alias_uploads_root = "@uploads_root";
        $user_file->created_by = $user_file->updated_by = $user->id;
        $user_file->created_at = $user_file->updated_at = YII_BT_TIME;
        $uf_root = \Yii::getAlias($user_file->yii_alias_uploads_root).$_path."/{$filename}";
        $uf_path = \Yii::getAlias($user_file->yii_alias_uploads_path).$_path."/{$filename}";
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
        return \Yii::$app->request->hostInfo.$uf_path;
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
}
