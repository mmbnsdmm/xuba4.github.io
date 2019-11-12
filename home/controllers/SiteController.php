<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace home\controllers;



use common\models\db\User;
use home\models\FormLogin;
use home\models\FormLogin2;
use home\models\FormResetPassword;
use home\models\FormSignup;
use xj\oauth\QqAuth;
use xj\oauth\WeiboAuth;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout', 'signup', 'login', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login', 'reset-password', 'do-reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout'],
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
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $model = new FormLogin();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->login()){
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}