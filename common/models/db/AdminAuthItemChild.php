<?php

namespace common\models\db;

use wodrow\yii2wtools\validators\Loop;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;

/**
 * This is the model class for table "{{%admin_auth_item_child}}".
 *
 * @author
 *
 * @property AdminAuthItemChild $p
 * @property AdminAuthItemChild $c
 * @property AdminAuthItem $parentAdminAuthItem
 * @property AdminAuthItem $childAdminAuthItem
 */
class AdminAuthItemChild extends \common\models\db\tables\AdminAuthItemChild
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
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
            /*'uuid' => [
                'class' => Uuid::class,
                'column' => false,
            ],*/
        ]);
    }

    public function rules()
    {
        $rules = parent::rules();
        /*foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }*/
        return ArrayHelper::merge($rules, [
            ['parent', Loop::class, 'parentForAttribute' => "child", 'parentModelLinkname' => "p"],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return ArrayHelper::merge($attributeLabels, []);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(self::className(), ['child' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(self::className(), ['parent' => 'child']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentAdminAuthItem()
    {
        return $this->hasOne(AdminAuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildAdminAuthItem()
    {
        return $this->hasOne(AdminAuthItem::className(), ['name' => 'child']);
    }
}
