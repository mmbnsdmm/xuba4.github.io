<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/11
 * Time: 10:53
 */

namespace api\modules\article\controllers;


use common\models\db\Article;
use common\models\db\Collection;
use common\models\db\User;
use wodrow\yii\rest\ApiException;
use wodrow\yii\rest\Controller;
use wodrow\yii2wtools\tools\Model;

class DefaultController extends Controller
{
    /**
     * 发布文章
     * @desc post
     * @param null|int $id
     * @param string $title
     * @param null|string $get_password
     * @param string $content
     * @param int $status
     * @param int $create_type
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object article 文章信息
     */
    public function actionPublish($id = null, $title, $get_password = null, $content, $status, $is_boutique = Article::IS_BOUTIQUE_N, $create_type = Article::CREATE_TYPE_ORIGINAL)
    {
        if ($id === null){
            $article = new Article();
        }else{
            $article = Article::findOne($id);
            if (!$article){
                throw new ApiException(202008111057, "没有找到文章");
            }
        }
        if (!$article->canYouOpt){
            throw new ApiException(202008131536, "你没有修改此文章权限");
        }
        $article->title = $title;
        $article->get_password = $get_password;
        $article->content = $content;
        $article->status = $status;
        $article->is_boutique = $is_boutique;
        $article->create_type = $create_type;
        if (!$article->save()){
            throw new ApiException(202008111100, "文章保存失败:".Model::getModelError($article));
        }
        $this->data['status'] = 200;
        $this->data['msg'] = "发布成功";
        $this->data['article'] = $article->info;
        return $this->data;
    }

    /**
     * 获取文章信息
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return object article 文章信息
     */
    public function actionView($id)
    {
        $article = $this->_getModel($id);
        $this->data['status'] = 200;
        $this->data['msg'] = "获取成功";
        $this->data['article'] = $article->info;
        return $this->data;
    }

    /**
     * 获取文章列表
     * @desc post
     * @param int $start_id 初始id，防新增数据后下拉数据异常
     * @param int $page 页码
     * @param int $page_size 每页数据数
     * @param string $json_filter_params 查询过滤参数(数组型json),详见https://www.yiichina.com/tutorial/1405,示例:["and/or",["and/or",["=","字段1","值"],["!=","字段2","值"]],["in","字段3",["值1","值2","值3"]],[">=","字段4","值"]]
     * @param int $collectionUser 是否$collectionUser的收藏文章
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @return array list 列表
     * @return int page 页码
     * @return int page_size 每页数据数
     * @return int total 总数据数
     * @throws
     */
    public function actionList($start_id = null, $page = 1, $page_size = 10, $json_filter_params = null, $collectionUser = null)
    {
        $appendData = ['list' => [], 'page' => $page, 'page_size' => $page_size, 'total' => 0];
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = Article::find()->where(['status' => Article::STATUS_ACTIVE]);
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        if ($collectionUser){
            $user = User::findOne($collectionUser);
            $caids = [];
            foreach ($user->collections as $k => $v) {
                $caids[] = $v->article_id;
            }
            $query->andWhere(['in', 'id', $caids]);
        }
        if ($start_id){
            $query->andWhere(['<=', 'id', $start_id]);
        }
        $query->orderBy(['id' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $articles = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($articles as $k => $v) {
            $info =$v->info;
            unset($info['content']);
            $list[] = $info;
        }
        $appendData['list'] = $list;
        $appendData['total'] = $total;
        return $this->success("获取成功", $appendData);
    }

    /**
     * 删除文章
     * @desc post
     * @param int $id
     * @throws
     * @return array
     * @return int status 是否成功
     * @return string msg
     */
    public function actionDelete($id)
    {
        $article = $this->_getModel($id);
        if (!$article->canYouOpt){
            throw new ApiException(202008140909, "你没有修改此留言权限");
        }
        $article->status = Article::STATUS_DELETE;
        if (!$article->save()){
            throw new ApiException(202008140908, "删除失败:".Model::getModelError($article));
        }
        $this->data['status'] = 200;
        $this->data['msg'] = "删除成功";
        return $this->data;
    }

    /**
     * 收藏
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws
     */
    public function actionCollection($id)
    {
        $article = $this->_getModel($id);
        $article->collection();
        return $this->success("收藏成功", ['info' => $article->info]);
    }

    /**
     * 取消收藏
     * @desc post
     * @param int $id
     * @return array
     * @return int status 是否成功
     * @return string msg
     * @throws ApiException
     */
    public function actionUnCollection($id)
    {
        $article = $this->_getModel($id);
        $article->unCollection();
        return $this->success("取消收藏成功", ['info' => $article->info]);
    }

    private function _getModel($id)
    {
        $leaveMessage = Article::findOne(['id' => $id, 'status' => Article::STATUS_ACTIVE]);
        if (!$leaveMessage){
            throw new ApiException(202008191739, "没有找到文章或文章已删除");
        }
        return $leaveMessage;
    }
}