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
        
        if (isset($_POST["Coupon"]) && isset($_POST["UserHasCoupon"])) {

            $model->attributes = $_POST["Coupon"];
            if ($model->save()) {
                //  Asignamos un cupon a un Usuario
                if ($model->type_coupon_id == 1) {

                    $UserHasCoupon->coupon_id = $model->id;
                    $model->discount = 100;
                    $model->save();
                    $UserHasCoupon->user_id = $_POST["UserHasCoupon"]["user_id"];
                    if ($UserHasCoupon->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    } else {

                        return $this->redirect([
                                    'index',
                                    'success' => false
                        ]);
                    }
                } // Asiganamos un cupon a una Categoria
                else if ($model->type_coupon_id == 2 && isset($_POST["asignar2"])) {
                    $CcategoryService->coupon_id = $model->id;
                    $CcategoryService->category_service_id = $_POST["asignar2"];
                    $UserHasCoupon->coupon_id = $model->id;
                    $UserHasCoupon->user_id = $_POST["UserHasCoupon"]["user_id"];

                    if ($UserHasCoupon->save() && $CcategoryService->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    } else {
                        return $this->render('create', [
                                    'model' => $model,
                                    'CcategoryService' => $CcategoryService,
                                    'Cservice' => $Cservice,
                                    'UserHasCoupon' => $UserHasCoupon,
                                    'success' => 0,
                        ]);
                    }
                } // Asignamos un cupon a un Servicio 
                else if ($model->type_coupon_id == 3 && isset($_POST["asignar2"])) {
                    $Cservice->coupon_id = $model->id;
                    $Cservice->service_id = $_POST["asignar2"];
                    $UserHasCoupon->coupon_id = $model->id;
                    $UserHasCoupon->user_id = $_POST["UserHasCoupon"]["user_id"];
                    if ($UserHasCoupon->save() && $Cservice->save()) {
                        return $this->redirect([
                                    'view',
                                    'id' => $model->id
                        ]);
                    } else {
                        return $this->render('create', [
                                    'model' => $model,
                                    'CcategoryService' => $CcategoryService,
                                    'Cservice' => $Cservice,
                                    'UserHasCoupon' => $UserHasCoupon,
                                    'success' => 0,
                        ]);
                    }
                }
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'CcategoryService' => $CcategoryService,
                            'Cservice' => $Cservice,
                            'UserHasCoupon' => $UserHasCoupon,
                            'success' => 0,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'CcategoryService' => $CcategoryService,
                        'Cservice' => $Cservice,
                        'UserHasCoupon' => $UserHasCoupon,
                        'success' => 0,
            ]);
        }
    }

    public function actionGetmodel() {

        if (isset($_POST["select"])) {
            $select = $_POST["select"];
            if ($select == 1) {
                $scri = "<option value='0'>Seleccione</option>";
                $model = FixedAmount::find()->where(['enable' => 1])->orderBy('amount')->asArray()->all();
                foreach ($model as $key => $model2) {
                    $scri .= '<option value="' . $model2["amount"] . '"> $' . $model2["amount"] . '</option>';
                }
            } else if ($select == 2) {
                $scri = "<option value='0'>-- Seleccione una Categoria --</option>";
                $model = CategoryService::find()->orderBy("description")->asArray()->all();
                foreach ($model as $key => $model1) {
                    $scri .= '<option value="' . $model1["id"] . '">' . $model1["description"] . '</option>';
                }
            } else if ($select == 3) {
                $scri = "<option value='0'>-- Seleccione un Servicio --</option>";
                $model = Service::find()->orderBy("name")->asArray()->all();
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

        $model = Coupon::find()
                ->where(['code' => $cupon, 'enable' => '1'])
                ->joinWith(['couponHasCategoryServices'])
                ->joinWith(['couponHasServices'])
                ->asArray()
                ->one();
        
//        var_dump($model); die();
        if (isset($model) && !empty($model)) {
            // Validacion si este servicio esta asociado a este cupon
            $modelS = Coupon::find()
                    ->where(['type_coupon_id' => 3, 'enable' => '1', 'coupon_has_service.service_id' => $id])
                    ->joinwith(['couponHasServices'])
                    ->asArray()->one();
            
//            return var_dump($m    odelS); die();
            if (isset($modelS) && !empty($model)) {
                if ($model ['used'] == '1') {
                    $response ["success"] = false;
                    $response ["data"] = [
                        'message' => 'Lo sentimos, este cupón ya ha sido utilizado'
                    ];
                    return $response;
                } else {
                    return var_dump($modelS); die();
                    switch ($model['type_coupon_id']) {
                        case 1 :                           // Cupón - Monto fijo
//                            $model_token = LogToken::find()
//                                ->where(['token' => $token])
//                                ->one();
//
//                            if (isset($model_token) && !empty($model_token)) {
//                                // buscamos la relación cupon-ususario
//                                $model_search = UserHasCoupon::find()
//                                        ->where(['user_id' => $model_token->FK_id_user,'coupon_id' => $model['id']])
//                                        ->asArray()
//                                        ->one();
//                                if (isset($model_search) && !empty($model_search)) {
//                                    $response ["success"] = true;
//                                    $response ['data'] = $model;
//                                    return $response;
//                                } else {
//                                    $response ["success"] = false;
//                                    $response ["data"] = [
//                                        "message" => "Este cupón se encuentra asignado a otro usuario"
//                                    ];
//                                    return $response;
//                                }
//                            } else {
//                                $response ["success"] = false;
//                                $response ["data"] = [
//                                    "message" => "Token inválido"
//                                ];
//                                return $response;
//                            }
//
//                            break;
                            $response ["success"] = false;
                            $response ["data"] = [
                                "message" => "Este cupón no perteneces a un Servicio."
                            ];
                            return $response;
                            
                        case 2:                           //  Cupón - Categorias
//                            $model_token = LogToken::find()
//                                ->where(['token' => $token])
//                                ->one();
//
//                            if (isset($model_token) && !empty($model_token)) {
//                                // buscamos la relación cupon-ususario
//                                $model_search = UserHasCoupon::find()
//                                        ->where(['user_id' => $model_token->FK_id_user,'coupon_id' => $model['id']])
//                                        ->asArray()
//                                        ->one();
//                                if (isset($model_search) && !empty($model_search)) {
//                                    $model_search = app\models\CouponHasCategoryService::find()
//                                        ->where(['user_id' => $model_token->FK_id_user])
//                                        ->asArray()
//                                        ->one();
//                                        if (isset($model_search) && !empty($model_search)) {
//                                    
//                                        }
//                                    $response ["success"] = true;
//                                    $response ['data'] = $model;
//                                    return $response;
//                                } else {
//                                    $response ["success"] = false;
//                                    $response ["data"] = [
//                                        "message" => "Este cupón se encuentra asignado a otro usuario"
//                                    ];
//                                    return $response;
//                                }
//                            } else {
//                                $response ["success"] = false;
//                                $response ["data"] = [
//                                    "message" => "Token inválido"
//                                ];
//                                return $response;
//                            }
//
//                            break;
                            
                            $response ["success"] = false;
                            $response ["data"] = [
                                "message" => "Este cupón no perteneces a un Servicio."
                            ];
                            return $response;
                        
                        case 3 :                             // Cupón - Servicio
                            $model_token = LogToken::find()
                                ->where(['token' => $token])
                                ->one();

                            if (isset($model_token) && !empty($model_token)) {
                                // buscamos la relación cupon-ususario
                                $model_search = UserHasCoupon::find()
                                        ->where(['user_id' => $model_token->FK_id_user,'coupon_id' => $model['id']])
                                        ->asArray()
                                        ->one();
                                if (isset($model_search) && !empty($model_search)) {
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

    /**
     * Check Coupon Redemption
     * 
     * return json response
     */
    public function actionRedemptionCoupon() {

        Yii::$app->response->format = 'json';
        $id_user = Yii::$app->request->post('id_user', '');
        $id_service = Yii::$app->request->post('id_service', '');

        $userCoupon = UserHasCoupon::find()
                ->where(['user_id' => $id_user, 'user.enable' => 1])
                ->joinWith('user')
                ->asArray()
                ->all();
        if (isset($userCoupon) && !empty($userCoupon)) {
            $search = false;
            $nomCoupon = "";
            $valServicio = 0;
            foreach ($userCoupon as $key => $value) {
                $couponSer = CouponHasService::find()
                        ->where(['coupon_id' => $value['coupon_id'],
                            'service_id' => $id_service,
                            'service.status' => 1])
                        ->joinWith('service')
                        ->joinWith('coupon')
                        ->asArray()
                        ->one();
                if (isset($couponSer) && !empty($couponSer)) {
                    $nomCoupon = $couponSer['coupon']['name'];
                    $valServicio = $couponSer['service']['price'];
                    $search = true;
                    break;
                } else {
                    $search = false;
                }
            }
            if ($search) {
                $response ["success"] = true;
                $response ["data"] = [
                    'nomCoupon' => $nomCoupon,
                    'valService' => $valServicio,
                    'message' => 'Este Usuario tiene un Cupón asociado a este servicio.'
                ];
                return $response;
            } else {
                $response ["success"] = false;
                $response ["data"] = [
                    'nomCoupon' => null,
                    'valCoupon' => null,
                    'message' => 'Lo sentimos, este Cupón no está asociado a este servicio.'
                ];
                return $response;
            }
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                'nomCoupon' => null,
                'valCoupon' => null,
                'message' => 'Lo sentimos, este usuario no tiene cupones asociados.'
            ];
            return $response;
        }
    }

}
