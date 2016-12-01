<?php

namespace app\controllers;

use Yii;
use app\models\ServiceHistory;
use app\models\ServiceHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ],
        ];
    }
    /**
     * Finds user by user_email and user_password
     *
     * @param string $email
     * @param string $password
     * @return static|null
     */
    public function Auth($email, $password) {
        // username, password are mandatory fields
        if(empty($email) || empty($password)){
            return null;
        }
        // get user using requested email
        $email = User::findOne([
            'email' => $email,
        ]);
    
        // if no record matching the requested user
        if(empty($email)){
            return null;    
        }
        // validate password        
        $isPass = Yii::$app->security->validatePassword($password, $email->password);
    
        // if password validation fails
        if(!$isPass){                
            return null;            
        }            
    
        // if user validates (both user_email, user_password are valid)
        return $email;
    }

    /**
     * Lists all ServiceHistory models.
     * 
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ServiceHistorySearch ();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ]);
    }

    /**
     * Displays a single ServiceHistory model.
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
     * Creates a new ServiceHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionCreate() {
        $model = new ServiceHistory ();

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
     * Updates an existing ServiceHistory model.
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
     * Deletes an existing ServiceHistory model.
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
     * Finds the ServiceHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id        	
     * @return ServiceHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ServiceHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // Android
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Get Hitorical service of a user
     * 
     * @param string token user
     * @return json response service
     */
    public function actionHistory() {
        
        Yii::$app->response->format = 'json';
        $token = Yii::$app->request->post ( "token", null );
        
        $model_token = LogToken::find ()->where ( [ 
            'token' => $token 
        ])->one ();
//var_dump($model_token);die();
        if ($model_token != null) {
            $model_history = VwServiceHistory::find()
                    ->where(['user_id' => $model_token->FK_id_user, 'status' => 1])
                    ->orderBy(['date' => SORT_DESC])
                    ->asArray()
                    ->all();
            if ($model_history != null) {
                $response ["success"] = true;
                $response ['data'] = $model_history;
            } else {
                $response ["success"] = true;
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

        $token = Yii::$app->request->post("token", null);

        $model_token = LogToken::find()->where([
                    'token' => $token
                ])->one();

        // var_dump($searched);

        if ($model_token != null) {

            $model_history = VwActualService::find()->where([
                        'user_id' => $model_token->FK_id_user
                    ])->asArray()->all();
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

    public function actionPendingService() {
        
        Yii::$app->response->format = 'json';

        $token = Yii::$app->request->post("token", null);

        $model_token = LogToken::find()->where([
                    'token' => $token
                ])->one();

        if ($model_token == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];

            return $response;
        }

        // Busca servicios que haya cancelado el especialista
        $model = ServiceHistory::find()->select(['id', 'date', 'time', 'service_id'])->where([
                    'state' => 1,
                    'user_id' => $model_token->FK_id_user
                ])->asArray()->all();

        foreach ($model as $service) {
            $pay = ServiceHistory::findOne(['id' => $service['id']])->getLastPay();

            if ($pay == null) {
                $response ["success"] = true;
                $response ['data'] = $service;
                $response ['data'] ['price'] = ServiceHistory::findOne(['id' => $service['id']])->getPrice();
                $response ['data'] ['duration'] = ServiceHistory::findOne(['id' => $service['id']])->getDuration();
                $response ['data'] ['service'] = ServiceHistory::findOne(['id' => $service['id']])->getServiceName();
                return $response;
            }
            if ($pay->state == 0) {
                $response ["success"] = true;
                $response ['data'] = $service;
                $response ['data'] ['price'] = ServiceHistory::findOne(['id' => $service['id']])->getPrice();
                $response ['data'] ['duration'] = ServiceHistory::findOne(['id' => $service['id']])->getDuration();
                $response ['data'] ['service'] = ServiceHistory::findOne(['id' => $service['id']])->getServiceName();
                return $response;
            }
        }
        $response ["success"] = true;
        $response ['data'] = null;
        return $response;
    }
    /**
     * Set qualify of service 
     * 
     * @return json response
     */
    
    public function actionQualifyService() {
     
        Yii::$app->response->format = 'json';
        
        $id = Yii::$app->user->identity->id;
        $expert_id = Yii::$app->request->post("expert_id", null);
        $service_id= Yii::$app->request->post("service_id", null);
        $qualify= Yii::$app->request->post("qualify", null);
        $observations= Yii::$app->request->post("observations", null);
        
        if(is_null($expert_id) || empty($expert_id)){
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El ID del Especialista no debe ser Nulo o Vacío."
            ];
            return $response;
        }
        if(is_null($service_id) || empty($service_id)){
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "El ID del Servicio no debe ser Nulo o Vacío."
            ];
            return $response;
        }
        if(is_null($qualify) || empty($qualify)){
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "La Calificación de Experto no debe ser Nulo o Vacío."
            ];
            return $response;
        }
        // Busca el servicio que se calificará
        $model = ServiceHistory::find()
                ->select(['id'])
                ->where([
                    'id' => $service_id,
                    'state' => 1,
                    'expert_id' => $expert_id ,
                    'user_id' => $id
                ])
                ->asArray()
                ->one();
        
        if(isset($model)){
            $serviceH= ServiceHistory::findOne($model["id"]);
           
            $serviceH->qualification = $qualify;
            $serviceH->observations = $observations;
            if($serviceH->update()){
                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "Gracias por calificar este servicio. Es muy importante para nosotros"
                ];
                return $response;
            } else {
                $response ["success"] = true;
                $response ["data"] = [
                    "message" => "Hemos tenido un problema al registrar tu calificación. Intenta de nuevo"
                ];
                return $response;
            }  
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Este servicio no está en nuestros registros."
            ];
            return $response;
        }
        return true;
    }
}
