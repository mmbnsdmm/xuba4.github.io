<?php

namespace admin\modules\article\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\Article as ArticleModel;

/**
 * Article represents the model behind the search form about `common\models\db\Article`.
 */
class Article extends ArticleModel
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => ['title','get_password'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'status', 'created_by', 'updated_by', 'get_password', 'min_level', 'min_integral', 'is_boutique', 'create_type'], 'safe'],
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
        $query = self::find();
        $this->load($params);
        $this->_rangeFilter($query, 'created_at', true);
        $this->_rangeFilter($query, 'updated_at', true);
        $this->_fieldFilter($query, 'id', 'id', '=');
        $this->_fieldFilter($query, 'title', 'title', 'like');
        $this->_fieldFilter($query, 'content', 'content', 'like');
        $this->_fieldFilter($query, 'status', 'status', '=');
        $this->_fieldFilter($query, 'created_by', 'created_by', '=');
        $this->_fieldFilter($query, 'updated_by', 'updated_by', '=');
        $this->_fieldFilter($query, 'get_password', 'get_password', 'like');
        $this->_fieldFilter($query, 'min_level', 'min_level', '=');
        $this->_fieldFilter($query, 'min_integral', 'min_integral', '=');
        $this->_fieldFilter($query, 'is_boutique', 'is_boutique', '=');
        $this->_fieldFilter($query, 'create_type', 'create_type', '=');;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC, ]],
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
    protected function _rangeFilter(&$query, $attribute, $isDate = false)
    {
        if ( ! is_null($this->$attribute) && strpos($this->$attribute, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->$attribute);
            if ($isDate){
                $s = strtotime($s);
                $e = strtotime($e);
            }
            if ($s)$query->andFilterWhere(['>=', $attribute, $s]);
            if ($e)$query->andFilterWhere(['<=', $attribute, $e]);
        }
    }

    /**
     * @param ActiveQuery $query
     * @param $attribute
     */
    protected function _fieldFilter(&$query, $field, $attribute, $filter_type)
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
