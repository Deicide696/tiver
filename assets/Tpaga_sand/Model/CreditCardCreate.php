<?php
/**
 * CreditCardCreate
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 *  Copyright 2015 SmartBear Software
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace app\assets\Tpaga\Model;

use \ArrayAccess;
/**
 * CreditCardCreate Class Doc Comment
 *
 * @category    Class
 * @description 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class CreditCardCreate implements ArrayAccess
{
    /**
      * Array of property to type mappings. Used for (de)serialization 
      * @var string[]
      */
    static $swaggerTypes = array(
        'primary_account_number' => 'string',
        'expiration_month' => 'string',
        'expiration_year' => 'string',
        'card_verification_code' => 'string',
        'card_holder_name' => 'string',
        'billing_address' => '\app\assets\Tpaga\Model\BillingAddress'
    );
  
    /** 
      * Array of attributes where the key is the local name, and the value is the original name
      * @var string[] 
      */
    static $attributeMap = array(
        'primary_account_number' => 'primaryAccountNumber',
        'expiration_month' => 'expirationMonth',
        'expiration_year' => 'expirationYear',
        'card_verification_code' => 'cardVerificationCode',
        'card_holder_name' => 'cardHolderName',
        'billing_address' => 'billingAddress'
    );
  
    /**
      * Array of attributes to setter functions (for deserialization of responses)
      * @var string[]
      */
    static $setters = array(
        'primary_account_number' => 'setPrimaryAccountNumber',
        'expiration_month' => 'setExpirationMonth',
        'expiration_year' => 'setExpirationYear',
        'card_verification_code' => 'setCardVerificationCode',
        'card_holder_name' => 'setCardHolderName',
        'billing_address' => 'setBillingAddress'
    );
  
    /**
      * Array of attributes to getter functions (for serialization of requests)
      * @var string[]
      */
    static $getters = array(
        'primary_account_number' => 'getPrimaryAccountNumber',
        'expiration_month' => 'getExpirationMonth',
        'expiration_year' => 'getExpirationYear',
        'card_verification_code' => 'getCardVerificationCode',
        'card_holder_name' => 'getCardHolderName',
        'billing_address' => 'getBillingAddress'
    );
  
    
    /**
      * $primary_account_number 
      * @var string
      */
    public $primary_account_number;
    
    /**
      * $expiration_month 
      * @var string
      */
    public $expiration_month;
    
    /**
      * $expiration_year 
      * @var string
      */
    public $expiration_year;
    
    /**
      * $card_verification_code 
      * @var string
      */
    public  $card_verification_code;
    
    /**
      * $card_holder_name 
      * @var string
      */
    public $card_holder_name;
    
    /**
      * $billing_address 
      * @var \app\assets\Tpaga\Model\BillingAddress
      */
    public $billing_address;
    

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        if ($data != null) {
            $this->primary_account_number = $data["primary_account_number"];
            $this->expiration_month = $data["expiration_month"];
            $this->expiration_year = $data["expiration_year"];
            $this->card_verification_code = $data["card_verification_code"];
            $this->card_holder_name = $data["card_holder_name"];
            $this->billing_address = $data["billing_address"];
        }
    }
    
    /**
     * Gets primary_account_number
     * @return string
     */
    public function getPrimaryAccountNumber()
    {
        return $this->primary_account_number;
    }
  
    /**
     * Sets primary_account_number
     * @param string $primary_account_number 
     * @return $this
     */
    public function setPrimaryAccountNumber($primary_account_number)
    {
        
        $this->primary_account_number = $primary_account_number;
        return $this;
    }
    
    /**
     * Gets expiration_month
     * @return string
     */
    public function getExpirationMonth()
    {
        return $this->expiration_month;
    }
  
    /**
     * Sets expiration_month
     * @param string $expiration_month 
     * @return $this
     */
    public function setExpirationMonth($expiration_month)
    {
        
        $this->expiration_month = $expiration_month;
        return $this;
    }
    
    /**
     * Gets expiration_year
     * @return string
     */
    public function getExpirationYear()
    {
        return $this->expiration_year;
    }
  
    /**
     * Sets expiration_year
     * @param string $expiration_year 
     * @return $this
     */
    public function setExpirationYear($expiration_year)
    {
        
        $this->expiration_year = $expiration_year;
        return $this;
    }
    
    /**
     * Gets card_verification_code
     * @return string
     */
    public function getCardVerificationCode()
    {
        return $this->card_verification_code;
    }
  
    /**
     * Sets card_verification_code
     * @param string $card_verification_code 
     * @return $this
     */
    public function setCardVerificationCode($card_verification_code)
    {
        
        $this->card_verification_code = $card_verification_code;
        return $this;
    }
    
    /**
     * Gets card_holder_name
     * @return string
     */
    public function getCardHolderName()
    {
        return $this->card_holder_name;
    }
  
    /**
     * Sets card_holder_name
     * @param string $card_holder_name 
     * @return $this
     */
    public function setCardHolderName($card_holder_name)
    {
        
        $this->card_holder_name = $card_holder_name;
        return $this;
    }
    
    /**
     * Gets billing_address
     * @return \app\assets\Tpaga\Model\BillingAddress
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }
  
    /**
     * Sets billing_address
     * @param \app\assets\Tpaga\Model\BillingAddress $billing_address 
     * @return $this
     */
    public function setBillingAddress($billing_address)
    {
        
        $this->billing_address = $billing_address;
        return $this;
    }
    
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
  
    /**
     * Gets offset.
     * @param  integer $offset Offset 
     * @return mixed 
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
  
    /**
     * Sets value based on offset.
     * @param  integer $offset Offset 
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
  
    /**
     * Unsets offset.
     * @param  integer $offset Offset 
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
  
    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
        } else {
            return json_encode(get_object_vars($this));
        }
    }
}
