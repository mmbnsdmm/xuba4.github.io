<?php

namespace common\models\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;

/**
 * This is the model class for table "{{%log_email_send_code}}".
 *
 * @author
 *
 * @property User $createdBy
 */
class LogEmailSendCode extends \common\models\db\tables\LogEmailSendCode
{
    const TYPE_SIGNUP = 1;
    const TYPE_LOGIN = 2;
    const TYPE_RESET_PASSWORD = 3;

    const STATUS_SEND_FAILED = "-10";
    const STATUS_SEND_SUCCESS = "10";

    public static function getTypes()
    {
        return [
            self::TYPE_SIGNUP => "注册",
            self::TYPE_LOGIN => "登录",
            self::TYPE_RESET_PASSWORD => "重置密码",
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
                'updatedByAttribute' => false,
            ],
            /*'uuid' => [
                'class' => Uuid::class,
                'column' => false,
            ],*/
        ]);
    }

    public function rules()
    {
        $rules = parent::rules();
        /*foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }*/
        return ArrayHelper::merge($rules, []);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return ArrayHelper::merge($attributeLabels, []);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
