<?php

namespace admin\modules\app\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\db\AppVersion;

/**
 * Version represents the model behind the search form about `common\models\db\AppVersion`.
 */
class Version extends AppVersion
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => ['v_id','version','is_force_update','pkg_url','wgt_url','desc','other_download_urls'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'v_id', 'version', 'is_force_update', 'pkg_url', 'wgt_url', 'desc', 'status', 'other_download_urls'], 'safe'],
            [['created_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $this->_fieldFilter($query, 'id', 'id', '=');
        $this->_fieldFilter($query, 'type', 'type', '=');
        $this->_fieldFilter($query, 'v_id', 'v_id', '=');
        $this->_fieldFilter($query, 'version', 'version', 'like');
        $this->_fieldFilter($query, 'is_force_update', 'is_force_update', '=');
        $this->_fieldFilter($query, 'pkg_url', 'pkg_url', 'like');
        $this->_fieldFilter($query, 'wgt_url', 'wgt_url', 'like');
        $this->_fieldFilter($query, 'desc', 'desc', 'like');
        $this->_fieldFilter($query, 'status', 'status', '=');
        $this->_fieldFilter($query, 'other_download_urls', 'other_download_urls', 'like');;
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
