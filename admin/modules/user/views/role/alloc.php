<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\db\AdminAuthItem;
use wodrow\yii2wtools\tools\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \admin\modules\user\models\forms\RoleAlloc */
/* @var $form \kartik\form\ActiveForm */

?>
<div class="user-alloc-roles">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'role_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, AdminAuthItem::GET_TYPE_ROLES)->checkboxList(ArrayHelper::map(AdminAuthItem::getAllRoles(), 'name', "name"), ['inline' => true]) ?>
        <?= $form->field($model, AdminAuthItem::GET_TYPE_PERMISSIONS)->checkboxList(ArrayHelper::map(AdminAuthItem::getAllPermissions(), 'name', "name"), ['inline' => true]) ?>
        <?= $form->field($model, AdminAuthItem::GET_TYPE_ROUTES)->checkboxList(ArrayHelper::map(AdminAuthItem::getAllRoutes(), 'name', "name"), ['inline' => true]) ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', '分配'), ['class' => 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
