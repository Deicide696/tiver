<?php

namespace app\controllers;

use Yii;
use app\models\Address;
use app\models\UserHasAddress;
use app\models\AddressSearch;
use app\models\LogToken;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Zone;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends Controller {

    public function behaviors() {
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
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new AddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Address model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Address();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Address model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
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
     * Deletes an existing Address model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Address::findOne($id)) !== null) {
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

    public function actionGetUserAddress() {


        $token = Yii::$app->request->post("token", null);

        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])
                ->one();

        //var_dump($searched);

        if ($model_token != null) {

            $model = Address::find()->select(['address.id', 'address', 'tower_apartment', 'housing_type', 'type_housing_id', 'custom_address', 'lat', 'lng'])->joinwith('userHasAddress')->joinwith('typeHousing')->where(['user_has_address.user_id' => $model_token->FK_id_user, 'enable' => '1'])
                            ->asArray()->all();
            if ($model != null) {
                $response["success"] = true;
                $response['data'] = $model;
            } else {
                $response["success"] = true;
                //$response["data"]=["message"=>"Lo sentimos, no hay historial"];
                $response["data"] = null;
            }
        } else {
            $response["success"] = false;
            $response["data"] = ["message" => "Token inválido"];
        }
        $response = json_encode($response);
        header('Content-Type: application/json');
        print $response;
    }

    public function actionRemoveUserAddress() {

        Yii::$app->response->format = 'json';

        $token = Yii::$app->request->post("token", null);
        $address = Yii::$app->request->post("id_address", null);

        // Validamos el token
        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])
                ->one();
        
        if ($model_token == null) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            //$response = json_encode ( $response );
            return $response;
        }
        $id_user = $model_token->FK_id_user;


        $model_address = \app\models\Address::find()->joinwith('userHasAddress')->where([
                    'user_has_address.user_id' => $model_token->FK_id_user,
                    'id' => $address
                ])->one();
        // print_r($model_address);
        if ($model_address == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Dirección no existe"
            ];
            //$response = json_encode ( $response );
            return $response;
        }
        $model_address->enable = '0';
        if (!$model_address->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "No se pudo eliminar la dirección"
            ];
            //$response = json_encode ( $response );
            return $response;
        } else {
            $response ["success"] = true;
            $response ["data"] = null;
            //$response = json_encode ( $response );
            return $response;
        }
    }

    public function actionAddUserAddress() {
        
        Yii::$app->response->format = 'json';

        $token = Yii::$app->request->post("token", null);
        $address = Yii::$app->request->post("address", null);
        $address_comp = Yii::$app->request->post("address_comp", null);
        $lat = Yii::$app->request->post("lat", null);
        $lng = Yii::$app->request->post("lng", null);
        $address_other = Yii::$app->request->post("address_other", null);
        $type_housing = Yii::$app->request->post("housing_type", null);

        $model_token = LogToken::find()
                ->where(['token' => $token, 'enable' => 1])
                ->one();

        if ($model_token == null) {
            $response["success"] = false;
            $response["data"] = ["message" => "Token inválido"];
            return $response;
        }
        //Validamos la cobertura
        $zone = Zone::getZone($lat, $lng);
        if (!$zone) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Esta dirección se encuentra fuera de la zona de cobertura"
            ];
            return $response;
        }

        $model_address = Address::find()->select([
                    'address.id',
                    'address',
                    'tower_apartment',
                    'housing_type',
                    'type_housing_id'
                ])
                ->joinwith('userHasAddress')
                ->joinwith('typeHousing')
                ->where(['user_has_address.user_id' => $model_token->FK_id_user,'address' => $address, 'tower_apartment' => $address_comp])
                ->asArray()
                ->all();
        
        if ($model_address == null) { // No existe la direccion en DB, se guarda
            $model_address = new Address();
            $model_address->address = $address;
            $model_address->tower_apartment = $address_comp;
            $model_address->id = 0;
            $model_address->custom_address = $address_other;
            $model_address->lat = $lat;
            $model_address->lng = $lng;
            $model_address->type_housing_id = $type_housing;
            
            if ($model_address->validate()){
                if ($model_address->save()) {
                    $model_user_addr = new UserHasAddress ();
                    $model_user_addr->user_id = $model_token->FK_id_user;
                    $model_user_addr->address_id = $model_address->id;
                    $model_user_addr->save();
                    $response["success"] = true;
                    $response["data"] = ["message" => "Dirección guardada correctamente"];
                    return $response;
                } 
            } else {
                if (!$model_address->validate(["address"])) {
                    $response["success"] = false;
                    $response["data"] = ["message" => "El numero de caracteres en el campo de Dirección no debe ser mayor a 100"];
                    return $response;
                }else if (!$model_address->validate(["tower_apartment"])) {
                    $response["success"] = false;
                    $response["data"] = ["message" => "El numero de caracteres en el campo de Indicaciones no debe ser mayor a 100"];
                    return $response;
                }else if (!$model_address->validate(["custom_address"])) {
                    $response["success"] = false;
                    $response["data"] = ["message" => "El numero de caracteres en el campo de Lugar no debe ser mayor a 100"];
                    return $response;
                }else {
                    $response["success"] = false;
                    $response["data"] = ["message" => "No se pudo guardar la dirección por error desconocido."];
                    return $response;
                }
            }
        } else {
            $response["success"] = false;
            $response["data"] = ["message" => "Dirección ya existe"];
            return $response;
        }
    }

}
