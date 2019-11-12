<?php
use wodrow\yii2wtools\rewrite\dmstr\Menu;
$items = [
    ['label' => 'Menu Yii2', 'options' => ['class' => 'header'], 'order' => -1000],
    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'visible' => YII_ENV_DEV?true:false],
    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => YII_ENV_DEV?true:false],
];

$mdm_items = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id, null, function ($menu){
    $item = [];
    $item['label'] = $menu['name'];
    $item['url'] = [$menu['route']];
    $options = json_decode($menu['data'], true);
    $item['options'] = $options?$options:[];
    $item['items'] = $menu['children'];
    $item['order'] = $menu['order'];
    return $item;
});
$items = \yii\helpers\ArrayHelper::merge($items, $mdm_items);
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
