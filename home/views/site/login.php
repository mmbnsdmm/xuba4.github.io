<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午5:13
 */
/**
 * @var \yii\web\View $this
 * @var \home\models\FormLogin1 $model
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
$this->title = "登录";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login1 ">
    <div class="row">
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'username')->textInput(['placeholder' => "用户名"]) ?>
            <?=$form->field($model, 'password')->passwordInput() ?>
            <?=Html::submitButton("登录", ['class' => "btn btn-primary"]) ?>
            <?php ActiveForm::end(); ?>
            <p>
                忘记密码？请<?=Html::a('重置密码', ['/site/reset-password']) ?>。你也可以<?=Html::a('验证码快速登录', ['site/login2']) ?>。
            </p>
        </div>
    </div>
</div>
