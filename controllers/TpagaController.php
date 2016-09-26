<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;


// Tpaga
// require_once 'Tpaga-php/Tpaga.php';

/**
 * UserController implements the CRUD actions for User model.
 */
class TpagaController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	

	
	// Android
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction ( $action );
	}
	
	
		public function actionTestRefund() {
			$id = Yii::$app->request->post ( "id", null );
			print"Tryng to refund -> $id" ;
			 $refund= Yii::$app->TPaga->RefundCharge($id);
			 if(!$refund){
			 	//No se pudo procesar
			 }
			 else{
			 	if(!$refund->paid){
			 		//Al hacer la reversión, la propiedad paid llega en false
			 		//La reversión se realizó correctamente
			 	}
			 }
			
		}
	
	//
	public function actionTest() {

		$firstname = Yii::$app->request->post ( "firstname", null );
		$lastname = Yii::$app->request->post ( "lastname", null );
		$email = Yii::$app->request->post ( "email", null );
		$phone = Yii::$app->request->post ( "phone", null );
		
		$id_user_created=Yii::$app->TPaga->CreateCustomer($firstname,$lastname,$email,$phone);
		
		if(!$id_user_created)
		{
		//Error creando el ususario
		return;
		}
		
		
		/*
		 *
		 * 
		 * 
		 * 
		 */
		
		//Crear Tarjeta de Credito		
		$city = new \app\assets\Tpaga\Model\City ();
		$city->country = "CO";
		$city->state = "DC";
		$city->name = "Bogotá";
		
		$address = new \app\assets\Tpaga\Model\Address ();
		$address->address_line1 = "Cualquier direccion";
		$address->postal_code = "12345";
		$address->city = $city;
		
		$id_credit_card_created=Yii::$app->TPaga->CreateCreditCard($id_user_created,"4356250656301093","08","2020","Leonardo Sapuy",$address);
		if(!$id_credit_card_created)
		{
			//Error creando la Tarjeta
			return;
		}
		
		/*
		 *
		 *
		 *
		 *
		 */
		
		
		//Adicionar Cargo
		$id_pay=Yii::$app->TPaga->CreateCharge($id_credit_card_created,55000,"Servicio 987654321");
		if(!$id_pay)
		{
			//Error creando el cargo
			return;
		}
		
	}
	
}
