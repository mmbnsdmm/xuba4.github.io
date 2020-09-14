<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;


/* @var $this yii\web\View */
/* @var $model admin\modules\article\models\forms\UploadFileArticle */

?>
<div class="article-create">
    <div class="article-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?php /* $form->field($model, 'txts[]')->widget(FileInput::class, [
            'pluginOptions' => [
                'initialPreviewAsData'=>true,
                'initialCaption'=>"txt文件",
                'overwriteInitial'=>false,
                'showUpload' => false,
                'maxFileSize'=>50*1024*1024,
                'initialPreviewConfig' => [
                    'caption' => 'txt文件'
                ],
            ],
            'options' => ['multiple' => true],
        ])*/ ?>

        <?php echo $form->field($model, 'txts[]')->fileInput(['multiple' => true]) ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '新建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
