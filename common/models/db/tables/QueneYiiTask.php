<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%quene_yii_task}}".
 *
 * @property int $id
 * @property int $created_by
 * @property int $created_at
 * @property int $run_at 执行时间
 * @property int $status 状态0:默认进入队列
 * @property string $route job路由
 * @property string $params 参数
 * @property string $error_msg 运行失败信息
 *
 * @property User $createdBy
 */
class QueneYiiTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quene_yii_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'created_at', 'run_at', 'status'], 'integer'],
            [['created_at', 'route'], 'required'],
            [['params', 'error_msg'], 'string'],
            [['route'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'run_at' => Yii::t('app', '执行时间'),
            'status' => Yii::t('app', '状态0:默认进入队列'),
            'route' => Yii::t('app', 'job路由'),
            'params' => Yii::t('app', '参数'),
            'error_msg' => Yii::t('app', '运行失败信息'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
