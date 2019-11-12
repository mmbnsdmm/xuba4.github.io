<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%admin_auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
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
class AdminAuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'rule_name', 'data', 'created_at', 'updated_at'], 'default'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AdminAuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
