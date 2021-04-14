<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午10:55
 */

namespace console\controllers;


use common\models\db\AdminAuthAssignment;
use common\models\db\AdminAuthItem;
use common\models\db\AdminAuthItemChild;
use common\models\db\Article;
use common\models\db\SearchIndex;
use common\models\db\User;
use common\models\db\UserFile;
use common\wodrow\spiders\BaiDuTieBa;
use GuzzleHttp\Client;
use QL\QueryList;
use wodrow\yii2wtools\tools\Color;
use wodrow\yii2wtools\tools\FileHelper;
use wodrow\yii2wtools\tools\Model;
use wodrow\yii2wtools\tools\StrHelper;
use common\components\Tools;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\Console;

class TestController extends Controller
{
    /**
     * php yii test
     */
    public function actionIndex()
    {
        /* 普通输出 */
        Console::output('hello world!');

        /* 前景色，背景色输出 */
        $fg = Console::ansiFormat('前景色',[Console::FG_GREEN]);
        $bg = Console::ansiFormat('背景色',[Console::BG_RED]);
        Console::output("{$fg}{$bg}");

        /* 同一变量设置前景色，背景色 */
        /**
         * 前景色 FG_BLACK / FG_RED / FG_GREEN / FG_YELLOW / FG_BLUE / FG_PURPLE / FG_CYAN / FG_GREY
         * 背景色 BG_BLACK / BG_RED / BG_GREEN / BG_YELLOW / BG_BLUE / BG_PURPLE / BG_CYAN / BG_GREY
         */
        $hello = Console::ansiFormat("Hello，Beijing!",[Console::FG_YELLOW,Console::BG_BLUE]);
        Console::output($hello);

        /* 变量输出字体正常，加粗，斜体，下划线，底色 */
        Console::output(Console::ansiFormat("normal（正常）",[Console::NORMAL]));
        Console::output(Console::ansiFormat("bold（加粗）",[Console::BOLD]));
        Console::output(Console::ansiFormat("italic（斜体）",[Console::ITALIC]));
        Console::output(Console::ansiFormat("underline（下划线）",[Console::UNDERLINE]));
        Console::output(Console::ansiFormat("negative（底色）",[Console::NEGATIVE]));

        /* 用户输入 */
        $name = Console::input("请输入你的名字：");
        $formatName = Console::ansiFormat($name,[Console::FG_YELLOW]);
        Console::output("你的名字是：{$formatName}");

        /* 用户选择1（select） */
        $sex = Console::select("性别：",[1=>'男',2=>'女']);
        $formatSex = Console::ansiFormat($sex,[Console::FG_YELLOW]);
        Console::output("你的性别是：{$formatSex}");

        /* 用户选择2（yes or no） */
        if (Console::confirm("Are you sure?")) {
            Console::output("user input yes");
        }else{
            Console::output("user input no");
        }

        /* 用户输入3（验证） */
        /**
         *required 真假，是否必须填写
         *default 默认值
         *pattern 正则匹配
         *validator 自定义验证函数
         *error 错误信息
         */
        Console::prompt("请输入你的姓名:",['required'=>true,'error'=>'===>姓名必须输入']);

        /* 进度条 */
        Console::startProgress(0, 1000);
        for ($n = 1; $n <= 1000; $n++) {
            usleep(1000);
            Console::updateProgress($n, 1000);

        }
        Console::endProgress();
    }

    /**
     * php yii test/test
     */
    public function actionTest()
    {
        var_dump(YII_APP_ID);
        var_dump(YII_BT_TIME + 86400*30);
        Tools::log(YII_BT_TIME);
    }

    /**
     * php yii test/test1
     */
    public function actionTest1()
    {
        $data = QueryList::getInstance()->get("https://packagist.org/packages/jaeger/querylist#V4.1.1")->rules([
            'version' => ['li.version>a', 'text'],
            'href' => ['li.version>a', 'href'],
        ])->queryData();
        var_dump($data);
        var_dump(\Yii::$app->user->identity->toArray());
    }

    public function actionTest2()
    {
        $x = AdminAuthItem::findOne(['name' => "测试"]);
        $x->description = date("Y-m-d H:i:s");
//        $x->name = "测试";
        $x->save();
        var_dump(AdminAuthItem::getAllRoles());
        var_dump(AdminAuthAssignment::getRoleNamesByUser(4));
    }

