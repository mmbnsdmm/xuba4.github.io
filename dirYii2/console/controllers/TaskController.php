<?php
namespace console\controllers;



use common\models\db\QueneYiiTask;
use wodrow\yii\rest\ApiException;
use yii\console\Controller;

class TaskController extends Controller
{
    /**
     * php yii test/init-quene
     */
    public function actionInitQuene()
    {
        QueneYiiTask::initQuene();
    }

    /**
     * php yii task/do-quene
     * @param int $pass_no_task
     * @throws
     */
    public function actionDoQuene($pass_no_task = 0)
    {
        $r = QueneYiiTask::doQuene($pass_no_task);
        var_dump($r);
    }

    public function actionAddTest($params = "0", $durning = null, $created_by = null)
    {
        $run_at = null;
        if ($durning){
            $run_at = YII_BT_TIME + $durning;
        }
        echo QueneYiiTask::addTask("task/test", $params, $run_at, $created_by);
    }

    public function actionTest($is_failed = 0)
    {
        if ($is_failed){
            if ($is_failed == 1){
                echo QueneYiiTask::STATUS_FAILED;
            }
            if ($is_failed == 2){
                throw new \Exception("test exception");
            }
            if ($is_failed == 3){
                echo "test";
            }
        }else{
            echo QueneYiiTask::STATUS_DONE;
        }
    }
}