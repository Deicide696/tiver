<?php

namespace app\controllers;

use Yii;
use app\models\AssignedService;
use app\models\AssignedServiceHasModifier;
use app\models\ServiceHistoryHasPay;
use app\models\AssignedServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LogToken;
use app\models\Service;
use app\models\Coupon;
use app\models\Zone;
use app\models\Expert;
use app\models\Pay;
use app\models\CreditCard;
use app\models\Modifier;
use app\models\ServiceHistory;
use app\models\User;
use yii\db\Expression;
use app\models\LogAssignedService;

/**
 * AssignedServiceController implements the CRUD actions for AssignedService model.
 */
class AssignedServiceController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all AssignedService models.
     *
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new AssignedServiceSearch ();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ]);
    }

    /**
     * Displays a single AssignedService model.
     *
     * @param integer $id        	
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id)
                ]);
    }

    /**
     * Creates a new AssignedService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate() {
        $model = new AssignedService ();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

    /**
     * Updates an existing AssignedService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id        	
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
     * Deletes an existing AssignedService model.
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
     * Finds the AssignedService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id        	
     * @return AssignedService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AssignedService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    Public function actionSendNotification() {

//            return true;
        $title = $_POST['title'];
        $message = $_POST['message'];
        $address = $_POST ['address'];
//		$address_comp = $_POST ['address_comp'];
//		$date = $_POST ['date'];
        $time = $_POST ['time'];
        $service = $_POST ['service'];
        // $service_name = $_POST ['service_name'];
        $token = $_POST ['token'];
        $modifier = $_POST ['modifier'];
        $comment = $_POST ['comment'];
        $lat = $_POST ['address_lat'];
        $lng = $_POST ['address_lng'];

        $data = [
            "ticker" => "Tienes trabajo",
            'time' => $time,
            'date' => date('Y-m-d'),
            'address' => $address,
            'lat' => $lat,
            'id_serv' => $service,
            'lng' => $lng,
            'name_user' => "Jhonny",
            'lastname_user' => "Romero",
            'id_service' => $service,
            'id_modifier' => $modifier,
            'comments' => $comment,
            'timestamp' => date("U"),
            'time_wait' => Yii::$app->params ['seconds_wait'],
            'type' => Yii::$app->params ['notification_type_assgigned_expert']
        ];
//            return var_dump($data);

        Yii::$app->PushNotifier->sendNotificationExpertOS($title, $message, $data, $token);
    }
    
    /**
     * Assign a service
     * 
     * @return type Service asingned
     */
    public function actionAssignService() { 
        
    
//            return 1;
        
        $address = $_POST ['address'];
        $address_comp = $_POST ['address_comp'];
        $date = $_POST ['date'];
        $time = $_POST ['time'];
        $service = $_POST ['service'];
        $token = $_POST ['token'];
        $modifier = $_POST ['modifier'];
        $comment = $_POST ['comment'];
        $lat = $_POST ['address_lat'];
        $lng = $_POST ['address_lng'];
        $cupon = $_POST ['cupon'];
    
        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])
                ->one();
        
//        return var_dump($model_token);die();
        if (!isset($model_token) || empty($model_token)) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            $response = json_encode($response);
            return $response;
        }

        // Validamos la zona de la dorección
        $zone = Zone::getZone($lat, $lng);
        if (!$zone) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Esta dirección se encuentra fuera de la zona de cobertura"
            ];
            $response = json_encode($response);
            return $response;
        }

        // Buscamos expertos disponibles para ese día y de ese servicio
        $day = date('N', strtotime($date));
//        return var_dump($day);die();
        $experts = Expert::find()
                ->where("enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$service')")
                ->joinwith('schedule')
                ->joinwith('assignedService')
                ->joinwith('expertHasService')
                ->orderBy(new Expression('rand()'))
                ->all();
        
        
        // obtenemos la duración del servicio, (duracion serv+ duracion mod)
        $dur_serv = 0;
        $dur_serv += Service::find()->select(['duration'])
                ->where(['id' => $service])
                ->one()
                ->duration;
        
        
        if (!empty($modifier)){
            $dur_serv += Modifier::find()->select(['duration'])
                    ->where(['id' => $modifier])
                    ->one()
                    ->duration;
        }
