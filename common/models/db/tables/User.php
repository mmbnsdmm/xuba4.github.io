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
 * @property string|null $signup_message 注册信息
 * @property int $updated_at 修改时间
 * @property string|null $nickname 昵称
 * @property string|null $avatar 头像
 * @property string|null $weixin_exceptional_code 微信打赏码图片
 * @property string|null $weixin_exceptional_url 微信打赏码链接
 * @property string|null $alipay_exceptional_code 支付宝打赏码图片
 * @property string|null $alipay_exceptional_url 支付宝打赏码链接
 * @property string|null $mobile 手机
 * @property string|null $qq QQ
 * @property string|null $weixin 微信号
 *
 * @property AdminAuthAssignment[] $adminAuthAssignments
 * @property Article[] $articles
 * @property Article[] $articles0
 * @property Collection[] $collections
 * @property Collection[] $collections0
 * @property Comments[] $comments
 * @property Comments[] $comments0
 * @property Fans[] $fans
 * @property Fans[] $fans0
 * @property AdminAuthItem[] $itemNames
 * @property LeaveMessagePraise[] $leaveMessagePraises
 * @property LeaveMessage[] $leaveMessages
 * @property LeaveMessage[] $leaveMessages0
 * @property LogEmailSendCode[] $logEmailSendCodes
 * @property LogUserLogin[] $logUserLogins
 * @property QueneYiiTask[] $queneYiiTasks
 * @property TagArticle[] $tagArticles
 * @property TagArticle[] $tagArticles0
 * @property Tag[] $tags
 * @property Tag[] $tags0
 * @property Tag[] $tags1
 * @property UserFile[] $userFiles
 * @property UserFile[] $userFiles0
 * @property UserTag[] $userTags
 * @property UserTag[] $userTags0
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
            [['username', 'email', 'password', 'pwd_back', 'status', 'created_at', 'token', 'key', 'auth_key', 'amount', 'frozen', 'signup_message', 'updated_at', 'nickname', 'avatar', 'weixin_exceptional_code', 'weixin_exceptional_url', 'alipay_exceptional_code', 'alipay_exceptional_url', 'mobile', 'qq', 'weixin'], 'trim'],
            [['username', 'email', 'password', 'pwd_back', 'created_at', 'token', 'key', 'auth_key', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['amount', 'frozen'], 'number'],
            [['signup_message'], 'string'],
            [['username', 'password', 'token', 'key', 'auth_key'], 'string', 'max' => 32],
            [['email', 'pwd_back'], 'string', 'max' => 150],
            [['nickname', 'avatar', 'weixin_exceptional_code', 'weixin_exceptional_url', 'alipay_exceptional_code', 'alipay_exceptional_url', 'weixin'], 'string', 'max' => 180],
            [['mobile', 'qq'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => 10],
            [['amount', 'frozen'], 'default', 'value' => 0.00],
            [['signup_message', 'nickname', 'avatar', 'weixin_exceptional_code', 'weixin_exceptional_url', 'alipay_exceptional_code', 'alipay_exceptional_url', 'mobile', 'qq', 'weixin'], 'default', 'value' => null],
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
            'signup_message' => Yii::t('app', '注册信息'),
            'updated_at' => Yii::t('app', '修改时间'),
            'nickname' => Yii::t('app', '昵称'),
            'avatar' => Yii::t('app', '头像'),
            'weixin_exceptional_code' => Yii::t('app', '微信打赏码图片'),
            'weixin_exceptional_url' => Yii::t('app', '微信打赏码链接'),
            'alipay_exceptional_code' => Yii::t('app', '支付宝打赏码图片'),
            'alipay_exceptional_url' => Yii::t('app', '支付宝打赏码链接'),
            'mobile' => Yii::t('app', '手机'),
            'qq' => Yii::t('app', 'QQ'),
            'weixin' => Yii::t('app', '微信号'),
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
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles0()
    {
        return $this->hasMany(Article::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections0()
    {
        return $this->hasMany(Collection::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments0()
    {
        return $this->hasMany(Comments::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasMany(Fans::className(), ['lender_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFans0()
    {
        return $this->hasMany(Fans::className(), ['fans_id' => 'id']);
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
    public function getLeaveMessagePraises()
    {
        return $this->hasMany(LeaveMessagePraise::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveMessages()
    {
        return $this->hasMany(LeaveMessage::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveMessages0()
    {
        return $this->hasMany(LeaveMessage::className(), ['updated_by' => 'id']);
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
    public function getTagArticles()
    {
        return $this->hasMany(TagArticle::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles0()
    {
        return $this->hasMany(TagArticle::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags0()
    {
        return $this->hasMany(Tag::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags1()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%user_tag}}', ['created_by' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTags()
    {
        return $this->hasMany(UserTag::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTags0()
    {
        return $this->hasMany(UserTag::className(), ['updated_by' => 'id']);
    }
}
