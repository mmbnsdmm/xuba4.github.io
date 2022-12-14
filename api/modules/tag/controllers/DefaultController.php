<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/24
 * Time: 9:56
 */

namespace api\modules\tag\controllers;


use common\models\db\Tag;
use common\models\db\User;
use common\models\db\UserTag;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class DefaultController extends Controller
{
    /**
     * 创建圈子
     * @desc post
     * @param string $name
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object tag 圈子信息
     */
    public function actionAdd($name)
    {
        if (Tag::findOne(['name' => $name])){
            throw new ApiException(202008240958, "圈子已存在");
        }
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $tag = new Tag();
            $tag->name = $name;
            $tag->status = Tag::STATUS_ACTIVE;
            if (!$tag->save()){
                throw new ApiException(202008241006, "圈子创建失败:".Model::getModelError($tag));
            }
            $userTag = new UserTag();
            $userTag->tag_id = $tag->id;
            $userTag->status = UserTag::STATUS_ACTIVE;
            if (!$userTag->save()) {
                throw new ApiException(202008241011, "用户加入圈子失败:".Model::getModelError($userTag));
            }
            $trans->commit();
        }catch (ApiException $e){
            $trans->rollBack();
            throw $e;
        }
        return $this->success("创建成功", ['tag' => $tag->info]);
    }

    /**
     * 获取圈子列表
     * @desc post
     * @param int $start_id 初始id，防新增数据后下拉数据异常
     * @param int $page 页码
     * @param int $page_size 每页数据数
     * @param string $json_filter_params 查询过滤参数(数组型json),详见https://www.yiichina.com/tutorial/1405,示例:["and/or",["and/or",["=","字段1","值"],["!=","字段2","值"]],["in","字段3",["值1","值2","值3"]],[">=","字段4","值"]]
     * @param int $joinUser 是否$joinUser加入的圈子
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return array list 列表
     * @return int page 页码
     * @return int page_size 每页数据数
     * @return int total 总数据数
     * @throws
     */
    public function actionList($start_id = null, $page = 1, $page_size = 10, $json_filter_params = null, $joinUser = null)
    {
        $appendData = ['list' => [], 'page' => $page, 'page_size' => $page_size, 'total' => 0];
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = Tag::find();
        $query->with([
            'createdBy', 'createdBy.attentions', 'createdBy.fanses', 'createdBy.articles'
        ]);
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        if ($joinUser){
            $user = User::findOne($joinUser);
            if (!$user){
                throw new ApiException(202008241028, "没有找到用户:{$joinUser}");
            }
            $jtids = [];
            foreach ($user->tags as $k => $v) {
                $jtids[] = $v->id;
            }
            $query->andWhere(['in', 'id', $jtids]);
        }
        if ($start_id){
            $query->andWhere(['<=', 'id', $start_id]);
        }
        $query->orderBy(['id' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $tags = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($tags as $k => $v) {
            $info = $v->info;
            $list[] = $info;
        }
        $appendData['list'] = $list;
        $appendData['total'] = $total;
        return $this->success("获取成功", $appendData);
    }

    /**
     * 退出圈子
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object tag
     */
    public function actionQuit($id)
    {
        $userTag = UserTag::findOne(['created_by' => \Yii::$app->user->id, 'tag_id' => $id]);
        if ($userTag){
            $userTag->delete();
        }
        return $this->success("退出成功", ['tag' => $userTag->tag->info]);
    }

    /**
     * 加入圈子
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object tag
     */
    public function actionJoin($id)
    {
        $userTag = UserTag::findOne(['created_by' => \Yii::$app->user->id, 'tag_id' => $id]);
        if (!$userTag){
            $userTag = new UserTag();
            $userTag->tag_id = $id;
            $userTag->status = UserTag::STATUS_ACTIVE;
            if (!$userTag->save()){
                throw new ApiException(202008241126, "加入失败:".Model::getModelError($userTag));
            }
        }
        return $this->success("加入成功", ['tag' => $userTag->tag->info]);
    }

    /**
     * 获取圈子信息
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object tag 圈子信息
     */
    public function actionView($id)
    {
        if (!$id) {
            return $this->error("id不能为空");
        }
        $tag = $this->_getModel($id);
        return $this->success("获取成功", ['tag' => $tag->info]);
    }

    /**
     * 删除圈子
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error("id不能为空");
        }
        $tag = $this->_getModel($id);
        if (!in_array(\Yii::$app->user->id, \Yii::$app->params['apiAdminUserIds'])){
            throw new ApiException(202008270953, "你没有修改此圈子权限");
        }
        $tag->status = Tag::STATUS_DELETE;
        if (!$tag->save()){
            throw new ApiException(202008270954, "删除失败:".Model::getModelError($tag));
        }
        $tag->delete();
        $this->data['status'] = 200;
        $this->data['msg'] = "删除成功";
        return $this->data;
    }

    /**
     * @param $id
     * @return Tag|null
     * @throws
     */
    private function _getModel($id)
    {
        $tag = Tag::findOne(['id' => $id, 'status' => Tag::STATUS_ACTIVE]);
        if (!$tag) {
            throw new ApiException(202008270948, "没有找到圈子或圈子已删除");
        }
        return $tag;
    }
}