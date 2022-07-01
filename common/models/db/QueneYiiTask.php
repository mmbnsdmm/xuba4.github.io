<?php
namespace common\models\db;


use wodrow\yii\rest\ApiException;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Model;

class QueneYiiTask extends \common\models\db\tables\QueneYiiTask
{
    const STATUS_REDAY = 0; // 准备中
    const STATUS_RUNNING = 1; // 运行中
    const STATUS_PASS = 2; // 跳过
    const STATUS_OVERDUE = -2; // 任务过期
    const STATUS_FAILED = -10; // 执行失败
    const STATUS_DONE = 10; // 任务完成

    /**
     * php yii test/init-quene
     * @throws
     */
    public static function initQuene()
    {
        \Yii::$app->cache->delete("QueneYiiTask2doQuene");
        static::updateAll(['status' => static::STATUS_REDAY], ['status' => static::STATUS_RUNNING]);
    }

    /**
     * @param int $pass_no_task
     * @return array
     * @throws
     */
    public static function doQuene($pass_no_task = 0)
    {
        $log = new LogQueneYiiTask();
        $result_msgs = LogQueneYiiTask::instance()->resultCodeMsgs;
        $log->created_at = YII_BT_TIME;
        $locked = \Yii::$app->cache->get("QueneYiiTask2doQuene");
        if ($locked){
            $locked_at = isset($locked['at'])?$locked['at']:0;
            $locked_at_date = date("Y-m-d H:i:s", $locked_at);
            $locked_taskID = isset($locked['taskID'])?$locked['taskID']:null;
            $log->result_code = LogQueneYiiTask::RESULT_CODE_LOCKED;
            $log->result_msg = $result_msgs[$log->result_code];
            $locked_second = YII_BT_TIME - $locked_at;
            if ($locked_second > 300){
                $log->result_code = LogQueneYiiTask::RESULT_CODE_LOCKED_LONG;
                $log->result_msg = $result_msgs[$log->result_code].",锁定时间:{$locked_at_date},已锁定秒数:{$locked_second},锁定任务id:{$locked_taskID}";
            }
            if (!$log->save()){
                throw new ApiException(201910151048, "执行日志保存失败:".Model::getModelError($log));
            }
            return [
                'log' => $log->toArray(),
                'locked' => $locked,
            ];
        }
        $locked = [
            'at' => YII_BT_TIME,
            'taskID' => null,
        ];
        $quenes = static::find()->where(['status' => static::STATUS_REDAY])->all();
        $s_ids = [];
        $e_ids = [];
        foreach ($quenes as $k => $v){
            $locked['taskID'] = $v->id;
            \Yii::$app->cache->set("QueneYiiTask2doQuene", $locked, 86400*365*10);
            if ($v->run_at){
                $time_diff = YII_BT_TIME - $v->run_at;
                if ($time_diff > 300){
                    $e_ids[] = $v->id;
                    $v->status = static::STATUS_OVERDUE;
                    $v->error_msg = json_encode([
                        'do_run_at' => YII_BT_TIME,
                        'time_diff' => $time_diff,
                    ], JSON_UNESCAPED_UNICODE);
                    if (!$v->save()){
                        throw new ApiException(201910151550, "执行任务更新失败:".Model::getModelError($v));
                    }
                    continue;
                }elseif($time_diff < 0){
                    continue;
                }
            }
            $v->status = static::STATUS_RUNNING;
            if (!$v->save()){
                throw new ApiException(201910151600, "执行任务更新失败:".Model::getModelError($v));
            }
            $v->run();
            if ($v->status != static::STATUS_DONE){
                $e_ids[] = $v->id;
            }else{
                $s_ids[] = $v->id;
            }
            if (!$v->save()){
                throw new ApiException(201910151551, "执行任务更新失败:".Model::getModelError($v));
            }
        }
        if (count($s_ids) > 0){
            $log->done_quene_yii_task_ids = ArrayHelper::arr2str($s_ids);
            $log->result_code = LogQueneYiiTask::RESULT_CODE_SUCCESS;
        }else{
            $log->result_code = LogQueneYiiTask::RESULT_CODE_NO_TASK;
        }
        if (count($e_ids) > 0){
            $log->failed_quene_yii_task_ids = ArrayHelper::arr2str($e_ids);
            if ($log->result_code == LogQueneYiiTask::RESULT_CODE_SUCCESS){
                $log->result_code = LogQueneYiiTask::RESULT_CODE_PART_DONE;
            }else{
                $log->result_code = LogQueneYiiTask::RESULT_CODE_FAILED;
            }
        }
        $log->result_msg = $result_msgs[$log->result_code];
        if ($log->result_code == LogQueneYiiTask::RESULT_CODE_NO_TASK){
            if ($pass_no_task){}else{
                if (!$log->save()){
                    throw new ApiException(201910151553, "执行日志保存失败:".Model::getModelError($log));
                }
            }
        }else{
            if (!$log->save()){
                throw new ApiException(201910151552, "执行日志保存失败:".Model::getModelError($log));
            }
        }
        \Yii::$app->cache->delete("QueneYiiTask2doQuene");
        return [
            'log' => $log->toArray(),
        ];
    }

    /**
     * @param $route
     * @param null $params
     * @param null $run_at
     * @param null $created_by
     * @return bool
     * @throws
     */
    public static function addTask($route, $params = null, $run_at = null, $created_by = null)
    {
        $quene = new static();
        $quene->route = $route;
        $quene->params = $params;
        if (!$run_at){
            $quene->run_at = YII_BT_TIME + 60*5;
        }
        $quene->created_by = $created_by;
        $quene->created_at = YII_BT_TIME;
        $quene->status = static::STATUS_REDAY;
        if (!$quene->save()){
            throw new ApiException(201910151422, "任务添加失败:".Model::getModelError($quene));
        }
        return true;
    }

    /**
     * 执行任务
     */
    public function run()
    {
        $yii_path = YII_PROJECT_ROOT."/yii";
        $r = exec("php {$yii_path} {$this->route} {$this->params} 2>&1", $output);
        if ($r == static::STATUS_DONE){
            $this->status = $r;
        }else{
            $this->status = static::STATUS_FAILED;
            $r = ArrayHelper::arr2str(array_filter($output), ';');
            $this->error_msg = $r;
        }
    }
}