<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gcm_token".
 *
 * @property string $token
 * @property string $one_signal_token
 * @property string $created_date
 * @property string $updated_date
 * @property integer $user_id
 * @property integer $type_token_id
 *
 * @property TypeToken $typeToken
 * @property User $user
 */
class GcmToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gcm_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'user_id', 'type_token_id'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['user_id', 'type_token_id'], 'integer'],
            [['token'], 'string', 'max' => 200],
            [['one_signal_token'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'one_signal_token' => 'One Signal Token',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'user_id' => 'User ID',
            'type_token_id' => 'Type Token ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeToken()
    {
        return $this->hasOne(TypeToken::className(), ['id' => 'type_token_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
