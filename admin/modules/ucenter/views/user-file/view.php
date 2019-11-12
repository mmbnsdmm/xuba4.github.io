<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserFile */
?>
<div class="user-file-view">
 
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
            'filename',
            'extension',
            'mime_type',
            'relation_path',
            'yii_alias_uploads_path',
            'yii_alias_uploads_root',
            'size',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'status',
        ],
    ]) ?>

</div>
