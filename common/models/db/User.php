<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-10-17
 * Time: 下午12:51
 */

namespace common\models\db;


use common\models\interfaces\SearchIndexInterface;
use common\Tools;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Color;
use wodrow\yii2wtools\tools\Model;
use wodrow\yii2wtools\tools\Security;
use wodrow\yii2wtools\tools\StrHelper;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\models\db
 *
 * @property-read string $nickname
 * @property QueneYiiTask[] $queneYiiTasks
 * @property Article[] $articles
 * @property Collection[] $collections
 * @property Fans[] $attentions
 * @property Fans[] $fanses
 * @property-read  Fans $isYourAttention
 * @property-read  Fans $isYourFans
 *
 * @property-read array $statusDesc
 * @property-read string $nickName
 * @property-read boolean $isAdmin
 * @property-read array $profile
 */
class User extends \common\models\db\tables\User implements IdentityInterface, SearchIndexInterface
{
    const SCENARIO_TEST = 'test';
    const STATUS_DELETE = -10;
    const STATUS_ACTIVE = 10;

    public function getStatusDesc()
    {
        return [
            self::STATUS_DELETE => "已删除",
            self::STATUS_ACTIVE => "正常",
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
            'timestamp' => [
                'class' => TimestampBehavior::class,
//                'createdAtAttribute' => false,
//                'updatedAtAttribute' => false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
                'updatedByAttribute' => false,
            ],
        ]);
        return $behaviors;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge($scenarios, [
            self::SCENARIO_TEST => [],
        ]);
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }
        $rules = ArrayHelper::merge($rules, [
//            [[], 'required', 'on' => self::SCENARIO_TEST],
        ]);
        return $rules;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels = ArrayHelper::merge($attributeLabels, []);
        return $attributeLabels;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token, 'status' => static::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
        $this->pwd_back = Security::thinkEncrypt($password);
    }

    public function validatepassword($password)
    {
        return $this->password == md5($password);
    }

    public function setToken()
    {
        Model::setUniqueStrForModelKey($this, 'token');
        $this->key = \Yii::$app->security->generateRandomString();
    }

    public function generateAvatar($isRandom = false, $isReset = false)
    {
        if ($this->avatar && !$isReset){}else{
            if ($isRandom){
                $this->avatar = "http://placeimg.com/400/400";
            }else{
                $hex = StrHelper::strToHex(md5($this->nickName));
                $colour = "#".substr($hex, 0, "6");
                $_colour = Color::rgb2contrast($colour);
                $c = str_replace("#", "", $colour);
                $_c = str_replace("#", "", $_colour);
                $this->avatar = "https://via.placeholder.com/400x400/{$c}/{$_c}?text={$this->nickName}";
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueneYiiTasks()
    {
        return $this->hasMany(QueneYiiTask::className(), ['created_by' => 'id']);
    }

    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['created_by' => 'id'])->andWhere(["=", 'status', Article::STATUS_ACTIVE]);
    }

    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['created_by' => 'id']);
    }

    public function getAttentions()
    {
        return $this->hasMany(Fans::className(), ['fans_id' => 'id']);
    }

    public function getFanses()
    {
        return $this->hasMany(Fans::className(), ['lender_id' => 'id']);
    }

    public function getIsYourAttention()
    {
        if (\Yii::$app->user->isGuest){
            return false;
        }
        $identify = \Yii::$app->user->identity;
        $fans = Fans::findOne(['lender_id' => $this->id, 'fans_id' => $identify->id]);
        return !!$fans;
    }

    public function getIsYourFans()
    {
        if (\Yii::$app->user->isGuest){
            return false;
        }
        $identify = \Yii::$app->user->identity;
        $fans = Fans::findOne(['lender_id' => $identify->id, 'fans_id' => $this->id]);
        return !!$fans;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickname?:$this->username;
    }

    public function getProfile()
    {
        $profile = \Yii::$app->apiTool->authReturn($this);
        $profile['isYourAttention'] = $this->isYourAttention;
        $profile['attentions'] = $this->attentions;
        $profile['attentionTotal'] = count($profile['attentions']);
        $profile['fanses'] = $this->fanses;
        $profile['fansTotal'] = count($profile['fanses']);
        $profile['collectionTotal'] = count($this->collections);
        $profile['articleTotal'] = count($this->articles);
        return $profile;
    }

    public function attention()
    {
        if (!$this->isYourAttention){
            if (\Yii::$app->user->isGuest){
                throw new Exception("必须登录后才能进行关注操作");
            }
            $identity = \Yii::$app->user->identity;
            if (count($identity->attentions) < 100){
                $fans = new Fans();
                $fans->lender_id = $this->id;
                $fans->fans_id = $identity->id;
                $fans->created_at = $fans->updated_at = YII_BT_TIME;
                $fans->status = Fans::STATUS_ACTIVE;
                if (!$fans->save()){
                    throw new Exception("关注失败:".Model::getModelError($fans));
                }
            }else{
                throw new Exception("你最多只能关注100人");
            }
        }
    }

    public function unAttention()
    {
        if ($this->isYourAttention){
            if (\Yii::$app->user->isGuest){
                throw new Exception("必须登录后才能进行取消关注操作");
            }
            $identify = \Yii::$app->user->identity;
            $fans = Fans::findOne(['lender_id' => $this->id, 'fans_id' => $identify->id]);
            if (!$fans->delete()){
                throw new Exception("取消关注失败:".Model::getModelError($fans));
            }
        }
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()){
            // TODO: Change the autogenerated stub
            return true;
        }else{
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            // TODO: Change the autogenerated stub
            return true;
        }else{
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()){
            // TODO: Change the autogenerated stub
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // TODO: Change the autogenerated stub
        $this->_deleteCaches();
    }

    public function afterValidate()
    {
        parent::afterValidate();
        // TODO: Change the autogenerated stub
    }

    public function afterRefresh()
    {
        parent::afterRefresh();
        // TODO: Change the autogenerated stub
        $this->_deleteCaches();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // TODO: Change the autogenerated stub
        $this->_deleteCaches();
    }

    protected function _deleteCaches()
    {
        if ($this->status == self::STATUS_ACTIVE){
            $this->setSearchIndex();
        }else{
            $this->delSearchIndex();
        }
    }

    public function test()
    {
        $test = self::instance();
        $test->setScenario(self::SCENARIO_TEST);
        $test->save();
        var_dump($test->toArray());
    }

    /**
     * @return bool
     */
    public function getIsAdmin()
    {
        $adminName = \Yii::$app->params['adminRoleAdminUserName'];
        if (AdminAuthAssignment::findOne(['user_id' => $this->id, 'item_name' => $adminName])){
            return true;
        }else{
            return false;
        }
    }

    public function setSearchIndex()
    {
        SearchIndex::setSearchIndex(SearchIndex::TYPE_USER, $this->id, $this->nickName);
    }

    public function delSearchIndex()
    {
        SearchIndex::delSearchIndex(SearchIndex::TYPE_USER, $this->id);
    }

    public function getSearchIndexData()
    {
        return [
            'type' => SearchIndex::TYPE_USER,
            'type_model_id' => $this->id,
            'title' => $this->nickName,
        ];
    }
}