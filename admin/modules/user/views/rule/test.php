<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\adminAuthRule */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="admin-auth-rule-test">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div class="admin-auth-rule-form-test">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'data')->textInput() ?>
            <?=$form->field($model, 'created_at')->textInput() ?>
            <?=$form->field($model, 'updated_at')->textInput() ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
