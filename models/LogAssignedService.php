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
 * @property integer $assigned_service_id
 * @property integer $expert_id
 *
 * @property AssignedService $assignedService
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
            [['expert_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assigned' => 'Assigned',
            'rejected' => 'Rejected',
            'missed' => 'Missed',
            'accepted' => 'Accepted',
            'date' => 'Date',
            'time' => 'Time',
            'attempt' => 'Attempt',
            'created_date' => 'Created Date',
            'assigned_service_id' => 'Assigned Service ID',
            'expert_id' => 'Expert ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedService()
    {
        return $this->hasOne(AssignedService::className(), ['id' => 'assigned_service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(Expert::className(), ['id' => 'expert_id']);
    }
}
