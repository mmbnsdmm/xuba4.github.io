<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%user_file}}".
 *
 * @property int $id
 * @property string $filename
 * @property string $extension 扩展名
 * @property string|null $mime_type 文件类型
 * @property string $relation_path 相对路径
 * @property string $yii_alias_uploads_path 上传地址前缀
 * @property string $yii_alias_uploads_abpath 上传绝对地址前缀
 * @property string $yii_alias_uploads_root 上传路径
 * @property int|null $size 文件大小
 * @property int $r_type 上传时的返回地址方式
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property string|null $original_url 原来的url地址
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class UserFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'extension', 'mime_type', 'relation_path', 'yii_alias_uploads_path', 'yii_alias_uploads_abpath', 'yii_alias_uploads_root', 'size', 'r_type', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status', 'original_url'], 'trim'],
            [['filename', 'extension', 'relation_path', 'yii_alias_uploads_path', 'yii_alias_uploads_abpath', 'yii_alias_uploads_root', 'r_type', 'created_at', 'updated_at', 'status'], 'required'],
            [['size', 'r_type', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status'], 'integer'],
            [['filename'], 'string', 'max' => 260],
            [['extension'], 'string', 'max' => 40],
            [['mime_type'], 'string', 'max' => 50],
            [['relation_path', 'yii_alias_uploads_path', 'yii_alias_uploads_abpath', 'yii_alias_uploads_root'], 'string', 'max' => 200],
            [['original_url'], 'string', 'max' => 180],
            [['mime_type', 'size', 'created_by', 'updated_by', 'original_url'], 'default', 'value' => null],
            [['filename'], 'unique'],
            [['original_url'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'filename' => Yii::t('app', 'Filename'),
            'extension' => Yii::t('app', '扩展名'),
            'mime_type' => Yii::t('app', '文件类型'),
            'relation_path' => Yii::t('app', '相对路径'),
            'yii_alias_uploads_path' => Yii::t('app', '上传地址前缀'),
            'yii_alias_uploads_abpath' => Yii::t('app', '上传绝对地址前缀'),
            'yii_alias_uploads_root' => Yii::t('app', '上传路径'),
            'size' => Yii::t('app', '文件大小'),
            'r_type' => Yii::t('app', '上传时的返回地址方式'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'original_url' => Yii::t('app', '原来的url地址'),
        ];
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
