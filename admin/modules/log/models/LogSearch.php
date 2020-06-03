<?php

namespace admin\modules\log\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\Log;

/**
 * LogSearch represents the model behind the search form about `common\models\db\Log`.
 */
class LogSearch extends Log
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
            [['id', 'level'], 'integer'],
            [['category', 'prefix', 'message'], 'safe'],
            [['log_time'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        if ( ! is_null($this->log_time) && strpos($this->log_time, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->log_time);
            $query->andFilterWhere(['between', 'log_time', strtotime($s), strtotime($e)]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'level' => $this->level,
        ]);
        $this->filterLike($query, 'category');
        $this->filterLike($query, 'prefix');
        $this->filterLike($query, 'message');;
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
                $query->andWhere([$attribute=>'']);
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