//        return var_dump($experts);die();
        $expert_id = 0;
        foreach ($experts as $expert) {
            $disponible = $expert->validateDateTime($date, $time, $dur_serv);
            // print "-disp->".$disponible;

            if ($disponible) { // Si está disponible
                $expert_id = $expert->id;
                break;
            }

            /*
             * exit();
             * // validamos que el experto no tenga ningún servicio a esa hora
             * $assign = AssignedService::find ()->where ( [
             * 'expert_id' => $expert ['id'],
             * 'time' => $time,
             * 'date' => $date
             * ] )->one ();
             * if ($assign == null) {
             * $expert_id = $expert ['id'];
             * break;
             * } else {
             * // si tiene algún servicio, validamos que sea del mismo usuario para podersele asignar
             * if ($assign->user_id == $model_token->FK_id_user) {
             * $expert_id = $expert->id;
             * break;
             * }
             * }
             */
            // en este punto, el especialista ya está ocupado para ese dia y a esa hora, se valida al siguiente especialista
        }
        // print $expert_id;
        // exit();

        if ($expert_id == 0) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El servicio no se pudo asignar porque no tenemos especialistas disponibles en este horario ($time $date)"
            ];
            $response = json_encode($response);
            return $response;
        }

        // Todo OK, se guarda el servicio
        $model = new AssignedService ();
        if ($address_comp != ''){
            $model->address = $address . " - " . $address_comp;
        } else {
            $model->address = $address;
        }
        $model->date = $date;
        $model->time = $time;
        $model->lat = $lat;
        $model->lng = $lng;
        $model->comment = $comment;
        $model->service_id = $service;
            // Si viene con un copon 
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
        $model->user_id = $model_token->FK_id_user;
        // Valores por defecto
        $model->id = 0;
        $model->state = 0;
        $model->city_id = 1;

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
        if (!empty($modifier)) {
            $model2 = new AssignedServiceHasModifier ();
            $model2->assigned_service_id = $model->id;
            $model2->modifier_id = $modifier;
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
        // Enviar notificación push
        /*
         * $gcm_tokens = GcmTokenExpert::find ()->select ( [
         * 'token'
         * ] )->where ( [
         * "expert_id" => $expert_id
         * ] )->all ();
         * foreach ( $gcm_tokens as $gcm_token ) {
         * $tokens [] = $gcm_token->token;
         * }
         * $data = [
         * "ticker" => "Tienes trabajo",
         * 'time' => $time,
         * 'date' => $date,
         * 'address' => $model->address,
         * 'lat' => $lat,
         * 'id_serv' => $model->id,
         * 'lng' => $lng,
         * 'name_user' => $model_user->first_name,
         * 'lastname_user' => $model_user->last_name,
         * 'id_service' => $service,
         * 'id_modifier' => $modifier,
         * 'comments' => $comment,
         * 'timestamp' => date ( "U" ),
         * 'time_wait'=>Yii::$app->params ['seconds_wait'] ,
         * //'id_not' => $model->id,
         * 'type' => Yii::$app->params ['notification_type_assgigned_expert']
         * ];
         *
         * // print_r($data);
         *
         * Yii::$app->PushNotifier->sendNotificationExpert ( $data, $tokens );
         */

        // Enviar notificación push OS

        if ($address_comp != ""){
            $address .= " - " . $address_comp;
        }
        
        $model_user = User::find()
                ->where(['id' => $model_token->FK_id_user])
                ->one();

        $tokens = Expert::findOne(["id" => $expert_id])
                ->getPushTokens();

        $data = [
            "ticker" => "Tienes trabajo",
            'time' => $time,
            'date' => $date,
            'address' => $model->address,
            'lat' => $lat,
            'id_serv' => $model->id,
            'lng' => $lng,
            'name_user' => $model_user->first_name,
            'lastname_user' => $model_user->last_name,
            'id_service' => $service,
            'id_modifier' => $modifier,
            'comments' => $comment,
            'timestamp' => date("U"),
            'time_wait' => Yii::$app->params ['seconds_wait'],
            // 'id_not' => $model->id,
            'type' => Yii::$app->params ['notification_type_assgigned_expert']
        ];
        if ($tokens != null){
            Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens);
        }
        $id_serv = $model->id;
        
        $url=Yii::getAlias('@webroot');
        $log = $url . "/logs/$id_serv.txt";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date . '" "' . $time . '"';
        exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script > $log) > /dev/null 2>&1 &");
        
        // Insertamos el log
        $model_log = new LogAssignedService ();
        $model_log->assigned = "1";
        $model_log->rejected = "0";
        $model_log->missed = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->accepted = "0";
        $model_log->attempt = $model->getNumAttempts() + 1;
        $model_log->assigned_service_id = $model->id;
        $model_log->expert_id = $model->expert_id;
        $model_log->save();
        
        $response ["success"] = true;
        $response ["data"] = [
            "message" => "Nuevo servicio correctamente"
        ];
        $response = json_encode($response);
        return $response;
    }

    public function actionGetAssignedService() {
        Yii::$app->response->format = 'json';
        // var_dump($data);
        $id = $_POST ['id'];

        // $model=Expert::find()->select(['expert.id','name','last_name','email','lat','lng'])->where("enable='1' and (schedule.weekday_id='$day' and '$hour' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$service')")->joinwith('schedule')->joinwith('expertHasService')->asArray()->all();
     
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service_expert'],[':expert_id' => $id, ':status' => 1]);
        $model_history = $command->queryAll();
        
//        $model_history = VwActualServiceExpert::find()->where([
//                    'expert_id' => $id,
//                    'status' => '1'
//                ])->asArray()->all();

        if ($model_history != null) {
            $response ["success"] = true;
            $response ['data'] = $model_history;
            return $response;
        } else {
            $response ["success"] = true;
            $response ["data"] = null;
            return $response;
        }

        print $response;
    }

    public function actionCancelService() {
        
        Yii::$app->response->format = 'json';
        $time = Yii::$app->request->post("time", "");
        $date = Yii::$app->request->post("date", "");
        $token = Yii::$app->request->post("token", "");

        // Validamos el token
        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])->one();
        if ($model_token == null) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            // $response = json_encode ( $response );
            return $response;
        }
        $id_user = $model_token->FK_id_user;

        // Buscamos el servicio activo
        $services = assignedService::find()->where([
                            "user_id" => $id_user,
                            // "expert_id" => $id_expert,
                            "date" => $date,
                            "time" => $time
                        ])->
                        // "state" => 1
                        joinWith('service')->one();

        if ($services == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            // $response = json_encode ( $response );
            return $response;
        }
        // Guardamos la cancelación;
        $services->state = 0;

        // Guardamos los cambios
        if (!$services->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($services->getErrors())
            ];
            return $response;
        }
        $tokens = Expert::findOne([
                    "id" => $services->expert_id
                ])->getPushTokens();

        $user = User::findOne([
                    "id" => $id_user
                ]);
        $value = $services->getPrice();

        // Enviar mail de pago en mora
        $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
        $email = new \SendGrid\Email ();
        $email->setFrom(Yii::$app->params ['sendgrid_from'])->setFromName(Yii::$app->params ['sendgrid_from_name'])->addTo($user->email)->setSubject(' ')->setHtml(' ')->setHtml(' ')->addSubstitution('{{ username }}', [
            $user->first_name
        ])->addSubstitution('{{ buydate }}', [
            $services->date
        ])->addSubstitution('{{ useraddress }}', [
            $services->address
        ])->addSubstitution('{{ item.servname }}', [
            $value
        ])->addSubstitution('{{ item.servmodif }}', [
            $value
        ])->addSubstitution('{{ item.prodprecio }}', [
            $value
        ])->addSubstitution('{{ item.servesp }}', [
            $value
        ])->addSubstitution('{{ total }}', [
            $value
        ])->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_cancelado']);
        $resp = $sendGrid->send($email);

        $services->delete();

        // print_r($tokens);
        $data = [
            "ticker" => "Servicio cancelado",
            'type' => Yii::$app->params ['notification_type_canceled_expert']
        ];
        if ($tokens != null) {
            Yii::$app->PushNotifier->sendNotificationExpertOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data, $tokens);
        }
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => $id_user, ':id' => '']);
        $model_history = $command->queryAll();
        
