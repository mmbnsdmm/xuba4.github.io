<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2019/11/4
 * Time: 11:10
 */

namespace admin\controllers;


use admin\models\FormLogin;
use common\components\Tools;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                /*'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'logout', 'test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],*/
                'only' => ['index', 'login', 'captcha', 'logout', 'error', 'test', 'info'],
                'rules' => [
                    [
                        'actions' => ['login', 'captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout', 'test', 'info'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'maxLength' => 4, //生成的验证码最大长度
                'minLength' => 4  //生成的验证码最短长度
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$container->set(\yii\web\JqueryAsset::class, [
            'sourcePath' => '@static_root/iframe-adminlte/plugins/jQuery',
            'js' => [
                'jquery-2.2.3.min.js',
            ],
        ]);
        Yii::$container->set(\yii\bootstrap\BootstrapAsset::class, [
            'sourcePath' => '@static_root/iframe-adminlte/bootstrap',
        ]);
        Yii::$container->set(\yii\bootstrap\BootstrapPluginAsset::class, [
            'sourcePath' => '@static_root/iframe-adminlte/bootstrap',
        ]);
        $this->layout = 'iframe-main';
        return $this->render('index');
    }

    public function actionLogin()
    {
        $model = new FormLogin();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->login()){
                return $this->goBack();
            }
        }
        return $this->renderPartial('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['site/login']);
    }

    public function actionInfo()
    {
        // 禁用函数
        $disableFunctions = ini_get('disable_functions');
        $disableFunctions = !empty($disableFunctions) ? explode(',', $disableFunctions) : '未禁用';
        // 附件大小
        $attachmentDir = \Yii::getAlias('@storage_root/uploads');
        $attachmentSize = Tools::getDirSize($attachmentDir);

        $models = \Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $this->render('info', [
            'mysql_size' => $mysqlSize,
            'attachment_dir' => $attachmentDir,
            'attachment_size' => $attachmentSize ?? 0,
            'disable_functions' => $disableFunctions,
        ]);
    }

    public function actionTest()
    {
        return $this->renderPartial('test');
    }
}