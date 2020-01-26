<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%admin_menu}}".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $route
 * @property int $order
 * @property resource $data
 *
 * @property AdminMenu $parent0
 * @property AdminMenu[] $adminMenus
 * @property AdminAuthItem $route0
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'route', 'order', 'data'], 'default'],
            [['parent', 'order'], 'integer'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 64],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AdminMenu::className(), 'targetAttribute' => ['parent' => 'id']],
            [['route'], 'exist', 'skipOnError' => true, 'targetClass' => AdminAuthItem::className(), 'targetAttribute' => ['route' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'parent' => Yii::t('app', 'Parent'),
            'route' => Yii::t('app', 'Route'),
            'order' => Yii::t('app', 'Order'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(AdminMenu::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminMenus()
    {
        return $this->hasMany(AdminMenu::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoute0()
    {
        return $this->hasOne(AdminAuthItem::className(), ['name' => 'route']);
    }
}
