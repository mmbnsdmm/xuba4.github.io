<?php

namespace admin\modules\ucenter\controllers;

use Yii;
use common\models\db\UserFile;
use admin\modules\ucenter\models\UserFileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\grid\EditableColumnAction;

/**
 * UserFileController implements the CRUD actions for UserFile model.
 */
class UserFileController extends Controller
{
    public function actions()
    {
        return [
            'editable-edit' => [
                'class' => EditableColumnAction::class,
                'modelClass' => UserFileSearch::class,                // the model for the record being edited
                'scenario' => UserFileSearch::SCENARIO_EDITABLE,
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
     * Lists all UserFile models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new UserFileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single UserFile model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title' => "UserFile #".$id,
                    'content' => $this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('Edit', ['update','id' => $id], ['class' => 'btn btn-primary','role' => 'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new UserFile model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new UserFile();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => "Create new UserFile",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('Save', ['class' => 'btn btn-primary','type' => "submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new UserFile",
                    'content' => '<span class="text-success">Create UserFile success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary','role' => 'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title' => "Create new UserFile",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('Save', ['class' => 'btn btn-primary','type' => "submit"])
        
                ];         
            }
        }else{
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing UserFile model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
                    'title' => "Update UserFile #".$id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('Save', ['class' => 'btn btn-primary','type' => "submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "UserFile #".$id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                            Html::a('Edit', ['update','id' => $id], ['class' => 'btn btn-primary','role' => 'modal-remote'])
                ];    
            }else{
                 return [
                    'title' => "Update UserFile #".$id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                                Html::button('Save', ['class' => 'btn btn-primary','type' => "submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing UserFile model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
            case 'soft':
                # TO DO soft delete
                $model->save();
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
     * Delete multiple existing UserFile model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
                case 'soft':
                    # TO DO soft delete
                    $model->save();
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

    public function actionTest($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                'title' => "test UserFile #".$id,
                    'content' => $this->renderAjax('test', [
                    'model' => $model,
                ]),
                'footer' =>
                    Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                    Html::button('test', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }elseif($model->load($request->post()) && $model->validate()){
                # TO DO test
                return ['forceClose' => true,'forceReload' => '#crud-datatable-pjax'];
            }else{
                return [
                    'title' => "test UserFile #".$id,
                    'content' => $this->renderAjax('test', [
                    'model' => $model,
                ]),
                'footer' =>
                    Html::button('Close', ['class' => 'btn btn-default pull-left','data-dismiss' => "modal"]).
                    Html::button('test', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }
        }else{
            if ($model->load($request->post())) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('test', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Finds the UserFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
