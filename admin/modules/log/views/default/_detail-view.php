<?php

use wodrow\yii2wtools\tools\ArrayHelper;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model admin\modules\log\models\forms\Log */
?>
<div class="log-_detail-view">

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
            'level',
            'category',
            'log_time',
            'prefix:ntext',
            'message:ntext',
        ],
    ]) ?>

</div>