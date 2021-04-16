<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/10/16
 * Time: 10:48
 */

namespace console\controllers;


use common\models\db\Article;
use common\models\db\Log;
use common\wodrow\spiders\BaiDuTieBa;
use QL\QueryList;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\BackUp;
use wodrow\yii2wtools\tools\FileHelper;
use common\components\Tools;
use wodrow\yii2wtools\tools\Model;
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
     * 获取贴吧文章
     * php yii job/ct
     * @param $url
     */
    public function actionCt($url, $saveAll = 0)
    {
        $spiderBaiDuTieBa = new BaiDuTieBa();
        $spiderBaiDuTieBa->url = $url;
        $spiderBaiDuTieBa->is_console = 1;
        $spiderBaiDuTieBa->is_cache = 1;
        $list = $spiderBaiDuTieBa->getList();
        $article = Article::findOne(['tieba_url' => $url]);
        if (!$article){
            $article = new Article();
            $article->tieba_url = $url;
            $article->tieba_author_id = $spiderBaiDuTieBa->author_id;
            $article->tieba_author_name = $spiderBaiDuTieBa->author_name;
            $article->title = $spiderBaiDuTieBa->title;
            $article->content = "";
            $article->status = $article::STATUS_ACTIVE;
            $article->is_boutique = $article::IS_BOUTIQUE_N;
            $article->create_type = $article::CREATE_TYPE_REPRINTED;
        }
        $postIds = Tools::isJson($article->tieba_post_ids)?:[];
        foreach ($list as $k => $v) {
            if ($saveAll == 0){
                if ($v['author_id'] == $spiderBaiDuTieBa->author_id){
                    if (!in_array($v['post_id'], $postIds)){
                        $postIds[] = $v['post_id'];
                        $article->content .= $v['text'];
                    }
                }
            }else{
                if (!in_array($v['post_id'], $postIds)){
                    $postIds[] = $v['post_id'];
                    $article->content .= $v['text'];
                }
            }
        }
        $article->tieba_post_ids = Tools::toJson($postIds);
        if (!$article->save()){
            var_dump("文章保存失败:".Model::getModelError($article));
        }else{
            var_dump("文章保存成功:{$article->id}");
        }
    }

    /**
     * php yii job/log-clear
     * @param int $keep
     */
    public function actionLogClear($keep = 86400 * 365)
    {
        Log::deleteAll(['<', 'log_time', YII_BT_TIME - $keep]);
    }

    public function actionClean()
    {
        $apps = ['admin', 'api', 'home', 'console', 'common'];
        foreach ($apps as $k => $v) {
            FileHelper::removeDirectory(\Yii::getAlias("@{$v}/runtime/cache"));
            FileHelper::removeDirectory(\Yii::getAlias("@{$v}/runtime/debug"));
            FileHelper::removeDirectory(\Yii::getAlias("@{$v}/runtime/HTML"));
            FileHelper::removeDirectory(\Yii::getAlias("@{$v}/runtime/URI"));
            for ($i = 20; $i < 50; $i++){
                FileHelper::removeDirectory(\Yii::getAlias("@{$v}/runtime/gii-2.0.{$i}"));
            }
            if ($v !== 'common'){
                $dirs = FileHelper::listDir(\Yii::getAlias("@wroot/{$v}/assets"));
                foreach ($dirs as $k1 => $v1) {
                    if (is_dir($v1)){
                        FileHelper::removeDirectory($v1);
                    }
                }
            }
        }
        $dirs = FileHelper::listDir(\Yii::getAlias("@wroot/assets"));
        foreach ($dirs as $k => $v) {
            if (is_dir($v1)){
                FileHelper::removeDirectory($v);
            }
        }
    }
}