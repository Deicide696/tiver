<?php

namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_REGISTER_FB = 'register_fb';
    const SCENARIO_UPDATE = 'update';

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $phone;
    public $gender;
    public $imei;
    public $fb_id;
    public $tpaga_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            ['id', 'exist', 'targetClass' => '\app\models\User', 'message' => 'This user no exist.'],
            ['id', 'required', 'on' => self::SCENARIO_UPDATE],
            ['firstname', 'filter', 'filter' => 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 2, 'max' => 45],
            ['lastname', 'filter', 'filter' => 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 2, 'max' => 45],
            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'required','on'=>[self::SCENARIO_REGISTER, self::SCENARIO_UPDATE]],
            ['phone', 'string', 'min' => 2, 'max' => 45],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email', 'checkDNS' => true],
            ['email', 'string', 'min' => 2, 'max' => 45],
            ['password', 'required', 'on' => self::SCENARIO_REGISTER],
            ['password', 'string', 'min' => 2, 'max' => 225],
        	['gender', 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_FB]],
        	['gender', 'integer'],
        	['imei', 'required'],
        	['imei', 'string', 'max' => 45],
        	['fb_id', 'required','on' => self::SCENARIO_REGISTER_FB],
        	['fb_id', 'string', 'max' => 45],
        		['tpaga_id', 'required','on' => [self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_FB]],
        		['tpaga_id', 'string', 'max' => 45],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if ($this->validate()) {
            $user = new User();
            $user->first_name = $this->firstname;
            $user->last_name = $this->lastname;
            $user->imei = $this->imei;
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->FK_id_gender = $this->gender;
           // $user->points = 0;
            $user->FK_id_rol = 1;
            $user->FK_id_city = 1;
            $user->FK_id_type_identification = 1;
            $user->enable = 1;
            $user->setPassword($this->password);
            $user->tpaga_id=$this->tpaga_id;
            $user->generateAuthKey();
            $user->created_date = date('Y-m-d H:i:s');
            $user->updated_date = date('Y-m-d H:i:s');
            if ($user->save()) { 
            }
            return $user;
        }
        return null;
    }
    public function signup_fb() {
    	if ($this->validate()) {
    		$user = new User();
    		$user->first_name = $this->firstname;
    		$user->last_name = $this->lastname;
    		$user->imei = $this->imei;
    		$user->fb_id = $this->fb_id;
    		$user->tpaga_id=$this->tpaga_id;
    		$user->phone = $this->phone;
    		$user->email = $this->email;
    		$user->FK_id_gender = $this->gender;
    		// $user->points = 0;
    		$user->FK_id_rol = 1;
    		$user->FK_id_city = 1;
    		$user->FK_id_type_identification = 1;
    		$user->enable = 1;
    		$user->created_date = date('Y-m-d H:i:s');
    		$user->updated_date = date('Y-m-d H:i:s');
    		if (!$user->save()) {
             
    		}
    		return $user;
    	}
    
    	return null;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function update() {
        if ($this->validate()) {
        	//print"->".$this->firstname;
            $user = User::findOne(['id' => $this->id, 'enable' => 1]);
            $user->first_name = $this->firstname;
            $user->last_name = $this->lastname;
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->imei = $this->imei;
            $user->updated_date = date('Y-m-d H:i:s');
             if ($user->save()) {
                
            }
            return $user;
        }

        return null;
    }

}
