<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use kartik\icons\Icon;
use admin\assets\IframeAdmin;

Icon::map($this);
$_path = IframeAdmin::register($this);
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

    <!--http://aimodu.org:7777/admin/index_iframe.html?q=audio&search=#-->
    <style type="text/css">
        html {
            overflow: hidden;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render('_iframe_header.php') ?>
    <?= $this->render('_iframe_left_aside.php') ?>
    <?= $this->render('_iframe_tabs.php') ?>
    <?= $this->render('_iframe_footer') ?>
    <?= $this->render('_iframe_right_aside') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
