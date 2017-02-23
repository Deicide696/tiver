<?php

namespace app\controllers;

require '../vendor/pusher/pusher-php-server/lib/Pusher.php';

use Yii;
use app\models\Expert;
use app\models\User;
use app\models\UploadForm;
use app\models\ExpertSearch;
use app\models\ScheduleSearch;
use app\models\GcmTokenExpert;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ExpertHasServiceSearch;
use app\models\LogToken;
use app\models\AssignedService;
use app\models\Coupon;
use app\models\AssignedServiceHasModifier;

/**
 * ExpertController implements the CRUD actions for Expert model.
 */
class ExpertController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ],
            // RBAC
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'view',
                    'create',
                    'update'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'update'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => [
                            'admin'
                        ],
//                        'matchCallback' => function ($rule, $action) {
//                            return User::isSuper(Yii::$app->user->identity->email);
//                        }
                    ]
                ]
            ]
        ];
        // RBAC
    }

    /**
     * Lists all Expert models.
     *
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new ExpertSearch ();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Expert model.
     *
     * @param integer $id        	
     * @return mixed
     */
    public function actionView($id) {

        $searchModel = new ScheduleSearch ();
        $dataProvider = $searchModel->search([
            'ScheduleSearch' => [
                'expert_id' => $id
            ]
        ]);

        $searchModel2 = new ExpertHasServiceSearch ();
        $dataProvider2 = $searchModel2->search([
            'ExpertHasServiceSearch' => [
                'expert_id' => $id
            ]
        ]);
        // $dataProvider = $searchModel->search(['expert_id' => $id]);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'searchModel2' => $searchModel2,
                    'dataProvider2' => $dataProvider2
        ]);
    }

    /**
     * Creates a new Expert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate() {

        $model = new Expert ();
        $modelU = new UploadForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                $model->password = $model->setPassword($model->password);
                $model->password_repeat = ($model->password);
                $modelU->imageFile = UploadedFile::getInstance($model, 'path');
                if (isset($modelU->imageFile)) {

                    $modelU->path = Url::to('@webroot/img/experts/') . $modelU->imageFile->baseName . '.' . $modelU->imageFile->extension;

                    if ($modelU->upload()) {
                        var_dump($modelU->imageFile);
                        die();
                        // file is uploaded successfully
                        $model->path = $_SERVER["HTTP_ORIGIN"] . Yii::$app->urlManager->baseUrl . "/img/experts/" . $modelU->imageFile->baseName . "." . $modelU->imageFile->extension;
                        if ($model->save()) {
                            $auth = Yii::$app->authManager;
                            $auth->assign($auth->getRole('expert'), $model->id);
                            return $this->redirect([
                                        'view',
                                        'id' => $model->id
                            ]);
                        } else {
                            return $this->render('create', [
                                        'model' => $model
                            ]);
//                            var_dump($model->errors);
                        }
                    }
                } else {
                    if ($model->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    } else {
                        return $this->render('create', [
                                    'model' => $model
                        ]);
                    }
                }
            }
        } else {
            $model->enable = 1;
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Expert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id        	
     * @return mixed
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);
//        $model2 = new Expert ();
        $modelU = new UploadForm();
        $path = $model->path;

        if ($model->load(Yii::$app->request->post())) {
            $modelU->imageFile = UploadedFile::getInstance($model, 'path');
            if (isset($modelU->imageFile)) {
                $modelU->path = Url::to('@webroot/img/experts/') . $modelU->imageFile->baseName . '.' . $modelU->imageFile->extension;
                if ($modelU->upload()) {
                    // file is uploaded successfully
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    $model->path = $_SERVER["HTTP_ORIGIN"] . Yii::$app->urlManager->baseUrl . "/img/experts/" . $modelU->imageFile->baseName . "." . $modelU->imageFile->extension;
                    if ($model->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    } else {
                        var_dump($model->errors);
                    }
                    return;
                }
            } else {
                if ($model->save()) {
                    return $this->redirect([
                                'view',
                                'id' => $model->id
                    ]);
                } else {
                    var_dump($model->errors);
                }
                return;
            }
            return $this->redirect([
                        'view',
                        'id' => $model->id
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Expert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id        	
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect([
                    'index'
        ]);
    }

    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id        	
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Expert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // Android
    public function actionGetExperts() {
        // var_dump($data);
        $hour = $_POST ['hour'];
        $day = $_POST ['day'];
        $service = $_POST ['service'];
        // $model=Expert::find()->select(['expert.id','name','last_name','email','lat','lng'])->where("enable='1' and (schedule.weekday_id='$day' and '$hour' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$service')")->joinwith('schedule')->joinwith('expertHasService')->asArray()->all();
        $model = Expert::find()->select([
                    'expert.id',
                    'name',
                    'last_name',
                    'email',
                    'lat',
                    'lng'
                ])->where([
                    'enable' => '1'
                ])->asArray()->all();

        if ($model != null) {
            $response ["success"] = true;
            $response ['data'] = $model;
        } else {
            $response ["success"] = false;
            $response ["mensaje"] = "Lo sentimos, no hay especialistas";
        }
        $response = json_encode($response);
        header('Content-Type: application/json');
        print $response;
    }

    public function actionLoginExpert() {

        Yii::$app->response->format = 'json';
        $correo = Yii::$app->request->post("login", null);
        $contrasena = Yii::$app->request->post("password", null);
        $gcm_id = Yii::$app->request->post("gcm_id", null);
        $os_id = Yii::$app->request->post("os_id", null);
        $device = Yii::$app->request->post("device", null);

        $searched = Expert::find()
                        ->where([
                            'email' => $correo
                        ])->one();

        if ($searched == null) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Correo incorrecto, verifica la información"
            ];
            return $response;
        }
        if (!$searched->enable == 1) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Este usuario se encuentra deshabilitado"
            ];
            return $response;
        }
        // Usuario existe
        $hash = $searched->password;
        if (!Yii::$app->getSecurity()->validatePassword($contrasena, $hash)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Contraseña incorrecta, intenta de nuevo"
            ];
            return $response;
        }

        // Buscamos y actualizamos el token GCM
        $gcm_token = GcmTokenExpert::find()->where([
                    "expert_id" => $searched->id,
                    "type_token_id" => $device
                ])->one();

        if ($gcm_token != null) {
            $gcm_token->token = $gcm_id;
            $gcm_token->one_signal_token = $os_id;
            $gcm_token->updated_date = date('Y-m-d H:i:s');
            $gcm_token->save();
        } else { // Si no existe, creamos uno nuevo
            $gcm_token = new GcmTokenExpert ();
            $gcm_token->updated_date = date('Y-m-d H:i:s');
            $gcm_token->token = $gcm_id;
            $gcm_token->one_signal_token = $os_id;
            $gcm_token->type_token_id = $device;
            $gcm_token->expert_id = $searched->id;
            $gcm_token->save();
        }

        $response ["success"] = true;
        $response ["data"] = [
            "id" => $searched->id
        ];
        return $response;
    }

    public function actionInmediato() {
        Yii::$app->response->format = 'json';
        $lat = Yii::$app->request->post("lat", null);
        $lng = Yii::$app->request->post("lng", null);
        $expert_id = Yii::$app->request->post("id_especialista", null);
        $event = Yii::$app->request->post("event", null);

        // $_POST['text']='Mensaje desde el servidor: '.$_POST['text'];
        // var_dump(Yii::$app->request->post());
        $pusher = new \Pusher(Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id']);
        // print 'Hell wrld';
        // $_POST['id']=$expert_id;

        $model = Expert::find()->where([
                    'id' => $expert_id
                ])->joinWith('expertHasService')->asArray()->one();

        $data = [
            'lat' => $lat,
            'lng' => $lng,
            'id_especialista' => $expert_id,
            'name' => $model ['name'],
            'last_name' => $model ['last_name'],
            'services' => $model ['expertHasService']
                ]
        ;
        // print_r($data);

        if ($pusher->trigger(Yii::$app->params ['pusher_channel_vip'], $event, $data)) {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Envio ok"
            ];
            return $response;
        } else {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
            return $response;
        }
    }

    /**
     * 
     * @return string
     */
    public function actionInmediatoAssign() {

        Yii::$app->response->format = 'json';

        $lat = Yii::$app->request->post("address_lat", null);
        $lng = Yii::$app->request->post("address_lng", null);
        $address = Yii::$app->request->post("address", null);
        $address_comp = Yii::$app->request->post("address_comp", null);
        $expert_id = Yii::$app->request->post("id_especialista", null);
        $service_id = Yii::$app->request->post("service", null);
        $modifier_id = Yii::$app->request->post("modifier", null);
        $token = Yii::$app->request->post("token", null);
        $event = Yii::$app->request->post("event", null);
        $cupon = Yii::$app->request->post("cupon", null);


        $model_token = LogToken::find()
                ->where([
                    'token' => $token,
                    'status' => 1])
                ->one();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }



        // $_POST['text']='Mensaje desde el servidor: '.$_POST['text'];
        // var_dump(Yii::$app->request->post());
        // print 'Hell wrld';
        // $_POST['id']=$expert_id;

        $model_user = User::findOne([
                    'id' => $model_token->FK_id_user
        ]);

        if ($address_comp != "")
            $address .= " - $address_comp";

        $data = [
            'lat' => $lat,
            'lng' => $lng,
            'id_especialista' => $expert_id,
            'name' => $model_user->first_name,
            'lastname' => $model_user->last_name,
            'address' => $address,
            'id_service' => $service_id,
            'id_modifier' => $modifier_id,
            'user' => $model_user->id,
            'cupon' => $cupon,
            'timestamp' => date("U"),
            'time_wait' => Yii::$app->params ['seconds_wait_inmediato'],
            'type' => Yii::$app->params ['notification_type_assigned_expert_immediate']
                ]
        ;
        //print Yii::$app->params ['pusher_channel_vip']."_$expert_id";

        $pusher = new \Pusher(Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id']);

        if ($pusher->trigger(Yii::$app->params ['pusher_channel_vip'] . "_$expert_id", $event, $data)) {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Envio ok"
            ];
            return $response;
        } else {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
            return $response;
        }
    }

    public function actionInmediatoCancel() {

        Yii::$app->response->format = 'json';

        $lat = Yii::$app->request->post("address_lat", null);
        $lng = Yii::$app->request->post("address_lng", null);
        $expert_id = Yii::$app->request->post("id_especialista", null);
        $service_id = Yii::$app->request->post("service", null);
        $token = Yii::$app->request->post("token", null);
        $event = Yii::$app->request->post("event", null);

        $model_token = LogToken::find()
                ->where([
                    'token' => $token,
                    'status' => 1])
                ->one();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }

        // $_POST['text']='Mensaje desde el servidor: '.$_POST['text'];
        // var_dump(Yii::$app->request->post());
        // print 'Hell wrld';
        // $_POST['id']=$expert_id;

        $model_user = User::findOne([
                    'id' => $model_token->FK_id_user
        ]);
        $data = [
            'lat' => $lat,
            'lng' => $lng,
            'id_especialista' => $expert_id,
            'name' => $model_user->first_name,
            'lastname' => $model_user->last_name,
            'id_service' => $service_id,
            'time_wait' => Yii::$app->params ['seconds_wait_inmediato'],
            'type' => Yii::$app->params ['notification_type_assigned_expert_immediate']
                ]
        ;
        //print Yii::$app->params ['pusher_channel_vip']."_$expert_id";

        $pusher = new \Pusher(Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id']);

        if ($pusher->trigger(Yii::$app->params ['pusher_channel_vip'] . "_$expert_id", $event, $data)) {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Envio ok"
            ];
            return $response;
        } else {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
            return $response;
        }
    }

    public function actionInmediatoConfirm() {

        Yii::$app->response->format = 'json';
        $lat = Yii::$app->request->post("address_lat", null);
        $lng = Yii::$app->request->post("address_lng", null);
        $expert_id = Yii::$app->request->post("id_especialista", null);
        $service_id = Yii::$app->request->post("service", null);
        $modifier_id = Yii::$app->request->post("modifier", null);
        $user_id = Yii::$app->request->post("user", null);
        $address = Yii::$app->request->post("address", null);
        $cupon = Yii::$app->request->post("cupon", null);
        $event = Yii::$app->request->post("event", null);


        // $_POST['text']='Mensaje desde el servidor: '.$_POST['text'];
        // var_dump(Yii::$app->request->post());
        // print 'Hell wrld';
        // $_POST['id']=$expert_id;


        $data = [
            'lat' => $lat,
            'lng' => $lng,
            'id_especialista' => $expert_id,
            //'name' => $model_user->first_name,
            //'lastname' => $model_user->last_name,
            'id_service' => $service_id,
                //'time_wait'=>Yii::$app->params ['seconds_wait_inmediato'] ,
                //'type' => Yii::$app->params ['notification_type_assigned_expert_immediate']
                ]
        ;
        //print Yii::$app->params ['pusher_channel_vip']."_$expert_id";
        // Todo OK, se guarda el servicio
        $model = new AssignedService ();

        $model->address = $address;
        $model->date = date('Y-m-d');
        $model->time = date('H:i:s');
        $model->lat = $lat;
        $model->lng = $lng;
        $model->service_id = $service_id;

        if ($cupon != "") {
            $model_coupon = Coupon::find()->where([
                        'code' => $cupon
                    ])->one();
            $model->coupon_id = $model_coupon->id;
            // Cambiamos el estado del cupon
            $model_coupon->used = 1;
            if (!$model_coupon->save()) {
                $response ["success"] = false;
                $response ["data"] = [
                    "message" => json_encode($model_coupon->getErrors())
                ];
                $response = json_encode($response);
                return $response;
            }
        }

        // $model->expert_id=rand (1,5);
        $model->expert_id = $expert_id;
        $model->user_id = $user_id;
        // Valores por defecto
        $model->id = 0;
        $model->state = 1;
        $model->comment = "";
        $model->city_id = 1;
        $model->immediate = 1;

        // VAlidamos el Nuevo servicio
        if (!$model->validate()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($model->getErrors())
            ];
            $response = json_encode($response);
            header('Content-Type: application/json');
            print $response;
        }
        // Guardamos el Nuevo servicio
        if (!$model->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($model->getErrors())
            ];
            $response = json_encode($response);
            return $response;
        }

        // Si el servicio tiene modificadores, los guardamos
        if (!empty($modifier_id)) {
            $model2 = new AssignedServiceHasModifier ();
            $model2->assigned_service_id = $model->id;
            $model2->modifier_id = $modifier_id;
            // $model2->item_modifier_id = '1';
            if (!$model2->save()) {
                $response ["success"] = false;
                $response ["data"] = [
                    "message" => json_encode($model2->getErrors())
                ];
                $response = json_encode($response);
                return $response;
            }
        }

        $pusher = new \Pusher(Yii::$app->params ['pusher_app_key'], Yii::$app->params ['pusher_app_secret'], Yii::$app->params ['pusher_app_id']);

        if ($pusher->trigger(Yii::$app->params ['pusher_channel_vip'] . "_$expert_id", $event, $data)) {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Envio ok"
            ];
            return $response;
        } else {
            // Yii::$app->response->statusCode = 200;
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
            return $response;
        }
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Get the Photo of the expert
     * 
     * @return json
     */
    public function actionGetPhoto() {

        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post("id", null);
        $token = Yii::$app->request->post("token", null);

        $model_token = LogToken::find()
                ->where([
                    'token' => $token,
                    'status' => 1])
                ->one();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }

        $expert = Expert::find()
                        ->where(['id' => $id])->one();
        if (!empty($expert)) {

            $response ["success"] = True;
            $response ["data"] = [
                "path" => $expert->path
            ];
        } else {
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
        }
        return $response;
    }

    /**
     * Get the Photo of the expert
     * 
     * @return json
     */
    public function actionGetService() {

        Yii::$app->response->format = 'json';

        $id = trim(Yii::$app->request->post("id", null));
        $token = trim(Yii::$app->request->post("token", null));

        $model_token = LogToken::find()
                ->where([
                    'token' => $token,
                    'status' => 1])
                ->one();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }

        $expert = Expert::find()
                ->where(['id' => $id])
                ->one();

        if (!empty($expert)) {

            $response ["success"] = True;
            $response ["data"] = [
                "path" => $expert->path
            ];
        } else {
            $response ["success"] = False;
            $response ["data"] = [
                "message" => "Error"
            ];
        }

        return $response;
    }

}
