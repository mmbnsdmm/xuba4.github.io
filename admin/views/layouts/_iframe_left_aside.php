<?php
use wodrow\yii2wtools\tools\JsBlock;
use yii\helpers\Url;

$items = [
    ['order' => -1000, 'isHeader' => true, 'id' => -1, 'text' => "菜单"],
];
if (YII_ENV_DEV){
    $items[] = ['icon' => 'fa fa-file-code-o', 'url' => Url::to(['/gii']), 'id' => -2, 'text' => "Gii", 'targetType' => "blank", 'urlType' => "abosulte"];
    $items[] = ['icon' => 'fa fa-dashboard', 'url' => '/debug', 'id' => -3, 'text' => "Dubeg", 'targetType' => "iframe-tab", 'urlType' => "relative"];
}
$mdm_items = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id, null, function ($menu){
    $item = [];
    $item['id'] = $menu['id'];
    $item['text'] = $menu['name'];
    $item['url'] = $menu['route'];
    $options = json_decode($menu['data'], true);
    if ($menu['children'])$item['children'] = $menu['children'];
    $item['icon'] = isset($options['icon'])?"fa fa-{$options['icon']}":"fa fa-circle-o";
    $item['targetType'] = isset($menu['targetType'])?$menu['targetType']:"iframe-tab";
    $item['urlType'] = isset($item['urlType'])?$item['urlType']:"relative";
    return $item;
});
$items = \yii\helpers\ArrayHelper::merge($items, $mdm_items);
?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="http://via.placeholder.com/160" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
    $(function () {
        App.setbasePath("<?=Yii::getAlias('@static_url/iframe-adminlte/') ?>");
        App.setGlobalImgPath("dist/img/");
        addTabs({
            id: '-4',
            title: '信息概览',
            close: false,
            url: "<?=Url::to(['/site/info']) ?>",
            targetType: "iframe-tab",
            icon: "fa fa-circle-o",
            urlType: "abosulte"
        });
        App.fixIframeCotent();
        let menus = <?=json_encode($items, JSON_UNESCAPED_UNICODE) ?>;
        $('.sidebar-menu').sidebarMenu({data: menus});
    });
</script>
<?php JsBlock::end(); ?>
