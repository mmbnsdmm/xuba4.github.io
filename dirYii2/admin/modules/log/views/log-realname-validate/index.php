<?php

use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\SerialColumn;
use kartik\grid\EditableColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\ExpandRowColumn;
use kartik\grid\EnumColumn;
use kartik\grid\ActionColumn;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use wodrow\wajaxcrud\CrudAsset;
use wodrow\wajaxcrud\BulkButtonWidget;
use wodrow\yii2wtools\tools\JsBlock;

/* @var $this yii\web\View */
/* @var $searchModel admin\modules\log\models\LogRealnameValidateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Log Realname Validates');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$this->registerCssFile('https://cdn.bootcss.com/baguettebox.js/1.10.0/baguetteBox.min.css');
$this->registerJsFile('https://cdn.bootcss.com/baguettebox.js/1.10.0/baguetteBox.min.js');

?>
<div class="log-realname-validate-index">
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
                [
                    'class' => ExpandRowColumn::class,
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return $this->render('view', ['model' => $model]);
                    },
                    'expandOneOnly' => true,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "id",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "idcard",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "name",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => 'idcard_f',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'mergeHeader' => true,
                    'enableSorting' => false,
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::a(Html::img($m->idcard_f, ['alt' => '缩略图', 'width' => 120]), $m->idcard_f);
                    },
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => 'idcard_b',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'mergeHeader' => true,
                    'enableSorting' => false,
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::a(Html::img($m->idcard_b, ['alt' => '缩略图', 'width' => 120]), $m->idcard_b);
                    },
                ],
                [
                    'class' => EditableColumn::class,
                    'attribute' => "idcard_invalid_date",
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
                    'attribute' => "created_by",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "created_at",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "created_at",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "left",
                            'timePicker' => true,
                            'timePickerIncrement' => 30,
                            'locale' => [
                                'format' => "Y-m-d H:i",
                                'cancelLabel' => "清除",
                            ],
                        ],
//                        'useWithAddon' => true,
//                        'presetDropdown' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                    ]),
                ],
                [
                    'class' => EnumColumn::class,
                    'attribute' => "status",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => \common\models\db\LogRealnameValidate::getStatus(),
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "replyed_by",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "replyed_at",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "replyed_at",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "left",
                            'timePicker' => true,
                            'timePickerIncrement' => 30,
                            'locale' => [
                                'format' => "Y-m-d H:i",
                                'cancelLabel' => "清除",
                            ],
                        ],
//                        'useWithAddon' => true,
//                        'presetDropdown' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                    ]),
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "replyed_msg",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'contentOptions' => [
                        'style' => [
                            'overflow' => "hidden",
                            'text-overflow' => "ellipsis",
                            'white-space' => "nowrap",
                            'table-layout' => "fixed",
                            'max-width' => "480px",
                        ],
                    ],
                ],
                [
                    'class' => ActionColumn::class,
                    'dropdown' => false,
                    'vAlign' => 'middle',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return Url::to([$action, 'id' => $key, 'type' => "soft"]);
                    },
                    'viewOptions' => ['role' => "modal-remote", 'title' => "View", 'data-toggle' => "tooltip"],
                    'updateOptions' => ['role' => 'modal-remote', 'title' => "Update", 'data-toggle' => "tooltip"],
                    'deleteOptions' => [
                        'role' => 'modal-remote',
                        'title' => "Delete",
                        'data-confirm' => false,
                        'data-method' => false, // for overide yii data api
                        'data-request-method' => "post",
                        'data-toggle' => "tooltip",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除本条数据吗?",
                    ],
                ],
                [
                    'class' => DataColumn::class,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'label' => '更多操作',
                    'format' => 'raw',
                    'mergeHeader' => true,
                    'value' => function ($m) {
                        return $m->status == \common\models\db\LogRealnameValidate::STATUS_SENDING ? Html::a(\kartik\icons\Icon::show('check-square'), ['agree', 'id' => $m->id], [
                                'title' => '通过',
                                'role' => 'modal-remote',
                                'data-toggle' => 'tooltip',
                            ]) . Html::a(\kartik\icons\Icon::show('minus-square'), ['reject', 'id' => $m->id], [
                                'title' => '拒绝',
                                'role' => 'modal-remote',
                                'data-toggle' => 'tooltip',
                            ]) : '';
                    },
                ],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                        ['role' => "modal-remote", 'title' => "Create new Log Realname Validates", 'class' => "btn btn-default"]) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                        ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => "Reset Grid"]) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary",
                'heading' => "<i class=\"glyphicon glyphicon-list\"></i> Log Realname Validates 列表",
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('yii', '(not set)') . "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                        'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i> 删除选择',
                            ["bulkdelete", 'type' => "soft"],
                            [
                                "class" => "btn btn-danger btn-xs",
                                'role' => "modal-remote-bulk",
                                'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                'data-request-method' => "post",
                                'data-confirm-title' => "删除数据提示!",
                                'data-confirm-message' => "你确认要删除这些数据吗?"
                            ]),
                    ]) .
                    '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    'id' => "ajaxCrudModal",
    'footer' => "", // always need it for jquery plugin
]); ?>
<?php Modal::end(); ?>

<?php JsBlock::begin() ?>
<script>
    $(function () {
        baguetteBox.run('.gvRowBaguetteBox', {
            animation: 'fadeIn'
        });
    })
</script>
<?php JsBlock::end() ?>
