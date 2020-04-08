<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-23
 * Time: 上午11:46
 */

namespace home\controllers;



use home\models\FormLogin;
use home\models\FormResetPassword;
use home\models\FormSignup;
use yii\captcha\CaptchaAction;
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
            'captcha' => [
                'class' => CaptchaAction::class,
                'maxLength' => 4, //生成的验证码最大长度
                'minLength' => 4  //生成的验证码最短长度
            ],
        ];
    }

    public function actionSignup()
    {
        $model = new FormSignup();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->signup()){
                return $this->redirect(['login']);
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string|\yii\web\Response
     * @throws
     */
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

    public function actionResetPassword()
    {
        $model = new FormResetPassword();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->resetPassword()){
                return $this->redirect(['login']);
            }
        }
        return $this->render('reset-password', ['model' => $model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}