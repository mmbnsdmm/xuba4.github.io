<?php

use wodrow\yii2wtools\tools\ArrayHelper;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model admin\modules\app\models\forms\Version */
?>
<div class="version-_detail-view">

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
            'type',
            'v_id',
            'version',
            'is_force_update',
            'pkg_url:url',
            'wgt_url:url',
            'desc:ntext',
            'created_at',
            'status',
            'other_download_urls:ntext',
        ],
    ]) ?>

</div>