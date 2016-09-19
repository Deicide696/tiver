<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_token".
 *
 * @property integer $id
 * @property string $token
 * @property integer $status
 * @property string $created_date
 * @property string $updated_date
 * @property string $connection_ip
 * @property integer $FK_id_user
 * @property integer $FK_id_token_type
 */
class LogToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'FK_id_user', 'FK_id_token_type'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['FK_id_user', 'FK_id_token_type'], 'required'],
            [['token'], 'string', 'max' => 255],
            [['connection_ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'status' => 'Status',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'connection_ip' => 'Connection Ip',
            'FK_id_user' => 'Fk Id User',
            'FK_id_token_type' => 'Fk Id Token Type',
        ];
    }
    public static function isTokenValid($token) {
    	if (empty($token)) {
    		return false;
    	}
    	
    	$timestamp = (int) substr($token, strrpos($token, '_') + 1);
    	//$expire = Yii::$app->params['user.passwordResetTokenExpire'];
    	$expire=3600;
    	return $timestamp + $expire >= time();
    }
    
}
