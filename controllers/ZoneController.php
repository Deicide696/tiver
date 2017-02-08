<?php

namespace app\controllers;

use Yii;
use app\models\Zone;
use app\models\Vertex;
use app\models\ZoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LogToken;

/**
 * ZoneController implements the CRUD actions for Zone model.
 */
class ZoneController extends Controller
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
     * Lists all Zone models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model_v=Vertex::find()->select(['zone_id'])->distinct()->asArray()->all();
       foreach ($model_v as $zone) {
         $model_vortex=Vertex::find()->where(['zone_id'=> $zone['zone_id']])->asArray()->all();
         $model_vort[]=$model_vortex;
       }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
             'vertex'=>$model_vort,
        ]);
    }

    /**
     * Displays a single Zone model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model_vortex=Vertex::find()->where(['zone_id'=>$id])->asArray()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'vertex'=>$model_vortex,
        ]);
    }

    /**
     * Creates a new Zone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Zone();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Insertar vértices
            $vortex=json_decode(Yii::$app->request->post( "vortex", null ),true);
            foreach ($vortex as $vort) {
                $model_vortex=new Vertex();
                $model_vortex->zone_id=$model->id;
                $model_vortex->lat=$vort['lat'];
                $model_vortex->lng=$vort['lng'];
                $model_vortex->save();
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Zone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Insertar vértices
            $model_vortex=Vertex::deleteAll(['zone_id'=>$id]);

            $vortex=json_decode(Yii::$app->request->post( "vortex", null ),true);
            foreach ($vortex as $vort) {
                $model_vortex=new Vertex();
                $model_vortex->zone_id=$model->id;
                $model_vortex->lat=$vort['lat'];
                $model_vortex->lng=$vort['lng'];
                $model_vortex->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model_vortex=Vertex::find()->where(['zone_id'=>$id])->asArray()->all();
            return $this->render('update', [
                'model' => $model,
                'vertex'=>$model_vortex,
            ]);
        }
    }

    /**
     * Deletes an existing Zone model.
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
     * Finds the Zone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Zone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zone::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    //Android
    
    public function actionGetZone(){
    	Yii::$app->response->format = 'json';
    	$token = Yii::$app->request->post ( "token", "" );
    	
    	$model_token = LogToken::find ()
            ->where ([
                'token' => $token, 
                'status' => 1])
            ->one ();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }
        
    	$model=Zone::find()->joinWith('vertex')->asArray()->all();
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
    public function beforeAction($action) {
    	$this->enableCsrfValidation = false;
    	return parent::beforeAction ( $action );
    }
    
}
