<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午5:13
 */
/**
 * @var \yii\web\View $this
 * @var \home\models\FormLogin $model
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\captcha\Captcha;
$this->title = "登录";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login1 ">
    <div class="row">
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'username')->textInput(['placeholder' => "用户名"]) ?>
            <?=$form->field($model, 'password')->passwordInput() ?>
            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'code')->widget(Captcha::class, [
                        'options' => [
//                        'class' => 'input-text size-L',
                            'style' => 'width:80px;',
                            'placeholder' => '输入验证码',
                        ],
                        'imageOptions' => [
                            'class' => "pull-left",
                            'style' => 'width:100px;height:34px;',
                        ],
                    ])->label(false) ?>
                </div>
            </div>
            <?=Html::submitButton("登录", ['class' => "btn btn-primary"]) ?>
            <?php ActiveForm::end(); ?>
            <p>
                忘记密码？请<?=Html::a('重置密码', ['/site/reset-password']) ?>。
            </p>
        </div>
    </div>
</div>
