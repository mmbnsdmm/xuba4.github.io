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
            'mobile',
            'password',
            'pwd_back',
            'status',
            'created_at',
            'idcard',
            'realname',
            'token',
            'key',
            'auth_key',
            'is_courier',
            'dot_belong',
            'avatar',
            'amount',
            'frozen',
            'deposit',
            'pay_password',
            'weixin_uuid',
            'alipay_uuid',
        ],
    ]) ?>

</div>
