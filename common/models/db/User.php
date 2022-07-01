<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-10-17
 * Time: 下午12:51
 */

namespace common\models\db;


use common\models\db\tables\TagArticle;
use common\models\interfaces\SearchIndexInterface;
use common\Tools;
use GuzzleHttp\Client;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Color;
use wodrow\yii2wtools\tools\FileHelper;
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
 * @property Tag[] $tags
 * @property TagArticle[] $tagArticles
 * @property TagArticle[] $tagArticles0
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
                /*$this->avatar = "http://placeimg.com/100/100";
                $path = \Yii::getAlias("@uploads_root");
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $_path = "/user_files/{$this->id}/{$y}/{$m}/{$d}/{$this->id}.jpg";
                if (!is_dir(dirname($path.$_path))){
                    FileHelper::createDirectory(dirname($path.$_path));
                }
                $client = new Client(['base_uri' => $this->avatar]);
                $client->request("get", "", ['sink' => $path.$_path]);
                $this->avatar = \Yii::$app->apiTool->baseUri.\Yii::getAlias("@uploads_url").$_path;*/
                $this->avatar = \Yii::getAlias(\Yii::$app->apiTool->randomAvatarUrl);
            }else{
                $hex = StrHelper::strToHex(md5($this->nickName));
                $colour = "#".substr($hex, 0, "6");
                $_colour = Color::rgb2contrast($colour);
                $c = str_replace("#", "", $colour);
                $_c = str_replace("#", "", $_colour);
                $this->avatar = "https://via.placeholder.com/100x100/{$c}/{$_c}?text={$this->nickName}";
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

    public function getTags()
    {
        return \Yii::$app->cache->getOrSet('User-getTags-'.$this->id, function () {
            return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%user_tag}}', ['created_by' => 'id']);
        });
    }

    public function getArticles()
    {
        return \Yii::$app->cache->getOrSet('User-getArticles-'.$this->id, function () {
            return $this->hasMany(Article::className(), ['created_by' => 'id'])->andWhere(["=", 'status', Article::STATUS_ACTIVE]);
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles()
    {
        return $this->hasMany(TagArticle::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles0()
    {
        return $this->hasMany(TagArticle::className(), ['updated_by' => 'id']);
    }

    public function getCollections()
    {
        return \Yii::$app->cache->getOrSet('User-getCollections-'.$this->id, function () {
            return $this->hasMany(Collection::className(), ['created_by' => 'id']);
        });
    }

    public function getAttentions()
    {
        return \Yii::$app->cache->getOrSet('User-getAttentions-'.$this->id, function () {
            return $this->hasMany(Fans::className(), ['fans_id' => 'id']);
        });
    }

    public function getFanses()
    {
        return \Yii::$app->cache->getOrSet('User-getFanses-'.$this->id, function () {
            return $this->hasMany(Fans::className(), ['lender_id' => 'id']);
        });
    }

    public function getIsYourAttention()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        foreach ($this->fanses as $k => $v) {
            if ($v->fans_id == \Yii::$app->user->id)return true;
        }
//        $identify = \Yii::$app->user->identity;
//        $fans = Fans::findOne(['lender_id' => $this->id, 'fans_id' => $identify->id]);
//        return !!$fans;
        return false;
    }

    public function getIsYourFans()
    {
        if (\Yii::$app->user->isGuest){
            return false;
        }
        foreach ($this->attentions as $k => $v) {
            if ($v->lender_id == \Yii::$app->user->identity)return true;
        }
        return false;
//        $identify = \Yii::$app->user->identity;
//        $fans = Fans::findOne(['lender_id' => $identify->id, 'fans_id' => $this->id]);
//        return !!$fans;
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
        $profile['isAdmin'] = $this->isAdmin;
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
            $this->avatar = UserFile::encodeContent($this->avatar);
            $this->weixin_exceptional_url = UserFile::encodeContent($this->weixin_exceptional_url);
            $this->weixin_exceptional_code = UserFile::encodeContent($this->weixin_exceptional_code);
            $this->alipay_exceptional_url = UserFile::encodeContent($this->alipay_exceptional_url);
            $this->alipay_exceptional_code = UserFile::encodeContent($this->alipay_exceptional_code);
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
        $this->avatar = \Yii::$app->cache->getOrSet('User-afterFind-avatar-'.$this->id, function (){
            return UserFile::decodeContent($this->avatar);
        });
        $this->weixin_exceptional_url = \Yii::$app->cache->getOrSet('User-afterFind-weixin_exceptional_url-'.$this->id, function (){
            return UserFile::decodeContent($this->weixin_exceptional_url);
        });
        $this->weixin_exceptional_code = \Yii::$app->cache->getOrSet('User-afterFind-weixin_exceptional_code-'.$this->id, function (){
            return UserFile::decodeContent($this->weixin_exceptional_code);
        });
        $this->alipay_exceptional_url = \Yii::$app->cache->getOrSet('User-afterFind-alipay_exceptional_url-'.$this->id, function (){
            return UserFile::decodeContent($this->alipay_exceptional_url);
        });
        $this->alipay_exceptional_code = \Yii::$app->cache->getOrSet('User-afterFind-alipay_exceptional_code-'.$this->id, function (){
            return UserFile::decodeContent($this->alipay_exceptional_code);
        });
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
        \Yii::$app->cache->delete('User-afterFind-avatar-'.$this->id);
        \Yii::$app->cache->delete('User-afterFind-weixin_exceptional_url-'.$this->id);
        \Yii::$app->cache->delete('User-afterFind-weixin_exceptional_code-'.$this->id);
        \Yii::$app->cache->delete('User-afterFind-alipay_exceptional_url-'.$this->id);
        \Yii::$app->cache->delete('User-afterFind-alipay_exceptional_code-'.$this->id);
        \Yii::$app->cache->delete('Article-getCreatedBy-'.$this->id);
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
        $adminAuthAssignment = \Yii::$app->cache->getOrSet('User-getIsAdmin', function () {
            $adminName = \Yii::$app->params['adminRoleAdminUserName'];
            return AdminAuthAssignment::findOne(['user_id' => $this->id, 'item_name' => $adminName]);
        }, 3600 * 24);
        if ($adminAuthAssignment){
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