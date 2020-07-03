<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\modules\log\models\forms\Log */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="log-test">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div><?= $this->render("_form", ['model' => $model]) ?></div>
    </div>
</div>
