<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%log_email_send_code}}".
 *
 * @property int $id
 * @property int|null $created_by
 * @property int $created_at
 * @property int $type 类型
 * @property string $from 发件邮箱
 * @property string $to 目标邮箱
 * @property string $subject 主题
 * @property int $code 验证码
 * @property string $params 其他参数
 * @property int $status 状态
 *
 * @property User $createdBy
 */
class LogEmailSendCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_email_send_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by'], 'default'],
            [['created_by', 'created_at', 'type', 'code', 'status'], 'integer'],
            [['created_at', 'type', 'from', 'to', 'subject', 'code', 'params', 'status'], 'required'],
            [['params'], 'string'],
            [['from', 'to', 'subject'], 'string', 'max' => 150],
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
            'type' => Yii::t('app', '类型'),
            'from' => Yii::t('app', '发件邮箱'),
            'to' => Yii::t('app', '目标邮箱'),
            'subject' => Yii::t('app', '主题'),
            'code' => Yii::t('app', '验证码'),
            'params' => Yii::t('app', '其他参数'),
            'status' => Yii::t('app', '状态'),
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
