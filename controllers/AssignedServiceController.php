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
//use yii\base\Object;
//use app\assets\Tpaga\Model\Address;
use app\models\LogToken;
use app\models\Service;
//use app\models\GcmToken;
use app\models\Coupon;
//use app\models\Vertex;
use app\models\Zone;
//use app\models\GcmTokenExpert;
use app\models\Expert;
use app\models\Pay;
//use app\models\VwServiceHistoryExpert;
//use app\models\UserHasAddress;
use app\models\CreditCard;
use app\models\VwActualServiceExpert;
use app\models\VwActualService;
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

    /**
     * Endpoint the Asign Service
     * 
     * @return Json response
     */
    public function actionAssignService() {
	
        Yii::$app->response->format = 'json';

        $address = trim(Yii::$app->request->post("address", ""));
        $address_comp = trim(Yii::$app->request->post("address_comp", ""));
        $date = trim(Yii::$app->request->post("date", ""));
        $time = trim(Yii::$app->request->post("time", ""));
        $service = trim(Yii::$app->request->post("service", ""));
        $token = trim(Yii::$app->request->post("token", ""));
        $modifier = trim(Yii::$app->request->post("modifier", ""));
        $comment = trim(Yii::$app->request->post("comment", ""));
        $lat = trim(Yii::$app->request->post("address_lat", ""));
        $lng = trim(Yii::$app->request->post("address_lng", ""));
        $cupon = trim(Yii::$app->request->post("cupon", ""));   
        
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
                        //      Fortmat Date and Time  //
        if (isset($time) && !empty($time)) {
            $time = date("H:i:s",strtotime($time));
        }
        if (isset($date) && !empty($date)) {
            $date = date("Y-m-d",strtotime($date));
        }
                        //      Validamos la zona de la dorección
        $zone = Zone::getZone ( $lat, $lng );
        if (! $zone) {
            $response ["success"] = false;
            $response ["data"] = [ 
                "message" => "Esta dirección se encuentra fuera de la zona de cobertura" 
            ];
            return $response;
        }
                        //      Buscamos expertos disponibles para ese día y de ese servicio
        $day = date ('N', strtotime($date));
        
        $experts = Expert::find()
            ->where("zone_id=".$zone." AND (schedule.weekday_id=".$day." AND '".$time."' between schedule.start_time AND schedule.finish_time) AND (expert_has_service.service_id=".$service. ") AND expert.enable = 1")
            ->joinwith('schedule')
            ->joinwith('expertHasService')
            ->orderBy(new Expression('rand()'))
            ->all();   

                        // obtenemos la duración del servicio, (duracion serv+ duracion mod)
        $dur_serv = 0;
        $dur_serv += Service::find ()
            ->select(['duration'])
                ->where(['id' => $service])
                ->one()
                ->duration;
        
        if (isset($modifier) && !empty($modifier)){
            $dur_serv += Modifier::find ()
                ->select(['duration'])
                ->where(['id' => $modifier])
                ->one()
                ->duration;
        }	

        foreach ( $experts as $expert ) {
            
            $disponible = $expert->validateDateTime ( $date, $time, $dur_serv );

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

        if (!isset($expert_id) || empty($expert_id)) {

            $response ["success"] = false;
            $response ["data"] = [ 
                "message" => "El servicio no se pudo asignar porque no tenemos especialistas disponibles en este horario ($time $date)" 
            ];
            return $response;
        }
                        //      Todo OK, se guarda el servicio
        $model = new AssignedService ();
        
        if (isset($address_comp) && !empty($address_comp)) {
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

        if (isset($cupon) && !empty($cupon)) {
            
            $model_coupon = Coupon::find ()
                ->where(['code' => $cupon])
                ->one ();
            
            $model->coupon_id = $model_coupon->id;
            // Cambiamos el estado del cupon
            $model_coupon->used = 1;
            
            if (! $model_coupon->save ()) {
                $response ["success"] = false;
                $response ["data"] = [ 
                                "message" => json_encode ( $model_coupon->getErrors () ) 
                ];
                return $response;
            }
        }

                        //      $model->expert_id=rand (1,5);
        $model->expert_id = $expert_id;
        $model->user_id = $model_token->FK_id_user;
                        //      Valores por defecto
        $model->id = 0;
        $model->state = 0;
        $model->city_id = 1;

                        //      VAlidamos el Nuevo servicio
        if (! $model->validate ()) {
            $response ["success"] = false;
            $response ["data"] = [ 
                            "message" => json_encode ( $model->getErrors () ) 
            ];
            return $response;
        }
                        //      Guardamos el Nuevo servicio
        if (! $model->save ()) {
            $response ["success"] = false;
            $response ["data"] = [ 
                            "message" => json_encode ( $model->getErrors () ) 
            ];
            return $response;
        }
                        //      Si el servicio tiene modificadores, los guardamos
        if (! empty ( $modifier )) {
            $model2 = new AssignedServiceHasModifier ();
            $model2->assigned_service_id = $model->id;
            $model2->modifier_id = $modifier;
            // $model2->item_modifier_id = '1';
            if (! $model2->save ()) {
                $response ["success"] = false;
                $response ["data"] = [ 
                                "message" => json_encode ( $model2->getErrors () ) 
                ];
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

//        if ($address_comp != ""){
//                $address .= " - " . $address_comp;
//        }

        $model_user = User::find()
            ->where(['id' => $model_token->FK_id_user ])
            ->one ();

        $tokens = Expert::findOne(["id" => $expert_id ])
                ->getPushTokens ();

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
            'timestamp' => date ( "U" ),
            'time_wait' => Yii::$app->params ['seconds_wait'],
            'type' => Yii::$app->params ['notification_type_assgigned_expert'] 
        ];

        if ($tokens != null){
            Yii::$app->PushNotifier->sendNotificationExpertOS ( "Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens );
        }

        $id_serv = $model->id;
        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        $log = $url . "/web/logs/$id_serv.txt";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date . '" "' . $time . '"';
        // $url="/var/www/html/tiver/web/log_date.txt";
        exec ( "(sleep " . Yii::$app->params ['seconds_wait'] . "; $script >> $log) > /dev/null 2>&1 &" );
        // Insertamos el log
//        var_dump($log, $script, "(sleep " . Yii::$app->params ['seconds_wait'] . "; $script > $log) > /dev/null 2>&1 &");
        // Insertar LOG
        $model_log = new LogAssignedService ();
        $model_log->assigned = "1";
        $model_log->rejected = "0";
        $model_log->missed = "0";
        $model_log->date = $date;
        $model_log->time = $time;
        $model_log->accepted = "0";
        $model_log->attempt = $model->getNumAttempts () + 1;
        $model_log->assigned_service_id = $model->id;
        $model_log->expert_id = $model->expert_id;
        $model_log->save ();
        //

        $response ["success"] = true;
        $response ["data"] = [ 
            "message" => "Nuevo servicio correctamente" 
        ];
        return $response;
    }

    public function actionGetAssignedService() {
        
        Yii::$app->response->format = 'json';
        // var_dump($data);
        $id = $_POST ['id'];

        // $model=Expert::find()->select(['expert.id','name','last_name','email','lat','lng'])->where("enable='1' and (schedule.weekday_id='$day' and '$hour' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$service')")->joinwith('schedule')->joinwith('expertHasService')->asArray()->all();
        $model_history = VwActualServiceExpert::find()->where([
                    'expert_id' => $id,
                    'status' => '1'
                ])->asArray()->all();

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

        $id_user = $model_token->FK_id_user;

        // Buscamos el servicio activo
        $services = assignedService::find()
            ->where(["user_id" => $id_user,
                // "expert_id" => $id_expert,
                "date" => $date,
                "time" => $time])
            ->joinWith('service')
            ->one();

        if(!isset($services) || empty($services)){
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
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
        $tokens = Expert::findOne(["id" => $services->expert_id])
                ->getPushTokens();

        $user = User::findOne(["id" => $id_user]);
        
        $value = $services->getPrice();

                        //      Enviar mail de Servicio Cancelado
        if(isset($user) && !empty($user) && isset($value) && !empty($value)){
            
            $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
            $email = new \SendGrid\Email ();
            $email->setFrom(Yii::$app->params ['sendgrid_from'])
                    ->setFromName(Yii::$app->params ['sendgrid_from_name'])
                    ->addTo($user->email)
                    ->setSubject(' ')
                    ->setHtml(' ')
                    ->setHtml(' ')
                    ->addSubstitution('{{ username }}', [$user->first_name])
                    ->addSubstitution('{{ buydate }}', [$services->date])
                    ->addSubstitution('{{ item.servname }}', [$value])
                    ->addSubstitution('{{ item.servmodif }}', [$value])
                    ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_cancelado']);
            $resp = $sendGrid->send($email);
        }
        $services->delete();

        // print_r($tokens);
        $data = [
            "ticker" => "Servicio cancelado",
            'type' => Yii::$app->params ['notification_type_canceled_expert']
        ];
        
        if ($tokens != null){
            Yii::$app->PushNotifier->sendNotificationExpertOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data, $tokens);
        }
        $model_history = VwActualService::find()
            ->where(['user_id' => $id_user])
            ->asArray()
            ->one();
        
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
                    'state' => 0,
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
        // Buscamos especialistas disponibles para la fecha y hora del servicioi
        $experts = Expert::find()->where(" expert.id<>'$services->expert_id' and  expert.enable='1' and expert.zone_id='$zone' and (schedule.weekday_id='$day' and '$services->time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();

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
            if ($tokens != null){
                Yii::$app->PushNotifier->sendNotificationUserOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data, $tokens);
            }
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

        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $services->id . '" "' . $date . '" "' . $time . '" ';
        $log = $url . "/web/logs/$id.txt";
        exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script > $log) > /dev/null 2>&1 &");
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
                    'state' => 0,
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
                "date" => $date,
                "time" => $time])
            ->joinWith('service')->one();

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
        $experts = Expert::find()->where("expert.id<>'$id_expert' and expert.enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();
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
        if (!$services->update()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => json_encode($services->getErrors())
            ];
            return $response;
        }

        $model_user = User::find()->where([
                    'id' => $id_user
                ])->one();

        // envio de notificacion push OS
        $tokens = Expert::findOne([
                    "id" => $expert_id
                ])->getPushTokens();

        // print_r($tokens);
        $data = [
            "ticker" => "Tienes trabajo",
            'time' => $time,
            'date' => $date,
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
        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        $log = $url . "/web/logs/$id_serv.txt";
        $script = 'php ' . $url . '/./yii tasks/check-service "' . $id_serv . '" "' . $date. '" "' . $time . '"';
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

        $token = Yii::$app->request->post("token", "");
        $time_new = Yii::$app->request->post("time_new", "");
        $time_old = Yii::$app->request->post("time_old", "");
        $date_old = Yii::$app->request->post("date_old", "");
        $date_new = Yii::$app->request->post("date_new", "");
        $comment = Yii::$app->request->post("comment", "");
        
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

        $id_user = $model_token->FK_id_user;
        
        // Buscamos el servicio activo
        $services = assignedService::find()
            ->where([
                "user_id" => $id_user,
                "date" => $date_old,
                "time" => $time_old
            ])
            ->joinWith('service')
            ->one();
        
        if (!isset($services) || empty($services)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            return $response;
        }

        // Validamos la zona de la dirección
        $zone = Zone::getZone($services->lat, $services->lng);
        if (!$zone) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Esta dirección se encuentra fuera de la zona de cobertura"
            ];
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
            $data_new = [
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
                Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data_new, $tokens);
            }
            $tokens_old = Expert::findOne([
                        "id" => $old_expert
                    ])->getPushTokens();

            // print_r($tokens);
            $data_cancel = [
                "ticker" => "Servicio cancelado",
                'type' => Yii::$app->params ['notification_type_canceled_expert']
            ];
            if ($tokens_old != null){
                Yii::$app->PushNotifier->sendNotificationExpertOS("Servicio eliminado", "Se ha cancelado un servicio que tenías asignado", $data_cancel, $tokens_old);
            }
        }

        //
        $id_serv = $services->id;
        $url = Yii::$app->params ['path_scripts'];
        // $url="/var/www/html/tiver";
        $log = $url . "/web/logs/$id_serv.txt";
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

        Yii::$app->response->format = 'json';

        $id_user = Yii::$app->request->post("id_user", "");
        $id_expert = Yii::$app->request->post("id_expert", "");
        $date = Yii::$app->request->post("date", "");
        $time = Yii::$app->request->post("time", "");
        $value = Yii::$app->request->post("value", "");

        $services = AssignedService::find()
                ->where([
                    "user_id" => $id_user,
                    "expert_id" => $id_expert,
                    "date" => $date,
                    "time" => $time,
                    "state" => 1])
                ->joinWith('service')
                ->joinWith('assignedServiceHasModifier.modifier')
                ->one();

        //var_dump($services, $id_user);        die();
        if (!isset($services) || empty($services)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            return $response;
        }
        // Obtener precio del servicio
        if (empty($value)) {
            $value = $services->getPrice();
        }

        $tax = $services->getTax();
        $duracion = ($services->getDuration()) - 15;
        $cupon = "";

        // Check time of checkout
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");

        $time1 = strtotime($date_now . " " . $time_now);
