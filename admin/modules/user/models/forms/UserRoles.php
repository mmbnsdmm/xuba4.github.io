<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/16
 * Time: 15:28
 */

namespace admin\modules\user\models\forms;


use common\components\Tools;
use common\models\db\AdminAuthAssignment;
use yii\base\Exception;
use yii\base\Model;

class UserRoles extends Model
{
    public $user_id;
    public $role_names;

    public function attributeLabels()
    {
        return [
            'user_id' => "用户",
            'role_names' => "角色列表",
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'role_names'], 'safe'],
        ];
    }

    public function alloc()
    {
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $olds = AdminAuthAssignment::getRoleNamesByUser($this->user_id);
            $deletes = array_values(array_diff($olds, $this->role_names));
            $news = array_values(array_diff($this->role_names, $olds));
            if ($deletes){
                foreach ($deletes as $k => $v) {
                    $deleteRole = AdminAuthAssignment::findOne(['user_id' => $this->user_id, 'item_name' => $v]);
                    if (!$deleteRole->delete()){
                        throw new Exception(\wodrow\yii2wtools\tools\Model::getModelError($deleteRole));
                    }
                }
            }
            if ($news){
                $m = new AdminAuthAssignment();
                foreach ($news as $k => $v) {
                    $_m = clone $m;
                    $_m->item_name = $v;
                    $_m->user_id = $this->user_id;
                    $_m->created_at = YII_BT_TIME;
                    if (!$_m->save()){
                        throw new Exception(\wodrow\yii2wtools\tools\Model::getModelError($_m));
                    }
//                    Tools::yiiLog($_m->toArray());
                }
            }
            $trans->commit();
            return true;
        }catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }
}