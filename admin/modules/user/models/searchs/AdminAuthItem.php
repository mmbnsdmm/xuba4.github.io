<?php

namespace admin\modules\user\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\AdminAuthItem as AdminAuthItemModel;

/**
 * AdminAuthItem represents the model behind the search form about `common\models\db\AdminAuthItem`.
 */
class AdminAuthItem extends AdminAuthItemModel
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => ['description','data'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description', 'rule_name', 'data'], 'safe'],
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
        $query = self::find()->where(['type' => self::TYPE_ROLE]);
        $this->load($params);
        $this->_rangeFilter($query, 'created_at', true);
        $this->_rangeFilter($query, 'updated_at', true);
        $this->_fieldFilter($query, 'name', 'name', 'like');
        $this->_fieldFilter($query, 'type', 'type', '=');
        $this->_fieldFilter($query, 'description', 'description', 'like');
        $this->_fieldFilter($query, 'rule_name', 'rule_name', 'like');
        $this->_fieldFilter($query, 'data', 'data', 'like');;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_DESC, ]],
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
