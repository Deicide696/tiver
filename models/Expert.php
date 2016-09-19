<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert".
 *
 * @property integer $id
 * @property string $identification
 * @property string $name
 * @property string $last_name
 * @property integer $phone
 * @property string $email
 * @property string $password
 * @property string $password_repeat
 * @property string $address
 * @property integer $enable
 * @property double $lat
 * @property double $lng
 * @property string $created_date
 * @property integer $zone_id
 * @property integer $type_identification_id
 * @property integer $rol_id
 * @property integer $gender_id
 *
 * @property AssignedService[] $assignedServices
 * @property Calendar[] $calendars
 * @property Zone $zone
 * @property Gender $gender
 * @property Rol $rol
 * @property IdentificationType $identificationType
 * @property ExpertHasAssignedServices $expertHasAssignedServices
 * @property ExpertHasService[] $expertHasServices
 * @property Service[] $services
 * @property Schedule[] $schedules
 */
class Expert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expert';
    }

    /**
     * @inheritdoc
     */
    //public $rol_id=2;
    public $password_repeat;
    public function rules()
    {
        return [
            [['identification', 'name', 'last_name', 'phone', 'email', 'password', 'address', 'zone_id', 'type_identification_id', 'rol_id', 'gender_id'], 'required'],
            [['phone', 'enable', 'zone_id', 'type_identification_id', 'rol_id', 'gender_id'], 'integer'],
            [['created_date'], 'date', 'format' => 'yyyy-M-d H:m:s'],
        	['email', 'email', 'checkDNS' => true],
        	[['email'], 'unique'],	
        		
        	//['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [['identification'], 'string', 'max' => 15],
            [['name', 'last_name',  'password', 'address'], 'string', 'max' => 100],
        	['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
     return [
            'id' => 'ID',
            'identification' => 'Cédula',
            'name' => 'Nombres',
            'last_name' => 'Apellidos',
            'phone' => 'Celular',
            'email' => 'Email',
            'password' => 'Contraseña',
     		'password_repeat' => 'Confirmar contraseña',
            'address' => 'Dirección',
            'enable' => 'Activo',
            'created_date' => 'Fecha Creación',
            'zone_id' => 'Zona',
            'type_identification_id' => 'Tipo de identificación',
            'rol_id' => 'Rol',
            'gender_id' => 'Genero',
        ];
    }

    
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
    	return $this->password ;
    	//$this->save();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedServices()
    {
        return $this->hasMany(AssignedService::className(), ['expert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::className(), ['expert_idexpert' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zone::className(), ['id' => 'zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Rol::className(), ['id' => 'rol_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeIdentification()
    {
        return $this->hasOne(TypeIdentification::className(), ['id' => 'type_identification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpertHasAssignedServices()
    {
        return $this->hasOne(ExpertHasAssignedServices::className(), ['expert_idexpert' => 'id']);
    }
    public function getAssignedService()
    {
    	return $this->hasOne(AssignedService::className(), ['expert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpertHasService()
    {
        return $this->hasMany(ExpertHasService::className(), ['expert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id' => 'service_id'])->viaTable('expert_has_service', ['expert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasMany(Schedule::className(), ['expert_id' => 'id']);
    }
    public function getShortName(){
    	$nombre=explode( " ", $this->name); 
    	$apellido=explode( " ", $this->last_name);
    	return (reset($nombre)." ".reset($apellido));
    }
    
    public function getPushTokens(){
    	$tokens=null;
    	$gcm_tokens = GcmTokenExpert::find ()->select ( [
    			'one_signal_token'
    	] )->where ( [
    			"expert_id" => $this->id
    	] )->all ();
    	foreach ( $gcm_tokens as $gcm_token ) {
    		$tokens [] = $gcm_token->one_signal_token;
    	}
    	//print_r($tokens);
    	return $tokens;
    }
    
    
    
    public function validateDateTimeActualService($date,$time,$duration_serv,$id_actual,$walk_time=30)
    {
    	$services = assignedService::find ()->where ("expert_id=' $this->id' and date ='$date' and assigned_service.id<>'$id_actual'" )->joinWith ( 'service' )->asArray ()->all ();
    
    	if ($services == null) {
    		//Si no tiene servicios para ese día retorna true, está disponible
    		return true;
    	}
    		
    	//var_dump($services);
    	$duration_serv+=$walk_time;
    
    
    	foreach ( $services as $serv ) {
    		//print PHP_EOL;print PHP_EOL;
    		$duracion=$walk_time;
    		//obtenemos la duración del servicio
    		$duracion+= $serv ['service'] ['duration'];
    		//obtenemos la duración del modificador, si lo hay
    		$modifiers=AssignedServiceHasModifier::find()->where(['assigned_service_id'=>$serv['id']])->with('modifier')->asArray()->one();
    		if($modifiers!=null)$duracion+=$modifiers['modifier']['duration'];
    			
    		// Check time of checkout
    		$hora_serv=$serv['time'];
    			
    		$inicio_serv = strtotime ( $date . ' ' . $hora_serv );
    		$fin_serv = strtotime ( $date . ' ' . $hora_serv . " +$duracion minute" );
    			
    		$serv_solicitado = strtotime ( $date . ' ' . $time );
    		$serv_solicitado_fin = strtotime ( $date . ' ' . $time . " +$duration_serv minute" );
    			
    		//print"$serv_solicitado -> $inicio_serv - $fin_serv".PHP_EOL;
    			
    		//Evaluamos si el servicio solicitado se encuentra dentro del inicio y fin+walktime de otro servicio
    		if($serv_solicitado>=$inicio_serv && $serv_solicitado<=$fin_serv){
    			//print"ocupado".PHP_EOL;
    			return false;
    		}
    		//print"$inicio_serv -> $serv_solicitado - $serv_solicitado_fin".PHP_EOL	;
    		//Evaluamos si el servicio solicitado junto a su duracion no se trunca con el servicio,
    		if($inicio_serv>=$serv_solicitado&&$inicio_serv<=$serv_solicitado_fin){
    			//print"ocupado2".PHP_EOL;
    			return false;
    
    		}
    			
    			
    		//var_dump($modifiers);
    			
    	}
    	return true;
    }
    
    public function validateDateTime($date,$time,$duration_serv,$walk_time=30)
    {
    	$services = assignedService::find ()->where ( [ 
				'expert_id' => $this->id,
				'date' => $date,
    			'state'=>'1',
		] )->joinWith ( 'service' )->asArray ()->all ();
		
		if ($services == null) {
			//Si no tiene servicios para ese día retorna true, está disponible
			return true;
		}
			
		//var_dump($services);
		$duration_serv+=$walk_time;
		
		
		foreach ( $services as $serv ) {
			//print PHP_EOL;print PHP_EOL;
			$duracion=$walk_time;
			//obtenemos la duración del servicio
			$duracion+= $serv ['service'] ['duration'];
			//obtenemos la duración del modificador, si lo hay
			$modifiers=AssignedServiceHasModifier::find()->where(['assigned_service_id'=>$serv['id']])->with('modifier')->asArray()->one();
			if($modifiers!=null)$duracion+=$modifiers['modifier']['duration'];
			
			// Check time of checkout
			$hora_serv=$serv['time'];
			
			$inicio_serv = strtotime ( $date . ' ' . $hora_serv );
			$fin_serv = strtotime ( $date . ' ' . $hora_serv . " +$duracion minute" );
			
			$serv_solicitado = strtotime ( $date . ' ' . $time );
			$serv_solicitado_fin = strtotime ( $date . ' ' . $time . " +$duration_serv minute" );
			
			//print"$serv_solicitado -> $inicio_serv - $fin_serv".PHP_EOL;
			
			//Evaluamos si el servicio solicitado se encuentra dentro del inicio y fin+walktime de otro servicio
			if($serv_solicitado>=$inicio_serv && $serv_solicitado<=$fin_serv){
				//print"ocupado".PHP_EOL;
				return false;
			}
			//print"$inicio_serv -> $serv_solicitado - $serv_solicitado_fin".PHP_EOL	;
			//Evaluamos si el servicio solicitado junto a su duracion no se trunca con el servicio, 
			if($inicio_serv>=$serv_solicitado&&$inicio_serv<=$serv_solicitado_fin){
				//print"ocupado2".PHP_EOL;
				return false;
				
			}
			
			
			//var_dump($modifiers);
			
		}
		return true;
    }
}
