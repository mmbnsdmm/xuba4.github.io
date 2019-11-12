<?php

namespace admin\modules\ucenter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\UserFile;

/**
 * UserFileSearch represents the model behind the search form about `common\models\db\UserFile`.
 */
class UserFileSearch extends UserFile
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'filename', 'extension', 'mime_type', 'relation_path', 'yii_alias_uploads_path', 'yii_alias_uploads_root', 'size', 'created_by', 'updated_by', 'status'], 'safe'],
            [['created_at', 'updated_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()->where(['created_by' => Yii::$app->user->id]);
        $this->load($params);
        if ( ! is_null($this->created_at) && strpos($this->created_at, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', 'created_at', strtotime($s), strtotime($e)]);
        }
        if ( ! is_null($this->updated_at) && strpos($this->updated_at, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->updated_at);
            $query->andFilterWhere(['between', 'updated_at', strtotime($s), strtotime($e)]);
        }
        $this->fieldFilter($query, 'id', 'id', '=');
        $this->fieldFilter($query, 'filename', 'filename', 'like');
        $this->fieldFilter($query, 'extension', 'extension', 'like');
        $this->fieldFilter($query, 'mime_type', 'mime_type', 'like');
        $this->fieldFilter($query, 'relation_path', 'relation_path', 'like');
        $this->fieldFilter($query, 'yii_alias_uploads_path', 'yii_alias_uploads_path', 'like');
        $this->fieldFilter($query, 'yii_alias_uploads_root', 'yii_alias_uploads_root', 'like');
        $this->fieldFilter($query, 'size', 'size', '=');
        $this->fieldFilter($query, 'created_by', 'created_by', '=');
        $this->fieldFilter($query, 'updated_by', 'updated_by', '=');
        $this->fieldFilter($query, 'status', 'status', '=');;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
    * @param ActiveQuery $query
    * @param $attribute
    */
    protected function fieldFilter(&$query, $field, $attribute, $filter_type)
    {
        $this->$attribute = trim($this->$attribute);
        switch($this->$attribute){
            case \Yii::t('yii', '(not set)'):
                $query->andFilterWhere(['IS', $field, new Expression('NULL')]);
                break;
            case self::EMPTY_STRING:
                $query->andWhere([$field => '']);
                break;
            case self::NO_EMPTY:
                $query->andWhere(['IS NOT', $field, new Expression('NULL')])->andWhere(['<>', $field, '']);
                break;
            default:
                $query->andFilterWhere([$filter_type, $field, $this->$attribute]);
                break;
        }
    }
}
