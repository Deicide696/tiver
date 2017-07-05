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
    public $personal_code;

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
                ['phone', 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE]],
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
                ['fb_id', 'required', 'on' => self::SCENARIO_REGISTER_FB],
                ['fb_id', 'string', 'max' => 45],
                ['tpaga_id', 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_FB]],
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
            $user->FK_id_city = 1;
            $user->FK_id_type_identification = 1;
            $user->setPassword($this->password);
            $user->tpaga_id = $this->tpaga_id;
            $ok = false;
            do {
                $codigo = self::getRandomCode();
                $encon = User::find()
                        ->where(['like','personal_code',$codigo])
                        ->one();
                if (!isset($encon) || empty($encon)) {
                    $user->personal_code = $codigo;
                    $ok = true;
                }
            } while (!$ok);
//            print($user->validate()); die();
            if ($user->save()) {
                return $user;
            } else {
                return var_dump($user->getErrors());
            }
            
        }else {
            return $this->getErrors();
        }
        
    }

    public static function getRandomCode() {
        $an = "abcdefghijklmnopqrstuvwxyz";
        $su = strlen($an) - 1;
        return substr($an, rand(0, $su), 1) .
                substr($an, rand(0, $su), 1) .
                substr($an, rand(0, $su), 1) .
                substr($an, rand(0, $su), 1) .
                substr($an, rand(0, $su), 1) .
                substr($an, rand(0, $su), 1);
    }

    public function signup_fb() {

        if ($this->validate()) {
            $user = new User();
            $user->first_name = $this->firstname;
            $user->last_name = $this->lastname;
            $user->imei = $this->imei;
            $user->fb_id = $this->fb_id;
            $user->tpaga_id = $this->tpaga_id;
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->FK_id_gender = $this->gender;
            $user->FK_id_city = 1;
            $user->FK_id_type_identification = 1;

            $ok = false;
            do {
                $codigo = self::getRandomCode();
                $encon = User::find()
                        ->where(['like','personal_code',$codigo])
                        ->one();
                if (!isset($encon) || empty($encon)) {
                    $user->personal_code = $codigo;
                    $ok = true;
                }
            } while (!$ok);

            if ($user->save()) {
                return $user;
            } else {
                return var_dump($user->getErrors());
            }
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

            if ($user->save()) {
                return $user;
            } else {
                return "No se pudo guardar";
            }

        }

        return null;
    }

}
