<?php

namespace app\components;

use app\assets\Tpaga\Api\CreditCardApi;
use app\assets\Tpaga\Api\CustomerApi;
use app\assets\Tpaga\ApiClient;
use yii\base\Component;
use Yii;

use app\assets\Tpaga\Model\Address;
use app\assets\Tpaga\Model\CreditCardCharge;
use app\assets\Tpaga\Model\CreditCardCreate;
use app\assets\Tpaga\Model\Customer;
use app\assets\Tpaga\Model\CreditCardRefund;
use app\assets\Tpaga\Api\RefundApi;


class TPaga extends Component {
	const api_key_user = "h5fc3s6hp3slpef9t7vr6dajcuqbdumk";
	
	
	public function CreateCustomer($first_name,$last_name,$email,$phone) {
		$api_client = new ApiClient ();
		//$api_client->api_key = self::api_key;
		
		// create customer object
		$customer = new \app\assets\Tpaga\Model\Customer();
		$customer->first_name = $first_name;
		$customer->last_name = $last_name;
		$customer->email = $email;
		$customer->phone = $phone;
		
		
	
		try {
			$customer_api = new CustomerAPI ( $api_client );
			// return Charge (model)
			$response = $customer_api->createCustomer ( $customer );
			//var_dump ( $response );
			$id_customer = $response->id;
			////print "<br>Usuario creado: " . $id_customer . "<br><br>";
			return $id_customer;
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
	}
	
	public function CreateCreditCard($id_customer,$number,$exp_month,$exp_year,$cvv,$card_holder,$address) {
		$api_client = new ApiClient ();
		//$api_client->api_key = self::api_key;
	
			// Agregamos tarjeta de credito al usuario creado
		$credit_card = new CreditCardCreate ();
		$credit_card->primary_account_number = "$number";
		$credit_card->expiration_month = "$exp_month";
		
		$credit_card->expiration_year = "$exp_year";
		$credit_card->card_verification_code = "$cvv";
		$credit_card->billing_address = $address;
		
		$credit_card->card_holder_name = "$card_holder";
		
		
		//var_dump($credit_card);
		
		try {
			$credit_card_api = new CreditCardAPI ( $api_client );
			// return Charge (model)
			$response = $credit_card_api->addCreditCard ( $id_customer, $credit_card );	
			$id_card = $response->id;
			//print "<br>Tarjeta creada: " . $id_card . "<br><br>";
			return $id_card;
		} catch ( Exception $e ) {
			//echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
		
	}
	public function CreateCreditCardToken($id_customer,$credit_card) {
		$api_client = new ApiClient ();
	
		//var_dump($credit_card);
	
		try {
			$credit_card_api = new CreditCardAPI ( $api_client );
			// return Charge (model)
			$response = $credit_card_api->addCreditCardToken ( $id_customer, $credit_card );
		$id_card = $response->id;
			//print "<br>Tarjeta creada: " . $id_card . "<br><br>";
			return $id_card;
		} catch ( Exception $e ) {
			//echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
	
	}
	
	public function GetCreditCard($id_customer,$hash) {
		$api_client = new ApiClient ();
		//$api_client->api_key = self::api_key;
	
		try {
			$credit_card_api = new CreditCardAPI ( $api_client );
			// return Charge (model)
			$response = $credit_card_api->getCreditCardById( $id_customer, $hash );
			return($response);
			//$id_card = $response->id;
			//print "<br>Tarjeta creada: " . $id_card . "<br><br>";
			
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
		
	}
	
	public function CreateCharge($id_card, $amount, $order_id, $tax) {
		$api_client = new ApiClient ();
		//$api_client->api_key = self::api_key;
	
		// Agregamos un cobro a la tarjeta de credito creada
		$amount=(int)$amount;
		$charge = new CreditCardCharge ();
		$charge->amount = $amount;
		$charge->tax_amount = $tax;
		$charge->currency = "COP";
		$charge->credit_card = $id_card;
		//$charge->installments =$installments;
		$charge->order_id = $order_id;
		$charge->description = "Pago de servicios Tiver";
		try {
			$charge_api = new CreditCardAPI ( $api_client );
			// return Charge (model)
			$response = $charge_api->addCreditCardCharge ( $charge );
			//var_dump ( $response );
			//print "<br>Pago creado: " . $id_payment . "<br><br>";
			return $response;
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
	
	}
	public function RefundCharge($id_charge) {
		$api_client = new ApiClient ();
	
		// Devolvemos el cargo creado a una tarjeta
		$refund=new CreditCardRefund();
		$refund->id=$id_charge;
		
		try {
			$refund_api=new RefundApi($api_client);
			// return Charge (model)
			$response = $refund_api->refundCreditCardCharge($refund);
			//var_dump ( $response );
			//print "<br>Pago creado: " . $id_payment . "<br><br>";
			return $response;
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage (), "\n";
			return false;
		}
	
	}
	
}