<?php

namespace app\controllers;

use Yii;
use app\models\Coupon;
use app\models\LogToken;
use app\models\CouponSearch;
use app\models\CouponHasCategoryService;
use app\models\CouponHasService;
use app\models\CategoryService;
use app\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UserHasCoupon;

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
        return $this->render('view', [
                    'model' => $this->findModel($id)
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
        $CcategoryService = new CouponHasCategoryService();
        $Cservice = new CouponHasService();

        if(isset($_POST["Coupon"]) && isset($_POST["asignar1"]) && isset($_POST["asignar2"])){
            
            $model->attributes= $_POST["Coupon"];
            if($model->save()){
                if($_POST["asignar1"] == 1){
                    $CcategoryService->coupon_id = $model->id;
                    $CcategoryService->category_service_id = $_POST["asignar2"];
                     if($CcategoryService->save()){
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                     }
                } else if($_POST["asignar1"] == 2){
                    $Cservice->coupon_id = $model->id;
                    $Cservice->service_id = $_POST["asignar2"];
                    if($Cservice->save()){
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                     }
                }    
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'CcategoryService' => $CcategoryService,
                        'Cservice' => $Cservice,
            ]);
        }  
    }
    
    public function actionGetmodel(){
        
        if (isset($_POST["select"])){
            $select = $_POST["select"];
            if($select == 1){
                $scri="<option value='0'>Seleccione </option>";
                $model = CategoryService::find()->asArray()->all();
                foreach ($model as $key => $model1) {
                     $scri.= '<option value="'.$model1["id"].'">'.$model1["description"].'</option>';
      
                }
            }else if($select == 2){
                $scri="<option value='0'>Seleccione</option>";
                $model = Service::find()->asArray()->all();
                foreach ($model as $key => $model2) {
                     $scri.= '<option value="'.$model2["id"].'">'.$model2["name"].'</option>';
                }
            }
        }
        return json_encode(['model'=> $model, 'select' => $select, 'scri'=> $scri]);
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

        Yii::$app->response->format = 'json';

        $model = Coupon::find()->where([
                    'code' => $cupon,
                    'enable' => '1'
                ])->joinwith([
                    'couponHasCategoryService'
                ])->joinwith([
                    'couponHasService'
                ])->asArray()->one();
        // $model=CategoryService::find()->select(['category_service.id','description','status','icon'])->where(['status'=>'1'])->joinwith('service')->asArray()->all();
        // print_r($model);
        if ($model != null) {

            if ($model ['used'] == '1') {
                $response ["success"] = false;
                $response ["data"] = [
                    'message' => 'Lo sentimos, este cupón ya ha sido utilizado'
                ];
                return $response;
            } else {
                //
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
                'message' => 'Lo sentimos, este cupón no existe'
            ];
            return $response;
        }
    }

}
