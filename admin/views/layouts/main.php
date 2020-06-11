<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use kartik\icons\Icon;
use admin\assets\Admin;
use dmstr\widgets\Alert;

Icon::map($this);
$_path = Admin::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        let ApiBaseUri = "<?= Yii::$app->apiTool->getFullUrl() ?>";
        <?php if(!Yii::$app->user->isGuest): ?>
        <?php $authReturn = Yii::$app->apiTool->authReturn(Yii::$app->user->identity); ?>
        let UserInfo = <?=json_encode($authReturn, JSON_UNESCAPED_UNICODE) ?>;
        <?php else: ?>
        let UserInfo = {};
        <?php endif; ?>
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="@static_url/iframe-adminlte/plugins/ie9/html5shiv.min.js"></script>
    <script src="@static_url/iframe-adminlte/plugins/ie9/respond.min.js"></script>
    <![endif]-->
</head>
<body class="">
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container-fluid" id="main-container">
        <?= Alert::widget() ?>
        <?=$this->render('_section') ?>
        <section class="content">
            <?=$content ?>
        </section>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
