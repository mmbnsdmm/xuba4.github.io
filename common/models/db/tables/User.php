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
 * @property int $status 状态
 * @property int $created_at 注册时间
 * @property string $token 登录令牌
 * @property string $key 登录秘钥
 * @property string $auth_key session认证秘钥
 * @property float $amount 余额
 * @property float $frozen 冻结资金
 * @property int $updated_at 修改时间
 *
 * @property AdminAuthAssignment[] $adminAuthAssignments
 * @property AdminAuthItem[] $itemNames
 * @property LogEmailSendCode[] $logEmailSendCodes
 * @property LogUserLogin[] $logUserLogins
 * @property QueneYiiTask[] $queneYiiTasks
 * @property UserFile[] $userFiles
 * @property UserFile[] $userFiles0
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
            [['username', 'email', 'password', 'pwd_back', 'status', 'created_at', 'token', 'key', 'auth_key', 'amount', 'frozen', 'updated_at'], 'trim'],
            [['username', 'email', 'password', 'pwd_back', 'created_at', 'token', 'key', 'auth_key', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['amount', 'frozen'], 'number'],
            [['username', 'password', 'token', 'key', 'auth_key'], 'string', 'max' => 32],
            [['email', 'pwd_back'], 'string', 'max' => 150],
            [['status'], 'default', 'value' => 10],
            [['amount', 'frozen'], 'default', 'value' => 0.00],
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
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '注册时间'),
            'token' => Yii::t('app', '登录令牌'),
            'key' => Yii::t('app', '登录秘钥'),
            'auth_key' => Yii::t('app', 'session认证秘钥'),
            'amount' => Yii::t('app', '余额'),
            'frozen' => Yii::t('app', '冻结资金'),
            'updated_at' => Yii::t('app', '修改时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminAuthAssignments()
    {
        return $this->hasMany(AdminAuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AdminAuthItem::className(), ['name' => 'item_name'])->viaTable('{{%admin_auth_assignment}}', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogEmailSendCodes()
    {
        return $this->hasMany(LogEmailSendCode::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogUserLogins()
    {
        return $this->hasMany(LogUserLogin::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueneYiiTasks()
    {
        return $this->hasMany(QueneYiiTask::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFiles()
    {
        return $this->hasMany(UserFile::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFiles0()
    {
        return $this->hasMany(UserFile::className(), ['updated_by' => 'id']);
    }
}
