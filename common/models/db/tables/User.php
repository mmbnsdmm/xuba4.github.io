<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $email 邮箱
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
 *
 * @property QueneYiiTask[] $queneYiiTasks
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
            [['username'], 'default'],
            [['email', 'password', 'pwd_back', 'created_at', 'token', 'key', 'auth_key'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['amount', 'frozen', 'deposit'], 'number'],
            [['username', 'password', 'token', 'key', 'auth_key'], 'string', 'max' => 32],
            [['email', 'pwd_back'], 'string', 'max' => 150],
            [['token'], 'unique'],
            [['email'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '用户名'),
            'email' => Yii::t('app', '邮箱'),
            'password' => Yii::t('app', '密码'),
            'pwd_back' => Yii::t('app', '只用于获取密码明文'),
            'status' => Yii::t('app', '状态10:正常'),
            'created_at' => Yii::t('app', '注册时间'),
            'token' => Yii::t('app', '登录令牌'),
            'key' => Yii::t('app', '登录秘钥'),
            'auth_key' => Yii::t('app', 'session认证秘钥'),
            'amount' => Yii::t('app', '余额'),
            'frozen' => Yii::t('app', '冻结资金'),
            'deposit' => Yii::t('app', '保证金'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueneYiiTasks()
    {
        return $this->hasMany(QueneYiiTask::className(), ['created_by' => 'id']);
    }
}
