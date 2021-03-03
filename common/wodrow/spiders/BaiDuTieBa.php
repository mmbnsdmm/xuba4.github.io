<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2021/3/2
 * Time: 15:12
 */

namespace common\wodrow\spiders;


use QL\QueryList;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\FileHelper;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Html;

class BaiDuTieBa extends Component
{
    public $url; # data
    public $is_console = 0;
    public $is_cache = 0;
    public $title; # data
    public $post_ids; # data
    public $author_id; # data
    public $author_name; # data
    protected $baiduTieziId;
    protected $upload_root;
    protected $upload_url;

    /**
     * @param $msg
     * @throws
     */
    public function error($msg)
    {
        if ($this->is_console){
            throw new \yii\console\Exception($msg);
        }else{
            throw new Exception($msg);
        }
    }

    public function consoleMsg($msg)
    {
        if ($this->is_console)var_dump($msg);
    }

    /**
     * @return void
     * @throws
     */
    public function checkIsTieZi()
    {
        $_url = $this->url;
        $_url = str_replace('https', 'http', $_url);
        $str = "http://tieba.baidu.com/p/";
        $_id = str_replace($str, '', $_url);
        if (strpos($_id, '?')!==false){
            $arr = explode('?', $_id);
            $_id = $arr[0];
        }
        if (is_numeric($_id)) {
            $this->baiduTieziId = $_id;
            $this->url = $str.$_id;
        } else {
            $this->error('不是帖子');
        }
    }

    /**
     * @throws Exception
     */
    public function getUploadRootAndUrl()
    {
        $_dir = str_replace("http://tieba.baidu.com/p/", "", $this->url);
        $this->upload_root = \Yii::getAlias("@uploads_root/baidu_tieba/{$_dir}");
        $this->upload_url = \Yii::getAlias("@uploads_url/baidu_tieba/{$_dir}");
        if (!is_dir($this->upload_root)){
            FileHelper::createDirectory($this->upload_root);
        }
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getList()
    {
        $this->checkIsTieZi();
        $this->getUploadRootAndUrl();
        $ql = QueryList::getInstance()->get($this->url);
        $this->title = $ql->find(".core_title_txt")->attr('title');
        if ($this->title)$this->title .= "--引自百度贴吧";
        $this->post_ids = [];
        $pages = $ql->find('.l_reply_num')->find('.red:eq(1)')->text();
        $this->consoleMsg($pages);
        if (!$this->is_cache)\Yii::$app->cache->delete('baiduTieziId_'.$this->baiduTieziId);
        $list = \Yii::$app->cache->get('baiduTieziId_'.$this->baiduTieziId);
        $qlProxy = \Yii::$app->params['qlProxy'];
        if (!$list){
            $list = [];
            for ($i = 1; $i <= $pages; $i++){
                $this->consoleMsg($i);
                if ($i == 1){
                    $_ql = $ql;
                }else{
                    if ($qlProxy) {
                        $_ql = QueryList::getInstance()->get($this->url."?pn={$i}", null, [
                            'proxy' => $qlProxy,
                        ]);
                    }else{
                        $_ql = QueryList::getInstance()->get($this->url."?pn={$i}");
                    }
                }
                $_list = $_ql->rules([
//                    'html' => ['.j_l_post:visible .j_d_post_content', 'html'],
                    'html' => ['.j_l_post:visible', 'html'],
                    'text' => ['.j_l_post:visible', 'text'],
                    'tail' => ['.j_l_post:visible', 'data-field'],
                ])->queryData();
                $list = ArrayHelper::merge($list, $_list);
            }
            \Yii::$app->cache->set('baiduTieziId_'.$this->baiduTieziId, $list, 3600);
        }
        foreach ($list as $k => $v){
            $_ql = QueryList::getInstance()->html($v['html']);
            if (!$_ql->find('.j_d_post_content')->html()){
                unset($list[$k]);
            }else{
                $list[$k]['html'] = $_ql->find('.j_d_post_content')->html();
                $list[$k]['text'] = $_ql->find('.j_d_post_content')->text();
                $list[$k]['tail'] = json_decode($v['tail'], true);
            }
        }
        foreach ($list as $k => $v) {
            $this->consoleMsg("list:".$k);
            $html = $v['html'];
            $tail = $v['tail'];
            unset($list[$k]['tail']);
            unset($list[$k]['html']);
            $list[$k]['author_id'] = $tail['author']['user_id'];
            $text = $v['text'];
            if ($k === 0){
                $this->author_id = $tail['author']['user_id'];
                $this->author_name = $tail['author']['user_name'];
                if (trim($text)){}else{
                    $text = "<p></p>";
                }
            }
            if (trim($text)){
                $text = "<p>{$v['text']}</p>";
            }
            $_ql = QueryList::getInstance()->html($html);
            $images = $_ql->rules([
                'image' => ['img', 'src'],
                'pic_type' => ['img', 'pic_type'],
            ])->queryData();
            $videos = $_ql->rules([
                'video' => ['embed', 'data-video'],
            ])->queryData();
            $vids = $this->saveTieBaVideo($videos);
            if ($vids){
                $text .= "<p>".implode('', $vids)."</p>";
            }
            $imgs = $this->saveTieBaImage($images);
            if ($imgs){
                $text .= "<p>".implode('', $imgs)."</p>";
            }
            if ($text){
                if ($tail) {
                    $this->post_ids[] = $tail['content']['post_id'];
                }
                $list[$k]['post_id'] = $v['tail']['content']['post_id'];
                $list[$k]['text'] = $text;
            }else{
                unset($list[$k]);
            }
        }
        $_l = [];
        foreach ($list as $k => $v) {
            $_l[] =$v;
        }
        return $_l;
    }

    /**
     * @param $images
     * @return array
     * @throws
     */
    public function saveTieBaImage($images)
    {
        $imgs = [];
        foreach ($images as $k => $v){
            switch ($v['pic_type']){
                case "0":
                    $image_name = basename($v['image']);
                    if (strpos($image_name, '.png?t=') !== false){
                        $_x = explode("?t=", $image_name);
                        $image_name = $_x[0];
                    }
                    $root = $this->upload_root.DIRECTORY_SEPARATOR.$image_name;
                    $url = $this->upload_url.DIRECTORY_SEPARATOR.$image_name;
                    if (!file_exists($root)){
                        $fg_con = file_get_contents($v['image']);
                        if ($fg_con){
                            file_put_contents($root, $fg_con);
                        }
                    }
                    $imgs[] = Html::img($url, ['class' => "img img-responsive"]);
                    $this->consoleMsg($url);
                    break;
                case "1":
                default:
                    unset($images[$k]);
                    break;
            }
        }
        return $imgs;
    }

    /**
     * @param $videos
     * @return array
     * @throws
     */
    public function saveTieBaVideo($videos)
    {
        $vids = [];
        foreach ($videos as $k => $v) {
            $video_name = basename($v['video']);
            if (strpos($video_name, '.png?t=') !== false) {
                $_x = explode("?t=", $video_name);
                $video_name = $_x[0];
            }
            $root = $this->upload_root . DIRECTORY_SEPARATOR . $video_name;
            $url = $this->upload_url . DIRECTORY_SEPARATOR . $video_name;
            if (!file_exists($root)) {
                $fg_con = @file_get_contents($v['image']);
                if ($fg_con) {
                    file_put_contents($root, $fg_con);
                }
            }
            $vids[] = "<video src='{$url}' controls='controls'>您的浏览器不支持 video 标签</video>";
//            $fi = new \finfo(FILEINFO_MIME_TYPE);
//            $mime_type = $fi->file($root);
        }
        return $vids;
    }
}