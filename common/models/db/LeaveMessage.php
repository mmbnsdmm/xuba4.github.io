<?php

namespace common\models\db;

use Yii;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%leave_message}}".
 *
 * @author
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property-read array $statusDesc
 * @property-read  array $info
 * @property-read  boolean $canYouOpt
 * @property  LeaveMessagePraise $praise
 * @property  LeaveMessagePraise $trample
 * @property-read  boolean $isYouPraise
 * @property-read  boolean $isYouTrample
 * @property-read  int $praiseTotal
 * @property-read  int $trampleTotal
 */
class LeaveMessage extends \common\models\db\tables\LeaveMessage
{
    const SCENARIO_TEST = 'test';
    const STATUS_DELETE = -10;
    const STATUS_ACTIVE = 10;

    public function getStatusDesc()
    {
        return [
            self::STATUS_DELETE => "已删除",
            self::STATUS_ACTIVE => "正常",
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
            'timestamp' => [
                'class' => TimestampBehavior::class,
//                'createdAtAttribute' => false,
//                'updatedAtAttribute' => false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
//                'createdByAttribute' => false,
//                'updatedByAttribute' => false,
            ],
        ]);
        return $behaviors;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge($scenarios, [
            self::SCENARIO_TEST => [],
        ]);
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }
        $rules = ArrayHelper::merge($rules, [
//            [[], 'required', 'on' => self::SCENARIO_TEST],
        ]);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function test()
    {
        $test = self::instance();
        $test->setScenario(self::SCENARIO_TEST);
        $test->save();
        var_dump($test->toArray());
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        $arr = $this->toArray();
        $createdBy = Yii::$app->apiTool->authReturn($this->createdBy);
        $arr['createdBy'] = $createdBy;
        $arr['createdBy']['profile'] = $this->createdBy->profile;
        $arr['canYouOpt'] = $this->canYouOpt;
        $arr['isYouPraise'] = $this->isYouPraise;
        $arr['isYouTrample'] = $this->isYouTrample;
        $arr['praiseTotal'] = $this->praiseTotal;
        $arr['trampleTotal'] = $this->trampleTotal;
        return $arr;
    }

    /**
     * @return bool
     */
    public function getCanYouOpt()
    {
        if ($this->isNewRecord){
            return true;
        }
        $user = \Yii::$app->user->identity;
        $author = $this->createdBy;
        if ($author->id == $user->id){
            return true;
        }else{
            if ($user->isAdmin){
                return true;
            }else{
                if (in_array($user->id, Yii::$app->params['apiAdminUserIds'])){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function getPraiseTotal()
    {
        return LeaveMessagePraise::find()->where(['leave_message_id' => $this->id, 'praise_type' => LeaveMessagePraise::PRAISE_TYPE_PRAISE])->count();
    }

    public function getTrampleTotal()
    {
        return LeaveMessagePraise::find()->where(['leave_message_id' => $this->id, 'praise_type' => LeaveMessagePraise::PRAISE_TYPE_TRAMPLE])->count();
    }

    public function getIsYouPraise()
    {
        if ($this->praise){
            return true;
        }else{
            return false;
        }
    }

    public function getPraise()
    {
        $user = \Yii::$app->user->identity;
        $praise = LeaveMessagePraise::findOne(['created_by' => $user->id, 'leave_message_id' => $this->id, 'praise_type' => LeaveMessagePraise::PRAISE_TYPE_PRAISE]);
        return $praise;
    }

    public function praise()
    {
        $user = \Yii::$app->user->identity;
        if (!$this->isYouPraise){
            $praise = new LeaveMessagePraise();
            $praise->created_by = $user->id;
            $praise->leave_message_id = $this->id;
            $praise->praise_type = LeaveMessagePraise::PRAISE_TYPE_PRAISE;
            $praise->created_at = YII_BT_TIME;
            $praise->status = LeaveMessagePraise::STATUS_ACTIVE;
            $praise->save();
        }
    }

    public function unPraise()
    {
        if ($this->isYouPraise){
            $praise = $this->praise;
            $praise->delete();
        }
    }

    public function getIsYouTrample()
    {
        if ($this->trample){
            return true;
        }else{
            return false;
        }
    }

    public function getTrample()
    {
        $user = \Yii::$app->user->identity;
        $trample = LeaveMessagePraise::findOne(['created_by' => $user->id, 'leave_message_id' => $this->id, 'praise_type' => LeaveMessagePraise::PRAISE_TYPE_TRAMPLE]);
        return $trample;
    }

    public function trample()
    {
        $user = \Yii::$app->user->identity;
        if (!$this->trample){
            $trample = new LeaveMessagePraise();
            $trample->created_by = $user->id;
            $trample->leave_message_id = $this->id;
            $trample->praise_type = LeaveMessagePraise::PRAISE_TYPE_TRAMPLE;
            $trample->created_at = YII_BT_TIME;
            $trample->status = LeaveMessagePraise::STATUS_ACTIVE;
            $trample->save();
        }
    }

    public function unTrample()
    {
        if ($this->isYouTrample){
            $trample = $this->trample;
            $trample->delete();
        }
    }
}
