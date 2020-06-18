<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\adminAuthRule */
/* @var $form \kartik\form\ActiveForm */
?>

<div class="admin-auth-rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textarea() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
