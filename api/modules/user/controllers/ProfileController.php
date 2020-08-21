<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/14
 * Time: 13:30
 */

namespace api\modules\user\controllers;


use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;

class ProfileController extends Controller
{
    /**
     * 获取用户信息
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否获取成功
     * @return string msg
     * @return object user 用户信息
     * @throws
     */
    public function actionInfo($id)
    {
        $u = $this->_getModel($id);
        return $this->success("获取成功", ['user' => $u->profile]);
    }

    /**
     * 关注
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     * @throws
     */
    public function actionAttention($id)
    {
        $u = $this->_getModel($id);
        $u->attention();
        return $this->success("关注成功", ['user' => $u->profile]);
    }

    /**
     * 取消踩一踩
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object user 用户信息
     * @throws
     */
    public function actionUnAttention($id)
    {
        $u = $this->_getModel($id);
        $u->unAttention();
        return $this->success("取消关注成功", ['user' => $u->profile]);
    }

    /**
     * 获取用户列表
     * @desc post
     * @param int $start_id 初始id，防新增数据后下拉数据异常
     * @param int $page 页码
     * @param int $page_size 每页数据数
     * @param string $json_filter_params 查询过滤参数(数组型json),详见https://www.yiichina.com/tutorial/1405,示例:["and/or",["and/or",["=","字段1","值"],["!=","字段2","值"]],["in","字段3",["值1","值2","值3"]],[">=","字段4","值"]]
     * @param int $attentionsForUserId 关注的用户
     * @param int $fansesForUserId 粉丝
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return array list 列表
     * @return int page 页码
     * @return int page_size 每页数据数
     * @return int total 总数据数
     * @throws
     */
    public function actionList($start_id = null, $page = 1, $page_size = 10, $json_filter_params = null, $attentionsForUserId= null, $fansesForUserId = null)
    {
        $appendData = ['list' => [], 'page' => $page, 'page_size' => $page_size, 'total' => 0];
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = User::find()->where(['status' => User::STATUS_ACTIVE]);
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        if ($attentionsForUserId !== null){
            $user = User::findOne($attentionsForUserId);
            if (!$user){
                throw new ApiException(202008201202, "没有找到用户:{$attentionsForUserId}");
            }
            $attUids = [];
            foreach ($user->attentions as $k => $v) {
                $attUids[] = $v->lender_id;
            }
            $query->andWhere(['in', 'id', $attUids]);
        }
        if ($fansesForUserId !== null){
            $user = User::findOne($fansesForUserId);
            if (!$user){
                throw new ApiException(202008201202, "没有找到用户:{$fansesForUserId}");
            }
            $fansUids = [];
            foreach ($user->fanses as $k => $v) {
                $fansUids[] = $v->fans_id;
            }
            $query->andWhere(['in', 'id', $fansUids]);
        }
        if ($start_id){
            $query->andWhere(['<=', 'id', $start_id]);
        }
        $query->orderBy(['id' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $users = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($users as $k => $v) {
            $list[] = $v->profile;
        }
        $appendData['list'] = $list;
        $appendData['total'] = $total;
        return $this->success("获取成功", $appendData);
    }

    /**
     * @param $id
     * @return User|null
     * @throws
     */
    private function _getModel($id)
    {
        $model = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
        if (!$model){
            throw new ApiException(202008201024, "没有找到用户或用户已删除");
        }
        return $model;
    }
}