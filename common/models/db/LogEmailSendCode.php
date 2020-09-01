<?php

namespace common\models\db;

use Yii;
use wodrow\yii2wtools\tools\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%log_email_send_code}}".
 *
 * @author
 *
 * @property User $createdBy
 * @property-read array $statusDesc
 * @property-read array $typeDesc
 */
class LogEmailSendCode extends \common\models\db\tables\LogEmailSendCode
{
    const SCENARIO_TEST = 'test';
    const TYPE_SIGNUP = 1;
    const TYPE_LOGIN = 2;
    const TYPE_RESET_PASSWORD = 3;

    const STATUS_SEND_FAILED = "-1";
    const STATUS_DELETE = -10;
    const STATUS_SEND_SUCCESS = "10";

    public function getTypeDesc()
    {
        return [
            self::TYPE_SIGNUP => "注册",
            self::TYPE_LOGIN => "登录",
            self::TYPE_RESET_PASSWORD => "重置密码",
        ];
    }

    public function getStatusDesc()
    {
        return [
            self::STATUS_DELETE => "已删除",
            self::STATUS_SEND_SUCCESS => "发送正常",
            self::STATUS_SEND_SUCCESS => "发送失败",
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
                'updatedByAttribute' => false,
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
        /*foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }*/
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
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function test()
    {
        $test = self::instance();
        $test->setScenario(self::SCENARIO_TEST);
        $test->save();
        var_dump($test->toArray());
    }
}