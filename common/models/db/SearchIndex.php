<?php

namespace common\models\db;

use common\models\interfaces\SearchIndexInterface;
use wodrow\yii2wtools\tools\Model;
use Yii;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%search_index}}".
 *
 * @author
 * @property-read array $typeDesc
 */
class SearchIndex extends \common\models\db\tables\SearchIndex
{
    const TYPE_USER = 1;
    const TYPE_ARTICLE = 2;
    const TYPE_TAG = 3;

    public function getTypeDesc()
    {
        return [
            self::TYPE_USER => "用户",
            self::TYPE_ARTICLE => "文章",
            self::TYPE_TAG => "圈子",
        ];
    }

    public static function setSearchIndex($type, $type_model_id, $title)
    {
        $model = static::findOne(['type' => $type, 'type_model_id' => $type_model_id]);
        if (!$model) {
            $model = new static;
            $model->type = $type;
            $model->type_model_id = $type_model_id;
            $model->created_at = YII_BT_TIME;
        }
        $model->updated_at = YII_BT_TIME;
        $model->title = $title;
        if (!$model->save()){
            throw new Exception("添加搜索索引失败:".Model::getModelError($model));
        }
    }

    public static function delSearchIndex($type, $type_model_id)
    {
        $model = static::findOne(['type' => $type, 'type_model_id' => $type_model_id]);
        if ($model){
            if ($model->delete()){}else{
                throw new Exception("删除搜索索引失败:".Model::getModelError($model));
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function initSearchIndex()
    {
        $trans = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->db->createCommand()->truncateTable('search_index')->execute();
            $model = new SearchIndex();
            foreach (User::find()->all() as $k => $v) {
                if ($v->status == User::STATUS_ACTIVE){
                    $m = clone $model;
                    $m->type = SearchIndex::TYPE_USER;
                    $m->type_model_id = $v->id;
                    $m->created_at = YII_BT_TIME;
                    $m->updated_at = YII_BT_TIME;
                    $m->title = $v->username;
                    if (!$m->save()){
                        throw new Exception("添加搜索索引失败:".Model::getModelError($m));
                    }else{
                        var_dump("user:".$m->title);
                    }
                }
                $v->save();
            }
            foreach (Article::find()->all() as $k => $v) {
                if ($v->status == Article::STATUS_ACTIVE){
                    $m = clone $model;
                    $m->type = SearchIndex::TYPE_ARTICLE;
                    $m->type_model_id = $v->id;
                    $m->created_at = YII_BT_TIME;
                    $m->updated_at = YII_BT_TIME;
                    $m->title = $v->title;
                    if (!$m->save()){
                        throw new Exception("添加搜索索引失败:".Model::getModelError($m));
                    }else{
                        var_dump("article:".$m->title);
                    }
                }
                $v->save();
            }
            foreach (Tag::find()->all() as $k => $v) {
                if ($v->status == Tag::STATUS_ACTIVE){
                    $m = clone $model;
                    $m->type = SearchIndex::TYPE_TAG;
                    $m->type_model_id = $v->id;
                    $m->created_at = YII_BT_TIME;
                    $m->updated_at = YII_BT_TIME;
                    $m->title = $v->name;
                    if (!$m->save()){
                        throw new Exception("添加搜索索引失败:".Model::getModelError($m));
                    }else{
                        var_dump("tag:".$m->title);
                    }
                }
                $v->save();
            }
            $trans->commit();
        }catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }

    /**
     * @param $type
     * @param $type_model_id
     * @return SearchIndexInterface|null
     * @throws Exception
     */
    public function getTypeModel($type, $type_model_id)
    {
        switch ($type){
            case self::TYPE_USER:
                $m = User::findOne(['id' => $type_model_id, 'status' => User::STATUS_ACTIVE]);
                break;
            case self::TYPE_ARTICLE:
                $m = Article::findOne(['id' => $type_model_id, 'status' => Article::STATUS_ACTIVE]);
                break;
            default :
                throw new Exception("没有找到搜索索引类型");
                break;
        }
        return $m;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, []);
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
    {}
}
