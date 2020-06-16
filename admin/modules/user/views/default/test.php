<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="user-test">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div class="user-form-test">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'pwd_back')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'status')->textInput() ?>
            <?=$form->field($model, 'created_at')->textInput() ?>
            <?=$form->field($model, 'token')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'key')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'frozen')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'updated_at')->textInput() ?>
            <?=$form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
