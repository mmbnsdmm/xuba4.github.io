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
use common\models\db\SearchIndex;
use common\models\db\User;
use GuzzleHttp\Client;
use QL\QueryList;
use wodrow\yii2wtools\tools\Color;
use wodrow\yii2wtools\tools\Model;
use wodrow\yii2wtools\tools\StrHelper;
use wodrow\yii2wtools\tools\Tools;
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
            var_dump($v->avatar);
            var_dump($avatar_len);
            if (in_array($avatar_len, [81, 82])){
                $v->avatar = \Yii::$app->apiTool->randomAvatarUrl;
                if (!$v->save()){
                    throw new Exception(Model::getModelError($v));
                }else{
                    var_dump($v->id.":".$v->avatar);
                }
            }
            exit;
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
                    \common\components\Tools::yiiLog($v);
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
}