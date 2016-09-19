<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_has_modifier".
 *
 * @property integer $service_id
 * @property integer $modifier_id
 */
class ServiceHasModifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_has_modifier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'modifier_id'], 'required'],
            [['service_id', 'modifier_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Servicio',
            'modifier_id' => 'Modificador',
        ];
    }
    
    public function getModifier(){
    	return $this->hasOne(Modifier::className(), ['id' => 'modifier_id']);
    }
    
    public function getService(){
    	return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
    
}
