<?php

namespace app\controllers;

use Yii;
use app\models\Modifier;
use app\models\ModifierSearch;
use app\models\ServiceHasModifier;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModifierController implements the CRUD actions for Modifier model.
 */
class ModifierController extends Controller
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
     * Lists all Modifier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModifierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Modifier model.
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
     * Creates a new Modifier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Modifier();
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	//var_dump(Yii::$app->request->post('Modifier')['service_id']);exit();
        	$model_relation=new ServiceHasModifier();
        	$model_relation->service_id=Yii::$app->request->post('Modifier')['service_id'];
        	$model_relation->modifier_id=$model->id;
        	if($model_relation->save()){
        		return $this->redirect(['view', 'id' => $model->id]);
        	}
        	else{var_dump( $model_relation->errors);}
        	
       
        } else {
        	$model->status=1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Modifier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$model_relation=ServiceHasModifier::find()->where(["modifier_id"=>$model->id])->one();
        	$model_relation->service_id=Yii::$app->request->post('Modifier')['service_id'];
        	if($model_relation->save()){
        		   return $this->redirect(['view', 'id' => $model->id]);
        	}
        	else{var_dump( $model_relation->errors);}
        	
         
        } else {
        	$model_relation=ServiceHasModifier::find()->where(["modifier_id"=>$model->id])->one();
        	//var_dump($model_relation);
        	//exit();
        	$model->service_id=$model_relation->service_id;
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Modifier model.
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
     * Finds the Modifier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Modifier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Modifier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
