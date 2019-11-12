<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\LogRealnameValidate */
?>
<div class="log-realname-validate-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'enableEditMode' => false,
        'panel' => [
            'heading' => "详细",
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'idcard',
            'name',
            'idcard_f',
            'idcard_b',
            'idcard_invalid_date',
            'created_by',
            'created_at',
            'status',
            'replyed_by',
            'replyed_at',
            'replyed_msg',
        ],
    ]) ?>

</div>
