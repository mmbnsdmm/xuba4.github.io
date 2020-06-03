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
            [['id', 'created_by', 'type', 'status'], 'integer'],
            [['created_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['from', 'to', 'subject', 'code', 'params'], 'safe'],
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
            'created_by' => $this->created_by,
            'type' => $this->type,
            'status' => $this->status,
        ]);
        $this->filterLike($query, 'from');
        $this->filterLike($query, 'to');
        $this->filterLike($query, 'subject');
        $this->filterLike($query, 'code');
        $this->filterLike($query, 'params');;
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