//        $model_history = VwActualService::find()->where([
//                            'user_id' => $id_user
//                        ])->
//                        // 'status' => '1'
//                        asArray()->one();
        $actual_service = false;
        if ($model_history != null) {
            $actual_service = true;
        }

        $response ["success"] = true;
        $response ["data"] = [
            "message" => "Servicio cancelado correctamente",
            "active_service" => $actual_service
        ];
        return $response;
    }

    public function actionDeclineService() {
        Yii::$app->response->format = 'json';
        $date = Yii::$app->request->post("date", "");
        $time = Yii::$app->request->post("time", "");
        $id = Yii::$app->request->post("id_assigned", "");
        $id_especialista = Yii::$app->request->post("id_especialista", "");

        $services = AssignedService::find()->where([
                    'id' => $id,
                    'state' => '0',
                    'date' => $date,
                    'time' => $time,
                    'expert_id' => $id_especialista
                ])->one();

        if ($services == null) {
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio no existe"
            ];
            return $response;
        }
        // Matamos el proceso anterior
        $script = "kill " . $services->getProcessId();
        exec($script);

        // Buscamos un nuevo especialista para el servicio
        $zone = Zone::getZone($services->lat, $services->lng);
        $day = date('N', strtotime($date));
        // Buscamos especialistas disponibles para la fecha y hora del servicio
        $experts = Expert::find()->where(" expert.id<>'$services->expert_id' and  enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$services->time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();

        foreach ($experts as $expert) {

            $disponible = $expert->validateDateTime($services->date, $services->time, $services->getDuration());
            // print "-disp->".$disponible;
            $model_l = LogAssignedService::find()->where([
                        'assigned_service_id' => $services->id,
                        'expert_id' => $expert->id,
                        'rejected' => '1',
                        'date' => $date,
                        'time' => $time
                    ])->one();
            if ($model_l != null)
                $disponible = false;

            // print "Experto disponible $expert->id $disponible" . PHP_EOL;
            if ($disponible) { // Si está disponible
                $expert_id = $expert->id;
                break;
            }
            // en este punto, el especialista ya está ocupado para ese dia y a esa hora, se valida al siguiente especialista
        }
        // No hay especialistas disponibles, cancelamos el servicio
        if (!isset($expert_id)) {

            $services->delete();
            $tokens = User::findOne([
                        "id" => $services->user_id
                    ])->getPushTokens();

            // print_r($tokens);
            $data = [
                "ticker" => "Servicio cancelado",
                'type' => Yii::$app->params ['notification_type_canceled_expert']
            ];
            if ($tokens != null)
                Yii::$app->PushNotifier->sendNotificationUserOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data, $tokens);
            // print "No hay especialistas disponibles" . PHP_EOL;
            $response ["success"] = true;
            $response ["data"] = [
                "message" => "No hay más especialistas disponibles"
            ];
            return $response;
        }

        // Guardamos el log de rechazo
        $model_log = new LogAssignedService ();
        $model_log->assigned = "0";
        $model_log->rejected = "1";
        $model_log->missed = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->accepted = "0";
        $model_log->attempt = $services->getNumAttempts();
        $model_log->assigned_service_id = $services->id;
        $model_log->expert_id = $services->expert_id;
        $model_log->save();

        // Guardamos la edición
        $services->expert_id = $expert_id;

        // Guardamos los cambios
        if (!$services->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($services->getErrors())
            ];
            return $response;
        }

        // Se lo asignamos al nuevo experto
        $tokens = Expert::findOne([
                    "id" => $expert_id
                ])->getPushTokens();

        // print_r($tokens);
        // //////////
        $model_user = User::find()->where([
                    'id' => $services->user_id
                ])->one();

        $data = [
            "ticker" => "Tienes trabajo",
            'time' => $services->time,
            'date' => $services->date,
            'address' => $services->address,
            'lat' => $services->lat,
            'id_serv' => $services->id,
            'lng' => $services->lng,
            'name_user' => $model_user->first_name,
            'lastname_user' => $model_user->last_name,
            'id_service' => $services->service_id,
            'id_modifier' => $services->getModifierId(),
            'comments' => $services->comment,
            'timestamp' => date("U"),
            'time_wait' => Yii::$app->params ['seconds_wait'],
            'type' => Yii::$app->params ['notification_type_assgigned_expert']
        ];
        if ($tokens != null)
            Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens);
        // //////

