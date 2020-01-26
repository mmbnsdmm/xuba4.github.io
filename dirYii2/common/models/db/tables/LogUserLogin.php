<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%log_user_login}}".
 *
 * @property int $id
 * @property int $created_by
 * @property int $created_at
 * @property string $from_ip 登录ip
 * @property string $from_app 从哪个应用登录
 * @property int $is_login 是否登录成功
 * @property string $params 请求参数
 * @property string $param_username
 * @property string $param_email
 *
 * @property User $createdBy
 */
class LogUserLogin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_user_login}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'from_ip', 'from_app', 'params', 'param_username', 'param_email'], 'default'],
            [['created_by', 'created_at', 'is_login'], 'integer'],
            [['created_at', 'is_login'], 'required'],
            [['params'], 'string'],
            [['from_ip'], 'string', 'max' => 120],
            [['from_app'], 'string', 'max' => 20],
            [['param_username', 'param_email'], 'string', 'max' => 150],
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
            'from_ip' => Yii::t('app', '登录ip'),
            'from_app' => Yii::t('app', '从哪个应用登录'),
            'is_login' => Yii::t('app', '是否登录成功'),
            'params' => Yii::t('app', '请求参数'),
            'param_username' => Yii::t('app', 'Param Username'),
            'param_email' => Yii::t('app', 'Param Email'),
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
