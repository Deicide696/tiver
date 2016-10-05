<?php

namespace app\models;

use Yii;
use app\models\TypeModifier;

/**
 * This is the model class for table "modifier".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $type_modifier_id
 */
class Modifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modifier';
    }

    /**
     * @inheritdoc
     */
    public $service_id;
    public function rules()
    {
        return [
            [['type_modifier_id','price','status','tax','name'], 'required'],
            [['type_modifier_id','price','duration'], 'integer'],
        		[['status'], 'boolean'],
        		['tax','integer','max'=>100],
        	
            [['name', 'description'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Modificador',
            'description' => 'Descripción',
        		'price' => 'Precio adicional',
        		'duration' => 'Duración adicional (mins)',
        		'tax' => 'I.V.A.',
        		'status' => 'Activo',
        		'service_id' => 'Servicio',
            'type_modifier_id' => 'Nivel de modificador',
        ];
    }
    
    public function getAssignedServiceHasModifier()
    {
    	return $this->hasMany(AssignedServiceHasModifier::className(), ['modifier_id' => 'id']);
    }
    
    public function getServiceHasModifier(){
    	return $this->hasOne(ServiceHasModifier::className(), ['modifier_id' => 'id']);
    }
    public function getTypeModifier(){
    	return $this->hasOne(TypeModifier::className(), ['id' => 'type_modifier_id']);
    }
}