//        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        $url=Yii::getAlias('@webroot');
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $services->id . '" "' . $date . '" "' . $time . '" ';
        $log = $url . "/logs/$id.txt";
        exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script >> $log) > /dev/null 2>&1 &");
        // Guardamos el log de asignación
        $model_log = new LogAssignedService ();
        $model_log->assigned = "1";
        $model_log->rejected = "0";
        $model_log->missed = "0";
        $model_log->accepted = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->attempt = $services->getNumAttempts() + 1;
        $model_log->assigned_service_id = $services->id;
        $model_log->expert_id = $services->expert_id;
        $model_log->save();

        $response ["success"] = true;
        $response ["data"] = [
            "message" => "Servicio rechazado"
        ];
        return $response;
    }

    public function actionConfirmService() {
        Yii::$app->response->format = 'json';
        $date = Yii::$app->request->post("date", "");
        $time = Yii::$app->request->post("time", "");
        $id = Yii::$app->request->post("id_assigned", "");
        $id_especialista = Yii::$app->request->post("id_especialista", "");

        $model = AssignedService::find()->where([
                    'id' => $id,
                    'state' => '0',
                    'date' => $date,
                    'time' => $time,
                    'expert_id' => $id_especialista
                ])->one();

        $duration = $model->getDuration();
        $expert = Expert::findOne([
                    'id' => $id_especialista
                ]); // Ya no está disponible para esta hora y fecha
        if (!$expert->validateDateTime($date, $time, $duration)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, no estás disponible a esa hora"
            ];
            return $response;
        }

        if ($model != null) {
            $model->state = 1;
            if ($model->save()) {

                // Guardamos el log de aceptación
                $model_log = new LogAssignedService ();
                $model_log->assigned = "0";
                $model_log->rejected = "0";
                $model_log->missed = "0";
                $model_log->accepted = "1";
                $model_log->date = $date;
                $model_log->time = $time;
                $model_log->attempt = $model->getNumAttempts();
                $model_log->assigned_service_id = $model->id;
                $model_log->expert_id = $model->expert_id;
                $model_log->save();

                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "Nuevo servicio"
                ];
                return $response;
            } else {
                $response ["success"] = false;
                $response ["data"] = [
                    "message" => "No se pudo confirmar, intenta de nuevo"
                ];
                return $response;
            }
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio no existe"
            ];
            return $response;
        }
    }

    public function actionCancelServiceExpert() {
        Yii::$app->response->format = 'json';

        $id_user = $_POST ['id_user'];
        $id_expert = $_POST ['id_expert'];
        $date = $_POST ['date'];
        $time = $_POST ['time'];

        $services = assignedService::find()->where([
                            "user_id" => $id_user,
                            // "expert_id" => $id_expert,
                            "date" => $date,
                            "time" => $time
                        ])->
                        // "state" => 1
                        joinWith('service')->one();

        if ($services == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        // Validamos la zona de la dirección
        $zone = Zone::getZone($services->lat, $services->lng);

        $dur_serv = $services->getDuration();

        $day = date('N', strtotime($date));

        // Buscamos expertos disponibles para ese día y de ese servicio distintos al anterior
        $experts = Expert::find()->where("expert.id<>'$id_expert' and enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();
        foreach ($experts as $expert) {

            $disponible = $expert->validateDateTime($date, $time, $dur_serv);
            // print "-disp->".$disponible;

            if ($disponible) { // Si está disponible
                $expert_id = $expert->id;
                break;
            }
            // en este punto, el especialista ya está ocupado para ese dia y a esa hora, se valida al siguiente especialista
        }

        // No hay especialistas disponibles
        if (!isset($expert_id)) {
            //No hay más especialistas disponibles, se cancela el servicio
            $user = User::findOne([
                        "id" => $id_user
                    ]);
            $value = $services->getPrice();



            // Enviar mail de pago en mora
            // $sendGrid = new \SendGrid ( Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass'] );
            // $email = new \SendGrid\Email ();
            // $email->setFrom ( Yii::$app->params ['sendgrid_from'] )->setFromName ( Yii::$app->params ['sendgrid_from_name'] )->addTo ( $user->email )->setSubject ( ' ' )->setHtml ( ' ' )->setHtml ( ' ' )->addSubstitution ( '{{ username }}', [
            // 		$user->first_name
            // ] )->addSubstitution ( '{{ buydate }}', [
            // 		$services->date
            // ] )->addSubstitution ( '{{ useraddress }}', [
            // 		$services->address
            // ] )->addSubstitution ( '{{ item.servname }}', [
            // 		$value
            // ] )->addSubstitution ( '{{ item.servmodif }}', [
            // 		$value
            // ] )->addSubstitution ( '{{ item.prodprecio }}', [
            // 		$value
            // ] )->addSubstitution ( '{{ item.servesp }}', [
            // 		$value
            // ] )->addSubstitution ( '{{ total }}', [
            // 		$value
            // ] )->addFilter ( 'templates', 'template_id', Yii::$app->params ['sendgrid_template_cancelado'] );
            // $resp = $sendGrid->send ( $email );
            // $services->delete ();



            $tokens = User::findOne(["id" => $services->user_id])->getPushTokens();

            // print_r($tokens);
            $data = [
                "ticker" => "Servicio cancelado",
                'type' => Yii::$app->params ['notification_type_canceled_user']
            ];
            if ($tokens != null)
                Yii::$app->PushNotifier->sendNotificationUserOS("Servicio cancelado", "El especialista no puede atenderte y se ha cancelado el servicio", $data, $tokens);


            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Servicio cancelado"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        // Guardamos la edición
        $services->state = 0;
        $services->expert_id = $expert_id;

        // Guardamos los cambios
        if (!$services->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($services->getErrors())
            ];
            return $response;
        }

        $model_user = User::find()->where([
                    'id' => $model_token->FK_id_user
                ])->one();

        // envio de notificacion push OS
        $tokens = Expert::findOne([
                    "id" => $expert_id
                ])->getPushTokens();

        // print_r($tokens);
        $data = [
            "ticker" => "Tienes trabajo",
            'time' => $time_new,
            'date' => $date_new,
            'address' => $services->address,
            'lat' => $services->lat,
            'id_serv' => $services->id,
            'lng' => $services->lng,
            'name_user' => $model_user->first_name,
            'lastname_user' => $model_user->last_name,
            'id_service' => $services->service_id,
            'id_modifier' => $services->getModifierId(),
            'comments' => $services->comment,
            'timestamp' => date("U"),
            'time_wait' => Yii::$app->params ['seconds_wait'],
            'type' => Yii::$app->params ['notification_type_assgigned_expert']
        ];
        if ($tokens != null)
            Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens);

        //
        $id_serv = $services->id;
//        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        
        $url=Yii::getAlias('@webroot');
        $log = $url . "/logs/$id_serv.txt";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date_new . '" "' . $time_new . '"';
        // $url="/var/www/html/tiver/web/log_date.txt";
        exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script > $log) > /dev/null 2>&1 &");

        // Guardamos el log de rechazo
        $model_log = new LogAssignedService ();
        $model_log->assigned = "0";
        $model_log->rejected = "1";
        $model_log->missed = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->accepted = "0";
        $model_log->attempt = $services->getNumAttempts() + 1;
        $model_log->assigned_service_id = $services->id;
        $model_log->expert_id = $services->expert_id;
        $model_log->save();

        // Guardamos el log de asignacion
        $model_log = new LogAssignedService ();
        $model_log->assigned = "1";
        $model_log->rejected = "0";
        $model_log->missed = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->accepted = "0";
        $model_log->attempt = "1";
        $model_log->assigned_service_id = $services->id;
        $model_log->expert_id = $services->expert_id;
        $model_log->save();
        //

        $response ["success"] = true;
        $response ["data"] = [
            "message" => "Nuevo servicio correctamente"
        ];
        return $response;
    }

    public function actionEditService() {
        
          Yii::$app->response->format = 'json';
        
        $time_new = Yii::$app->request->post("time_new", "");
        $time_old = Yii::$app->request->post("time_old", "");
        $date_old = Yii::$app->request->post("date_old", "");
        $date_new = Yii::$app->request->post("date_new", "");
        $comment = Yii::$app->request->post("comment", "");
        $token = Yii::$app->request->post("token", "");

        // Validamos el token
        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])
                ->one();
        
        if ($model_token == null) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            // $response = json_encode ( $response );
            return $response;
        }
        $id_user = $model_token->FK_id_user;
        // var_dump($id_user);
        // exit();
        // Buscamos el servicio activo
        $services = assignedService::find()->where([
                            "user_id" => $id_user,
                            // "expert_id" => $id_expert,
                            "date" => $date_old,
                            "time" => $time_old
                        ])->
                        // "state" => 1
                        joinWith('service')->one();

        if ($services == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        // Validamos la zona de la dirección
        $zone = Zone::getZone($services->lat, $services->lng);
        if (!$zone) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Esta dirección se encuentra fuera de la zona de cobertura"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        $dur_serv = $services->getDuration();

        $old_expert = $services->expert_id;
        $day = date('N', strtotime($date_new));
        // Primero validamos si el experto asignado puede tomar el servicio en la nueva hora
        $experts = Expert::find()->where("expert.id='$old_expert' and enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$time_new' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->one();
        if ($experts != null) {
            if ($experts->validateDateTimeActualService($date_new, $time_new, $dur_serv, $services->id, true)) {
                $expert_id = $old_expert;
            }
        }

        if (!isset($expert_id)) {
            // Si no está disponible el experto Buscamos expertos disponibles para ese día y de ese servicio
            $experts = Expert::find()->where("enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$time_new' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();
            foreach ($experts as $expert) {

                $disponible = $expert->validateDateTime($date_new, $time_new, $dur_serv);
                // print "-disp->".$disponible;

                if ($disponible) { // Si está disponible
                    $expert_id = $expert->id;
                    break;
                }
                // en este punto, el especialista ya está ocupado para ese dia y a esa hora, se valida al siguiente especialista
            }
        }

        // No hay especialistas disponibles
        if (!isset($expert_id)) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El servicio no se pudo editar porque no tenemos especialistas disponibles, intenta en otro horario"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        // Guardamos la edición
        $services->state = 0;
        $services->date = $date_new;
        $services->time = $time_new;
        $services->comment = $comment;

        $services->expert_id = $expert_id;

        // Guardamos los cambios
        if (!$services->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($services->getErrors())
            ];
            return $response;
        }

        $model_user = User::find()->where([
                    'id' => $model_token->FK_id_user
                ])->one();

        // el experto es el mismo, se envía notificacion de edicion
        if ($old_expert == $expert_id) {
            // envio de notificacion push OS
            $tokens = Expert::findOne([
                        "id" => $expert_id
                    ])->getPushTokens();

            // print_r($tokens);
            $data = [
                "ticker" => "Tienes trabajo",
                'time' => $time_new,
                'date' => $date_new,
                'address' => $services->address,
                'lat' => $services->lat,
                'id_serv' => $services->id,
                'lng' => $services->lng,
                'name_user' => $model_user->first_name,
                'lastname_user' => $model_user->last_name,
                'id_service' => $services->service_id,
                'id_modifier' => $services->getModifierId(),
                'comments' => $services->comment,
                'timestamp' => date("U"),
                'time_wait' => Yii::$app->params ['seconds_wait'],
                'type' => Yii::$app->params ['notification_type_edited_expert']
            ];
            if ($tokens != null){
                Yii::$app->PushNotifier->sendNotificationExpertOS("Servicio editado", "Se ha editado un servicio que tenías asignado", $data, $tokens);
        
            }
        } else {
            // envio de notificacion push OS
            $tokens = Expert::findOne([
                        "id" => $expert_id
                    ])->getPushTokens();

            // print_r($tokens);
            $data = [
                "ticker" => "Tienes trabajo",
                'time' => $time_new,
                'date' => $date_new,
                'address' => $services->address,
                'lat' => $services->lat,
                'id_serv' => $services->id,
                'lng' => $services->lng,
                'name_user' => $model_user->first_name,
                'lastname_user' => $model_user->last_name,
                'id_service' => $services->service_id,
                'id_modifier' => $services->getModifierId(),
                'comments' => $services->comment,
                'timestamp' => date("U"),
                'time_wait' => Yii::$app->params ['seconds_wait'],
                'type' => Yii::$app->params ['notification_type_assgigned_expert']
            ];
            if ($tokens != null){
                Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens);
            }
            $tokens_old = Expert::findOne([
                        "id" => $old_expert
                    ])->getPushTokens();

            // print_r($tokens);
            $data = [
                "ticker" => "Servicio cancelado",
                'type' => Yii::$app->params ['notification_type_canceled_expert']
            ];
            if ($tokens_old != null){
                Yii::$app->PushNotifier->sendNotificationExpertOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data, $tokens_old);
        
            }
        }

        //
        $id_serv = $services->id;
