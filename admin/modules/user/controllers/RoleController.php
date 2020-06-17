<?php

namespace admin\modules\user\controllers;

use admin\modules\user\models\forms\RoleAlloc;
use Yii;
use common\models\db\AdminAuthItem;
use admin\modules\user\models\searchs\AdminAuthItem as AdminAuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\grid\EditableColumnAction;

/**
 * RoleController implements the CRUD actions for AdminAuthItem model.
 */
class RoleController extends Controller
{
    public function actions()
    {
        return [
            'editable-edit' => [
                'class' => EditableColumnAction::class,
                'modelClass' => AdminAuthItemSearch::class,                // the model for the record being edited
                'scenario' => AdminAuthItemSearch::SCENARIO_EDITABLE,
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return (int)$model->$attribute / 100;      // return any custom output value if desired
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return '';                                  // any custom error to return after model save
                },
                'showModelErrors' => true,                        // show model validation errors after save
                'errorOptions' => ['header' => ''],              // error summary HTML options
                'postOnly' => true,
                'ajaxOnly' => true,
                // 'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminAuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AdminAuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single AdminAuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title' => "AdminAuthItem({$id})",
                    'content' => $this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('编辑', ['update','id' => $id], ['class' => 'btn btn-primary','role' => 'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new AdminAuthItem model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new AdminAuthItem();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => "新建 AdminAuthItem",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('保存', ['class' => 'btn btn-primary','type' => "submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new AdminAuthItem",
                    'content' => '<span class="text-success">Create AdminAuthItem success</span>',
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('新建更多', ['create'], ['class' => 'btn btn-primary','role' => 'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title' => "新建 AdminAuthItem",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('保存', ['class' => 'btn btn-primary','type' => "submit"])
        
                ];         
            }
        }else{
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing AdminAuthItem model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => "修改 AdminAuthItem({$id})",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('保存', ['class' => 'btn btn-primary','type' => "submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "AdminAuthItem #".$id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('编辑', ['update','id' => $id], ['class' => 'btn btn-primary','role' => 'modal-remote'])
                ];    
            }else{
                 return [
                    'title' => "修改 AdminAuthItem #".$id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('保存', ['class' => 'btn btn-primary','type' => "submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing AdminAuthItem model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $type = 'hard')
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        switch($type){
            case 'hard':
                $model->delete();
                break;
            default:
                break;
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing AdminAuthItem model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulkdelete($type = 'hard')
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            switch($type){
                case 'hard':
                    $model->delete();
                    break;
                default:
                    break;
            }
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionAlloc($id)
    {
        $request = Yii::$app->request;
        $model = new RoleAlloc();
        $model->role_id = $id;
        $model->alloc_roles = AdminAuthItem::getChildsMapByRole($id, AdminAuthItem::GET_TYPE_ROLES);
        $model->alloc_permissions = AdminAuthItem::getChildsMapByRole($id, AdminAuthItem::GET_TYPE_PERMISSIONS);
        $model->alloc_routes = AdminAuthItem::getChildsMapByRole($id, AdminAuthItem::GET_TYPE_ROUTES);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => "分配({$id})",
                    'content' => $this->renderAjax('alloc', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]) .
                        Html::button('保存', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }elseif($model->load($request->post()) && $model->validate()){
                $model->alloc();
                return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
            }else{
                return [
                    'title' => "分配({$id})",
                    'content' => $this->renderAjax('alloc', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                        Html::button('保存', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }
        }else{
            if ($model->load($request->post())) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                return $this->render('alloc', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionTest($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                'title' => "test AdminAuthItem({$id})",
                    'content' => $this->renderAjax('test', [
                    'model' => $model,
                ]),
                'footer' =>
                    Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                    Html::button('test', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }elseif($model->load($request->post()) && $model->validate()){
                # TO DO test
                return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
            }else{
                return [
                    'title' => "test AdminAuthItem({$id})",
                    'content' => $this->renderAjax('test', [
                    'model' => $model,
                ]),
                'footer' =>
                    Html::button('关闭', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                    Html::button('test', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }
        }else{
            if ($model->load($request->post())) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                return $this->render('test', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete multiple existing AdminAuthItem model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulktest()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' ));
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            # TO DO
        }
        if($request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the AdminAuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AdminAuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminAuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
