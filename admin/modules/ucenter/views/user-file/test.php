<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserFile */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="user-file-test">

    <div class="user-file-form">

        <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extension')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mime_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relation_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yii_alias_uploads_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yii_alias_uploads_root')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>


        <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton("test", ['class' => 'btn btn-primary']) ?>
        </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
