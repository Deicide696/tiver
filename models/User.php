<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id * @property string $fb_id
 * @property string $tpaga_id
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $identification
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $birth_date
 * @property integer $receive_interest_info
 * @property integer $status
 * @property string $last_login
 * @property integer $imei
 * @property string $created_date
 * @property integer $rol_id
 * @property integer $gender_id
 * @property integer $type_identification_id
 * @property integer $city_id
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    // Roles
    const ROLE_USER = 1;
    const ROLE_ADMIN = 3;
    const ROLE_SUPER = 4;
    public $full_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'email', 'receive_interest_info', 'enable', 'last_login', 'imei', 'FK_id_rol', 'FK_id_gender', 'FK_id_type_identification', 'FK_id_city'], 'required'],
            [['phone', 'receive_interest_info', 'enable', 'FK_id_rol', 'FK_id_gender', 'FK_id_type_identification', 'FK_id_city'], 'integer'],
            [['birth_date', 'last_login', 'created_date', 'updated_date'], 'safe'],
            [['birth_date'], 'date', 'format' => 'yyyy-M-d'],
            [['created_date', 'updated_date', 'last_login'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['first_name', 'last_name', 'email', 'password'], 'string', 'max' => 100],
            ['email', 'email', 'checkDNS' => true],
            [['identification'], 'string', 'max' => 15],
            [['imei', 'fb_id', 'tpaga_id'], 'string', 'max' => 45],
            [['personal_code'], 'string', 'max' => 6],
            [['FK_id_city'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['FK_id_city' => 'id']],
            [['FK_id_type_identification'], 'exist', 'skipOnError' => true, 'targetClass' => TypeIdentification::className(), 'targetAttribute' => ['FK_id_type_identification' => 'id']],
            [['FK_id_gender'], 'exist', 'skipOnError' => true, 'targetClass' => Gender::className(), 'targetAttribute' => ['FK_id_gender' => 'id']],
            [['FK_id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::className(), 'targetAttribute' => ['FK_id_rol' => 'id']],
            [['email'], 'unique'],
            ['enable', 'default', 'value' => self::STATUS_ACTIVE],
            ['enable', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['FK_id_rol', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_SUPER]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'FK_id_city' => 'Ciudad',
            'imei' => 'IMEI	',
            'identification' => 'Identificación	',
            'birth_date' => 'Fecha de nacimiento',
            'email' => 'Correo electrónico',
            'FK_id_gender' => 'Género',
            'password' => 'Contraseña',
            'phone' => 'Celular',
            'FK_id_rol' => 'Rol',
            'enable' => 'Activo',
            'last_login' => 'Último ingreso',
            'last_name' => 'Apellido',
            'first_name' => 'Nombre',
            'FK_id_type_identification' => 'Tipo de identificación',
            'tpaga_id' => "ID TPaga",
            'created_date' => "Fecha de creación",
            'updated_date' => "Fecha de modificación",
            'receive_interest_info' => "Recibir información de interés"
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogTokensMmz() {
        return $this->hasMany(LogTokensMmz::className(), [
                    'FK_id_mmz_user' => 'id'
                ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity() {
        return $this->hasOne(City::className(), [
                    'id' => 'FK_id_city'
                ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeIdentification() {
        return $this->hasOne(TypeIdentification::className(), [
                    'id' => 'FK_id_type_identification'
                ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */

    /**
     * Validates password
     *
     * @param string $password
     *        	password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password        	
     */
    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        // $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    // public static function findByUserEmail($email) {
    // return static::findOne(['email' => $email]);
    // }

    /**
     * Finds user by username
     *
     * @param string $username        	
     * @return static|null
     */
    public static function findByUsername($username) {
        // return static::findOne(['email' => $username, 'enable' => self::STATUS_ACTIVE]);
        // Solo roles diferentes a usuario and FK_id_rol>2
        return static::find()->where("email='$username' and enable='" . self::STATUS_ACTIVE . "' and FK_id_rol>2")->one();
    }

    public function getAuthKey() {
        
    }

    public function getId() {
        return $this->getPrimaryKey();
    }

    public function validateAuthKey($authKey) {
        return $this->password;
    }

    public static function findIdentity($id) {
        return static::findOne([
                    'id' => $id,
                    'enable' => self::STATUS_ACTIVE
                ]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return static::findOne([
                    'token' => $token
                ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token
     *        	password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE
                ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token
     *        	password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        // $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $expire = 3600;
        return $timestamp + $expire >= time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * esta funcion realiza una abreviacion de el ombre y el apellido del usuario
     * 
     * @param $firstName: corresponde
     *        	a el nombre del usuario
     * @param $lastName: corresponde
     *        	a el apellido del usuario
     * @return String
     */
    public function shortenName($firstName, $lastName) {
        $name = '';

        $arrayFirst = explode(' ', $firstName);
        if ($arrayFirst > 1) {
            for ($i = 0; $i < count($arrayFirst); $i ++) {
                $text = $arrayFirst [$i];
                if ($text != '') {
                    if ($i == 0) {
                        $name .= strtoupper($text [0]) . substr($text, 1, strlen($text)) . ' ';
                    } else if ($i == 1) {
                        $name .= strtoupper($text [0]) . '. ';
                    }
                }
            }
        } else {
            $name .= strtoupper($firstName [0]) . substr($firstName, 1, strlen($firstName)) . ' ';
        }

        $arrayLast = explode(' ', $lastName);
        if ($arrayLast > 1) {
            for ($i = 0; $i < count($arrayLast); $i ++) {
                $text = $arrayLast [$i];
                if ($text != '') {
                    if ($i == 0) {
                        $name .= strtoupper($text [0]) . substr($text, 1, strlen($text)) . ' ';
                    } else if ($i == 1) {
                        $name .= strtoupper($text [0]) . '. ';
                    }
                }
            }
        } else {
            $name .= strtoupper($lastName [0]) . substr($lastName, 1, strlen($lastName)) . ' ';
        }

        return $name;
    }

    public function getAssignedService() {
        return $this->hasMany(AssignedService::className(), [
                    'user_id' => 'id'
                ]);
    }

    public function getUserHasAddress() {
        return $this->hasMany(UserHasAddress::className(), [
                    'user_id' => 'id'
                ]);
    }

    public function getRol() {
        return $this->hasOne(Rol::className(), [
                    'id' => 'FK_id_rol'
                ]);
    }

    public function getGender() {
        return $this->hasOne(Gender::className(), [
                    'id' => 'FK_id_gender'
                ]);
    }

    public function getShortName() {
        $nombre = explode(" ", $this->first_name);
        $apellido = explode(" ", $this->last_name);
        return (reset($nombre) . " " . reset($apellido));
    }

    public function getPushTokens() {
        $tokens = null;
        $gcm_tokens = GcmToken::find()->select([
                    'one_signal_token', 'token'
                ])->where([
                    "user_id" => $this->id
                ])->all();
        foreach ($gcm_tokens as $gcm_token) {
            $tokens [] = $gcm_token->one_signal_token;
        }
        return $tokens;
    }

    public function hasPendingPay() {

        //buscamos los servicios que haya finalizado el usuario
        $pending = false;
        $model = ServiceHistory::find()->where(['state' => 1, 'user_id' => $this->id])->all();
        foreach ($model as $service) {
            $pay = $service->getLastPay();
            if ($pay == null) {
                $pending = true;
                break;
            }
            if ($pay->state == 0) {
                $pending = true;
                break;
            }
        }
        return $pending;
    }

    // RBAC
    public static function isAdmin($email) {
        if (static::findOne([
                    'email' => $email,
                    'FK_id_rol' => self::ROLE_ADMIN
                ])) {
            return true;
        } else {

            return false;
        }
    }

    public static function isUser($email) {
        if (static::findOne([
                    'email' => $email,
                    'FK_id_rol' => self::ROLE_USER
                ])) {
            return true;
        } else {

            return false;
        }
    }

    public static function isSuper($email) {
        if (static::findOne([
                    'email' => $email,
                    'FK_id_rol' => self::ROLE_SUPER
                ])) {
            return true;
        } else {

            return false;
        }
    }

}
