<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/3/12
 * Time: 16:53
 */

namespace common\components;


class Tools
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

    public static function yiiLog($msg, $log_name = null)
    {
        if (!$log_name){
            $log_name = \Yii::$app->controller->route;
        }
        \wodrow\yii2wtools\tools\Tools::log($msg, $log_name);
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
}