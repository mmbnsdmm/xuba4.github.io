<?php

namespace admin\modules\log\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\LogEmailSendCode;

/**
 * LogEmailSendCodeSearch represents the model behind the search form about `common\models\db\LogEmailSendCode`.
 */
class LogEmailSendCodeSearch extends LogEmailSendCode
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
            [['id', 'created_by', 'created_at', 'type', 'from', 'to', 'subject', 'code', 'params', 'status'], 'safe'],
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
        $this->_fieldFilter($query, 'id', 'id', '=');
        $this->_fieldFilter($query, 'created_by', 'created_by', '=');
        $this->_fieldFilter($query, 'created_at', 'created_at', '=');
        $this->_fieldFilter($query, 'type', 'type', '=');
        $this->_fieldFilter($query, 'from', 'from', 'like');
        $this->_fieldFilter($query, 'to', 'to', 'like');
        $this->_fieldFilter($query, 'subject', 'subject', 'like');
        $this->_fieldFilter($query, 'code', 'code', 'like');
        $this->_fieldFilter($query, 'params', 'params', 'like');
        $this->_fieldFilter($query, 'status', 'status', '=');;
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
    protected function _timeFilter(&$query, $attribute)
    {
        if ( ! is_null($this->$attribute) && strpos($this->$attribute, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->$attribute);
            $query->andFilterWhere(['between', $attribute, strtotime($s), strtotime($e)]);
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
