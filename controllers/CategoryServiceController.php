<?php

namespace app\controllers;

use Yii;
use app\models\CategoryService;
use app\models\CategoryServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ServiceSearch;

/**
 * CategoryServiceController implements the CRUD actions for CategoryService model.
 */
class CategoryServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CategoryService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoryServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoryService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	
    	$searchModel = new ServiceSearch();
    	$dataProvider = $searchModel->search(['ServiceSearch'=>['category_service_id'=>$id]]);
    	//$dataProvider = $searchModel->search(['expert_id' => $id]);
    	
    	return $this->render ( 'view', [
    			'model' => $this->findModel ( $id ),
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	] );
    	
        
    }

    /**
     * Creates a new CategoryService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoryService();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
        	$model->status=1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CategoryService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CategoryService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoryService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoryService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoryService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
	    //Android
    public function beforeAction($action) {
    	$this->enableCsrfValidation = false;
    	return parent::beforeAction($action);
    }    
    
	    public function actionGetCategorias()
	    {
	    	Yii::$app->response->format = 'json';
	    	$model=CategoryService::find()->select(['category_service.id','category_service.description','category_service.status','category_service.icon'])->where(['category_service.status'=>'1'])->joinwith(['service'])->joinwith([ 'service.serviceHasModifier.modifier' =>
	    			
	    			function ($query) { $query->select(['id','name','description','price','tax','status','duration']);}
	    	])->asArray()->all();
	    	//$model=CategoryService::find()->select(['category_service.id','description','status','icon'])->where(['status'=>'1'])->joinwith('service')->asArray()->all();
	    
	    	if($model!=null){
	    		$response["success"]=true;
	    		$response['data']=$model;
	    		return $response;
	    			
	    	}
	    	else{
	    		$response["success"]=false;
	    		$response["data"]=["message"=>"Lo sentimos, no hay categorias"];
	    		return $response;
	    	}
	    	
	    
	    }
}
