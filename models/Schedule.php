<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property integer $id
 * @property string $start_time
 * @property string $finish_time
 * @property string $date_created
 * @property integer $expert_id
 * @property integer $weekday_id
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'finish_time'], 'safe'],
            [['start_time', 'finish_time','expert_id', 'weekday_id'], 'required'],
            [['expert_id', 'weekday_id'], 'integer']
        ];
    }
    
    public function behaviors()
    {
       return [           
           'timestamp' => [
               'class' => 'yii\behaviors\TimestampBehavior',
               'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['date_created'],                   
               ],
           ],
       ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_time' => 'Hora Inicio',
            'finish_time' => 'Hora Fin',
            'date_created' => 'Fecha creación',
            'expert_id' => 'Especialista',
            'weekday_id' => 'Día de la semana',
        ];
    }
    
    public function getWeekday(){
    	return $this->hasOne(Weekday::className(), ['id' => 'weekday_id']);
    }
    
    public function getExpert(){
    	return $this->hasOne(Expert::className(), ['id' => 'expert_id']);
    }
}
