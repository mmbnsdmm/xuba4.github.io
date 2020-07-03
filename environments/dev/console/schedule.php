<?php
/**
 * @var \wodrow\scheduling\Schedule $schedule
 */
/**
 * 任务调度
 * crontab -e * * * * * php /path/to/yii schedule/run --scheduleFile=path/to/schedule.php 1>> /dev/null 2>&1
 * @see
 */

// $schedule->command('migrate')->cron('* * * * *');

// $schedule->exec('composer self-update')->daily();

// 检测计划任务是否执行
$schedule->exec('php yii job/check')->cron('1 */8 * * *');

// 更新时间
//$schedule->exec('ntpdate time.nist.gov')->cron('*/30 * * * *');
$schedule->exec('ntpdate ntp1.aliyun.com')->daily();

// quene yii task
$schedule->exec('php yii task/do-quene 1')->cron('* * * * *');

// git auto update
$schedule->exec('git pull')->cron('* * * * *');

// yii-china-sign
$schedule->exec('php yii job/yiichina-sign')->daily();
$schedule->exec('php yii job/php-la-sign')->daily();

// 备份
$schedule->exec('php yii job/backup')->daily();
$schedule->exec('php yii job/db-backup')->sundays();

// 清理
$schedule->exec('php yii job/log-clear')->sundays();