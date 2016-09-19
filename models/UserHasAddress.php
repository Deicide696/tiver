<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_has_address".
 *
 * @property integer $user_id
 * @property integer $address_id
 */
class UserHasAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'address_id'], 'required'],
            [['user_id', 'address_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'address_id' => 'Address ID',
        ];
    }
    public function getUser(){
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getAddress(){
    	return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }
}
