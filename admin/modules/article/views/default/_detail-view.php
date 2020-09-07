<?php

use wodrow\yii2wtools\tools\ArrayHelper;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model admin\modules\article\models\forms\Article */
?>
<div class="article-_detail-view">

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
            'title',
            'content:ntext',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'get_password',
            'min_level',
            'min_integral',
            'is_boutique',
            'create_type',
        ],
    ]) ?>

</div>