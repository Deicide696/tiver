<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credit_card".
 *
 * @property integer $id
 * @property string $hash
 * @property integer $enable
 * @property string $created_date
 * @property integer $user_id
 */
class CreditCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credit_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enable', 'user_id'], 'integer'],
            [['created_date'], 'safe'],
            [['user_id'], 'required'],
            [['hash'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'enable' => 'Activo',
            'created_date' => 'Fecha Creación',
            'user_id' => 'Usuario',
        ];
    }
    public function getUser(){
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
