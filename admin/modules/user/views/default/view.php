<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
?>
<div class="user-view">
    <div class="row">
        <div class="col-sm-12"><?= $this->render("_detail-view", ['model' => $model]) ?></div>
        <div class="col-sm-12"></div>
    </div>
</div>
