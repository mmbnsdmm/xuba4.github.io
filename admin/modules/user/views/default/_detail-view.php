<?php

use wodrow\yii2wtools\tools\ArrayHelper;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
?>
<div class="user-_detail-view">

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
            'username',
            'email:email',
//            'password',
//            'pwd_back',
            [
                'attribute' => "status",
                'value' => function()use($model){
                    return $model->statusDesc[$model->status];
                }
            ],
            'token',
            'key',
            'auth_key',
            'amount',
            'frozen',
            'created_at:datetime',
            'updated_at:datetime',
            'nickname',
            'avatar',
        ],
    ]) ?>

</div>