    public function actionTest3()
    {
        $x = AdminAuthItem::getRolesByRole("t1");
        foreach ($x as $k => $v){
            var_dump($v->toArray());
        }
    }

    public function actionTest4($isRandom = false)
    {
        foreach (User::find()->all() as $k => $v) {
            $avatar_len = mb_strlen($v->avatar);
            var_dump($avatar_len);
            var_dump($v->avatar);
            if (in_array($avatar_len, [75, 76, 77, 78, 79, 80, 81, 82])){
                $v->avatar = \Yii::$app->apiTool->randomAvatarUrl;
                if (!$v->save()){
                    throw new Exception(Model::getModelError($v));
                }else{
                    var_dump($v->id.":".$v->avatar);
                }
            }
        }
    }

    public function actionTest5()
    {
        SearchIndex::initSearchIndex();
    }

    public function actionTest6()
    {
        $randomAvatarUrl = "http://placeimg.com/100/100";
        $path = \Yii::getAlias("@uploads_root/test.jpg");
        $client = new Client(['base_uri' => $randomAvatarUrl]);
        $client->request("get", "", ['sink' => $path]);
    }

    public function actionTest7()
    {
        $xuba3Users = \Yii::$app->dbXuba3->createCommand("SELECT * FROM `user`")->queryAll();
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $user = new User();
            foreach ($xuba3Users as $k => $v){
                if (User::findOne(['username' => $v['username']])){
                    Tools::yiiLog($v);
                    var_dump($v['username']);
                }else{
                    $u = clone $user;
                    $u->username = $v['username'];
                    $u->email = $v['email'];
                    $u->password = $v['password'];
                    $u->pwd_back = $v['pwd_back'];
                    $u->status = $v['status'];
                    $u->created_at = $v['created_at'];
                    $u->token = $v['token'];
                    $u->key = $v['key'];
                    $u->auth_key = $v['auth_key'];
                    $u->amount = $v['amount'];
                    $u->frozen = $v['frozen'];
                    $u->signup_message = $v['signup_message'];
                    $u->updated_at = YII_BT_TIME;
                    $u->weixin_exceptional_url = $v['weixin_income_image'];
                    $u->alipay_exceptional_url = $v['alipay_income_image'];
                    $u->qq = $v['qq'];
                    $u->weixin = $v['weixin'];
                    if (!$u->save()){
                        throw new Exception(Model::getModelError($u));
                    }else{
                        var_dump($u->id);
                    }
                    $adminRoleOrdinaryUserName = \Yii::$app->params['adminRoleOrdinaryUserName'];
                    if (!AdminAuthAssignment::findOne(['item_name' => $adminRoleOrdinaryUserName, 'user_id' => $u->id])) {
                        if (!AdminAuthItem::findOne(['name' => $adminRoleOrdinaryUserName, 'type' => 1])) {
                            var_dump("没有找到".$adminRoleOrdinaryUserName."的角色");
                            exit;
                        }
                        $adminAuthAssignment = new AdminAuthAssignment();
                        $adminAuthAssignment->item_name = $adminRoleOrdinaryUserName;
                        $adminAuthAssignment->user_id = $u->id;
                        $adminAuthAssignment->created_at = YII_BT_TIME;
                        if (!$adminAuthAssignment->save()) {
                            var_dump("角色分配失败:" . Model::getModelError($adminAuthAssignment));
                            exit;
                        }
                    }
                }
            }
            $this->actionTest4();
            $trans->commit();
        }catch (\yii\base\Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }

    public function actionTest8()
    {
        $users = User::find()->all();
        $assign = new AdminAuthAssignment();
        $param = \Yii::$app->params['adminRoleOrdinaryUserName'];
        foreach ($users as $k => $v) {
            if (AdminAuthAssignment::findOne(['item_name' => $param, 'user_id' => $v->id])){
                var_dump("pass:{$v->id}");
                continue;
            }
            $a = clone $assign;
            $a->item_name = $param;
            $a->user_id = $v->id;
            $a->created_at = YII_BT_TIME;
            if ($a->save()){
                var_dump($a->toArray());
            }else{
                var_dump($a->errors);
            }
        }
    }

