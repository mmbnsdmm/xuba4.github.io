<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\modules\article\models\forms\Article */
/* @var $form \kartik\form\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->statusDesc) ?>

    <?= $form->field($model, 'get_password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_boutique')->dropDownList($model->isBoutiqueDesc) ?>

    <?= $form->field($model, 'create_type')->dropDownList($model->createTypeDesc) ?>

    <?= $form->field($model, 'min_level')->textInput() ?>

    <?= $form->field($model, 'min_integral')->textInput() ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '新建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
