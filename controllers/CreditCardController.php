<?php

namespace app\controllers;

use Yii;
use app\models\CreditCard;
use app\models\CreditCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\LogToken;
use yii\filters\VerbFilter;
use app\models\User;
use yii\base\Object;

/**
 * CreditCardController implements the CRUD actions for CreditCard model.
 */
class CreditCardController extends Controller {

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
     * Lists all CreditCard models.
     * 
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CreditCardSearch ();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single CreditCard model.
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
     * Creates a new CreditCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionCreate() {
        $model = new CreditCard ();

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
     * Updates an existing CreditCard model.
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
     * Deletes an existing CreditCard model.
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

    public function actionDeactive() {
        
        Yii::$app->response->format = 'json';

        $token = Yii::$app->request->post("token", null);
        $card = Yii::$app->request->post("id_card", null);

        // Validamos el token
        $model_token = LogToken::find ()
            ->where ([
                'token' => $token, 
                'status' => 1])
            ->one ();

        if (!isset($model_token) || empty($model_token)) {

            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }
        
        $id_user = $model_token->FK_id_user;

        $model_card = CreditCard::find()->where([
                    'user_id' => "$id_user",
                    'id' => "$card"
                ])->one();

        if ($model_card == null) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Tarjeta inexistente"
            ];
            // $response = json_encode ( $response );
            return $response;
        }

        $model_card->enable = '0';
        if (!$model_card->save()) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "No se pudo eliminar la tarjeta"
            ];
            // $response = json_encode ( $response );
            return $response;
        } else {
            $model_cc = CreditCard::find()->where([
                        'user_id' => $id_user,
                        'enable' => '1'
                    ])->count();

            $response ["success"] = true;
            $response ["data"] = [
                "message" => "Tarjeta eliminada",
                "count" => "$model_cc"
            ];
            // $response = json_encode ( $response );
            return $response;
        }
    }

    public function actionAdd() {
        
        Yii::$app->response->format = 'json';

        $token = Yii::$app->request->post("token", null);
        $token_card = Yii::$app->request->post("token_card", null);
        $token_body = Yii::$app->request->post("token_body", null);
        
        $model_token = LogToken::find ()
            ->where ([
                'token' => $token, 
                'status' => 1])
            ->one ();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }

        $model_user = User::find()
            ->where([
                'id' => $model_token->FK_id_user])
            ->one();
        
        if (!isset($model_user) || empty($model_user)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Usuario no existe"
            ];
        }
//        var_dump($model_user);die();
        
        // crear tarjeta de crédito
        //$city = new \app\assets\Tpaga\Model\City ();
        //$city->country = "CO";
        //$city->state = "DC";
        //$city->name = "Bogotá";
        //	$address = new \app\assets\Tpaga\Model\Address ();
        //$address->address_line1 = "Cualquier direccion";
        //$address->postal_code = "12345";
        //$address->city = $city;
        //$name = $model_user->first_name . " " . $model_user->last_name;
        // print $model_user->tpaga_id;
        $id_credit_card_created = Yii::$app->TPaga->CreateCreditCardToken($model_user->tpaga_id, $token_body);

        //if (! $id_credit_card_created) {
        //$response ["success"] = false;
        //$response ["data"] = [ 
        //	"message" => "No se pudo crear la tarjeta" 
        //];
        //} else {
        $model_card = new CreditCard ();
        $model_card->hash = $id_credit_card_created;
        $model_card->user_id = $model_user->id;
        if ($model_card->save()) {
            $response ["success"] = true;
            $response ['data'] = $model_user;
            return $response;
        } else {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "No se pudo guardar la tarjeta"
            ];
            return $response;
        }
        //}
    //
		
	
	}

    public function actionGetCreditCard() {

        Yii::$app->response->format = 'json';
        $token = Yii::$app->request->post("token", null);

        $model_token = LogToken::find ()
            ->where ([
                'token' => $token, 
                'status' => 1])
            ->one ();

        if (!isset($model_token) || empty($model_token)) {
            $response ["success"] = false;
            $response ["data"] = [
                "message" => "Token inválido"
            ];
            return $response;
        }

        $model_creditcard = CreditCard::find()
                ->where(['user_id' => $model_token->FK_id_user])
                ->asArray()->all();

        if ($model_creditcard != null) {
            $model_user = User::find()->where([
                        'id' => $model_token->FK_id_user
                    ])->one();

            foreach ($model_creditcard as $tarjeta) {

                //print PHP_EOL."getting -> ".$tarjeta ['hash'].PHP_EOL;
                $info = Yii::$app->TPaga->GetCreditCard($model_user->tpaga_id, $tarjeta ['hash']);
                $bin = $info->bin;
                $last_four = $info->last_four;
                $type = $info->type;
                $tar = [
                    "id" => $tarjeta ['id'],
                    "hash" => $tarjeta ['hash'],
                    "enable" => $tarjeta ['enable'],
                    "bin" => $bin,
                    "last_four" => $last_four,
                    "type" => $type
                ];
                $resp [] = $tar;
            }

            $response ["success"] = true;
            $response ['data'] = $resp;
        } else {
            $response ["success"] = true;
            $response ["data"] = null;
        }
       
        return $response;
    }

    // Android
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Finds the CreditCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id        	
     * @return CreditCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = CreditCard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
