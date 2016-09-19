<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert_has_service".
 *
 * @property integer $expert_id
 * @property integer $service_id
 * @property double $qualification
 */
class ExpertHasService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expert_has_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expert_id', 'service_id'], 'required'],
            [['expert_id', 'service_id'], 'integer'],
            [['qualification'], 'number']
        ];
    }
    public function getExpert(){
    	return $this->hasOne(Expert::className(), ['id' => 'expert_id']);
    }
    
    public function getService(){
    	return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expert_id' => 'Especialista',
            'service_id' => 'Servicio',
            'qualification' => 'Calificación',
        ];
    }
}
