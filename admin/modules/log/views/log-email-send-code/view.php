<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\LogEmailSendCode */
?>
<div class="log-email-send-code-view">
 
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
            'created_by',
            'created_at',
            'type',
            'from',
            'to',
            'subject',
            'code',
            'params:ntext',
            'status',
        ],
    ]) ?>

</div>
