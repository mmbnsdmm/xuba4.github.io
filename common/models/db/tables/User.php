<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $password 密码
 * @property string $pwd_back 只用于获取密码明文
 * @property int $status 状态10:正常
 * @property int $created_at 注册时间
 * @property string $token 登录令牌
 * @property string $key 登录秘钥
 * @property string $auth_key session认证秘钥
 * @property string $amount 余额
 * @property string $frozen 冻结资金
 * @property string $deposit 保证金
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'pwd_back', 'created_at', 'token', 'key', 'auth_key'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['amount', 'frozen', 'deposit'], 'number'],
            [['password', 'token', 'key', 'auth_key'], 'string', 'max' => 32],
            [['pwd_back'], 'string', 'max' => 150],
            [['token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'password' => Yii::t('app', 'Password'),
            'pwd_back' => Yii::t('app', 'Pwd Back'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'token' => Yii::t('app', 'Token'),
            'key' => Yii::t('app', 'Key'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'amount' => Yii::t('app', 'Amount'),
            'frozen' => Yii::t('app', 'Frozen'),
            'deposit' => Yii::t('app', 'Deposit'),
        ];
    }
}
