<?php
use wodrow\yii2wtools\tools\JsBlock;

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
            id: '10008',
            title: '百度',
            close: false,
            url: 'https://www.baidu.com',
            targetType: "iframe-tab",
            icon: "fa fa-circle-o",
            urlType: "abosulte"
        });
        App.fixIframeCotent();
        let menus = [
            {
                id: "9000",
                text: "header",
                icon: "",
                isHeader: true
            },
            {
                id: "9002",
                text: "Forms",
                icon: "fa fa-edit",
                children: [
                    {
                        id: "90023",
                        text: "editors",
                        url: "forms/editors_iframe.html",
                        targetType: "iframe-tab",
                        icon: "fa fa-circle-o"
                    },
                    {
                        id: "90024",
                        text: "百度",
                        url: "https://www.baidu.com",
                        targetType: "iframe-tab",
                        icon: "fa fa-circle-o",
                        urlType: 'abosulte'
                    }
                ]
            }
        ];
        $('.sidebar-menu').sidebarMenu({data: menus});
    });
</script>
<?php JsBlock::end(); ?>
