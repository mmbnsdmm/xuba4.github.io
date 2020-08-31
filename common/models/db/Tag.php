<?php

namespace common\models\db;

use common\models\interfaces\SearchIndexInterface;
use Yii;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @author
 *
 * @property Article[] $articles
 * @property User $createdBy
 * @property TagArticle[] $tagArticles
 * @property User $updatedBy
 * @property-read array $statusDesc
 *
 * @property array $info
 * @property UserTag $youJoin
 * @property bool $isYouJoin
 */
class Tag extends \common\models\db\tables\Tag implements SearchIndexInterface
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])->viaTable('{{%tag_article}}', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles()
    {
        return $this->hasMany(TagArticle::className(), ['tag_id' => 'id']);
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

    public function getInfo()
    {
        $arr = $this->toArray();
        $createdBy = Yii::$app->apiTool->authReturn($this->createdBy);
        $arr['createdBy'] = $createdBy;
        $arr['createdBy']['profile'] = $this->createdBy->profile;
        $arr['isYouJoin'] = $this->isYouJoin;
        return $arr;
    }

    public function getIsYouJoin()
    {
        return $this->youJoin?true:false;
    }

    public function getYouJoin()
    {
        return UserTag::findOne(['created_by' => Yii::$app->user->id, 'tag_id' => $this->id]);
    }

    public function setSearchIndex()
    {
        SearchIndex::setSearchIndex(SearchIndex::TYPE_TAG, $this->id, $this->name);
    }

    public function delSearchIndex()
    {
        SearchIndex::delSearchIndex(SearchIndex::TYPE_TAG, $this->id);
    }

    public function getSearchIndexData()
    {
        return [
            'type' => SearchIndex::TYPE_TAG,
            'type_model_id' => $this->id,
            'title' => $this->name,
        ];
    }
}
