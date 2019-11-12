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
use wodrow\yii2wtools\enum\Status;

/* @var $this yii\web\View */
/* @var $searchModel admin\modules\log\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="log-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
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
                    'attribute' => "level",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "category",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "log_time",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "log_time",
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
                    'attribute' => "prefix",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                [
                    'class' => DataColumn::class,
                    'attribute' => "message",
                ],
                [
                    'class' => ActionColumn::class,
                    'dropdown' => false,
                    'vAlign' => 'middle',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return Url::to([$action, 'id' => $key, 'type' => "hard"]);
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
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                        ['role' => "modal-remote", 'title' => "Create new Logs", 'class' => "btn btn-default"]) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                        ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => "Reset Grid"]) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary",
                'heading' => "<i class=\"glyphicon glyphicon-list\"></i> Logs 列表",
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('yii', '(not set)') . "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                        'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i> 删除选择',
                            ["bulkdelete", 'type' => "hard"],
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
