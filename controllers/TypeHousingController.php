<?php

namespace app\controllers;

use Yii;
use app\models\TypeHousing;
use app\models\TypeHousingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TypeHousingController implements the CRUD actions for TypeHousing model.
 */
class TypeHousingController extends Controller
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
     * Lists all TypeHousing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeHousingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TypeHousing model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TypeHousing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TypeHousing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TypeHousing model.
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
     * Deletes an existing TypeHousing model.
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
     * Finds the TypeHousing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TypeHousing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TypeHousing::findOne($id)) !== null) {
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
    
    public function actionGetTypeHousing()
    {
    	Yii::$app->response->format = 'json';
    
    	$model=TypeHousing::find()->asArray()->all();
    			//$model=CategoryService::find()->select(['category_service.id','description','status','icon'])->where(['status'=>'1'])->joinwith('service')->asArray()->all();
    			 
       
	    	if($model!=null){
	    		$response["success"]=true;
	    		$response['data']=$model;
	    		return $response;
	    			
	    	}
	    	else{
	    		$response["success"]=false;
	    		$response["data"]=["message"=>"Lo sentimos, no hay tipos de direcciones"];
	    		return $response;
	    	}
    			 
    }
}
