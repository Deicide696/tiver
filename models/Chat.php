<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property string $message
 * @property string $date
 * @property string $time
 * @property integer $expert_sent
 * @property integer $conversation_id
 *
 * @property Conversation $conversation
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'time'], 'safe'],
            [['expert_sent', 'conversation_id'], 'integer'],
            [['conversation_id'], 'required'],
            [['message'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'date' => 'Date',
            'time' => 'Time',
            'expert_sent' => 'Expert Sent',
            'conversation_id' => 'Conversation ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['id' => 'conversation_id']);
    }
}
