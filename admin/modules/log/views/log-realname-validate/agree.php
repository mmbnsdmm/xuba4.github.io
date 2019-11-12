<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 18-8-26
 * Time: 上午10:30
 */
/* @var $this yii\web\View */
/**
 * @var \common\models\db\LogRealnameValidate $model
 */
use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\form\ActiveForm;
use common\models\db\LogRealnameValidate;
?>

<div class="log-log-realname-validate-agree">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <?=Html::img($model->idcard_f, [
                        'class' => "img img-responsive",
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?=Html::img($model->idcard_b, [
                        'class' => "img img-responsive",
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <?=DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'idcard',
                    'idcard_invalid_date',
                ],
            ]) ?>
        </div>
        <div class="col-sm-12">
            <?php $form = ActiveForm::begin([
                'id' => "agree-form",
                'enableAjaxValidation' => true,
            ]); ?>
            <?= $form->field($model, 'status')->hiddenInput(['value' => LogRealnameValidate::STATUS_SUCCESS])->label(false) ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton("agree", ['class' => 'btn btn-primary']) ?>
                </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