//        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        
        $url=Yii::getAlias('@webroot');
        $log = $url . "/logs/$id_serv.txt";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date_new . '" "' . $time_new . '"';
        // $url="/var/www/html/tiver/web/log_date.txt";
        exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script > $log) > /dev/null 2>&1 &");
        // Guardamos el log de asignacion
        $model_log = new LogAssignedService ();
        $model_log->assigned = "1";
        $model_log->rejected = "0";
        $model_log->missed = "0";
        $model_log->date = $date_new;
        $model_log->time = $time_new;
        $model_log->accepted = "0";
        $model_log->attempt = "1";
        $model_log->assigned_service_id = $services->id;
        $model_log->expert_id = $services->expert_id;
        $model_log->save();
        //

        $response ["success"] = true;
        $response ["data"] = [
            "message" => "Nuevo servicio correctamente"
        ];
        return $response;
    }

    public function actionCheckoutExpert() {
        
        $id_user = $_POST ['id_user'];
        $id_expert = $_POST ['id_expert'];
        $date = $_POST ['date'];
        $time = $_POST ['time'];
        $value = $_POST ['value'];
        // $value=1;
        // $model=Expert::find()->select(['expert.id','name','last_name','email','lat','lng'])->where("enable='1' and (schedule.weekday_id='$day' and '$hour' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$service')")->joinwith('schedule')->joinwith('expertHasService')->asArray()->all();

        $services = assignedService::find()->where([
                    "user_id" => $id_user,
                    "expert_id" => $id_expert,
                    "date" => $date,
                    "time" => $time,
                    "state" => 1
                ])->joinWith('service')->one();
//        return var_dump($services);die();
        if (isset($services) && !empty($services)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            $response = json_encode($response);
            return $response;
        }

        // Obtener precio del servicio
       if($value == ""){
           $value = $services->getPrice();
       } 
       
        $tax = $services->getTax();
        return var_dump($tax);die();
        $duracion = ($services->getDuration()) - 15;
        // $value=2;
        $cupon = "";
        // Check time of checkout
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");

        $time1 = strtotime($date_now . " " . $time_now);
        $time2 = strtotime($date . " " . $time . " +$duracion minute");
        var_dump($time2," . ",$time2);
        if ($time1 < $time2) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Aún no puedes finalizar este servicio"
            ];
            $response = json_encode($response);
            return $response;
        }

        //

        $credit_card = CreditCard::find()->where([
                    "user_id" => $id_user,
                    "enable" => 1
                ])->one();
        if ($credit_card == null) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El usuario no tiene tarjetas asociadas"
            ];
            $response = json_encode($response);
            return $response;
        }

        $deleted = AssignedService::deleteAll([
                    "user_id" => $id_user,
                    "expert_id" => $id_expert,
                    "date" => $date,
                    "time" => $time
                ]);
        // "state" => 1

        $tokens = User::findOne([
                    "id" => $id_user
                ])->getPushTokens();
        var_dump("DELETE: ",$deleted);die();
        if ($deleted > 0) {
            // Si hay cupon, se omite la peticion a TPaga
            /*
             * if ($cupon != "") {
             * // Enviar notificación push
             *
             * $model_history = VwServiceHistory::find ()->where ( [
             * 'user_id' => $id_user,
             * 'status' => '1'
             * ] )->asArray ()->one ();
             * $actual_service = false;
             * if ($model_history != null) {
             * $actual_service = true;
             * }
             *
             * /*$data = array (
             * "title" => "Servicio finalizado",
             * "body" => "El servicio ha sido cobrado",
             * "ticker" => "Qué tal el servicio??",
             * "actual_service" => $actual_service,
             * 'type' => Yii::$app->params ['notification_type_checkout_user']
             * );
             *
             * $data=["ticker" => "Qué tal el servicio??",
             * "actual_service" => $actual_service,
             * 'type' => Yii::$app->params ['notification_type_checkout_user'] ];
             * Yii::$app->PushNotifier->sendNotificationUserOS ( "Servicio finalizado", "El servicio ha sido cobrado",$data,$tokens );
             *
             * //Yii::$app->PushNotifier->sendNotificationUser ( $data, $tokens );
             *
             * // print_r($tokens);
             *
             * //
             * $response ["success"] = true;
             * $response ["data"] = [
             * "message" => "Checkout OK"
             * ];
             * $response = json_encode ( $response );
             * return $response;
             * }
             */

    
            $data_pay = Yii::$app->TPaga->CreateCharge($credit_card->hash, $value, "Servicio Tiver", $tax);

            var_dump("DATA_PAY",$data_pay);die();

            $user = User::findOne([
                        "id" => $id_user
                    ]);
            $info = Yii::$app->TPaga->GetCreditCard($user->tpaga_id, $credit_card->hash);
            $last_four = $info->last_four;
            $type = $info->type;

            // $id_pay = Yii::$app->TPaga->CreateCharge ( $credit_card->hash, $value, "Servicio Tiver" );
            if (!$data_pay) {
                /*
                 * $data = array (
                 * "title" => "Error al finalizar servicio",
                 * "body" => "Estás en deuda, no se pudo realizar el cobro",
                 * "ticker" => "Oooops, algo ha salido mal",
                 * 'type' => Yii::$app->params ['notification_type_checkout_user']
                 * );
                 *
                 * Yii::$app->PushNotifier->sendNotificationUser ( $data, $tokens );
                 */
                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => $id_user,':id' => '']);
                $model_history = $command->queryAll();
                
