<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_token".
 *
 * @property integer $id
 * @property integer $FK_id_user
 * @property integer $FK_id_token_type
 * @property string $token
 * @property string $connection_ip
 * @property string $created_date
 * @property string $updated_date
 * @property integer $enable
 *
 * @property TypeToken $fKIdTokenType
 * @property User $fKIdUser
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
            [['FK_id_user', 'FK_id_token_type'], 'required'],
            [['FK_id_user', 'FK_id_token_type', 'enable'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['connection_ip'], 'string', 'max' => 45],
            [['FK_id_token_type'], 'exist', 'skipOnError' => true, 'targetClass' => TypeToken::className(), 'targetAttribute' => ['FK_id_token_type' => 'id']],
            [['FK_id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['FK_id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'FK_id_user' => Yii::t('app', 'Fk Id User'),
            'FK_id_token_type' => Yii::t('app', 'Fk Id Token Type'),
            'token' => Yii::t('app', 'Token'),
            'connection_ip' => Yii::t('app', 'Connection Ip'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'enable' => Yii::t('app', 'Enable'),
        ];
    }
    
    public function behaviors()
    {
       return [           
           'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT =>  ['created_date', 'updated_date'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_date',
                ],
                'value' => function() { return  date ( 'Y-m-d H:i:s' );},
            ],
            'activeBehavior' => [
               'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'enable',
                ],
                'value' => 1,
           ],
       ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFKIdTokenType()
    {
        return $this->hasOne(TypeToken::className(), ['id' => 'FK_id_token_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFKIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'FK_id_user']);
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
