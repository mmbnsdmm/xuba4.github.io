<?php
/**
 * @var \yii\web\View $this
 */

use common\models\db\AdminAuthAssignment;
use kartik\helpers\Html;
use kartik\form\ActiveForm;

$this->title = "清理缓存";

$this->params['breadcrumbs'][] = ['label' =>  $this->title];
$hasJurisdiction = AdminAuthAssignment::findOne(['user_id' => Yii::$app->user->id, 'item_name' => Yii::$app->params['adminRoleAdminUserName']])?true:false;
?>

<div id="site-clean-cache">
    <div class="row">
        <div class="col-sm-12">
            <?php if ($hasJurisdiction): ?>
                <?php ActiveForm::begin(); ?>
                <?=Html::submitButton("清理所有缓存", ['class' => "btn btn-warning"]) ?>
                <?php ActiveForm::end(); ?>
            <?php else: ?>
            <p>
                你不是管理员，没有权限进行该操作
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>


