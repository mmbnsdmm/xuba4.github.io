<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%search_index}}".
 *
 * @property int $id
 * @property int $type 类型
 * @property int $type_model_id 类型模型id
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 */
class SearchIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%search_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'type_model_id', 'title', 'created_at', 'updated_at'], 'trim'],
            [['type', 'type_model_id', 'title', 'created_at', 'updated_at'], 'required'],
            [['type', 'type_model_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 180],
            [['type', 'type_model_id'], 'unique', 'targetAttribute' => ['type', 'type_model_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', '类型'),
            'type_model_id' => Yii::t('app', '类型模型id'),
            'title' => Yii::t('app', 'Title'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
