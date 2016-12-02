<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Coupon;
use app\models\LogToken;
use app\models\CouponSearch;
use app\models\CouponHasCategoryService;
use app\models\CouponHasService;
use app\models\CategoryService;
use app\models\Service;
use app\models\UserHasCoupon;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller {

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
     * Lists all Coupon models.
     * 
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CouponSearch ();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Coupon model.
     * 
     * @param integer $id        	
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $user = new User();
        $userHasCoupon = new UserHasCoupon();
//        print_r($model);die();

        return $this->render('view', [
                    'model' => $model,
                    'user' => $user,
                    'userHasCoupon' => $userHasCoupon,
        ]);
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionCreate() {

        $model = new Coupon ();
        $UserHasCoupon = new UserHasCoupon();
        $CcategoryService = new CouponHasCategoryService();
        $Cservice = new CouponHasService();

        if (isset($_POST["Coupon"]) && isset($_POST["asignar2"])) {

            $model->attributes = $_POST["Coupon"];

            if ($model->save()) {
                //  Asignamos un cupon a un Usuario
                if ($model->type_coupon_id == 1) {
                    $UserHasCoupon->coupon_id = $model->id;
                    $UserHasCoupon->user_id = $_POST["asignar2"];
                    if ($UserHasCoupon->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    }
                } // Asiganamos un cupon a una Categoria
                else if ($model->type_coupon_id == 2) {
//                    var_dump($CcategoryService);die();
                    $CcategoryService->coupon_id = $model->id;
                    $CcategoryService->category_service_id = $_POST["asignar2"];
                    if ($CcategoryService->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    }
                } // Asignamos un cupon a un Servicio 
                else if ($model->type_coupon_id == 3) {
                    $Cservice->coupon_id = $model->id;
                    $Cservice->service_id = $_POST["asignar2"];
                    if ($Cservice->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    }
                }
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'CcategoryService' => $CcategoryService,
                            'Cservice' => $Cservice,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'CcategoryService' => $CcategoryService,
                        'Cservice' => $Cservice,
            ]);
        }
    }

    public function actionGetmodel() {

        if (isset($_POST["select"])) {
            $select = $_POST["select"];
            if ($select == 1) {
                $scri = "<option value='0'>Seleccione</option>";
                $model = User::find()->where(['enable' => 1])->orderBy('first_name')->asArray()->all();
                foreach ($model as $key => $model2) {
                    $scri .= '<option value="' . $model2["id"] . '">' . $model2["first_name"] . ' ' . $model2["last_name"] . '</option>';
                }
            } else if ($select == 2) {
                $scri = "<option value='0'>Seleccione </option>";
                $model = CategoryService::find()->asArray()->all();
                foreach ($model as $key => $model1) {
                    $scri .= '<option value="' . $model1["id"] . '">' . $model1["description"] . '</option>';
                }
            } else if ($select == 3) {
                $scri = "<option value='0'>Seleccione</option>";
                $model = Service::find()->asArray()->all();
                foreach ($model as $key => $model2) {
                    $scri .= '<option value="' . $model2["id"] . '">' . $model2["name"] . '</option>';
                }
            }
        }
        return json_encode(['model' => $model, 'select' => $select, 'scri' => $scri]);
    }

    /**
     * Updates an existing Coupon model.
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
     * Deletes an existing Coupon model.
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
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id        	
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Coupon::findOne($id)) !== null) {
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

    public function actionCheckCoupon() {

        $cupon = Yii::$app->request->post('cupon', '');
        $token = Yii::$app->request->post('token', '');
        $id = Yii::$app->request->post('service_id', '');
        Yii::$app->response->format = 'json';

        $model = Coupon::find()->where([
                    'code' => $cupon,
                    'enable' => '1'
                ])->joinwith([
                    'couponHasCategoryService'
                ])->joinwith([
                    'couponHasService'
                ])->asArray()->one();

        if (isset($model)) {
            // Validacion si este servicio esta asociado a este cupon
            $modelS = Coupon::find()->where([
                        'type_coupon_id' => 3,
                        'enable' => '1',
                        'coupon_has_service.service_id' => $id
                    ])->joinwith([
                    'couponHasService'
                    ])->asArray()->one();
//            return var_dump($modelS);
            if (isset($modelS)) {
                if ($model ['used'] == '1') {
                    $response ["success"] = false;
                    $response ["data"] = [
                        'message' => 'Lo sentimos, este cupón ya ha sido utilizado'
                    ];
                    return $response;
                } else {
                    switch ($model['type_coupon_id']) {
                        case 1 : // Caso coupon - usuario
                            $model_token = LogToken::find()->where([
                                        'token' => $token
                                    ])->one();

                            if ($model_token != null) {
                                // buscamos la relación cupon-ususario
                                $model_search = UserHasCoupon::find()->where([
                                            'user_id' => $model_token->FK_id_user,
                                            'coupon_id' => $model['id']
                                        ])->asArray()->one();
                                if ($model_search != null) {
                                    $response ["success"] = true;
                                    $response ['data'] = $model;
                                    return $response;
                                } else {
                                    $response ["success"] = false;
                                    $response ["data"] = [
                                        "message" => "Este cupón se encuentra asignado a otro usuario"
                                    ];
                                    return $response;
                                }
                            } else {
                                $response ["success"] = false;
                                $response ["data"] = [
                                    "message" => "Token inválido"
                                ];
                                return $response;
                            }

                            break;
                        case 2://CAso cuponcategoria
                            $response ["success"] = true;
                            $response ['data'] = $model;
                            return $response;
                            break;
                    }
                }
            } else {
                $response ["success"] = false;
                $response ["data"] = [
                    'message' => 'Lo sentimos, este cupón no está asignado a este servicio.'
                ];
                return $response;
            }
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                'message' => 'Lo sentimos, este cupón no existe'
            ];
            return $response;
        }
    }

}
