<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model admin\modules\app\models\forms\Version */
/* @var $form \kartik\form\ActiveForm */

$pkg_pluginOptions = [
    'initialPreviewAsData'=>true,
    'initialCaption'=>"安装包",
    'overwriteInitial'=>false,
    'showUpload' => false,
//    'maxFileSize'=>2800,
];
if ($model->pkg_url){
    $pkg_pluginOptions['initialPreview'] = [
        $model->pkg_url,
    ];
    $pkg_pluginOptions['initialPreviewConfig'] = [
        ['caption' => '安装包'],
    ];
}
$wgt_pluginOptions = [
    'initialPreviewAsData'=>true,
    'initialCaption'=>"升级包",
    'overwriteInitial'=>false,
    'showUpload' => false,
//    'maxFileSize'=>2800,
];
if ($model->wgt_url){
    $wgt_pluginOptions['initialPreview'] = [
        $model->wgt_url,
    ];
    $wgt_pluginOptions['initialPreviewConfig'] = [
        ['caption' => '升级包'],
    ];
}
?>

<div class="version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList($model->typeDesc) ?>

    <?= $form->field($model, 'v_id')->textInput() ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_force_update')->dropDownList($model->isForceUpdateDesc) ?>

    <?= $form->field($model, 'pkg')->widget(FileInput::class, [
        'pluginOptions' => $pkg_pluginOptions,
        'options' => ['multiple' => false],
    ]) ?>

    <?= $form->field($model, 'wgt')->widget(FileInput::class, [
        'pluginOptions' => $wgt_pluginOptions,
        'options' => ['multiple' => false],
    ]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->statusDesc) ?>

    <?= $form->field($model, 'other_download_urls')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '新建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
