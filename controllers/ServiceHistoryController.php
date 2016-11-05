<?php

namespace app\controllers;

use Yii;
use app\models\ServiceHistory;
use app\models\ServiceHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LogToken;
use app\models\VwServiceHistory;
use app\models\VwActualService;

/**
 * ServiceHistoryController implements the CRUD actions for ServiceHistory model.
 */
class ServiceHistoryController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all ServiceHistory models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ServiceHistorySearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single ServiceHistory model.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render ( 'view', [ 
				'model' => $this->findModel ( $id ) 
		] );
	}
	
	/**
	 * Creates a new ServiceHistory model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new ServiceHistory ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing ServiceHistory model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel ( $id );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Deletes an existing ServiceHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel ( $id )->delete ();
		
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the ServiceHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return ServiceHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = ServiceHistory::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	// Android
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction ( $action );
	}
	public function actionHistory() {
		Yii::$app->response->format = 'json';
		
		$token = Yii::$app->request->post ( "token", null );
		
		$model_token = LogToken::find ()->where ( [ 
				'token' => $token 
		] )->one ();
		
		// var_dump($searched);
		
		if ($model_token != null) {
			
			$model_history = VwServiceHistory::find ()->where ( [ 
					'user_id' => $model_token->FK_id_user 
			] )->asArray ()->all ();
			if ($model_history != null) {
				$response ["success"] = true;
				$response ['data'] = $model_history;
			} else {
				$response ["success"] = true;
				// $response["data"]=["message"=>"Lo sentimos, no hay historial"];
				$response ["data"] = null;
			}
		} else {
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => "Token inválido" 
			];
		}
		
		return $response;
	}
	public function actionActualService() {
		Yii::$app->response->format = 'json';
		
		$token = Yii::$app->request->post ( "token", null );
		
		$model_token = LogToken::find ()->where ( [ 
				'token' => $token 
		] )->one ();
		
		// var_dump($searched);
		
		if ($model_token != null) {
			
			$model_history = VwActualService::find ()->where ( [ 
					'user_id' => $model_token->FK_id_user 
			] )->asArray ()->all ();
			if ($model_history != null) {
				$response ["success"] = true;
				$response ['data'] = $model_history;
			} else {
				$response ["success"] = true;
				// $response["data"]=["message"=>"Lo sentimos, no hay historial"];
				$response ["data"] = null;
			}
		} else {
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => "Token inválido" 
			];
		}
		
		return $response;
	}
	public function actionPendingService()
	{
		Yii::$app->response->format = 'json';
		
		$token = Yii::$app->request->post ( "token", null );
		
		$model_token = LogToken::find ()->where ( [ 
				'token' => $token 
		] )->one ();
				
		if ($model_token == null)
		{
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => "Token inválido" 
			];

			return $response;
		}

		// Busca servicios que haya cancelado el especialista
		$model = ServiceHistory::find ()->select(['id','date','time','service_id'])->where ( [ 
				'state' => 1,
				'user_id' => $model_token->FK_id_user 
		] )->asArray()->all();
	
		foreach ($model as $service)
		{	
			$pay = ServiceHistory::findOne(['id'=>$service['id']])->getLastPay ();

			if ($pay == null) {
				$response ["success"] = true;
				$response ['data'] = $service;
			$response ['data'] ['price'] = ServiceHistory::findOne(['id'=>$service['id']])->getPrice ();
				$response ['data'] ['duration'] =  ServiceHistory::findOne(['id'=>$service['id']])->getDuration ();
				$response ['data'] ['service'] =  ServiceHistory::findOne(['id'=>$service['id']])->getServiceName ();
				return $response;
			}
			if ($pay->state == 0) {
				$response ["success"] = true;
				$response ['data'] = $service;
				$response ['data'] ['price'] = ServiceHistory::findOne(['id'=>$service['id']])->getPrice ();
				$response ['data'] ['duration'] =  ServiceHistory::findOne(['id'=>$service['id']])->getDuration ();
				$response ['data'] ['service'] =  ServiceHistory::findOne(['id'=>$service['id']])->getServiceName ();
				return $response;
			}
		}
		$response ["success"] = true;
		$response ['data'] = null;
		return $response;
	}
}
