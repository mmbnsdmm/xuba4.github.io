<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/11/8
 * Time: 9:06
 */

namespace admin\modules\ucenter\rules;


use common\models\db\UserFile;
use yii\rbac\Rule;

class UserFileRule extends Rule
{
    /**
     * @param string|integer $user 当前登录用户的uid
     * @param array $item 所属规则rule
     * @param array $params 当前请求携带的参数.
     * @return boolean true用户可访问 false用户不可访问
     */
    public function execute($user, $item, $params)
    {
        $user_file_id = isset($params['id'])?$params['id']:null;
        if ($user_file_id){
            $userFile = UserFile::findOne(['id' => $user_file_id]);
            if (!$userFile){
                return false;
            }else{
                if ($userFile->created_by != $user){
                    return false;
                }else{
                    return true;
                }
            }
        }
        return true;
    }
}