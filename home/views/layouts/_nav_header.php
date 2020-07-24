<?php
/**
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
use wodrow\yii2wtools\tools\JsBlock;
?>

<div class="layouts-_nav_header">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- 导航头部 -->
            <div class="navbar-header">
                <!-- 移动设备上的导航切换按钮 -->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-example">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- 品牌名称或logo -->
                <?=Html::a("HOME", Url::home(), ['class' => "navbar-brand"]) ?>
            </div>
            <!-- 导航项目 -->
            <div class="collapse navbar-collapse navbar-collapse-example">
                <!-- 一般导航项目 -->
                <ul class="nav navbar-nav">
                    <!--                <li class="active">--><?//=Html::a("TEST", ['/test']) ?><!--</li>-->
                </ul>
                <!-- 右侧的导航项目 -->
                <ul class="nav navbar-nav navbar-right">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li class="<?=Yii::$app->controller->route == 'site/login'?'active':'' ?>"><?=Html::a("登录", ['/site/login']) ?></li>
                        <li class="<?=Yii::$app->controller->route == 'site/signup'?'active':'' ?>"><?=Html::a("注册", ['/site/signup']) ?></li>
                    <?php else: $user = Yii::$app->user->identity; ?>
                        <li class="<?=Yii::$app->controller->route == 'user/message/index'?'active':'' ?>"><?=Html::a(\kartik\icons\Icon::show('envelope-o').Html::tag('span', "", ['class' => "label label-warning", 'v-text' => "unread_messages"]), ['/user/message/index']) ?></li>
                        <li class="dropdown">
                            <a class="avatar dropdown-toggle" href="#" data-toggle="dropdown">
                                <?=$user->nickName ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="<?=substr(Yii::$app->controller->route, 0, 12) == 'user/profile'?'active':'' ?>"><?=Html::a('个人中心', ['/user/profile']) ?></li>
                                <li><?=Html::a('退出', ['/site/logout'], ['data-method' => "post"]) ?></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div><!-- END .navbar-collapse -->
        </div>
    </nav>
</div>

<?php JsBlock::begin(); ?>
    <script>
        var _nav_header = new Vue({
            el: ".layouts-_nav_header",
            data: {
                unread_messages: 0
            }
        });
    </script>
<?php JsBlock::end(); ?>