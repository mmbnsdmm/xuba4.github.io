<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 18-8-29
 * Time: 下午3:30
 */

/* @var $this yii\web\View */

/* @var $model common\models\db\LogRealnameValidate */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\DetailView;
use common\models\db\LogRealnameValidate;

?>
<div class="log-log-realname-validate-reject">
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
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'status')->hiddenInput(['value' => LogRealnameValidate::STATUS_BACK])->label(false) ?>
            <?= $form->field($model, 'replyed_msg')->textarea(['rows' => 6]) ?>
            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton("reject", ['class' => 'btn btn-primary']) ?>
                </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>