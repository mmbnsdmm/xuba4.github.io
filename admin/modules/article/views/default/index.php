<?php
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\SerialColumn;
use kartik\grid\EditableColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\ExpandRowColumn;
use kartik\grid\EnumColumn;
use kartik\grid\ActionColumn;
use kartik\grid\FormulaColumn;
use kartik\daterange\DateRangePicker;
use wodrow\wajaxcrud\rangecolumn\RangeColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use wodrow\wajaxcrud\CrudAsset;
use wodrow\wajaxcrud\BulkButtonWidget;
use wodrow\yii2wtools\tools\JsBlock;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel admin\modules\article\models\searchs\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="article-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'rowOptions' => [
                'class' => 'gvRowBaguetteBox',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsive' => true,
            'showPageSummary' => true,
            'pjax' => true,
            'hover' => true,
            'striped' => true,
            'condensed' => true,
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'width' => "20px",
                ],
                [
                    'class' => SerialColumn::class,
                    'width' => "40px",
                    'pageSummary' => "合计",
                ],
                /*[
                    'class' => ExpandRowColumn::class,
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return $this->render('view', ['model' => $model]);
                    },
                    'expandOneOnly' => true,
                ],*/
                [
                    'class' => DataColumn::class,
                    'attribute' => "id",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => EditableColumn::class,
                    'attribute' => "title",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'readonly' => function ($model, $key, $index, $widget) {
                        return false;
                    },
                    'editableOptions' => function ($model, $key, $index, $widget) {
                        return [
                            'header' => "修改",
                            'size' => "md",
                            'formOptions' => ['action' => ['editable-edit']],
                        ];
                    },
                    'refreshGrid' => true,
                ],
//                [
//                    'class' => DataColumn::class,
//                    'attribute' => "content",
//                    'hAlign' => GridView::ALIGN_CENTER,
//                    'vAlign' => GridView::ALIGN_MIDDLE,
//                ],
                [
                    'class' => EnumColumn::class,
                    'attribute' => "status",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => $searchModel->statusDesc,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "created_at",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "created_at",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "center",
                            'timePicker' => true,
                            'timePicker24Hour' => true,
                            'timePickerSeconds' => true,
                            'showWeekNumbers' => true,
                            'showDropdowns' => true,
                            'timePickerIncrement' => 1,
                            'locale' => [
                                'format' => "Y-m-d H:i:s",
                                'applyLabel' => "确认",
                                'cancelLabel' => "清除",
                                'fromLabel' => "开始时间",
                                'toLabel' => "结束时间",
                                'daysOfWeek' => ["日","一","二","三","四","五","六"],
                                'monthNames' => ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
                            ],
                        ],
                        'presetDropdown' => true,
                        'autoUpdateOnInit' => false,
                        'useWithAddon' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                        'pluginEvents' => [
                            'cancel.daterangepicker' => new JsExpression("function(ev, picker) {let e13=$.Event('keydown');e13.keyCode=13;let _input=$(this);if(!$(this).is('input')){_input=$(this).parent().find('input:hidden');}_input.val('').trigger(e13);}"),
                        ],
                    ]),
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "updated_at",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "updated_at",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "center",
                            'timePicker' => true,
                            'timePicker24Hour' => true,
                            'timePickerSeconds' => true,
                            'showWeekNumbers' => true,
                            'showDropdowns' => true,
                            'timePickerIncrement' => 1,
                            'locale' => [
                                'format' => "Y-m-d H:i:s",
                                'applyLabel' => "确认",
                                'cancelLabel' => "清除",
                                'fromLabel' => "开始时间",
                                'toLabel' => "结束时间",
                                'daysOfWeek' => ["日","一","二","三","四","五","六"],
                                'monthNames' => ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
                            ],
                        ],
                        'presetDropdown' => true,
                        'autoUpdateOnInit' => false,
                        'useWithAddon' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                        'pluginEvents' => [
                            'cancel.daterangepicker' => new JsExpression("function(ev, picker) {let e13=$.Event('keydown');e13.keyCode=13;let _input=$(this);if(!$(this).is('input')){_input=$(this).parent().find('input:hidden');}_input.val('').trigger(e13);}"),
                        ],
                    ]),
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "created_by",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "createdBy.nickName",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => EditableColumn::class,
                    'attribute' => "get_password",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'readonly' => function ($model, $key, $index, $widget) {
                        return false;
                    },
                    'editableOptions' => function ($model, $key, $index, $widget) {
                        return [
                            'header' => "修改",
                            'size' => "md",
                            'formOptions' => ['action' => ['editable-edit']],
                        ];
                    },
                    'refreshGrid' => true,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "min_level",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "min_integral",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => EnumColumn::class,
                    'attribute' => "is_boutique",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => $searchModel->isBoutiqueDesc,
                ],
                [
                    'class' => EnumColumn::class,
                    'attribute' => "create_type",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => $searchModel->createTypeDesc,
                ],
                [
                    'class' => ActionColumn::class,
                    'dropdown' => false,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to([$action,'id' => $key, 'type' => "soft"]);
                    },
                    'viewOptions' => ['role' => "modal-remote", 'title' => "View",'data-toggle' => "tooltip"],
                    'updateOptions' => ['role' => 'modal-remote', 'title' => "Update", 'data-toggle' => "tooltip"],
                    'deleteOptions' => [
                        'role' => 'modal-remote',
                        'title' => "删除",
                        'data-confirm' => false,
                        'data-method' => false, // for overide yii data api
                        'data-request-method' => "post",
                        'data-toggle' => "tooltip",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除本条数据吗?",
                    ],
                ],
                [
                    'class' => ActionColumn::class,
                    'header' => '其他操作',
                    'template' => '{test}',
                    'mergeHeader' => true,
                    'buttons' => [
                        'test' => function ($url, $model, $key) {
                            return Html::a('Test', $url, [
                                'title' => Yii::t('yii', 'Test'),
                                'aria-label' => Yii::t('yii', 'Test'),
                                'data-pjax' => '0',
                                'role' => 'modal-remote',
                                'data-toggle' => 'tooltip',
                            ]);
                        },
                    ],
                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                ],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role' => "modal-remote", 'title' => "新建 Articles", 'class' => "btn btn-default"]).
                    Html::a('<i class="glyphicon glyphicon-open-file"></i>', ['create-from-file'],
                        ['role' => "modal-remote", 'title' => "新建 Articles", 'class' => "btn btn-default"]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => "Reset Grid"]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary", 
                'heading' => "<i class=\"glyphicon glyphicon-list\"></i> Articles 列表",
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('yii', '(not set)'). "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i> 删除选择', ["bulkdelete", 'type' => "soft"], [
                        "class" => "btn btn-danger btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => "post",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除这些数据吗?"
                    ])." ".
                    Html::a('<i class="glyphicon glyphicon-wrench"></i> test选择', ["bulktest"], [
                        "class" => "btn btn-warning btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,
                        'data-request-method' => "post",
                        'data-confirm-title' => "test数据提示!",
                        'data-confirm-message' => "你确认要test这些数据吗?"
                    ]),
                ]).
                '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    'id' => "ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    'footer' => "", // always need it for jquery plugin
]); ?>
<?php Modal::end(); ?>

<?php JsBlock::begin(); ?>
<script>
$(function () {
    baguetteBox.run(".gvRowBaguetteBox", {
        animation: "fadeIn"
    });
})
</script>
<?php JsBlock::end(); ?>
