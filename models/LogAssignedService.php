<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_assigned_service".
 *
 * @property integer $id
 * @property integer $assigned
 * @property integer $rejected
 * @property integer $missed
 * @property integer $accepted
 * @property string $date
 * @property string $time
 * @property integer $attempt
 * @property string $created_date
 * @property string $assigned_service_id
 * @property string $expert_id
 *
 * @property Expert $expert
 */
class LogAssignedService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_assigned_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assigned', 'rejected', 'missed', 'accepted', 'attempt', 'assigned_service_id', 'expert_id'], 'integer'],
            [['date', 'time', 'created_date'], 'safe'],
            [['expert_id'], 'required'],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expert::className(), 'targetAttribute' => ['expert_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assigned' => 'Asignado',
            'rejected' => 'Rechazado',
            'missed' => 'Perdido',
            'accepted' => 'Confirmados',
            'date' => 'Fecha',
            'time' => 'Hora',
            'attempt' => 'Intento',
            'created_date' => 'Fecha de creación',
            'assigned_service_id' => 'Servicio asignado',
            'expert_id' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(Expert::className(), ['id' => 'expert_id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistory()
    {
        return $this->hasOne(ServiceHistory::className(), ['id' => 'assigned_service_id']);
    }
 
}
