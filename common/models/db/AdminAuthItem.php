<?php

namespace common\models\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use wodrow\yii2wtools\behaviors\Uuid;

/**
 * This is the model class for table "{{%admin_auth_item}}".
 *
 * @author
 *
 * @property AdminAuthAssignment[] $adminAuthAssignments
 * @property User[] $users
 * @property AdminAuthRule $ruleName
 * @property AdminAuthItemChild[] $adminAuthItemChildren
 * @property AdminAuthItemChild[] $adminAuthItemChildren0
 * @property AdminAuthItem[] $children
 * @property AdminAuthItem[] $parents
 * @property AdminMenu[] $adminMenus
 */
class AdminAuthItem extends \common\models\db\tables\AdminAuthItem
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
    public function getAdminAuthAssignments()
    {
        return $this->hasMany(AdminAuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%admin_auth_assignment}}', ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AdminAuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminAuthItemChildren()
    {
        return $this->hasMany(AdminAuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminAuthItemChildren0()
    {
        return $this->hasMany(AdminAuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AdminAuthItem::className(), ['name' => 'child'])->viaTable('{{%admin_auth_item_child}}', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AdminAuthItem::className(), ['name' => 'parent'])->viaTable('{{%admin_auth_item_child}}', ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminMenus()
    {
        return $this->hasMany(AdminMenu::className(), ['route' => 'name']);
    }
}
