<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $get_password
 * @property int $min_level
 * @property int $min_integral
 * @property int $is_boutique 是否精品
 * @property int $is_publish 是否发布
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'get_password', 'min_level', 'min_integral', 'is_boutique', 'is_publish'], 'trim'],
            [['title', 'content', 'status', 'created_at', 'updated_at', 'created_by', 'get_password', 'is_boutique', 'is_publish'], 'required'],
            [['content'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'min_level', 'min_integral', 'is_boutique', 'is_publish'], 'integer'],
            [['title', 'get_password'], 'string', 'max' => 180],
            [['updated_by', 'min_level', 'min_integral'], 'default', 'value' => null],
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
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'get_password' => Yii::t('app', 'Get Password'),
            'min_level' => Yii::t('app', 'Min Level'),
            'min_integral' => Yii::t('app', 'Min Integral'),
            'is_boutique' => Yii::t('app', '是否精品'),
            'is_publish' => Yii::t('app', '是否发布'),
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
