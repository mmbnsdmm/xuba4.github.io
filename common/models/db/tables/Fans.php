<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%fans}}".
 *
 * @property int $id
 * @property int $lender_id
 * @property int $fans_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 *
 * @property User $fans
 * @property User $lender
 */
class Fans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fans}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lender_id', 'fans_id', 'created_at', 'updated_at', 'status'], 'trim'],
            [['lender_id', 'fans_id', 'created_at', 'updated_at', 'status'], 'required'],
            [['lender_id', 'fans_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['lender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lender_id' => 'id']],
            [['fans_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['fans_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lender_id' => Yii::t('app', 'Lender ID'),
            'fans_id' => Yii::t('app', 'Fans ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(User::className(), ['id' => 'fans_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLender()
    {
        return $this->hasOne(User::className(), ['id' => 'lender_id']);
    }
}