//              $model_history = VwActualService::find()
//                  ->where(['user_id' => $id_user])
//                  ->asArray()->one();                 
                
                $actual_service = false;
                if ($model_history != null) {
                    $actual_service = true;
                }

                $data = [
                    "ticker" => "Oooops, algo ha salido mal",
                    "actual_service" => $actual_service,
                    'type' => Yii::$app->params ['notification_type_checkout_user']
                ];
                Yii::$app->PushNotifier->sendNotificationUserOS("Error al finalizar servicio", "Estás en deuda, no se pudo realizar el cobro", $data, $tokens);

                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "No se pudo realizar el cobro"
                ];

                // Enviar mail de pago en mora
                $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
                $email = new \SendGrid\Email ();
                $email->setFrom(Yii::$app->params ['sendgrid_from'])
                    ->setFromName(Yii::$app->params ['sendgrid_from_name'])
                    ->addTo($user->email)->setSubject(' ')->setHtml(' ')->setHtml(' ')
                    ->addSubstitution('{{ username }}', [$user->first_name])
                    ->addSubstitution('{{ usercard }}', [$type])
                    ->addSubstitution('{{ usercardnum }}', [$last_four])
                    ->addSubstitution('{{ buydate }}', [$services->date])
                    ->addSubstitution('{{ useraddress }}', [$services->address])
                    ->addSubstitution('{{ item.servname }}', [$value])
                    ->addSubstitution('{{ item.servmodif }}', [$value])
                    ->addSubstitution('{{ item.prodprecio }}', [$value])
                    ->addSubstitution('{{ item.servesp }}', [$value])
                    ->addSubstitution('{{ total }}', [$value])
                    ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_mora']);
                
                $resp = $sendGrid->send($email);

                //

                $response = json_encode($response);
                return $response;
            } else {
                $id_pay = $data_pay->id;
                $message_pay = $data_pay->error_message;
                $paid_pay = $data_pay->paid;

                $pay = new Pay ();

                // Enviar mail de pago en mora
                $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
                $email = new \SendGrid\Email ();
                $email->setFrom(Yii::$app->params ['sendgrid_from'])->setFromName(Yii::$app->params ['sendgrid_from_name'])->addTo($user->email)->setSubject(' ')->setHtml(' ')->setHtml(' ');

                if ($paid_pay) {
                    $pay->state = 1;
                    $email->addSubstitution('{{ username }}', [$user->first_name])
                            -> addSubstitution('{{ buydate }}', [$services->date])
                            // ->addSubstitution('{{ usercard }}',[$type)
                            // ->addSubstitution('{{ usercardnum }}',[$last_four)
                            ->addSubstitution('{{ useraddress }}', [$services->address])
                            ->addSubstitution('{{ item.servname }}', [$value])
                            ->addSubstitution('{{ item.servmodif }}', [$value])
                            ->addSubstitution('{{ item.prodprecio }}', [$value])
                            ->addSubstitution('{{ item.servesp }}', [$value])
                            ->addSubstitution('{{ total }}', [$value])
                            ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_compraok']);
                } else {

                    $email->addSubstitution('{{ username }}', [$user->first_name])
                            ->addSubstitution('{{ usercard }}', [$type])
                            ->addSubstitution('{{ usercardnum }}', [$last_four])
                            ->addSubstitution('{{ buydate }}', [$services->date])
                            ->addSubstitution('{{ useraddress }}', [$services->address])
                            ->addSubstitution('{{ item.servname }}', [$value])
                            ->addSubstitution('{{ item.servmodif }}', [$value])
                            ->addSubstitution('{{ item.prodprecio }}', [$value])
                            ->addSubstitution('{{ item.servesp }}', [$value])
                            ->addSubstitution('{{ total }}', [$value])
                            ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_mora']);

                    $pay->state = 0;
                }
                $resp = $sendGrid->send($email);

                $pay->message = $message_pay;
                $pay->hash = $id_pay;
                $pay->value = $value;
                if (!$pay->save()) {

                    $response ["success"] = true;
                    $response ["data"] = [
                        "message" => "No se pudo guardar el cobro"
                    ];
                    $response = json_encode($response);
                    return $response;
                }
                $services_h = ServiceHistory::find()->where([
                                    "user_id" => $id_user,
                                    "expert_id" => $id_expert,
                                    "date" => $date,
                                    "time" => $time
                                ])->
                                // "state" => 1
                                asArray()->all();

                foreach ($services_h as $row) {
                    $pay_service = new ServiceHistoryHasPay ();
                    $pay_service->pay_id = $pay->id;
                    $pay_service->service_history_id = $row ['id'];
                    $pay_service->save();
                }
                // Enviar notificación push
                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => $id_user,':id' => '']);
                $model_history = $command->queryAll();
