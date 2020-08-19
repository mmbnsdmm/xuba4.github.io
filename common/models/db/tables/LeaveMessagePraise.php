<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%leave_message_praise}}".
 *
 * @property int $id
 * @property int $leave_message_id
 * @property int $created_by
 * @property int $created_at
 * @property int $status
 * @property int $praise_type 点赞类型
 *
 * @property User $createdBy
 * @property LeaveMessage $leaveMessage
 */
class LeaveMessagePraise extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%leave_message_praise}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['leave_message_id', 'created_by', 'created_at', 'status', 'praise_type'], 'trim'],
            [['leave_message_id', 'created_by', 'created_at', 'status', 'praise_type'], 'required'],
            [['leave_message_id', 'created_by', 'created_at', 'status', 'praise_type'], 'integer'],
            [['leave_message_id', 'created_by', 'praise_type'], 'unique', 'targetAttribute' => ['leave_message_id', 'created_by', 'praise_type']],
            [['leave_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveMessage::className(), 'targetAttribute' => ['leave_message_id' => 'id']],
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
            'leave_message_id' => Yii::t('app', 'Leave Message ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'status' => Yii::t('app', 'Status'),
            'praise_type' => Yii::t('app', '点赞类型'),
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
    public function getLeaveMessage()
    {
        return $this->hasOne(LeaveMessage::className(), ['id' => 'leave_message_id']);
    }
}
