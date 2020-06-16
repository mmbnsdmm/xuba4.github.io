<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\db\AdminAuthItem;
use wodrow\yii2wtools\tools\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \admin\modules\user\models\forms\UserRoles */
/* @var $form \kartik\form\ActiveForm */

?>
<div class="user-alloc-roles">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'role_names')->checkboxList(ArrayHelper::map(AdminAuthItem::getAllRoles(), 'name', "name")) ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', '分配权限'), ['class' => 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