//                $model_history = VwActualService::find()->where([
//                                    'user_id' => $id_user
//                                ])->
//                                // 'status' => '1'
//                                asArray()->one();
                $actual_service = false;
                if ($model_history != null) {
                    $actual_service = true;
                }

                /*
                 * $data = array (
                 * "title" => "Servicio finalizado",
                 * "body" => "El servicio ha sido cobrado",
                 * "ticker" => "Qué tal el manicure??",
                 * "actual_service" => $actual_service,
                 * 'type' => Yii::$app->params ['notification_type_checkout_user']
                 * );
                 * Yii::$app->PushNotifier->sendNotificationUser ( $data, $tokens );
                 */
                $data = [
                    "ticker" => "Qué tal el servicio??",
                    "actual_service" => $actual_service,
                    'type' => Yii::$app->params ['notification_type_checkout_user']
                ];
                if ($paid_pay)
                    Yii::$app->PushNotifier->sendNotificationUserOS("Servicio finalizado", "El servicio ha sido cobrado", $data, $tokens);
                else
                    Yii::$app->PushNotifier->sendNotificationUserOS("Servicio finalizado", "Estás en deuda, no se pudo realizar el cobro", $data, $tokens);
                //
                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "Checkout OK"
                ];
                $response = json_encode($response);
                return $response;
            }
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            $response = json_encode($response);
            return $response;
        }
        // /
        // $id_credit_card_created="n6ps53q0i58nhg1glbeamlf5nhj1qikt";
    }

    /*
     * public function actionTestTimer() {
     * $id_serv = Yii::$app->request->post ( 'id_serv', 0 );
     * // $num_intent=Yii::$app->request->post('num_intent',0);
     *
     * $url = "/Applications/MAMP/htdocs/tiver";
     * // $url="/var/www/html/tiver";
     * $log = $url . "/web/$id_serv.txt";
     * $script = "php " . $url . "/./yii tasks/check-service \"$id_serv\" \"1\" ";
     * // $url="/var/www/html/tiver/web/log_date.txt";
     * exec ( "(sleep 60; $script > $log) > /dev/null 2>&1 &" );
     * print "Ok -> $script";
     * // exec("echo `date` >> $url | at now + 1 minutes");
     * }
     */

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

}
