<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<header class="main-header">
    <!-- Logo -->
    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?=Yii::$app->urlManagerHome->baseUrl ?>" target="_blank">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="http://via.placeholder.com/160" class="user-image" alt="User Image">
                        <span class="hidden-xs">Alexander Pierce</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="http://via.placeholder.com/160" class="img-circle" alt="User Image">

                            <p>
                                Alexander Pierce - Web Developer
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <?= Html::button(
                                        '个人中心',
                                        ['class' => 'btn btn-default btn-flat', 'onclick' => "addTabs({id:'-10',title: '个人中心',close: true,url: '/ucenter/profile',urlType: 'relative'})"]
                                    ) ?>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <?= Html::button(
                                        '清理缓存',
                                        ['class' => 'btn btn-default btn-flat', 'onclick' => "addTabs({id:'-11',title: '清理缓存',close: true,url: '/site/clean-cache',urlType: 'relative'})"]
                                    ) ?>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <?= Html::a(
                                        '退出',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'data-confirm' => "确认退出?", 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>