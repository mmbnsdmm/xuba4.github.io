<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/17
 * Time: 14:27
 */

namespace admin\modules\user\models\forms;


use common\components\Tools;
use common\models\db\AdminAuthItem;
use common\models\db\AdminAuthItemChild;
use wodrow\yii2wtools\validators\Loop;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\base\Model;

class RoleAlloc extends Model
{
    public $role_id;
    public $alloc_roles;
    public $alloc_permissions;
    public $alloc_routes;

    public function attributeLabels()
    {
        return [
            'role_id' => "角色",
            'alloc_roles' => "角色列表",
            'alloc_permissions' => "权限列表",
            'alloc_routes' => "路由列表",
        ];
    }

    public function rules()
    {
        return [
            [['role_id', 'alloc_roles', 'alloc_permissions', 'alloc_routes'], 'safe'],
        ];
    }

    /**
     * @param string $alloc_type
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function _doAlloc($alloc_type)
    {
        $olds = AdminAuthItem::getChildsMapByRole($this->role_id, $alloc_type);
        $deletes = array_values(array_diff($olds, $this->$alloc_type));
        $news = array_values(array_diff($this->$alloc_type, $olds));
        if ($deletes){
            foreach ($deletes as $k => $v) {
                $delete = AdminAuthItemChild::findOne(['parent' => $this->role_id, 'child' => $v]);
                if (!$delete->delete()){
                    throw new Exception(\wodrow\yii2wtools\tools\Model::getModelError($delete));
                }
            }
        }
        if ($news){
            $m = new AdminAuthItemChild();
            foreach ($news as $k => $v) {
                $_m = clone $m;
                $_m->child = $v;
                $_m->parent = $this->role_id;
                if (!$_m->save()){
                    throw new Exception(\wodrow\yii2wtools\tools\Model::getModelError($_m));
                }
//                    Tools::yiiLog($_m->toArray());
            }
        }
    }

    public function alloc()
    {
        $this->alloc_roles = $this->alloc_roles?:[];
        $this->alloc_permissions = $this->alloc_permissions?:[];
        $this->alloc_routes = $this->alloc_routes?:[];
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $this->_doAlloc(AdminAuthItem::GET_TYPE_ROLES);
            $this->_doAlloc(AdminAuthItem::GET_TYPE_PERMISSIONS);
            $this->_doAlloc(AdminAuthItem::GET_TYPE_ROUTES);
            $trans->commit();
            return true;
        }catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }
}