//        $time3 = strtotime($date . " " . $time);
        $time2 = strtotime($date . " " . $time . " +$duracion minute");
        //      Validate if the time is within range
        if ($time1 < $time2) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Aún no puedes finalizar este servicio"
            ];
            return $response;
        }

        //      Find to Credit cart the user
        $credit_card = CreditCard::find()
                ->where([
                    "user_id" => $id_user,
                    "enable" => 1])
                ->one();

        if (!isset($credit_card) || empty($credit_card)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El usuario no tiene tarjetas asociadas"
            ];
            return $response;
        }
        //      Se busca la tarjeta de credito asociada a un cliente en TPAGA
        $user = User::findOne(["id" => $id_user]);
        $info = Yii::$app->TPaga->GetCreditCard($user->tpaga_id, $credit_card->hash);

        if (!isset($info)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, esta tarjeta de credito no existe o no esta asociada a este cliente"
            ];
            return $response;
        }
        //    Service is Canceled
        $canceled = AssignedService::findOne($services->id);

        if (isset($canceled) && !empty($canceled) && !$canceled->delete()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, no se pudo Cancelar este servicio asignado."
            ];
            return $response;
        }

        $tokens = User::findOne([
                    "id" => $id_user])
                ->getPushTokens();

        if (count($canceled) > 0) {
            //      Generate charge on Credit card
            $data_pay = Yii::$app->TPaga->CreateCharge($credit_card->hash, $value, "Servicio Tiver", $tax);

            //      Data send to email the user
            $username = $user->first_name;
            $buydate = date_format(date_create($services->date), 'd-m-Y');
            $useraddress = $services->address;
            $servmodif = (isset($services->assignedServiceHasModifier) && !empty($services->assignedServiceHasModifier)) ? $services->assignedServiceHasModifier[0]->modifier->name : "";
            $servname = (isset($services->service) && !empty($services->service)) ? $services->service->name : "";
            $servesp = $services->getExpertName();
            $cardType = $info->type;
            $cardNumber = $info->last_four;



            //      Si no se realizo el cargo a la tarjeta
            if (!isset($data_pay) || empty($data_pay)) {


                /* $connection = Yii::$app->getDb();
                  $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'], [':user_id' => $id_user, ':id' => '']);
                  $model_history = $command->queryAll(); */


                $model_history = VwActualService::find()->where([
                                    'user_id' => $id_user
                                ])->
                                // 'status' => '1'
                                asArray()->one();
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
                        ->addSubstitution('{{ username }}', [$username])
                        ->addSubstitution('{{ buydate }}', [$buydate])
                        ->addSubstitution('{{ useraddress }}', [$useraddress])
                        ->addSubstitution('{{ item.servmodif }}', [$servmodif])
                        ->addSubstitution('{{ item.servname }}', [$servname])
                        ->addSubstitution('{{ item.servesp }}', [$servesp])
                        ->addSubstitution('{{ usercard }}', [$cardType])
                        ->addSubstitution('{{ usercardnum }}', [$cardNumber])
                        ->addSubstitution('{{ item.prodprecio }}', [$value])
                        ->addSubstitution('{{ total }}', [$value])
                        ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_mora']);

                $resp = $sendGrid->send($email);
                $response = json_encode($response);
                return $response;
            } else {                //      if all is OK
                //      Get info charge to credit card
                $info_charge = Yii::$app->TPaga->GetChargeCreditCard($data_pay->id);
                //      Data send to email the user

                $total = $data_pay->amount;
                $dateTime = date_format(date_create($services->created_date), 'd-m-Y H:i:s');
                $feeNumber = $data_pay->installments;
                $paymentReference = $data_pay->order_id;
                $authorizationNumber = $info_charge->transactionInfo->authorizationCode;
                $replyCode = $info_charge->errorCode . " " . $info_charge->errorMessage;
                $description = $data_pay->description;
                $iva = $data_pay->tax_amount;
                //      Sendgrid Header
                $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
                $email = new \SendGrid\Email ();
                $email->setFrom(Yii::$app->params ['sendgrid_from'])->setFromName(Yii::$app->params ['sendgrid_from_name'])->addTo($user->email)->setSubject(' ')->setHtml(' ')->setHtml(' ');
                //      Generate new Pay
                $pay = new Pay ();
                $paid_pay = $data_pay->paid;

                if ($paid_pay) {

                    $value = $services->setDiscountCoupon($total);
                    $pay->state = 1;
                    $email->addSubstitution('{{ username }}', [$username])
                            ->addSubstitution('{{ buydate }}', [$buydate])
                            ->addSubstitution('{{ useraddress }}', [$useraddress])
                            ->addSubstitution('{{ item.servname }}', [$servname])
                            ->addSubstitution('{{ item.servmodif }}', [$servmodif])
                            ->addSubstitution('{{ item.servesp }}', [$servesp])
                            ->addSubstitution('{{ total }}', [$total])
                            ->addSubstitution('{{ dateTime }}', [$dateTime])
                            ->addSubstitution('{{ cardType }}', [$cardType])
                            ->addSubstitution('{{ cardNumber }}', [$cardNumber])
                            ->addSubstitution('{{ feeNumber }}', [$feeNumber])
                            ->addSubstitution('{{ paymentReference }}', [$paymentReference])
                            ->addSubstitution('{{ authorizationNumber }}', [$authorizationNumber])
                            ->addSubstitution('{{ replyCode }}', [$replyCode])
                            ->addSubstitution('{{ description }}', [$description])
                            ->addSubstitution('{{ iva }}', [$iva])
                            ->addSubstitution('{{ total }}', [$total])
                            ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_compraok']);
                } else {

                    $email->addSubstitution('{{ username }}', [$username])
                            ->addSubstitution('{{ usercard }}', [$username])
                            ->addSubstitution('{{ usercardnum }}', [$cardNumber])
                            ->addSubstitution('{{ buydate }}', [$buydate])
                            ->addSubstitution('{{ useraddress }}', [$useraddress])
                            ->addSubstitution('{{ item.servname }}', [$servname])
                            ->addSubstitution('{{ item.servmodif }}', [$servmodif])
                            ->addSubstitution('{{ item.prodprecio }}', [$value])
                            ->addSubstitution('{{ item.servesp }}', [$servesp])
                            ->addSubstitution('{{ total }}', [$total])
                            ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_mora']);

                    $pay->state = 0;
                }
                $resp = $sendGrid->send($email);

                $pay->message = $data_pay->error_message;
                $pay->hash = $data_pay->id;
                $pay->value = intval($total);

                if (!$pay->save()) {

                    $response ["success"] = true;
                    $response ["data"] = [
                        "message" => "No se pudo guardar el cobro en nuestros registros."
                    ];
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
                //      Enviar notificación push
                /* $connection = Yii::$app->getDb();
                  $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'], [':user_id' => $id_user, ':id' => '']);
                  $model_history = $command->queryAll(); */

                //   Cosulta en produccion
                $model_history = VwActualService::find()->where([
                                    'user_id' => $id_user
                                ])->
                                // 'status' => '1'
                                asArray()->one();

                $actual_service = false;
                if (isset($model_history)) {
                    $actual_service = true;
                }

                $data = [
                    "ticker" => "Qué tal el servicio??",
                    "actual_service" => $actual_service,
                    'type' => Yii::$app->params ['notification_type_checkout_user']
                ];

//                Queda hasta que no se soluciones definitivamente los de Firebase
                if ($paid_pay && isset($tokens) && !empty($tokens)) {
                    Yii::$app->PushNotifier->sendNotificationUserOS("Servicio finalizado", "El servicio ha sido cobrado", $data, $tokens);
                } else {
                    Yii::$app->PushNotifier->sendNotificationUserOS("Servicio finalizado", "Estás en deuda, no se pudo realizar el cobro", $data, $tokens);
                }

                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "Checkout OK"
                ];
                return $response;
            }
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Lo sentimos, este servicio ya fue finalizado o no existe"
            ];
            return $response;
        }
    }

   /* public function actionTestTimer() {
        $id_serv = Yii::$app->request->post('id_serv', 0);
        // $num_intent=Yii::$app->request->post('num_intent',0);
        // $url = "/Applications/MAMP/htdocs/tiver";
        $url = "/var/www/html/tiver";
        $log = $url . "/web/$id_serv.txt";
        $script = "php " . $url . "/./yii tasks/check-service \"$id_serv\" \"1\" ";
        // $url="/var/www/html/tiver/web/log_date.txt";
        exec("(sleep 60; $script > $log) > /dev/null 2>&1 &");
        print "Ok -> $script";
        // exec("echo `date` >> $url | at now + 1 minutes");
    }*/

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
}
