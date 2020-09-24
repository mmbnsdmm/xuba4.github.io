<?php

namespace admin\modules\user\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\User as UserModel;

/**
 * User represents the model behind the search form about `common\models\db\User`.
 */
class User extends UserModel
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => ['nickname'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username', 'email', 'password', 'pwd_back', 'status', 'token', 'key', 'auth_key', 'nickname', 'avatar', 'signup_message'], 'safe'],
            [['created_at', 'amount', 'frozen', 'updated_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $this->_rangeFilter($query, 'amount');
        $this->_rangeFilter($query, 'frozen');
        $this->_rangeFilter($query, 'updated_at', true);
        $this->_fieldFilter($query, 'id', 'id', '=');
        $this->_fieldFilter($query, 'username', 'username', 'like');
        $this->_fieldFilter($query, 'email', 'email', 'like');
        $this->_fieldFilter($query, 'password', 'password', 'like');
        $this->_fieldFilter($query, 'pwd_back', 'pwd_back', 'like');
        $this->_fieldFilter($query, 'status', 'status', '=');
        $this->_fieldFilter($query, 'token', 'token', 'like');
        $this->_fieldFilter($query, 'key', 'key', 'like');
        $this->_fieldFilter($query, 'auth_key', 'auth_key', 'like');
        $this->_fieldFilter($query, 'nickname', 'nickname', 'like');
        $this->_fieldFilter($query, 'avatar', 'avatar', 'like');
        $this->_fieldFilter($query, 'signup_message', 'signup_message', 'like');
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
