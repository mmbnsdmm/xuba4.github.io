<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
?>

<?php $this->beginContent('@home/views/layouts/base.php'); ?>

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'encodeLabels' => false,
]) ?>
<?= Alert::widget() ?>

<?= $content ?>

<?php $this->endContent() ?>