<?php

namespace app\controllers;

//use app\pusher\Pusher;
require '../vendor/pusher/pusher-php-server/lib/Pusher.php';
use Yii;
use app\models\User;
use app\models\Expert;
use app\models\Address;
use app\models\UserSearch;
use app\models\CreditCard;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User_create;
use yii\base\Object;
use app\assets\AppDate;
use app\models\City;
// use app\models\Gender;
use app\models\TermTaxonomy;
use app\models\Rol;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Users;
use app\models\UsersSearch;
use app\models\AddressSearch;
use app\models\LogToken;
use app\models\GcmToken;
use app\models\LoginForm;
use app\models\VwActualService;
use app\models\SignupForm;
use app\models\CheckPreRegister;
use app\models\ResetPasswordForm;
use app\models\TypeToken;
use app\assets\EmailAsset;
use app\assets\Facebook\Facebook;
use app\assets\Facebook\FacebookRequest;
use app\assets\Facebook\FacebookApp;
use app\assets\Facebook\FacebookClient;
use yii\helpers\Url;

//
use yii\data\ActiveDataProvider;
use SendGrid\Email;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				],
				// RBAC
				'access' => [ 
						'class' => AccessControl::className (),
						'only' => [ 
								'view',
								// 'create',
								// 'update',
								'index' 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'view',
												'index' 
										],
										// 'update'
										'allow' => true,
										'roles' => [ 
												'@' 
										],
										'matchCallback' => function ($rule, $action) {
											return User::isSuper ( Yii::$app->user->identity->email );
										} 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new UserSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single User model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		$searchModel = new Address ();
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $searchModel->find ()->joinWith ( [ 
						'userHasAddress' 
				] )->where ( [ 
						'user_has_address.user_id' => $id 
				] ) 
		] );
		
		// $dataProvider = $searchModel->search(['expert_id' => $id]);
		
		return $this->render ( 'view', [ 
				'model' => $this->findModel ( $id ),
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionCreate() {
		$model = new SignupForm ( [ 
				'scenario' => SignupForm::SCENARIO_REGISTER 
		] );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} 

		else if (Yii::$app->request->isPost) {
			// se define el layout
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			// este caso se da solo cuando los datos del usuario pasaron por el "tunel de creacion de usuarios del servicio registerUser.php"
			$firstname = Yii::$app->request->post ( "firstname", null );
			$lastname = Yii::$app->request->post ( "lastname", null );
			$email = Yii::$app->request->post ( "email", null );
			$password = Yii::$app->request->post ( "password", null );
			$phone = Yii::$app->request->post ( "phone", null );
			$gender = Yii::$app->request->post ( "gender", null );
			$imei = Yii::$app->request->post ( "imei", null );
			
			$tpaga_id = Yii::$app->TPaga->CreateCustomer ( $firstname, $lastname, $email, $phone );
			;
			
			$infoForm = [ 
					'SignupForm' => [ 
							'firstname' => $firstname,
							'lastname' => $lastname,
							'email' => $email,
							'imei' => $imei,
							'password' => $password,
							'phone' => $phone,
							'gender' => $gender,
							'tpaga_id' => $tpaga_id 
					] 
			];
			if ($model->load ( $infoForm )) {
				// se gusarda el usuario mmz en la nueva tabla
				if ($user = $model->signup ()) {
					// se elimina el registro del email en la lista de usuarios no registrado en mailchimp
					if (empty ( $user->getErrors () )) {
						
						try {
							// se envia un email al usuario mmz con la bienvenida
							$assetEmail = new EmailAsset ();
							if ($user->email != '') {
								/*
								 * $arg = [
								 * 'model' => $user
								 * ];
								 * $booleanSend = $assetEmail->sendMail ( 'tiver@zugartek.com', $user->email, '¡Bienvenido a Tiver!', 'user/create', $arg );
								 */
								
								//
								
								$sendGrid = new \SendGrid ( Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass'] );
								$email = new \SendGrid\Email ();
								$email
								->setFrom ( Yii::$app->params ['sendgrid_from'] )
								->setFromName ( Yii::$app->params ['sendgrid_from_name'] )
								->addTo ( $user->email )
								->setSubject ( ' ' )
								->setHtml ( ' ' )
								->addSubstitution ( '{{ username }}', [ $user->first_name ] )
								->addFilter ( 'templates', 'enabled', 1 )
								->addFilter ( 'templates', 'template_id', Yii::$app->params ['sendgrid_template_welcome'] );
								$resp = $sendGrid->send ( $email );
							//	var_dump($resp);
							}
						} catch ( \Exception $e ) {
							Yii::error ( $e->getMessage () );
							echo $e->getMessage ();
						}
						return [ 
								'success' => true,
								'data' => [ 
										'message' => 'Usuario creado correctamente' 
								] 
						];
					} else {
						Yii::trace ( json_encode ( [ 
								'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $user->getErrors () ) 
						] ) );
						return [ 
								'success' => false,
								'data' => [ 
										'message' => json_encode ( $user->getErrors () ) 
								] 
						];
					}
				} else {
					Yii::trace ( json_encode ( [ 
							'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $model->getErrors () ) 
					] ) );
					return [ 
							'success' => false,
							'data' => [ 
									'message' => json_encode ( $model->getErrors () ) 
							] 
					];
				}
			} else {
				Yii::trace ( json_encode ( [ 
						'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $model->getErrors () ) 
				] ) );
				return [ 
						'success' => false,
						'data' => [ 
								'message' => json_encode ( $model->getErrors () ) 
						] 
				];
			}
		} 

		else {
			$model = new User ( $id );
			
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	public function actionCheckPreregister() {
		$model = new CheckPreRegister ();
		// Creación del usuario (WS) para la aplicación
		if (Yii::$app->request->isPost) {
			// se define el layout
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			// este caso se da solo cuando los datos del usuario pasaron por el "tunel de creacion de usuarios del servicio registerUser.php"
			
			$email = Yii::$app->request->post ( "email", null );
			$password = Yii::$app->request->post ( "password", null );
			$infoForm = [ 
					'email' => $email,
					'password' => $password 
			];
			
			$model->attributes = $infoForm;
			
			// var_dump($infoForm);
			if ($model->validate ()) {
				return [ 
						'success' => true,
						'data' => [ 
								'message' => 'Datos correctos' 
						] 
				];
			} else {
				return [ 
						'success' => False,
						'data' => [ 
								'message' => json_encode ( $model->getErrors () ) 
						] 
				];
			}
		}
	}
	public function actionFacebook() {
		Yii::trace ( json_encode ( Yii::$app->request->post () ) );
		// se define el layout
		$this->layout = "json";
		Yii::$app->response->format = 'json';
		
		if (Yii::$app->request->isPost) {
			
			// var_dump($_POST);
			
			$app_id = Yii::$app->params ['fb_app_id'];
			$app_secret = Yii::$app->params ['fb_app_secret'];
			
			$user_id = Yii::$app->request->post ( "fb_id", null );
			$token = Yii::$app->request->post ( "fb_token", null );
			$device = Yii::$app->request->post ( "device", null );
			$gcm_id = Yii::$app->request->post ( "gcm_id", null );
			$os_id = Yii::$app->request->post ( "os_id", null );
			
			$imei = Yii::$app->request->post ( "imei", null );
			$phone = Yii::$app->request->post ( "phone", null );
			
			$fb = new Facebook ( [ 
					'app_id' => $app_id,
					'app_secret' => $app_secret,
					'default_graph_version' => 'v2.4' 
			] );
			$fb->setDefaultAccessToken ( $token );
			
			$permissions = array (
					'email',
					'user_location',
					'user_birthday' 
			);
			try {
				$request = $fb->get ( '/me?fields=gender,first_name,last_name,locale,age_range,email' );
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				echo 'Graph returned an error: ' . $e->getMessage ();
				exit ();
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage ();
				exit ();
			}
			
			$user_fb = $request->getGraphUser ();
			$email = $user_fb->getProperty ( 'email' );
			$firstname = $user_fb->getProperty ( 'first_name' );
			$lastname = $user_fb->getProperty ( 'last_name' );
			$genero = $user_fb->getProperty ( 'gender' );
			if (empty ( $email )) {
				
				return [ 
						'success' => false,
						'errcode' => 10,
						'data' => [ 
								'message' => 'No se pudo obtener el correo de tu cuenta de Facebook, debes registrarte manualmente desde la aplicación' 
						] 
				];
			}
			// Obtenemos el tipo de token
			$typeToken = TypeToken::findOne ( $device );
			
			if (empty ( $typeToken )) {
				
				return [ 
						'success' => false,
						'errcode' => 10,
						'data' => [ 
								'message' => 'Error en el nombre del dispositivo.' 
						] 
				];
			}
			
			// Buscamos el usuario asociado a dicho fb_id
			$user = User::find ()->where ( "enable='1' and(fb_id='$user_id' or email='$email') and FK_id_rol=1" )->one ();
			if ($user) {
				$updateTokens = LogToken::updateAll ( [ 
						'status' => 0 
				], [ 
						'FK_id_user' => $user->id,
						'FK_id_token_type' => $typeToken->id,
						'status' => 1 
				] );
				// se crea el token del nuevo usuario mmz
				$tokenMmz = new LogToken ();
				$token = MD5 ( $user->id . $user->email . time () );
				$arrayLog = [ 
						'LogToken' => [ 
								'token' => $token,
								'connection_ip' => Yii::$app->request->userIP,
								'status' => 1,
								'FK_id_token_type' => $typeToken->id,
								'FK_id_user' => $user->id,
								'created_date' => date ( 'Y-m-d H:i:s' ),
								'updated_date' => date ( 'Y-m-d H:i:s' ) 
						] 
				];
				if ($tokenMmz->load ( $arrayLog ) && $tokenMmz->save ()) {
					
					// Actualizamos ultimlo login
					$user->last_login = date ( 'Y-m-d H:i:s' );
					// $user->gcm_id = $gcm_id;
					$user->save ();
					
					// Buscamos y actualizamos el token GCM
					$gcm_token = GcmToken::find ()->where ( [ 
							"user_id" => $user->id,
							"type_token_id" => $device 
					] )->one ();
					if ($gcm_token != null) {
						$gcm_token->token = $gcm_id;
						$gcm_token->one_signal_token = $os_id;
						$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
						$gcm_token->save ();
					} else { // Si no existe, creamos uno nuevo
						$gcm_token = new GcmToken ();
						$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
						$gcm_token->token = $gcm_id;
						$gcm_token->one_signal_token = $os_id;
						$gcm_token->type_token_id = $device;
						$gcm_token->user_id = $user->id;
						$gcm_token->save ();
					}
					// var_dump($gcm_token->getErrors());
					
					$model_history = VwActualService::find ()->where ( [ 
							'user_id' => $user->id 
					] )->
					// 'status' => '1'
					asArray ()->one ();
					$actual_service = false;
					if ($model_history != null) {
						$actual_service = true;
					}
					
					$model_cc = CreditCard::find ()->where ( [ 
							'user_id' => $user->id,
							'enable' => '1' 
					] )->count ();
					$credit_card = $model_cc;
					
					return [ 
							'success' => true,
							'data' => [ 
									'token' => $token,
									"id" => $user->id,
									"name" => $user->first_name,
									"last_name" => $user->last_name,
									"email" => $user->email,
									"phone" => $user->phone,
									"active_service" => $actual_service,
									"count_credit_card" => $credit_card,
									"pending_pay" => $user->hasPendingPay () 
							] 
					];
				} else {
					Yii::trace ( json_encode ( [ 
							'message' => 'El token no se pudo guardar  -  ' . json_encode ( $tokenMmz->getErrors () ) 
					] ) );
					return [ 
							'success' => false,
							'data' => [ 
									'message' => 'El token no se pudo guardar' . json_encode ( $tokenMmz->getErrors () ) 
							] 
					];
				}
			} else { // Si el usuario no existe, lo creamos
				$model = new SignupForm ( [ 
						'scenario' => SignupForm::SCENARIO_REGISTER_FB 
				] );
				$tpaga_id = Yii::$app->TPaga->CreateCustomer ( $firstname, $lastname, $email, $phone );
				$gender = 3;
				if ($genero == 'male')
					$gender = 1;
				elseif ($genero = 'female')
					$gender = 2;
				
				$infoForm = [ 
						'SignupForm' => [ 
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'imei' => $imei,
								'phone' => $phone,
								'fb_id' => $user_id,
								'tpaga_id' => $tpaga_id,
								'gender' => $gender 
						] 
				];
				
				if ($model->load ( $infoForm )) {
					// se gusarda el usuario mmz en la nueva tabla
					if ($user = $model->signup_fb ()) {
						// se elimina el registro del email en la lista de usuarios no registrado en mailchimp
						if (empty ( $user->getErrors () )) {
							
							try {
								// se envia un email al usuario mmz con la bienvenida
								$assetEmail = new EmailAsset ();
								if ($email != '') {
									/*
									 * $arg = [
									 * 'model' => $user
									 * ];
									 * $booleanSend = $assetEmail->sendMail ( 'tiver@zugartek.com', $email, '¡Bienvenido a Tiver!', 'user/create', $arg );
									 */
									//
									$sendGrid = new \SendGrid ( Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass'] );
									$email = new \SendGrid\Email ();
									$email
									->setFrom ( Yii::$app->params ['sendgrid_from'] )
									->setFromName ( Yii::$app->params ['sendgrid_from_name'] )
									->addTo ( $user->email )
									->setSubject ( ' ' )
									->setHtml ( ' ' )
									->addSubstitution ( '{{ username }}', [ $user->first_name ] )
									->addFilter ( 'templates', 'enabled', 1 )
									->addFilter ( 'templates', 'template_id', Yii::$app->params ['sendgrid_template_welcome'] );
									$resp = $sendGrid->send ( $email );
								}
							} catch ( \Exception $e ) {
								Yii::error ( $e->getMessage () );
							}
							
							// Usuario creado correctamente, hacer login
							
							$url = Url::current ( [ ], true );
							// print"--->".$url;
							// Any other field you might want to post
							$post_data ['fb_id'] = $user_id;
							$post_data ['fb_token'] = $token;
							$post_data ['imei'] = $imei;
							$post_data ['device'] = $device;
							
							// Initialize cURL
							$ch = curl_init ();
							
							// Set URL on which you want to post the Form and/or data
							curl_setopt ( $ch, CURLOPT_URL, $url );
							// Data+Files to be posted
							curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
							// Pass TRUE or 1 if you want to wait for and catch the response against the request made
							curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
							// For Debug mode; shows up any error encountered during the operation
							curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
							// Execute the request
							$response = curl_exec ( $ch );
							
							// Just for debug: to see response
							print $response;
						} else {
							Yii::trace ( json_encode ( [ 
									'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $user->getErrors () ) 
							] ) );
							return [ 
									'success' => false,
									'data' => [ 
											'message' => json_encode ( $user->getErrors () ) 
									] 
							];
						}
					} else {
						Yii::trace ( json_encode ( [ 
								'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $model->getErrors () ) 
						] ) );
						return [ 
								'success' => false,
								'data' => [ 
										'message' => json_encode ( $model->getErrors () ) 
								] 
						];
					}
				} else {
					Yii::trace ( json_encode ( [ 
							'message' => "El usuario no se pudo guardar'  -  " . json_encode ( $model->getErrors () ) 
					] ) );
					return [ 
							'success' => false,
							'data' => [ 
									'message' => json_encode ( $model->getErrors () ) 
							] 
					];
				}
			}
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate() {
		$id = Yii::$app->request->get ( "id", 0 );
		
		if ($id > 0) {
			$model = $this->findModel ( $id );
			if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
				return $this->redirect ( [ 
						'view',
						'id' => $model->id 
				] );
			} else {
				
				$model = $this->findModel ( $id );
				$model->updated_date = date ( 'Y-m-d H:i:s' );
				return $this->render ( 'update', [ 
						'model' => $model 
				] );
			}
		} elseif (Yii::$app->request->isPost) {
			
			// var_dump(Yii::$app->request->post());
			// var_dump($model->getErrors());
			// exit();
			// se imprime el trace de la peticion con el fin de realizar pruebas
			Yii::trace ( json_encode ( Yii::$app->request->post () ) );
			// se define el layout
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			// este caso se da solo cuando los datos del usuario pasaron por el "tunel de creacion de usuarios del servicio registerUser.php"
			$firstname = Yii::$app->request->post ( "firstname", null );
			$lastname = Yii::$app->request->post ( "lastname", null );
			
			$email = Yii::$app->request->post ( "email", null );
			$imei = Yii::$app->request->post ( "imei", null );
			$phone = Yii::$app->request->post ( "phone", null );
			$token = Yii::$app->request->post ( "token", null );
			try {
				$modelToken = LogToken::findOne ( [ 
						'token' => $token 
				] );
				if (! empty ( $modelToken )) {
					if ($modelToken->status) {
						$arrayUpdate = [ 
								'SignupForm' => [ 
										'id' => $modelToken->FK_id_user,
										'firstname' => $firstname,
										'lastname' => $lastname,
										'phone' => $phone,
										'email' => $email,
										'imei' => $imei 
								] 
						];
						$modelUpdate = new SignupForm ( [ 
								'scenario' => SignupForm::SCENARIO_UPDATE 
						] );
						// se gusarda el usuario mmz en la nueva tabla
						if ($modelUpdate->load ( $arrayUpdate ) && $user = $modelUpdate->update ()) {
							// se elimina el registro del email en la lista de usuarios no registrado en mailchimp
							if (! $user->hasErrors ()) {
								
								return [ 
										'success' => true,
										'data' => [ 
												'message' => 'Usuario actualizado correctamente' 
										] 
								];
							} else {
								
								Yii::trace ( json_encode ( [ 
										'message' => "el usuario no se pudo guardar'  -  " . json_encode ( $user->getErrors () ) 
								] ) );
								return [ 
										'success' => false,
										'data' => [ 
												'message' => json_encode ( $user->getErrors () ) 
										] 
								];
							}
						} else {
							Yii::trace ( json_encode ( [ 
									'message' => "el usuario no se pudo guardar'  -  " . json_encode ( $modelUpdate->getErrors () ) 
							] ) );
							return [ 
									'success' => false,
									'data' => [ 
											'message' => json_encode ( $modelUpdate->getErrors () ) 
									] 
							];
						}
					} else {
						Yii::trace ( json_encode ( [ 
								'message' => "Ingreso inválido" 
						] ) );
						return [ 
								'success' => false,
								'data' => [ 
										'message' => 'Ingreso inválido, favor volver a iniciar sesión' 
								] 
						];
					}
				} else {
					Yii::trace ( json_encode ( [ 
							'message' => "Usuario no existe" 
					] ) );
					return [ 
							'success' => false,
							'data' => [ 
									'message' => 'Usuario no existe' 
							] 
					];
				}
			} catch ( Exception $e ) {
				Yii::trace ( json_encode ( [ 
						'message' => "el usuario no se pudo guardar' ]];" 
				] ) );
				return [ 
						'success' => false,
						'data' => [ 
								'message' => 'el usuario no se pudo guardar' 
						] 
				];
			}
		}
	}
	
	/**
	 * Deletes an existing User model.
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
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = User::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	
	// Android
	public function actionLogin() {
		// if (Yii::$app->request->isPost) {
		$model = new LoginForm ();
		Yii::trace ( json_encode ( Yii::$app->request->post () ) );
		// se define el layout
		$this->layout = "json";
		Yii::$app->response->format = 'json';
		// se obtiene el parametro request de la peticion
		// se intacian las variables que se van a usar en la creacion del usuario
		$email = Yii::$app->request->post ( "email", null );
		$password = Yii::$app->request->post ( "password", null );
		$gcm_id = Yii::$app->request->post ( "gcm_id", null );
		$os_id = Yii::$app->request->post ( "os_id", null );
		$device = Yii::$app->request->post ( "device", null );
		
		/*
		 * $arrayPost = [
		 * 'LoginForm' => [
		 * 'username' => $email,
		 * 'password' => $password,
		 * 'remember' => true
		 * ]
		 * ];
		 * if ($model->load ( $arrayPost )) {
		 */
		
		$typeToken = TypeToken::findOne ( $device );
		if (empty ( $typeToken )) {
			
			return [ 
					'success' => false,
					'errcode' => 10,
					'data' => [ 
							'message' => 'Error en el nombre del dispositivo.' 
					] 
			];
		}
		
		$user = User::findOne ( [ 
				'email' => $email,
				'enable' => User::STATUS_ACTIVE,
				'FK_id_rol' => '1' 
		] );
		if ($user == null) {
			return [ 
					'success' => false,
					'errcode' => 10,
					'data' => [ 
							'message' => 'Lo sentimos, este usuario no existe' 
					] 
			];
		}
		
		if (! $user->validatePassword ( $password )) {
			return [ 
					'success' => false,
					'errcode' => 10,
					'data' => [ 
							'message' => 'Contraseña incorrecta, intenta de nuevo' 
					] 
			];
		}
		// Actualizamos Log Token
		/*
		 * $log_token = LogToken::find ()->where ( [
		 * 'status' => 1,
		 * 'FK_id_token_type' => $typeToken->id,
		 * 'FK_id_user' => $user->id
		 * ] )->one ();
		 *
		 * if ($log_token != null) {
		 * $token = MD5 ( $user->id . $user->email . time () );
		 * $log_token->token = $token;
		 * $log_token->connection_ip = Yii::$app->request->userIP;
		 * $log_token->updated_date = date ( 'Y-m-d H:i:s' );
		 * if (! $log_token->save ())
		 * return [
		 * 'success' => false,
		 * 'data' => [
		 * 'message' => 'El token no se pudo guardar',
		 * 'errors ' => ($tokenMmz->getErrors ())
		 * ]
		 * ];
		 * } else { // Si no existe, creamos uno nuevo
		 * $tokenMmz = new LogToken ();
		 * $token = MD5 ( $user->id . $user->email . time () );
		 * $arrayLog = [
		 * 'LogToken' => [
		 * 'token' => $token,
		 * 'connection_ip' => Yii::$app->request->userIP,
		 * 'status' => 1,
		 * 'FK_id_token_type' => $typeToken->id,
		 * 'FK_id_user' => $user->id,
		 * 'created_date' => date ( 'Y-m-d H:i:s' ),
		 * 'updated_date' => date ( 'Y-m-d H:i:s' )
		 * ]
		 * ];
		 * if (! ($tokenMmz->load ( $arrayLog ) && $tokenMmz->save ())) {
		 * return [
		 * 'success' => false,
		 * 'data' => [
		 * 'message' => 'El token no se pudo guardar',
		 * 'errors ' => ($tokenMmz->getErrors ())
		 * ]
		 * ];
		 * }
		 * }
		 */
		// se crea el token del nuevo usuario mmz
		$tokenMmz = new LogToken ();
		$token = MD5 ( $user->id . $user->email . time () );
		$updateTokens = LogToken::updateAll ( [ 
				'status' => 0 
		], [ 
				'FK_id_user' => $user->id,
				'FK_id_token_type' => $typeToken->id,
				'status' => 1 
		] );
		$arrayLog = [ 
				'LogToken' => [ 
						'token' => $token,
						'connection_ip' => Yii::$app->request->userIP,
						'status' => 1,
						'FK_id_token_type' => $typeToken->id,
						'FK_id_user' => $user->id,
						'created_date' => date ( 'Y-m-d H:i:s' ),
						'updated_date' => date ( 'Y-m-d H:i:s' ) 
				] 
		];
		if (! ($tokenMmz->load ( $arrayLog ) && $tokenMmz->save ())) {
			return [ 
					'success' => false,
					'data' => [ 
							'message' => 'El token no se pudo guardar',
							'errors ' => ($tokenMmz->getErrors ()) 
					] 
			];
		}
		
		// Actualizamos ultimlo login
		$user->last_login = date ( 'Y-m-d H:i:s' );
		// $user->gcm_id = $gcm_id;
		// print $user->last_login; exit();
		$user->save ();
		
		// Buscamos y actualizamos el token GCM
		$gcm_token = GcmToken::find ()->where ( [ 
				"user_id" => $user->id,
				"type_token_id" => $device 
		] )->one ();
		if ($gcm_token != null) {
			$gcm_token->token = $gcm_id;
			$gcm_token->one_signal_token = $os_id;
			$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
			$gcm_token->save ();
		} else { // Si no existe, creamos uno nuevo
			$gcm_token = new GcmToken ();
			$gcm_token->one_signal_token = $os_id;
			$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
			$gcm_token->token = $gcm_id;
			$gcm_token->type_token_id = $device;
			$gcm_token->user_id = $user->id;
			$gcm_token->save ();
		}
		
		$model_history = VwActualService::find ()->where ( [ 
				'user_id' => $user->id 
		] )->
		// 'status' => '1'
		asArray ()->one ();
		$actual_service = false;
		if ($model_history != null) {
			$actual_service = true;
		}
		
		$model_cc = CreditCard::find ()->where ( [ 
				'user_id' => $user->id,
				'enable' => '1' 
		] )->count ();
		$credit_card = $model_cc;
		
		return [ 
				'success' => true,
				'data' => [ 
						'token' => $token,
						"id" => $user->id,
						"name" => $user->first_name,
						"last_name" => $user->last_name,
						"email" => $user->email,
						"phone" => $user->phone,
						"active_service" => $actual_service,
						"count_credit_card" => $credit_card,
						"pending_pay" => $user->hasPendingPay () 
				] 
		];
		
		/*
		 * } else {
		 * return [
		 * 'success' => false,
		 * 'errcode' => 10,
		 * 'data' => [
		 * 'message' => json_encode ( $model->getErrors () )
		 * ]
		 * ];
		 * }
		 * /*} else {
		 * Yii::trace ( json_encode ( [
		 * 'message' => "El parametro password es requerido"
		 * ] ) );
		 * return [
		 * 'success' => false,
		 * 'data' => [
		 * 'message' => 'El parametro password es requerido'
		 * ]
		 * ];
		 * }
		 */
	}
	public function actionOldLogin() {
		if (Yii::$app->request->isPost) {
			$model = new LoginForm ();
			Yii::trace ( json_encode ( Yii::$app->request->post () ) );
			// se define el layout
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			// se obtiene el parametro request de la peticion
			// se intacian las variables que se van a usar en la creacion del usuario
			$email = Yii::$app->request->post ( "email", null );
			$password = Yii::$app->request->post ( "password", null );
			$gcm_id = Yii::$app->request->post ( "gcm_id", null );
			$os_id = Yii::$app->request->post ( "os_id", null );
			
			$device = Yii::$app->request->post ( "device", null );
			$arrayPost = [ 
					'LoginForm' => [ 
							'username' => $email,
							'password' => $password,
							'remember' => true 
					] 
			];
			if ($model->load ( $arrayPost )) {
				$typeToken = TypeToken::findOne ( $device );
				if (! empty ( $typeToken )) {
					if ($model->login ()) {
						$user = User::findOne ( [ 
								'email' => $email,
								'enable' => 1 
						] );
						if ($user) {
							$updateTokens = LogToken::updateAll ( [ 
									'status' => 0 
							], [ 
									'FK_id_user' => $user->id,
									'FK_id_token_type' => $typeToken->id,
									'status' => 1 
							] );
							// se crea el token del nuevo usuario mmz
							$tokenMmz = new LogToken ();
							$token = MD5 ( $user->id . $user->email . time () );
							$arrayLog = [ 
									'LogToken' => [ 
											'token' => $token,
											'connection_ip' => Yii::$app->request->userIP,
											'status' => 1,
											'FK_id_token_type' => $typeToken->id,
											'FK_id_user' => $user->id,
											'created_date' => date ( 'Y-m-d H:i:s' ),
											'updated_date' => date ( 'Y-m-d H:i:s' ) 
									] 
							];
							if ($tokenMmz->load ( $arrayLog ) && $tokenMmz->save ()) {
								
								// Actualizamos ultimlo login
								$user->last_login = date ( 'Y-m-d H:i:s' );
								// $user->gcm_id = $gcm_id;
								// print $user->last_login; exit();
								$user->save ();
								
								// Buscamos y actualizamos el token GCM
								$gcm_token = GcmToken::find ()->where ( [ 
										"user_id" => $user->id,
										"type_token_id" => $device 
								] )->one ();
								if ($gcm_token != null) {
									$gcm_token->token = $gcm_id;
									$gcm_token->one_signal_token = $os_id;
									$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
									$gcm_token->save ();
								} else { // Si no existe, creamos uno nuevo
									$gcm_token = new GcmToken ();
									$gcm_token->one_signal_token = $os_id;
									$gcm_token->updated_date = date ( 'Y-m-d H:i:s' );
									$gcm_token->token = $gcm_id;
									$gcm_token->type_token_id = $device;
									$gcm_token->user_id = $user->id;
									$gcm_token->save ();
								}
								
								$model_history = VwActualService::find ()->where ( [ 
										'user_id' => $user->id 
								] )->
								// 'status' => '1'
								asArray ()->one ();
								$actual_service = false;
								if ($model_history != null) {
									$actual_service = true;
								}
								
								$model_cc = CreditCard::find ()->where ( [ 
										'user_id' => $user->id,
										'enable' => '1' 
								] )->count ();
								$credit_card = $model_cc;
								
								return [ 
										'success' => true,
										'data' => [ 
												'token' => $token,
												"id" => $user->id,
												"name" => $user->first_name,
												"last_name" => $user->last_name,
												"email" => $user->email,
												"phone" => $user->phone,
												"active_service" => $actual_service,
												"count_credit_card" => $credit_card,
												"pending_pay" => $user->hasPendingPay () 
										] 
								];
							} else {
								Yii::trace ( json_encode ( [ 
										'message' => 'El token no se pudo guardar  -  ' . json_encode ( $tokenMmz->getErrors () ) 
								] ) );
								return [ 
										'success' => false,
										'data' => [ 
												'message' => 'el token no se pudo guardar',
												'errors ' => ($tokenMmz->getErrors ()) 
										] 
								];
							}
						} else {
							Yii::trace ( json_encode ( [ 
									'message' => "Usuario deshabilitado" 
							] ) );
							return [ 
									'success' => false,
									'data' => [ 
											'message' => 'Usuario deshabilitado' 
									] 
							];
						}
					} else {
						return [ 
								'success' => false,
								'errcode' => 10,
								'data' => [ 
										'message' => json_encode ( $model->getErrors () ) 
								] 
						];
					}
				} else {
					return [ 
							'success' => false,
							'errcode' => 10,
							'data' => [ 
									'message' => 'Error en el nombre del dispositivo.' 
							] 
					];
				}
			} else {
				return [ 
						'success' => false,
						'errcode' => 10,
						'data' => [ 
								'message' => json_encode ( $model->getErrors () ) 
						] 
				];
			}
		} else {
			Yii::trace ( json_encode ( [ 
					'message' => "El parametro password es requerido" 
			] ) );
			return [ 
					'success' => false,
					'data' => [ 
							'message' => 'El parametro password es requerido' 
					] 
			];
		}
	}
	public function actionResetPassword() {
		// if (Yii::$app->request->isPost) {
		$token = Yii::$app->request->get ( 'token', null );
		// $this->layout = "acciones_mimeza"; //abre ventana
		$model = new ResetPasswordForm ( $token );
		// $usuario->user_pass = ''; //toma el dato especifico user_pass
		if ($model->load ( Yii::$app->request->post () ) && $model->validate () && $model->resetPassword ()) {
			Yii::$app->session->setFlash ( 'success', 'New password was saved.' );
			return $this->render ( 'password_change', [ 
					'model' => null 
			] );
		}
		return $this->render ( 'password_change', [ 
				'model' => $model 
		] );
		// } else {
		// throw new \yii\web\HttpException(500, 'Ingreso inválido.'); //mensaje con la ecxepcion
		// }
	}
	
	/**
	 * Requests password reset.
	 *
	 * @return mixed
	 */
	public function actionRequestPasswordReset() {
		if (Yii::$app->request->isPost) {
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			$email = Yii::$app->request->post ( 'email', null );
			$model = new \app\models\PasswordResetRequestForm ();
			$model->attributes = \Yii::$app->request->post ();
			if ($model->validate ()) {
				if ($model->sendEmail ()) {
					return [ 
							'success' => true,
							'data' => [ 
									'message' => 'Revisa tu email y sigue las instrucciones' 
							] 
					];
				} else {
					return [ 
							'success' => true,
							'errcode' => 11,
							'data' => [ 
									'message' => 'No se ha podido enviar el correo' 
							] 
					];
				}
			} else {
				return [ 
						'success' => false,
						'errcode' => 10,
						'data' => [ 
								'message' => json_encode ( $model->getErrors () ) 
						] 
				];
			}
			
			return $this->render ( 'requestPasswordResetToken', [ 
					'model' => $model 
			] );
		} else {
			$this->layout = "json";
			Yii::$app->response->format = 'json';
			$email = Yii::$app->request->get ( 'email', null );
			$model = new \app\models\PasswordResetRequestForm ();
			$model->attributes = \Yii::$app->request->get ();
			if ($model->validate ()) {
				if ($model->sendEmail ()) {
					return [ 
							'success' => true,
							'data' => [ 
									'message' => 'Revisa tu email y sigue las instrucciones' 
							] 
					];
				} else {
					return [ 
							'success' => true,
							'errcode' => 10,
							'data' => [ 
									'message' => 'No se ha podido enviar el correo',
									'errors' => json_encode ( $model->getErrors () ) 
							] 
					];
				}
			} else {
				return [ 
						'success' => false,
						'errcode' => 10,
						'data' => [ 
								'message' => json_encode ( $model->getErrors () ) 
						] 
				];
			}
			
			return $this->render ( 'requestPasswordResetToken', [ 
					'model' => $model 
			] );
		}
	}
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction ( $action );
	}
	public function actionViewDetails() {
		$data = json_decode ( $_POST ['request'], true );
		$token = $data ['token'];
		$model_token = LogToken::find ()->where ( [ 
				'token' => $token 
		] )->one ();
		
		// var_dump($searched);
		
		if ($model_token != null) {
			
			$model_user = User::find ()->select ( [ 
					'id',
					'name',
					'last_name',
					'email',
					'phone' 
			] )->where ( [ 
					'id' => $model_token->user_id 
			] )->asArray ()->all ();
			if ($model_user != null) {
				$response ["success"] = true;
				$response ['data'] = $model_user;
			} else {
				$response ["success"] = false;
				$response ["mensaje"] = "Usuario no existe";
			}
		} else {
			$response ["success"] = false;
			$response ["mensaje"] = "Token inválido";
		}
		$response = json_encode ( $response );
		header ( 'Content-Type: application/json' );
		print $response;
	}
	public function actionPusherAuth() {
		// Yii::$app->response->format = 'json';
		$pusher = new \Pusher ( Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id'] );
		$presence_data = array (
				'name' => "fulanito" 
		);
		// $pusher->presence_auth($_POST['channel_name'], $_POST['socket_id'], $user->uid, $presence_data);
		// print "h!!!i";
		print $pusher->presence_auth ( $_POST ['channel_name'], $_POST ['socket_id'], rand ( 1, 100 ), $presence_data );
	}
}
