<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-4-30
 * Time: 上午9:01
 */

/**
 * @var \yii\web\View $this ;
 * @var \admin\models\FormLogin $model
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use admin\assets\Admin;

$this->title = "登录";

Admin::register($this);

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        let ApiBaseUri = "<?= Yii::$app->apiTool->getFullUrl() ?>";
        <?php if(!Yii::$app->user->isGuest): ?>
        <?php $authReturn = Yii::$app->apiTool->authReturn(Yii::$app->user->identity); ?>
        let UserInfo = <?=json_encode($authReturn, JSON_UNESCAPED_UNICODE) ?>;
        <?php else: ?>
        let UserInfo = {};
        <?php endif; ?>
    </script>
</head>
<body class="login-page">

<?php $this->beginBody() ?>

<div class="login-box">
    <div class="login-logo">
        <b>Admin</b>LTE
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎登陆</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => "用户名"]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <?= $form->field($model, 'code')->widget(Captcha::class, [
            'template' => '<div class="row"><div class="col-sm-7">{input}</div><div class="col-sm-5">{image}</div></div>',
            'options' => [
//                'class' => 'input-text size-L',
//                'style' => 'width:80px;',
                'placeholder' => '输入验证码',
            ],
            'imageOptions' => [
                'alt' => '点击换图',
                'title' => '点击换图',
                'style' => [
//                    'width' => "100px",
//                    'height' => "34px",
                    'cursor' => "pointer",
                ],
            ],
        ])->label(false) ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <?= Html::submitButton('立即登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
        <?php ActiveForm::end(); ?>
        <div class="social-auth-links text-center">
            <p>© <?=date("Y") ?></p>
        </div>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
