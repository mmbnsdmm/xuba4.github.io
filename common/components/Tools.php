<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/3/12
 * Time: 16:53
 */

namespace common\components;


use common\models\db\LogYiiLog;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

class Tools extends \wodrow\yii2wtools\tools\Tools
{
    /**
     * 文件下载到本地
     * @param string $url 只能是类似这种的http://chedai.baozeqiche.com/Uploads/2018/11/154113098091120441.jpg
     * @return bool|string
     */
    public static function downloadTmpFileFromUrl($url)
    {
        $tmp_file = basename($url);
        $tmp_path = \Yii::getAlias("@tmp_root/{$tmp_file}");
        $data = file_get_contents($url);
        file_put_contents($tmp_path, $data);
        return $tmp_path;
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    public static function generateOrdernoExt()
    {
        return rand(10, 99).rand(1000, 9999);
    }

    /*public static function yiiLog($msg, $log_name = null, $is_saveto_db = false)
    {
        if (!$log_name){
            $log_name = \Yii::$app->controller->route;
        }
        \wodrow\yii2wtools\tools\Tools::log($msg, $log_name);
        if ($is_saveto_db){
            $ip = null;
            $user_id = null;
            if (!is_string($msg)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($msg instanceof \Throwable || $msg instanceof \Exception) {
                    $msg = (string) $msg;
                } else {
                    $msg = VarDumper::export($msg);
                }
            }
            if (\Yii::$app->has('user', true)){
                if (\Yii::$app->user->isGuest){}else{
                    $ip = \Yii::$app->user->loginIp;
                    $user_id = \Yii::$app->user->id;
                }
            }
            $logYiiLog = new LogYiiLog();
            $logYiiLog->log_name = $log_name;
            $logYiiLog->msg = $msg;
            $logYiiLog->created_at = YII_BT_TIME;
            $logYiiLog->from_ip = $ip;
            $logYiiLog->user_id = $user_id;
            if (!$logYiiLog->save()){
                \wodrow\yii2wtools\tools\Tools::log($logYiiLog->errors, $log_name);
            }
        }
    }*/

    /**
     * @param $msg
     * @param null $log_name
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\log\LogRuntimeException
     */
    public static function yiiLog($msg, $log_name = null, $is_rewrite = 0)
    {
        if (!$log_name){
            $log_name = \Yii::$app->controller->route;
        }else{
            $log_name = \Yii::$app->controller->route.DIRECTORY_SEPARATOR.$log_name;
        }
//        \wodrow\yii2wtools\tools\Tools::log($msg, $log_name);
        $log = New FileTarget();
        $log->logFile = \Yii::getAlias('@common/runtime/logs/') . \Yii::$app->id . DIRECTORY_SEPARATOR . "{$log_name}.log";
        $dir = dirname($log->logFile);
        if (!is_dir($dir))FileHelper::createDirectory($dir);
        if ($is_rewrite) {
            file_put_contents($log->logFile, '');
        }
        $log->messages[] = [$msg, Logger::LEVEL_INFO, 'tool-log', time()];
        $log->export();
    }

    /**
     * 获取文件列表
     * @param string $dir
     * @param bool $recursive
     * @return array
     */
    public static function listDir($dir, $recursive = true)
    {
        $result = array();
        if (is_dir($dir)) {
            $file_dir = scandir($dir);
            foreach ($file_dir as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                } else {
                    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                    array_push($result, $filePath);
                    if (is_dir($filePath) && $recursive){
                        $result = array_merge($result, self::listDir($filePath, $recursive));
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 判断是否为时间戳
     * @param int $timestamp
     * @return bool
     */
    public static function isTimestamp($timestamp)
    {
        if (!is_numeric($timestamp)){
            return false;
        }else{
            $timestamp = intval($timestamp);
        }
        if (strtotime(date('Y-m-d H:i:s', $timestamp)) === $timestamp) {
            return true;
        } else return false;
    }

    /**
     * 删除目录下的所有文件和文件夹
     * @param string $path
     */
    public static function deldir($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    $pv = $path . DIRECTORY_SEPARATOR . $val;
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($pv)){
                        //子目录中操作删除文件夹和文件
                        self::deldir($pv);
                        //目录清空后删除空文件夹
                        rmdir($pv);
                    }else{
                        //如果是文件直接删除
                        unlink($pv);
                    }
                }
            }
        }
    }

    /**
     * 删除目录
     * @param string $path
     */
    public static function removeDir($path)
    {
        self::deldir($path);
        rmdir($path);
    }


    /**
     * 生成不带横杠的UUID
     * @return string
     */
    public static function genuuid(){
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * 获取文件夹大小
     *
     * @param string $dir 根文件夹路径
     * @return int
     */
    public static function getDirSize($dir)
    {
        $handle = opendir($dir);
        $sizeResult = 0;
        while (false !== ($folderOrFile = readdir($handle))) {
            if ($folderOrFile != "." && $folderOrFile != "..") {
                if (is_dir("$dir/$folderOrFile")) {
                    $sizeResult += self::getDirSize("$dir/$folderOrFile");
                } else {
                    $sizeResult += filesize("$dir/$folderOrFile");
                }
            }
        }

        closedir($handle);

        return $sizeResult;
    }

    /**
     * 判断字符串是否为json，若是输出json
     * @param string $str Json字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    public static function isJson($str, $assoc = true)
    {
        $data = json_decode($str, $assoc);
        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return false;
    }

    /**
     * 转json
     * @param $data
     * @return string Json字符串
     */
    public static function toJson($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}