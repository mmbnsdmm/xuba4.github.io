<?php
/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use kartik\icons\Icon;

\home\assets\HomeAsset::register($this);
Icon::map($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
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
</head>
<body class="vue-body">
<?php $this->beginBody() ?>

<div class="wrap">
    <?=$this->render('_nav_header') ?>
    <div class="container" id="main-container">
        <?=$content ?>
    </div>
</div>

<footer class="footer">
    <div class="container" id="main_footer">
        <?=Yii::powered() ?>
        <?=Html::a('如何对接api', ['/public/how-to-use-api']) ?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
