<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class CheckPreRegister  extends \yii\db\ActiveRecord {

	
    public $email;
    public $password;

    public static function tableName() {
    	return 'user';
    }
    
    /**
     * @inheritdoc
     */
    public function rules() {
        
    	return [
            [['email','password'], 'required'],
            ['email', 'email', 'checkDNS' => true],
            ['email', 'string', 'min' => 2, 'max' => 45],
    		['email', 'unique'],
            ['password', 'string', 'min' => 2, 'max' => 225],

        ];
    }

}
