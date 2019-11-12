<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
?>
<div class="user-view">
 
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
            'password',
            'pwd_back',
            'status',
            'created_at',
            'token',
            'key',
            'auth_key',
            'amount',
            'frozen',
            'deposit',
        ],
    ]) ?>

</div>
