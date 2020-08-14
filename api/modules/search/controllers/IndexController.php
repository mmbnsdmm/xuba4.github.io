<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/14
 * Time: 15:23
 */

namespace api\modules\search\controllers;


use common\models\db\SearchIndex;
use wodrow\yii\rest\Controller;

class IndexController extends Controller
{
    /**
     * 获取索引列表
     * @desc post
     * @param string $keyword 关键字
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
    public function actionList($keyword, $page = 1, $page_size = 10, $json_filter_params = null)
    {
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = SearchIndex::find()->andWhere(['like', 'title' => $keyword]);
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        $query->orderBy(['created_at' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $searchIndexes = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($searchIndexes as $k => $v) {
            $info =$v->toArray();
            $list[] = $info;
        }
        $r = $this->data;
        $r['status'] = 200;
        $r['msg'] = "获取成功";
        $r['list'] = $list;
        $r['page'] = $page;
        $r['page_size'] = $page_size;
        $r['total'] = $total;
        return $r;
    }
}