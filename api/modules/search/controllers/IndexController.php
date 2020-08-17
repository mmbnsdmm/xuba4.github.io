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
     * @param string $keyword 关键字，如果是searchAll则搜索全部
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
    public function actionList($keyword = '', $page = 1, $page_size = 10, $json_filter_params = null)
    {
        $appendData = ['list' => [], 'page' => $page, 'page_size' => $page_size, 'total' => 0];
        if (!$keyword) {
            return $this->success("获取成功", $appendData);
        }
        $limit = $page_size;
        $offset = $limit * ($page - 1);
        $query = SearchIndex::find();
        if ("searchAll" == $keyword){}else{
            $query->andWhere(['like', 'title', $keyword]);
        }
        if ($json_filter_params){
            $filter_params = json_decode($json_filter_params, true);
            $query->andWhere($filter_params);
        }
        $query->orderBy(['id' => SORT_DESC, 'created_at' => SORT_DESC, 'updated_at' => SORT_DESC]);
        $_query = clone $query;
        $total = $_query->count();
        $searchIndexes = $query->limit($limit)->offset($offset)->all();
        $list = [];
        foreach ($searchIndexes as $k => $v) {
            $info =$v->toArray();
            $list[] = $info;
        }
        $appendData['list'] = $list;
        $appendData['total'] = $total;
        return $this->success("获取成功", $appendData);
    }
}