    public function actionTest9($url)
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
            if ($v['author_id'] == $spiderBaiDuTieBa->author_id){
                if (!in_array($v['post_id'], $postIds)){
                    $postIds[] = $v['post_id'];
                    $article->content .= $v['text'];
                }
            }
        }
        if (!$article->save()){
            var_dump("文章保存失败:".Model::getModelError($article));
        }else{
            var_dump($article->toArray());
        }
        /*$content = "";
        foreach ($list as $k => $v) {
            if ($v['author_id'] == $spiderBaiDuTieBa->author_id){
                $content .= $v['text'];
            }
        }
        var_dump($content);
        var_dump($spiderBaiDuTieBa->title);
        $resp = \Yii::$app->apiTool->post("/article/default/publish", [
            'title' => $spiderBaiDuTieBa->title,
            'content' => $content,
            'create_type' => Article::CREATE_TYPE_REPRINTED,
//            'tagModify' => Tools::toJson([
//                'plus' => [1],
//                'reduce' => [],
//            ]),
        ], User::findOne(1));
        var_dump($resp);*/
    }

    public function actionTest10()
    {
        $x = UserFile::find()->all();
        foreach ($x as $k => $v) {
            $v->yii_alias_uploads_abpath = $v->yii_alias_uploads_abpath?:"@uploads_aburl";
            $file = $v->root;
            if (!file_exists($file)){
                var_dump($file);
                $file = str_replace("/dev/", "/prod/", $file);
                var_dump(file_exists($file));
                exit;
            }
            $v->save();
        }
    }

    public function actionTest11()
    {
        $content = <<<HTML
<p>如下图，女孩的背景是足球场草坪，抠图之后衣服和鞋子的边缘都映射有少量的绿色，如何去除？如果用简单的hue saturation就把整个图像的颜色都变了，如何仅仅去除映射的少量绿色？</p><p><img class="img img-responsive" src="@USER_FILE_GET_{39}" alt=""><img class="img img-responsive" src="http://xuba4.tc/storage/dev/uploads/user_files/1/20210408_162250_b_oxRKycgIi77gGA2zHMn-nX30DZDFKI.jpg" alt=""></p><p>已经解决了，看看最后的作品：</p><p><img class="img img-responsive" src="http://xuba4.tc/storage/dev/uploads/user_files/1/20210408_162251_-dtRpkxfadOanBi-_5Mdko7Ws_YUtsf9.jpg" alt=""></p>
HTML;
        $content = <<<HTML
<img src="@USER_FILE_GET_{39}">
<img src="http://xuba4.tc/storage/dev/uploads/user_files/1/20210408_162250_b_oxRKycgIi77gGA2zHMn-nX30DZDFKI.jpg">
<img src="http://xuba4.tc/storage/dev/uploads/user_files/1/20210408_162251_-dtRpkxfadOanBi-_5Mdko7Ws_YUtsf9.jpg">
HTML;

        $content = UserFile::encodeContent($content);
        var_dump($content);
    }

    public function actionTest12($articleStartId = 1, $articleEndId = null)
    {
        $query = Article::find()->where([">=", 'id', $articleStartId]);
        if ($articleEndId !== null)$query->andWhere(["<=", 'id', $articleEndId]);
        $articles =$query->all();
        foreach ($articles as $k => $v) {
            $article = $v;
            $fileRollBack = [];
            $trans = \Yii::$app->db->beginTransaction();
            var_dump($article->id);
            $content = UserFile::encodeContent($article->content);
            $reg = <<<REGEXP
/src\=\"(https?\:\/\/[\w|\.|\-|\:?]+)?([\w|\/|\\\|\.|\-]+)\"/
REGEXP;
            try{
                $content = preg_replace_callback($reg, function ($matches) use ($article, &$fileRollBack){
                    $domain = $matches[1];
                    $path = $matches[2];
                    $file = \Yii::getAlias("@wroot{$path}");
                    $file = str_replace('storage/uploads/prod/baidu_tieba', 'storage/prod/uploads/xuba3/baidu_tieba', $file);
                    $file = str_replace('storage/uploads/prod/xuba3/baidu_tieba', 'storage/prod/uploads/xuba3/baidu_tieba', $file);
                    $file = str_replace('storage/uploads/baidu_tieba', 'storage/prod/uploads/xuba3/baidu_tieba', $file);
                    if (!array_key_exists($file, $fileRollBack)){
                        if (!file_exists($file)){
                            if ($domain){
                                $ips = ["120.92.150.43", "49.235.220.19", "121.37.179.86"];
                                foreach ($ips as $k1 => $v1) {
                                    if(strpos($path, $v1) !== false){
                                        throw new \yii\console\Exception("没有找到文件:{$file}");
                                    }
                                }
                                return $matches[0];
                            }
                        }
                        $userFile = UserFile::findOne(['original_url' => $path]);
                        if (!$userFile){
                            $user = \Yii::$app->user->identity;
                            $_path = "/user_files/{$user->id}";
                            $userFile = new UserFile();
                            $userFile->original_url = $path;
                            $userFile->r_type = $userFile::R_TYPE_ABSOLUTELY;
                            $userFile->extension = substr(strrchr(basename($path), '.'), 1);;
                            $userFile->generateFilename();
                            $userFile->relation_path = $_path;
                            $userFile->yii_alias_uploads_path = "@uploads_url";
                            $userFile->yii_alias_uploads_abpath = "@uploads_aburl";
                            $userFile->yii_alias_uploads_root = "@uploads_root";
                            $userFile->created_by = $userFile->updated_by = $user->id;
                            $userFile->created_at = $userFile->updated_at = YII_BT_TIME;
                            $userFile->status = $userFile::STATUS_UPLOADED;
                        }
                        $uf_root = $userFile->root;
                        if (!is_dir(dirname($uf_root))){
                            FileHelper::createDirectory(dirname($uf_root));
                        }
                        if (!$userFile->save()){
                            throw new \yii\console\Exception("移动文件数据保存失败:".Model::getModelError($userFile));
                        }else{
                            if (!rename($file, $uf_root)){
                                throw new \yii\console\Exception("移动文件失败:rename('{$file}', '{$uf_root}')");
                            }else{
                                $fileRollBack[$file] = ['from' => $uf_root, 'to' => $file, 'src' => $userFile->funurl];
                            }
                        }
                    }
                    return "src=\"{$fileRollBack[$file]['src']}\"";
                }, $content);
                $article->content = $content;
                if (!$article->save()){
                    throw new \yii\console\Exception("文章保存失败:".Model::getModelError($article));
                }
                $trans->commit();
            }catch (\yii\base\Exception $e){
                $trans->rollBack();
                if ($fileRollBack){
                    foreach ($fileRollBack as $k1 => $v1) {
                        var_dump("php -r \"rename('{$v1['from']}', '{$v1['to']}');\"");
                        rename($v1['from'], $v1['to']);
                    }
                }
                throw $e;
            }
        }
    }

    public function actionTest13()
    {
//        $f = "/www/wwwroot/bnsdmm/xuba4/web/storage/prod/uploads/xuba3/baidu_tieba/5986829561/e6b9292bd40735fa51f56f2893510fb30e2408bf.jpg";
        $f = "/www/wwwroot/bnsdmm/xuba4/web/storage/prod/uploads/xuba3/baidu_tieba/5986829561/e6b9292bd407356f2893510fb30e2408bf.jpg";
//        $f = "/home/wodrow/Test/orgi.jpg";
        $x = file_exists($f);
        var_dump($x);
//        exit;
//        rename($f,'/www/wwwroot/bnsdmm/xuba4/web/storage/prod/uploads/user_files/1/20210412_143021_0_VOy4rbMwPB9Gf0xAiCPz9Y3sAws0kK.jpg');
//        rename($f,'/home/wodrow/Test/tt.jpg');
//        rename($f,'/home/wodrow/Test/orgi.jpg');
//        rename('/home/wodrow/Test/orgi.jpg', $f);
    }

    public function actionTest14($startId = 1, $endId = null)
    {
        $query = User::find()->where([">=", 'id', $startId]);
        if ($endId !== null)$query->andWhere(["<=", 'id', $endId]);
        $users = $query->all();
        foreach ($users as $k => $v) {
            $user = $v;
            $user->avatar = str_replace("http://49.235.220.19:65108/static", "@static_aburl", $user->avatar);
            if ($user->save()){
                var_dump($user->avatar);
            }else{
                var_dump($user->errors);exit;
            }
        }
    }

    public function actionTest15()
    {
        $x = Article::find()->select(['content'])->where(['id' => 13])->one();
        var_dump($x->toArray());
    }
}