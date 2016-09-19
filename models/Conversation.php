<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conversation".
 *
 * @property integer $id
 * @property integer $assigned_service_id
 * @property integer $expert_id
 * @property integer $user_id
 *
 * @property Chat[] $chats
 * @property AssignedService $assignedService
 * @property Expert $expert
 * @property User $user
 */
class Conversation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assigned_service_id', 'expert_id', 'user_id'], 'integer'],
            [['expert_id', 'user_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assigned_service_id' => 'Assigned Service ID',
            'expert_id' => 'Expert ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasMany(Chat::className(), ['conversation_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
