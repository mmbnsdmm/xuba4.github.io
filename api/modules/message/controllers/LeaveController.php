<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/18
 * Time: 17:06
 */

namespace api\modules\message\controllers;


use common\components\Tools;
use common\models\db\LeaveMessage;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class LeaveController extends Controller
{
    /**
     * 发布或修改留言
     * @desc post
     * @param int $id
     * @param string $message
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object info 留言信息
     */
    public function actionPublish($id = null, $message)
    {
        if ($id !== null){
            $m = $this->_getModel($id);
        }else{
            $m = new LeaveMessage();
            $m->status = LeaveMessage::STATUS_ACTIVE;
        }
        if (!$m->canYouOpt){
            throw new ApiException(202008181711, "你没有修改此留言权限");
        }
        $m->message = $message;
        if (!$m->save()){
            throw new ApiException(202008181710, "留言失败:".Model::getModelError($m));
        }
        return $this->success("留言成功", ['info' => $m->info]);
    }

    /**
     * 获取留言列表
     * @desc post
     * @param int $start_id 初始id，防新增数据后下拉数据异常
     * @param int $page 页码
     * @param int $page_size 每页数据数
     * @param string $json_filter_params 查询过滤参数(数组型json),详见https://www.yiichina.com/tutorial/1405,示例:["and/or",["and/or",["=","字段1","值"],["!=","字段2","值"]],["in","字段3",["值1","值2","值3"]],[">=","字段4","值"]]
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return array list 列表
     * @return int page 页码
     * @return int page_size 每页数据数
     * @return int total 总数据数
     * @throws
     */
    public function actionList($start_id = null, $page = 1, $page_size = 10, $json_filter_params = null)
    {
        $appendData = ['list' => [], 'page' => $page, 'page_size' => $page_size, 'total' => 0];
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = LeaveMessage::find()
            ->with([
                'createdBy', 'createdBy.attentions', 'createdBy.fanses', 'createdBy.articles'
            ])
            ->where(['status' => LeaveMessage::STATUS_ACTIVE]);
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        if ($start_id){
            $query->andWhere(['<=', 'id', $start_id]);
        }
        $query->orderBy(['id' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $leaveMessages = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($leaveMessages as $k => $v) {
            $info = $v->info;
            unset($info['content']);
            $list[] = $info;
        }
        $appendData['list'] = $list;
        $appendData['total'] = $total;
        return $this->success("获取成功", $appendData);
    }

    /**
     * 获取留言信息
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object info 留言信息
     */
    public function actionView($id)
    {
        $leaveMessage = $this->_getModel($id);
        return $this->success("获取成功", ['info' => $leaveMessage->info]);
    }

    /**
     * 删除留言
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionDelete($id)
    {
        $leaveMessage = $this->_getModel($id);
        if (!$leaveMessage->canYouOpt){
            throw new ApiException(202008191129, "你没有修改此留言权限");
        }
        $leaveMessage->status = LeaveMessage::STATUS_DELETE;
        if (!$leaveMessage->save()){
            throw new ApiException(202008191130, "删除失败:".Model::getModelError($leaveMessage));
        }
        return $this->success("删除成功");
    }

    /**
     * 点赞
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws ApiException
     */
    public function actionPraise($id)
    {
        $leaveMessage = $this->_getModel($id);
        $leaveMessage->praise();
        return $this->success("点赞成功", ['info' => $leaveMessage->info]);
    }

    /**
     * 取消点赞
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws ApiException
     */
    public function actionUnPraise($id)
    {
        $leaveMessage = $this->_getModel($id);
        $leaveMessage->unPraise();
        return $this->success("取消点赞成功", ['info' => $leaveMessage->info]);
    }

    /**
     * 踩一踩
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws ApiException
     */
    public function actionTrample($id)
    {
        $leaveMessage = $this->_getModel($id);
        $leaveMessage->trample();
        return $this->success("踩一踩成功", ['info' => $leaveMessage->info]);
    }

    /**
     * 取消踩一踩
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws ApiException
     */
    public function actionUnTrample($id)
    {
        $leaveMessage = $this->_getModel($id);
        $leaveMessage->unTrample();
        return $this->success("取消踩一踩成功", ['info' => $leaveMessage->info]);
    }

    /**
     * @param $id
     * @return LeaveMessage|null
     * @throws
     */
    private function _getModel($id)
    {
        $model = LeaveMessage::findOne(['id' => $id, 'status' => LeaveMessage::STATUS_ACTIVE]);
        if (!$model){
            throw new ApiException(202008181709, "没有找到留言或留言已删除");
        }
        return $model;
    }
}