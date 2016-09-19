<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gcm_token_expert".
 *
 * @property string $token
 * @property string $one_signal_token
 * @property string $created_date
 * @property string $updated_date
 * @property integer $type_token_id
 * @property integer $expert_id
 *
 * @property Expert $expert
 * @property TypeToken $typeToken
 */
class GcmTokenExpert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gcm_token_expert';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'type_token_id', 'expert_id'], 'required'],
            [['created_date', 'updated_date'], 'safe'],
            [['type_token_id', 'expert_id'], 'integer'],
            [['token'], 'string', 'max' => 200],
            [['one_signal_token'], 'string', 'max' => 50]
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
            'type_token_id' => 'Type Token ID',
            'expert_id' => 'Expert ID',
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
    public function getTypeToken()
    {
        return $this->hasOne(TypeToken::className(), ['id' => 'type_token_id']);
    }
}
