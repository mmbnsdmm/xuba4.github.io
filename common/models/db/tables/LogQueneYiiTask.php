<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%log_quene_yii_task}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $result_code
 * @property string $result_msg
 * @property string $done_quene_yii_task_ids 本次完成的所有任务
 * @property string $failed_quene_yii_task_ids 本次失败的所以任务
 */
class LogQueneYiiTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_quene_yii_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'result_code', 'result_msg'], 'required'],
            [['created_at', 'result_code'], 'integer'],
            [['result_msg', 'done_quene_yii_task_ids', 'failed_quene_yii_task_ids'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'result_code' => Yii::t('app', 'Result Code'),
            'result_msg' => Yii::t('app', 'Result Msg'),
            'done_quene_yii_task_ids' => Yii::t('app', '本次完成的所有任务'),
            'failed_quene_yii_task_ids' => Yii::t('app', '本次失败的所以任务'),
        ];
    }
}
