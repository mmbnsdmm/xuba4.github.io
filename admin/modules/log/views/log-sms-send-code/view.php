<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\LogSmsSendCode */
?>
<div class="log-sms-send-code-view">
 
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
            'created_at',
            'nation_code',
            'to_mobile',
            'sms_key',
            'code',
            'platform',
            'params:ntext',
            'result:ntext',
        ],
    ]) ?>

</div>
