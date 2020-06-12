<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\Log */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="log-test">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div class="log-form-test">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'level')->textInput() ?>
            <?=$form->field($model, 'category')->textInput(['maxlength' => true]) ?>
            <?=$form->field($model, 'log_time')->textInput() ?>
            <?=$form->field($model, 'prefix')->textarea(['rows' => 6]) ?>
            <?=$form->field($model, 'message')->textarea(['rows' => 6]) ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
