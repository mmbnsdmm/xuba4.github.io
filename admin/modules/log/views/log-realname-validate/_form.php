<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\LogRealnameValidate */
/* @var $form \kartik\form\ActiveForm */
?>

<div class="log-realname-validate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard_f')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard_b')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard_invalid_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'replyed_by')->textInput() ?>

    <?= $form->field($model, 'replyed_at')->textInput() ?>

    <?= $form->field($model, 'replyed_msg')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
