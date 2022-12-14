<?php

namespace common\models\db;

use common\components\Tools;
use common\models\interfaces\SearchIndexInterface;
use wodrow\yii2wtools\tools\Model;
use Yii;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article}}".
 *
 * @author
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property-read array $statusDesc
 * @property-read array $isBoutiqueDesc
 * @property-read array $createTypeDesc
 * @property-read  array $info
 * @property-read  boolean $canYouOpt
 * @property TagArticle[] $aTags
 * @property array $aTagIds
 * @property Collection[] $collections
 * @property-read  int $collectionTotal
 * @property Collection $yourCollection
 * @property-read  boolean $isYouCollection
 */
class Article extends \common\models\db\tables\Article implements SearchIndexInterface
{
    const SCENARIO_TEST = 'test';
    const STATUS_DELETE = -10;
    const STATUS_ACTIVE = 10;
    const STATUS_SECRET = 5;
    const IS_BOUTIQUE_Y = 1;
    const IS_BOUTIQUE_N = 0;
    const CREATE_TYPE_ORIGINAL = 1;
    const CREATE_TYPE_REPRINTED = 2;
    const CREATE_TYPE_TRANSLATION = 3;

    public function getStatusDesc()
    {
        return [
            self::STATUS_DELETE => "已删除",
            self::STATUS_ACTIVE => "正常",
            self::STATUS_SECRET => "私密",
        ];
    }

    public function getIsBoutiqueDesc()
    {
        return [
            self::IS_BOUTIQUE_Y => "是",
            self::IS_BOUTIQUE_N => "否",
        ];
    }

    public function getCreateTypeDesc()
    {
        return [
            self::CREATE_TYPE_ORIGINAL => "原创",
            self::CREATE_TYPE_REPRINTED => "转载",
            self::CREATE_TYPE_TRANSLATION => "翻译",
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
//                'createdByAttribute' => false,
//                'updatedByAttribute' => false,
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
            $this->content = UserFile::encodeContent($this->content);
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
        $this->content = Yii::$app->cache->getOrSet('Article2afterFind2content'.$this->id, function (){
            return UserFile::decodeContent($this->content);
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
        Yii::$app->cache->delete('Article2afterFind2content'.$this->id);
        Yii::$app->cache->delete('Article2getATags'.$this->id);
        Yii::$app->cache->delete('Article2getCollections'.$this->id);
        Yii::$app->cache->delete('User2getArticles'.$this->created_by);
        if ($this->status == self::STATUS_ACTIVE){
            $this->setSearchIndex();
        }else{
            $this->delSearchIndex();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return Yii::$app->cache->getOrSet('Article2getCreatedBy'.$this->created_by, function () {
            return $this->hasOne(User::className(), ['id' => 'created_by']);
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function test()
    {
        $test = self::instance();
        $test->setScenario(self::SCENARIO_TEST);
        $test->save();
        var_dump($test->toArray());
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        $arr = $this->toArray();
        $createdBy = Yii::$app->apiTool->authReturn($this->createdBy);
        $arr['createdBy'] = $createdBy;
        $arr['createdBy']['profile'] = $this->createdBy->profile;
        $arr['canYouOpt'] = $this->canYouOpt;
        $arr['collections'] = $this->collections;
        $arr['collectionTotal'] = $this->collectionTotal;
        $arr['isYouCollection'] = $this->isYouCollection;
        $arr['aTags'] = $this->aTags;
        $arr['aTagIds'] = $this->aTagIds;
        return $arr;
    }

    /**
     * @return bool
     */
    public function getCanYouOpt()
    {
        if ($this->isNewRecord){
            return true;
        }
        $user = \Yii::$app->user->identity;
        $author = $this->createdBy;
        if ($author->id == $user->id){
            return true;
        }else{
            if ($user->isAdmin){
                return true;
            }else{
                if (in_array($user->id, Yii::$app->params['apiAdminUserIds'])){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getATags()
    {
        return Yii::$app->cache->getOrSet('Article2getATags'.$this->id, function (){
            return $this->hasMany(TagArticle::className(), ['article_id' => 'id']);
        });
    }

    /**
     * @return array
     */
    public function getATagIds()
    {
        $ids = [];
        foreach ($this->aTags as $k => $v) {
            $ids[] = $v->tag_id;
        }
        return $ids;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return Yii::$app->cache->getOrSet('Article2getCollections'.$this->id, function (){
            return $this->hasMany(Collection::className(), ['article_id' => 'id']);
        });
    }

    public function getCollectionTotal()
    {
//        return Collection::find()->where(['article_id' => $this->id])->count();
        return count($this->collections);
    }

    /**
     * @return bool
     */
    public function getIsYouCollection()
    {
        return $this->yourCollection?true:false;
    }

    public function getYourCollection()
    {
//        return Collection::findOne(['article_id' => $this->id, 'created_by' => Yii::$app->user->id]);
        foreach ($this->collections as $k => $v) {
            if ($v->created_by == Yii::$app->user->id)return true;
        }
        return false;
    }

    public function collection()
    {
        if (!$this->isYouCollection){
            if (\Yii::$app->user->isGuest){
                throw new Exception("必须登录后才能进行收藏操作");
            }
            $identity = \Yii::$app->user->identity;
            if (count($identity->collections) < 1000){
                $collection = new Collection();
                $collection->created_by = $collection->updated_by = $identity->id;
                $collection->article_id = $this->id;
                $collection->created_at = $collection->updated_at = YII_BT_TIME;
                $collection->status = Collection::STATUS_ACTIVE;
                if (!$collection->save()){
                    throw new Exception("收藏失败:".Model::getModelError($collection));
                }
            }else{
                throw new Exception("你最多只能收藏1000文章");
            }
        }
    }

    public function unCollection()
    {
        if ($this->isYouCollection){
            $collection = $this->yourCollection;
            $collection->delete();
        }
    }

    public function setSearchIndex()
    {
        SearchIndex::setSearchIndex(SearchIndex::TYPE_ARTICLE, $this->id, $this->title);
    }

    public function delSearchIndex()
    {
        SearchIndex::delSearchIndex(SearchIndex::TYPE_ARTICLE, $this->id);
    }

    public function getSearchIndexData()
    {
        return [
            'type' => SearchIndex::TYPE_ARTICLE,
            'type_model_id' => $this->id,
            'title' => $this->title,
        ];
    }
}
