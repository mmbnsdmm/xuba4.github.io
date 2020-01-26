<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/15
 * Time: 10:41
 */

namespace common\models\db;


class LogQueneYiiTask extends \common\models\db\tables\LogQueneYiiTask
{
    const RESULT_CODE_SUCCESS = 10;
    const RESULT_CODE_PART_DONE = 1;
    const RESULT_CODE_NO_TASK = 0;
    const RESULT_CODE_LOCKED = -1;
    const RESULT_CODE_OVERDUE = -2;
    const RESULT_CODE_LOCKED_LONG = -3;
    const RESULT_CODE_FAILED = -10;

    public static function getResultCodeMsgs()
    {
        return [
            self::RESULT_CODE_SUCCESS => "成功",
            self::RESULT_CODE_PART_DONE => "部分成功",
            self::RESULT_CODE_NO_TASK => "无任务",
            self::RESULT_CODE_LOCKED => "任务锁定中",
            self::RESULT_CODE_OVERDUE => "任务超时",
            self::RESULT_CODE_LOCKED_LONG => "长时间锁定",
            self::RESULT_CODE_FAILED => "任务失败",
        ];
    }
}