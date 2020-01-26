<?php

namespace admin\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\User;

/**
 * UserSearch represents the model behind the search form about `common\models\db\User`.
 */
class UserSearch extends User
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
            [['id', 'status'], 'integer'],
            [['username', 'email', 'password', 'pwd_back', 'token', 'key', 'auth_key'], 'safe'],
            [['created_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['amount', 'frozen', 'deposit'], 'number'],
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
        if ( ! is_null($this->created_at) && strpos($this->created_at, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', 'created_at', strtotime($s), strtotime($e)]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'amount' => $this->amount,
            'frozen' => $this->frozen,
            'deposit' => $this->deposit,
        ]);
        $this->filterLike($query, 'username');
        $this->filterLike($query, 'email');
        $this->filterLike($query, 'password');
        $this->filterLike($query, 'pwd_back');
        $this->filterLike($query, 'token');
        $this->filterLike($query, 'key');
        $this->filterLike($query, 'auth_key');;
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
    protected function filterLike(&$query, $attribute)
    {
        $this->$attribute = trim($this->$attribute);
        switch($this->$attribute){
            case \Yii::t('yii', '(not set)'):
                $query->andFilterWhere(['IS', $attribute, new Expression('NULL')]);
                break;
            case self::EMPTY_STRING:
                $query->andWhere([$attribute => '']);
                break;
            case self::NO_EMPTY:
                $query->andWhere(['IS NOT', $attribute, new Expression('NULL')])->andWhere(['<>', $attribute, '']);
                break;
            default:
                $query->andFilterWhere(['like', $attribute, $this->$attribute]);
                break;
        }
    }
}
