<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\AdminAuthItem */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="admin-auth-item-test">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div class="admin-auth-item-form-test">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'description')->textarea(['rows' => 6]) ?>
            <?=$form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'data')->textInput() ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
