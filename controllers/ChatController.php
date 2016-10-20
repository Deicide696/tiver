<?php

namespace app\controllers;

use Yii;
use app\models\Chat;
use app\models\ChatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LogToken;
use app\models\AssignedService;
//use app\pusher\Pusher;
require '../vendor/pusher/pusher-php-server/lib/Pusher.php';
use function yii\db\all;
use app\models\User;
use app\models\Expert;
use app\models\Conversation;

/**
 * ChatController implements the CRUD actions for Chat model.
 */
class ChatController extends Controller {
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
	 * Lists all Chat models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ChatSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Chat model.
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
	 * Creates a new Chat model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Chat ();
		
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
	 * Updates an existing Chat model.
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
	 * Deletes an existing Chat model.
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
	 * Finds the Chat model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return Chat the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Chat::findOne ( $id )) !== null) {
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
	public function actionSendMessage() {
		Yii::$app->response->format = 'json';
		
		$token = Yii::$app->request->post ( 'token', '' );
		$service = Yii::$app->request->post ( 'assigned_service_id', '' );
		$message = Yii::$app->request->post ( 'message', '' );
		$event = Yii::$app->request->post ( 'event', '' );
		
		$model_token = LogToken::find ()->where ( [ 
				'token' => $token 
		] )->one ();
		
		// var_dump($searched);
		if ($model_token == null) {
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => "Token inválido" 
			];
			return $response;
		}
		$id_user = $model_token->FK_id_user;
		
		$model_service = AssignedService::findOne ( [ 
				'id' => $service 
		] );
		if ($model_service == null) {
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => "Lo sentimos, este servicio ya fue finalizado o no existe" 
			]
			;
			return $response;
		}
		
		//buscamos la conversación
		$model_conversation=Conversation::findOne(['assigned_service_id'=>$service,'expert_id'=>$model_service->expert_id]);
		if($model_conversation==null){//si no existe, la creamos
			$model_conversation=new Conversation();
			$model_conversation->assigned_service_id=$service;
			$model_conversation->expert_id=$model_service->expert_id;
			$model_conversation->user_id=$id_user;
			if (! $model_conversation->save ()) {
				$response ["success"] = false;
				$response ["data"] = [
						"message" => json_encode ( $model_conversation->getErrors () )
				];
				return $response;
			}
			
		}
		
		
		$model = new Chat ();
		
		$model->message = $message;
		$model->date = date ( 'Y-m-d' );
		$model->time = date ( 'H:i:s' );
		$model->conversation_id = $model_conversation->id;
		$model->expert_sent=0;
		
		if (! $model->save ()) {
			$response ["success"] = false;
			$response ["data"] = [ 
					"message" => json_encode ( $model->getErrors () ) 
			];
			return $response;
		}
		
		$data = [ 
				'message' => $model->message,
				'date' => $model->date,
				'time' => $model->time 
		];
		
		
		
		$pusher = new \Pusher( Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id'] );
		
		if (! $pusher->trigger ( Yii::$app->params ['pusher_channel_chat'] . "_$service", $event, $data )) {
			
			// Yii::$app->response->statusCode = 200;
			$response ["success"] = False;
			$response ["data"] = [ 
					"message" => "Error" 
			];
			return $response;
			// Yii::$app->response->statusCode = 200;
		}
		
		//enviar notificacion
		$tokens = Expert::findOne ( [
				"id" => $model_service->expert_id
		] )->getPushTokens ();
		
		
		// print_r($tokens);
		$nombre=User::findOne(['id'=>$id_user])->getShortName();
		$data = [
				"ticker" => "Nuevo chat",
				'type' => Yii::$app->params ['notification_type_chat_expert'],
				'id_assigned'=>$service,
				'nombre'=>$nombre,
		];
		if ($tokens != null)
			Yii::$app->PushNotifier->sendNotificationExpertOS ( "Tienes un nuevo mensaje", "$nombre: $message", $data, $tokens );
		
		
		$response ["success"] = true;
		$response ["data"] = [ 
				"message" => "Envio ok" 
		];
		return $response;
	}
	public function actionSendMessageExpert() {
		Yii::$app->response->format = 'json';
	
		$especialista = Yii::$app->request->post ( 'especialista', '' );
		$service = Yii::$app->request->post ( 'assigned_service_id', '' );
		$message = Yii::$app->request->post ( 'message', '' );
		$event = Yii::$app->request->post ( 'event', '' );
	
		
	
		$model_service = AssignedService::findOne ( [
				'id' => $service
		] );
		if ($model_service == null) {
			$response ["success"] = false;
			$response ["data"] = [
					"message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
			]
			;
			return $response;
		}
		
		$id_user=$model_service->user_id;
		
		//buscamos la conversación
		$model_conversation=Conversation::findOne(['assigned_service_id'=>$service,'expert_id'=>$model_service->expert_id]);
		if($model_conversation==null){//si no existe, la creamos
			$model_conversation=new Conversation();
			$model_conversation->assigned_service_id=$service;
			$model_conversation->expert_id=$model_service->expert_id;
			$model_conversation->user_id=$id_user;
			if (! $model_conversation->save ()) {
				$response ["success"] = false;
				$response ["data"] = [
						"message" => json_encode ( $model_conversation->getErrors () )
				];
				return $response;
			}
				
		}
		
		
		$model = new Chat ();
		
		$model->message = $message;
		$model->date = date ( 'Y-m-d' );
		$model->time = date ( 'H:i:s' );
		$model->conversation_id = $model_conversation->id;
		$model->expert_sent=1;
	
	
		if (! $model->save ()) {
			$response ["success"] = false;
			$response ["data"] = [
					"message" => json_encode ( $model->getErrors () )
			];
			return $response;
		}
	
		$data = [
				'message' => $model->message,
				'date' => $model->date,
				'time' => $model->time
		];
	
		$pusher = new \Pusher ( Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id'] );
	
		if (! $pusher->trigger ( Yii::$app->params ['pusher_channel_chat'] . "_$service", $event, $data )) {
				
			// Yii::$app->response->statusCode = 200;
			$response ["success"] = False;
			$response ["data"] = [
					"message" => "Error"
			];
			return $response;
			// Yii::$app->response->statusCode = 200;
		}
		$tokens = User::findOne ( [
				"id" => $id_user
		] )->getPushTokens ();
		
		
		// print_r($tokens);
		$nombre=Expert::findOne(['id'=>$especialista])->getShortName();
		$data = [
				"ticker" => "Nuevo chat",
				'type' => Yii::$app->params ['notification_type_chat_user'],
				'id_assigned'=>$service,
				'nombre'=>$nombre,
		];
		if ($tokens != null)
			Yii::$app->PushNotifier->sendNotificationUserOS ( "Tienes un nuevo mensaje", "$nombre: $message", $data, $tokens );
		
		
		
		$response ["success"] = true;
		$response ["data"] = [
				"message" => "Envio ok"
		];
		return $response;
	}
	
	
	public function actionGetChat(){
		Yii::$app->response->format = 'json';
	
		
		$service = Yii::$app->request->post ( 'assigned_service_id', '' );
		$token = Yii::$app->request->post ( 'token', '' );
	
		
		$model_token = LogToken::find ()->where ( [
				'token' => $token
		] )->one ();
		
		// var_dump($searched);
		if ($model_token == null) {
			$response ["success"] = false;
			$response ["data"] = [
					"message" => "Token inválido"
			];
			return $response;
		}
		
		$id_user = $model_token->FK_id_user;
	
		$model_service = AssignedService::findOne ( [
				'id' => $service,
	
		] );
		if ($model_service == null) {
			$response ["success"] = false;
			$response ["data"] = [
					"message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
			]
			;
			return $response;
		}
		
		//buscamos la conversación
		
		$model_conversation=Chat::find()->select(['message','date','time','expert_sent','conversation_id','expert_id','name','last_name'])->where(['assigned_service_id'=>$service])->joinWith(['conversation','conversation.expert'])->orderBy(['chat.date'=>SORT_ASC,'chat.time'=>SORT_ASC])->asArray()->all();
	
		
	
		for($i=0;$i<sizeof($model_conversation);$i++){
			unset($model_conversation[$i]['conversation']);	
		}
		
		$response ["success"] = true;
		$response ['data'] = $model_conversation;
			
		return $response;
	
	
	}
	
	public function actionGetChatExpert(){
		Yii::$app->response->format = 'json';
		
		$especialista = Yii::$app->request->post ( 'especialista', '' );
		$service = Yii::$app->request->post ( 'assigned_service_id', '' );
		
		$model_service = AssignedService::findOne ( [
				'id' => $service,
				
		] );
		if ($model_service == null) {
			$response ["success"] = false;
			$response ["data"] = [
					"message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
			]
			;
			return $response;
		}
		
		$model_conversation=Chat::find()->where(['assigned_service_id'=>$service,'expert_id'=>$especialista])->joinWith('conversation')->orderBy(['chat.date'=>SORT_ASC,'chat.time'=>SORT_ASC])->asArray()->all();
		
	
		$response ["success"] = true;
		$response ['data'] = $model_conversation;
			
		return $response;
		
		
	}
}
