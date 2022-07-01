<?php

namespace common\models\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;

/**
 * This is the model class for table "{{%admin_auth_assignment}}".
 *
 * @author
 *
 * @property AdminAuthItem $itemName
 * @property User $user
 */
class AdminAuthAssignment extends \common\models\db\tables\AdminAuthAssignment
{
    public static function getRolesByUser($user_id)
    {
        $assignRoles = Yii::$app->cache->getOrSet("AdminAuthAssignment2getRolesByUser{$user_id}", function () use ($user_id) {
            return self::find()->where(['user_id' => $user_id])->all();
        });
        return $assignRoles;
    }

    public static function getRoleNamesByUser($user_id)
    {
        $assignRoles = self::getRolesByUser($user_id);
        $names = [];
        foreach ($assignRoles as $k => $v) {
            if (!in_array($v->item_name, $names))$names[] = $v->item_name;
        }
        return $names;
    }

    public static function getRoleNamesMapByUser($user_id)
    {
        $assignRoles = self::getRolesByUser($user_id);
        $names = [];
        foreach ($assignRoles as $k => $v) {
            if (!in_array($v->item_name, $names))$names[$v->item_name] = $v->item_name;
        }
        return $names;
    }

    protected function _deleteCaches()
    {
        Yii::$app->cache->delete("AdminAuthAssignment2getRolesByUser{$this->user_id}");
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->_deleteCaches();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->_deleteCaches();
    }

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
        return ArrayHelper::merge($rules, []);
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
    public function getItemName()
    {
        return $this->hasOne(AdminAuthItem::className(), ['name' => 'item_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
