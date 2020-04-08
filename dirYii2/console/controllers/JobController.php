<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/16
 * Time: 10:48
 */

namespace console\controllers;


use QL\QueryList;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\FileHelper;
use wodrow\yii2wtools\tools\Tools;
use yii\base\Exception;
use yii\console\Controller;

class JobController extends Controller
{
    /**
     * php yii job/check
     * @desc 检测计划任务是否执行
     */
    public function actionCheck()
    {
        Tools::log(YII_BT_MTIME, "check-schedule");
    }

    /**
     * php yii job/yiichina-sign
     * @desc https://www.yiichina.com 签到
     * @throws
     */
    public function actionYiichinaSign()
    {
        $ql = QueryList::getInstance();
        $ql->get("https://www.yiichina.com/login");
        $csrf = $ql->find("input[name='_csrf']")->val();
        $username = \Yii::$app->params['yiichina-username'];
        $password = \Yii::$app->params['yiichina-password'];;
        $userInfo= [
            '_csrf'  => $csrf,
            'LoginForm[username]'  => $username,
            'LoginForm[password]'  => $password,
            'LoginForm[rememberMe]'  => '1',
        ];
        $ql->post("https://www.yiichina.com/login", $userInfo);
        $ql->get("https://www.yiichina.com");
        $csrf = $ql->find("input[name='_csrf']")->val();
        $ql->post("https://www.yiichina.com/registration", [
            '_csrf'  => $csrf,
        ], [
            'headers' => [
                'accept' => "application/json, text/javascript, */*; q=0.01",
                'accept-encoding' => "gzip, deflate, br",
                'accept-language' => "en,zh-CN;q=0.9,zh;q=0.8,zh-TW;q=0.7",
                'content-type' => "application/x-www-form-urlencoded; charset=UTF-8",
                'origin' => "https://php.la",
                'referer' => "https://php.la",
                'user-agent' => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36",
                'x-csrf-token' => $csrf,
                'x-requested-with' => "XMLHttpRequest",
            ],
        ]);
        var_dump($ql->getHtml());
    }

    /**
     * php yii job/php-la-sign
     * @desc https://php.la/ 签到
     * @throws
     */
    public function actionPhpLaSign()
    {
        $home_url = "https://phpqz.com";
        $ql = QueryList::getInstance();
        $ql->get("{$home_url}/login");
        $csrf = $ql->find("input[name='_csrf-frontend']")->val();
        $username = \Yii::$app->params['yiichina-username'];;
        $password = \Yii::$app->params['yiichina-password'];;
        $userInfo= [
            '_csrf-frontend'  => $csrf,
            'LoginForm[username]'  => $username,
            'LoginForm[password]'  => $password,
            'LoginForm[rememberMe]'  => '1',
        ];
        $ql->post("{$home_url}/login", $userInfo);
        $ql->get("{$home_url}");
        $csrf_token = $ql->find('meta[name="csrf-token"]')->attr('content');
        $ql->post("{$home_url}/registration", [
            '_csrf'  => $csrf_token,
        ], [
            'headers' => [
                'accept' => "application/json, text/javascript, */*; q=0.01",
                'accept-encoding' => "gzip, deflate, br",
                'accept-language' => "en,zh-CN;q=0.9,zh;q=0.8,zh-TW;q=0.7",
                'content-type' => "application/x-www-form-urlencoded; charset=UTF-8",
                'origin' => "{$home_url}",
                'referer' => "{$home_url}",
                'user-agent' => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36",
                'x-csrf-token' => $csrf_token,
                'x-requested-with' => "XMLHttpRequest",
            ],
        ]);
        var_dump($ql->getHtml());
    }

    /**
     * php yii job/backup
     * @throws
     */
    public function actionBackup()
    {
        $exec_str = "";
        $backupFileRoot = \Yii::getAlias("@uploads_root");
        $db = \Yii::$app->db;
        $dsn = $db->dsn;
        $_a1 = ArrayHelper::str2arr($dsn, ":");
        $_db_type = $_a1[0];
        switch ($_db_type){
            case "mysql":
                $exec_str .= "mysqldump -u{$db->username} -p{$db->password} ";
                $exec_str_end = "";
                $_db_dsn_confs = ArrayHelper::str2arr($_a1[1], ";");
                foreach ($_db_dsn_confs as $k => $v) {
                    $_a2 = ArrayHelper::str2arr($v, "=");
                    $_k = $_a2[0];
                    $_v = $_a2[1];
                    switch ($_k){
                        case "host":
                            $exec_str .= "-h{$_v} ";
                            break;
                        case "port":
                            $exec_str .= "-P{$_v} ";
                            break;
                        case "dbname":
                            $exec_str_end .= "{$_v}>{$backupFileRoot}/{$_v}.sql";
                            break;
                        default:
                            break;
                    }
                }
                $exec_str .= $exec_str_end;
                exec($exec_str);
                break;
            default:
                throw new Exception("没有找到数据库类型:{$_db_type}");
                break;
        }
        FileHelper::fileSysMove('sftpFileProd', 'sftpFileBackup');
        FileHelper::fileSysMove('sftpFileDev', 'sftpFileBackup');
    }
}