<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%log_yii_log}}".
 *
 * @property int $id
 * @property string $log_name
 * @property string $msg
 * @property int $created_at
 * @property string|null $from_ip
 * @property int|null $user_id
 */
class LogYiiLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_yii_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_name', 'msg', 'created_at', 'from_ip', 'user_id'], 'trim'],
            [['log_name', 'msg', 'created_at'], 'required'],
            [['msg'], 'string'],
            [['created_at', 'user_id'], 'integer'],
            [['log_name', 'from_ip'], 'string', 'max' => 180],
            [['from_ip', 'user_id'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'log_name' => Yii::t('app', 'Log Name'),
            'msg' => Yii::t('app', 'Msg'),
            'created_at' => Yii::t('app', 'Created At'),
            'from_ip' => Yii::t('app', 'From Ip'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}
